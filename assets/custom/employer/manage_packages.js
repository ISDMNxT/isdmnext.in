document.addEventListener('DOMContentLoaded', function (e) {
    const manage_packages_form      = document.getElementById('manage_packages');
    const table                     = $('#list_job_role');

    if (manage_packages_form) {
        // Init form validation rules. For more info check the FormValidation plugin's official documentation:https://formvalidation.io/
        var validator = FormValidation.formValidation(
            manage_packages_form,
            {
                fields: {
                    packages: {
                        validators: {
                            notEmpty: {
                                message: 'Packages Name is required'
                            }
                        }
                    },
                    packages_details: {
                        validators: {
                            notEmpty: {
                                message: 'Packages Details is required'
                            }
                        }
                    },
                    interview_charges: {
                        validators: { notEmpty: { message: 'Send Job Interview Request Charges/Student is requried' } }
                    },
                    apply_charges: {
                        validators:{
                            notEmpty :{
                                message : 'Receive Student Interested Request Charges/Student is required.'
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
        // Submit button handler
        manage_packages_form.addEventListener('submit', function (e) {
            // Prevent default button action
            e.preventDefault();
            var submitButton = $(this).find('button');

            // Validate form before submit
            if (validator) {
                // console.log(validator);
                validator.validate().then(function (status) {
                    // console.log(validator);
                    var formData = new FormData(manage_packages_form);

                    if (status == 'Valid') {
                        $(submitButton).attr('data-kt-indicator', 'on').prop('disabled', true);
                        axios
                        .post(
                            ajax_url + 'employer/manage_packages',
                            new FormData(manage_packages_form)
                        )
                        .then(function (e) {
                            if (e.data.status) {
                                toastr.success(e.data.msg);
                                setTimeout(function() {
                                    location.href = location.href;
                                }, 1000); // 3000 milliseconds = 3 seconds
                            } else {
                                toastr.error('Something went wrong!');
                            }
                        })
                        .catch(function (t) {
                            toastr.error('Sorry, looks like there are some errors detected, please try again.');
                        })
                        .then(() => {
                            $(submitButton).removeAttr('data-kt-indicator').prop("disabled", false);
                        });
                    }
                });
            }
        });
    }

});