document.addEventListener('DOMContentLoaded', function (e) {
    const add_employer_form = document.getElementById('add_employer_form');
    if (add_employer_form) {
        // Init form validation rules. For more info check the FormValidation plugin's official documentation:https://formvalidation.io/
        var validator = FormValidation.formValidation(
            add_employer_form,
            {
                fields: {
                    name: {
                        validators: {
                            notEmpty: {
                                message: 'Employer Head Name is required'
                            }
                        }
                    },
                    institute_name: {
                        validators: { notEmpty: { message: 'Employer Name is requried' } }
                    },
                    rollno_prefix  : {
                        validators:{
                            notEmpty :{
                                message : 'Employer Code is required.'
                            }
                        }
                    },
                    qualification_of_center_head: {
                        validators: {
                            notEmpty: {
                                message: 'Designation of employer head is required'
                            }
                        }
                    },
                    center_full_address: {
                        validators: {
                            notEmpty: {
                                message: 'Address is required'
                            },
                        }
                    },
                    pincode: {
                        validators: {
                            notEmpty: {
                                message: 'Pincode is required'
                            },
                            regexp: {
                                regexp: /^[1-9][0-9]{5}$/,
                                message: 'Invalid Pincode format'
                            },
                            stringLength: {
                                max: 6,
                                message: 'Pincode must be 6 digits'
                            }
                        }
                    },
                    image: {
                        validators: {
                            notEmpty: {
                                message: 'Please choose a file.'
                            },
                            file: {
                                extension: 'jpg,jpeg,png,gif',
                                type: 'image/jpeg,image/png,image/gif',
                                maxSize: 5 * 1024 * 1024, // 5 MB
                                message: 'The selected file is not valid. Allowed types: jpg, jpeg, png, gif. Maximum size: 5 MB.'
                            }
                        }
                    },
                    state_id: {
                        validators: {
                            notEmpty: {
                                message: 'State is required'
                            },
                        }
                    },
                    city_id: {
                        validators: {
                            notEmpty: {
                                message: 'Distric is required'
                            },
                        }
                    },
                    whatsapp_number: {
                        validators: {
                            notEmpty: {
                                message: 'Please enter a WhatsApp number.'
                            },
                            regexp: {
                                regexp: /^(?:\+|\d)[\d-\s]+$/,
                                message: 'Please enter a valid WhatsApp number.'
                            },
                            stringLength: {
                                min: 10,
                                max: 10,
                                message: 'The WhatsApp number must be 10 characters.'
                            }
                        }
                    },
                    contact_number: {
                        validators: {
                            notEmpty: {
                                message: 'Please enter a contact number.'
                            },
                            regexp: {
                                regexp: /^(?:\+|\d)[\d-\s]+$/,
                                message: 'Please enter a valid contact number.'
                            },
                            stringLength: {
                                min: 10,
                                max: 10,
                                message: 'The contact number must be must be 10 characters.'
                            }
                        }
                    },
                    email_id: {
                        validators: {
                            regexp: {
                                regexp: /^[^\s@]+@[^\s@]+\.[^\s@]+$/,
                                message: "The value is not a valid email address",
                            },
                            notEmpty: { message: "Email address is required" },
                        },
                    },
                    password: {
                        validators: {
                            notEmpty: { message: "The password is required" },
                            stringLength: {
                                min: 8,
                                message: 'The password must be at least 8 characters long.'
                            },
                            regexp: {
                                regexp: /^(?=.*[a-z])(?=.*[A-Z])(?=.*\d)(?=.*[@$!%*?&])[A-Za-z\d@$!%*?&]+$/,
                                message: 'The password must contain at least one uppercase letter, one lowercase letter, one digit, and one special character.'
                            }
                        },
                    },
                    signature: {
                        validators: {
                            notEmpty: {
                                message: 'Please choose a file of Signature.'
                            },
                            file: {
                                extension: 'jpg,jpeg,png,gif',
                                type: 'image/jpeg,image/png,image/gif',
                                maxSize: max_upload_size,
                                message: 'The selected file is not valid. Allowed types: jpg, jpeg, png, gif. Maximum size: 2 MB.'
                            }
                        }
                    },
                    logo: {
                        validators: {
                            notEmpty: {
                                message: 'Please choose a file of logo.'
                            },
                            file: {
                                extension: 'jpg,jpeg,png,gif',
                                type: 'image/jpeg,image/png,image/gif',
                                maxSize: max_upload_size,
                                message: 'The selected file is not valid. Allowed types: jpg, jpeg, png, gif. Maximum size: 2 MB.'
                            }
                        }
                    },
                    website: {
                        validators: {
                            notEmpty: {
                                message: 'Website URL is required.'
                            },
                            uri: {
                                message: 'Please enter a valid website URL.'
                            }
                        }
                    },
                    about_company: {
                        validators: {
                            notEmpty: {
                                message: 'About employer is required.'
                            },
                            stringLength: {
                                min: 50,
                                message: 'About employer must be at least 10 characters long.'
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
        add_employer_form.addEventListener('submit', function (e) {
            // Prevent default button action
            e.preventDefault();
            var submitButton = $(this).find('button');

            // Validate form before submit
            if (validator) {
                // console.log(validator);
                validator.validate().then(function (status) {
                    // console.log(validator);
                    var formData = new FormData(add_employer_form);

                    if (status == 'Valid') {
                        $(submitButton).attr('data-kt-indicator', 'on').prop('disabled', true);
                        axios
                            .post(
                                ajax_url + 'employer/employer_mgmt',
                                new FormData(add_employer_form)
                            )
                            .then(function (e) {
                                if (e.data.status) {
                                    add_employer_form.reset(),
                                        Swal.fire({
                                            text: "Employer Saved Successfully.",
                                            icon: "success",
                                            buttonsStyling: !1,
                                            confirmButtonText: "Ok, got it!",
                                            customClass: {
                                                confirmButton: "btn btn-primary",
                                            },
                                        });
                                }
                                else {
                                    Swal.fire({
                                        text: 'Please Check It.',
                                        html: e.data.html,
                                        icon: "warning",
                                        buttonsStyling: !1,
                                        confirmButtonText: "Ok, got it!",
                                        customClass: {
                                            confirmButton: "btn btn-primary",
                                        },
                                    });
                                }
                            })
                            .catch(function (t) {
                                console.log(t);
                                Swal.fire({
                                    text: "Sorry, looks like there are some errors detected, please try again.",
                                    icon: "error",
                                    buttonsStyling: !1,
                                    confirmButtonText: "Ok, got it!",
                                    customClass: { confirmButton: "btn btn-primary" },
                                });
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