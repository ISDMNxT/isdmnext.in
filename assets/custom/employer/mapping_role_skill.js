document.addEventListener('DOMContentLoaded', function (e) {
    const form          = document.getElementById('manage_mapping');
    const category_box  = $('select[name="role_id"]');
    const table         = $('#list_job_role');
    const save_url      = 'employer/manage-role-skill';

    function reinitializeValidation() {
        // Re-add skill[] validation
        validator.addField('skill[]', {
            validators: {
                choice: {
                    min: 1,
                    message: 'Please select at least one skill'
                }
            }
        });
    }

    category_box.change(function () {
        var role_id = $(this).val();
        table.html('');
        $.AryaAjax({
            url: 'employer/list-role-skill',
            data: { role_id }
        }).then(function (res) {
            table.html(res.html);
            reinitializeValidation();
        }).catch(function (a) {
            console.log(a);
        });
    });

    if (form) {
        var validator = FormValidation.formValidation(
            form,
            {
                fields: {
                    role_id: { // Validation for category dropdown
                        validators: {
                            notEmpty: {
                                message: 'Please select a Role'
                            }
                        }
                    },
                    'skill[]': { // Validation for checkboxes
                        validators: {
                            choice: {
                                min: 1,
                                message: 'Please select at least one skill'
                            }
                        }
                    }
                },
                plugins: {
                    trigger: new FormValidation.plugins.Trigger(),
                    bootstrap: new FormValidation.plugins.Bootstrap5({
                        rowSelector: '.form-group',
                        eleInvalidClass: '',
                        eleValidClass: ''
                    })
                }
            }
        );

        form.addEventListener('submit', function (e) {
            // Prevent default button action
            e.preventDefault();

            // Perform form validation before submitting via AJAX
            validator.validate().then(function (status) {
                if (status === 'Valid') {
                    var test = save_ajax(form, save_url, validator);
                    test.done(function (data) {
                        //location.href = location.href;
                    });
                }
            });
        });
    }

});