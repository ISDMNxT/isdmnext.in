document.addEventListener('DOMContentLoaded', function (e) {
    const form          = document.getElementById('manage_mapping_group');
    const category_box  = $('select[name="group_id"]');
    const table         = $('#list_job_role');
    const save_url      = 'employer/mapping-group-industry';

    function reinitializeValidation() {
        // Re-add skill[] validation
        validator.addField('industry[]', {
            validators: {
                choice: {
                    min: 1,
                    message: 'Please select at least one industry'
                }
            }
        });
    }

    category_box.change(function () {
        var group_id = $(this).val();
        table.html('');
        $.AryaAjax({
            url: 'employer/list-group-industry',
            data: { group_id }
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
                    group_id: { // Validation for category dropdown
                        validators: {
                            notEmpty: {
                                message: 'Please select a Group'
                            }
                        }
                    },
                    'industry[]': { // Validation for checkboxes
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