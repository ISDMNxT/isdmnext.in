document.addEventListener('DOMContentLoaded', function (e) {
    const add_staff_form    = document.getElementById('add_franchise_form');

    if (add_staff_form) {
        // Init form validation rules. For more info check the FormValidation plugin's official documentation:https://formvalidation.io/
        var validator = FormValidation.formValidation(
            add_staff_form,
            {
                fields: {
                    name  : {
                        validators:{
                            notEmpty :{
                                message : 'Name is required.'
                            }
                        }
                    },
                    institute_name  : {
                        validators:{
                            notEmpty :{
                                message : 'Master Franchise Name is required.'
                            }
                        }
                    },
                    contact_number: {
                        validators: {
                            notEmpty: {
                                message: 'Contact Number is required'
                            }
                        }
                    },
                    email_id: {
                        validators: {
                            regexp: {
                                regexp: /^[^\s@]+@[^\s@]+\.[^\s@]+$/,
                                message: "The value is not a valid email",
                            },
                            notEmpty: { message: "Email is required" },
                        },
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
                                message: 'City is required'
                            },
                        }
                    },
                    earning_percent: {
                        validators: {
                            notEmpty: {
                                message: 'Wallet Recharge Transaction Commission is required'
                            },
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

        var validatorn = FormValidation.formValidation(
            add_staff_form,
            {
                fields: {
                    name  : {
                        validators:{
                            notEmpty :{
                                message : 'Name is required.'
                            }
                        }
                    },
                    institute_name  : {
                        validators:{
                            notEmpty :{
                                message : 'Master Franchise Name is required.'
                            }
                        }
                    },
                    contact_number: {
                        validators: {
                            notEmpty: {
                                message: 'Contact Number is required'
                            }
                        }
                    },
                    email_id: {
                        validators: {
                            regexp: {
                                regexp: /^[^\s@]+@[^\s@]+\.[^\s@]+$/,
                                message: "The value is not a valid email",
                            },
                            notEmpty: { message: "Email is required" },
                        },
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
                                message: 'City is required'
                            },
                        }
                    },
                    earning_percent: {
                        validators: {
                            notEmpty: {
                                message: 'Wallet Recharge Transaction Commission is required'
                            },
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
        // const submitButton = document.getElementById('kt_docs_formvalidation_text_submit');
        add_staff_form.addEventListener('submit', function (e) {
            // Prevent default button action
            e.preventDefault();
            var submitButton = $(this).find('button');
            var vv = '';
            if($('#franchise_id').val()!= ''){
                vv = validatorn;
            } else {
                vv = validator;
            }

            // Validate form before submit
            if (vv) {
                // console.log(validator);
                vv.validate().then(function (status) {
                    // console.log(validator);
                    var formData = new FormData(add_staff_form);
                    if (status == 'Valid') {
                        $(submitButton).attr('data-kt-indicator', 'on').prop('disabled', true);
                        axios
                            .post(
                                ajax_url + 'center/add-master-franchise',
                                new FormData(add_staff_form)
                            )
                            .then(function (e) {
                                if (e.data.status) {
                                    if($('#franchise_id').val()!= ''){
                                        Swal.fire({
                                            text: "Master Franchise Updated Successfully.",
                                            icon: "success",
                                            buttonsStyling: !1,
                                            confirmButtonText: "Ok, got it!",
                                            customClass: {
                                                confirmButton: "btn btn-primary",
                                            },
                                        });
                                        location.href = base_url + 'center/add-master-franchise';
                                    } else {
                                        add_staff_form.reset(),
                                        Swal.fire({
                                            text: "Master Franchise Added Successfully.",
                                            icon: "success",
                                            buttonsStyling: !1,
                                            confirmButtonText: "Ok, got it!",
                                            customClass: {
                                                confirmButton: "btn btn-primary",
                                            },
                                        });
                                    }
                                    
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

        if(parseInt($("#franchise_id").val()) > 0){
            $(".get_city").trigger('change');
        }
    }
});