document.addEventListener('DOMContentLoaded', function (e) {
    const form          = document.getElementById('manage_mapping');
    const category_box  = $('select[name="department_id"]');
    const table         = $('#list_department_role');
    const save_url      = 'employer/mapping-department-role';

    function reinitializeValidation() {
        // Re-add skill[] validation
        validator.addField('role[]', {
            validators: {
                choice: {
                    min: 1,
                    message: 'Please select at least one role'
                }
            }
        });
    }

    category_box.change(function () {
        var department_id = $(this).val();
        table.html('');
        $.AryaAjax({
            url: 'employer/list-department-role',
            data: { department_id }
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
                    department_id: { // Validation for category dropdown
                        validators: {
                            notEmpty: {
                                message: 'Please select a Department'
                            }
                        }
                    },
                    'role[]': { // Validation for checkboxes
                        validators: {
                            choice: {
                                min: 1,
                                message: 'Please select at least one role'
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