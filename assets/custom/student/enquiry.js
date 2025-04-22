document.addEventListener('DOMContentLoaded', function (e) {
    const form = document.getElementById('form');
    const institue_box = $('select[name="center_id"]');
    const roll_no_box = $('input[name="enquiry_no"]');
    const course_box = $('select[name="course_id"]');
    if (form) {
        var validator = FormValidation.formValidation(
            form,
            {
                fields: {
                    name: {
                        validators: {
                            notEmpty: {
                                message: 'Student Name is required'
                            }
                        }
                    },
                    dob: {
                        validators: { notEmpty: { message: 'Date of Birth is requried' } }
                    },
                    address: {
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
                    gender: {
                        validators: {
                            notEmpty: {
                                message: 'Select A Gender'
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

                    alternative_mobile: {
                        validators: {
                            regexp: {
                                regexp: /^(?:\+|\d)[\d-\s]+$/,
                                message: 'Please enter a valid contact number.'
                            },
                            stringLength: {
                                min: 10,
                                max: 15,
                                message: 'The Mobile number must be between 10 and 15 characters.'
                            }
                        }
                    },
                    contact_number: {
                        validators: {
                            notEmpty: {
                                message: 'Please enter a Whatsapp number.'
                            },
                            regexp: {
                                regexp: /^(?:\+|\d)[\d-\s]+$/,
                                message: 'Please enter a valid Whatsapp number.'
                            },
                            stringLength: {
                                min: 10,
                                max: 15,
                                message: 'The Whatsapp number must be between 10 and 15 characters.'
                            }
                        }
                    },
                    father_name: {
                        validators: {
                            notEmpty: {
                                message: 'Please enter father name.'
                            }
                        }
                    },
                    mother_name: {
                        validators: {
                            notEmpty: {
                                message: 'Please enter mother name.'
                            },
                        }
                    },
                    batch_id: {
                        validators: { notEmpty: { message: 'Please Select a batch' } }
                    },
                    course_id: {
                        validators: { notEmpty: { message: 'Please Select a course' } }
                    },
                    center_id: {
                        validators: { notEmpty: { message: 'Please Select a Center' } }
                    },
                    estimatedjoin_date: {
                        validators: { notEmpty: { message: 'Estimated Join Date is requried' } }
                    },
                    followup_date: {
                        validators: { notEmpty: { message: 'Follow Up Date is requried' } }
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
        form.addEventListener('submit', function (e) {
            // Prevent default button action
            e.preventDefault();
            var submitButton = $(this).find('button');
            // Validate form before submit
            if (validator) {
                // console.log(validator);
                validator.validate().then(function (status) {
                    // console.log(validator);
                    var formData = new FormData(form);

                    if (status == 'Valid') {
                        $(submitButton).attr('data-kt-indicator', 'on').prop('disabled', true);
                        axios
                            .post(
                                ajax_url + 'student/enquiry',
                                new FormData(form)
                            )
                            .then(function (e) {
                                console.log(e);
                                if (e.data.status) {
                                    form.reset(),
                                        Swal.fire({
                                            text: "Student Enquiry Submited Successfully.",
                                            icon: "success",
                                            buttonsStyling: !1,
                                            confirmButtonText: "Ok, got it!",
                                            customClass: {
                                                confirmButton: "btn btn-primary",
                                            },
                                        }).then(function (result) {
                                            if (result.isConfirmed) {
                                                location.reload();
                                            }
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


        institue_box.change(function () {
            var center_id = $(this).val();
            course_box.html('');
            roll_no_box.val('');
            var show = $('#wallet_system_course_wise').length;
            // alert(center_id);
            $.AryaAjax({
                url: 'student/genrate-a-new-enquiryno-with-center-courses',
                data: { center_id },
                dataType: 'json'
            }).then(function (res) {
                // log(res);
                if (res.status) {
                    roll_no_box.val(res.enquiry_no);
                    var options = '<option value=""></option>';
                    if (res.courses.length) {
                        // log(res.courses);
                        $.each(res.courses, function (index, course) {
                            options += `<option data-price_show="${show}" value="${course.course_id}" data-course_fee="${course.course_fee}" data-course_royality_fees="${course.royality_fees}" data-kt-rich-content-subcontent="${course.duration} ${course.duration_type}">${course.course_name}</option>`;
                        });
                    }
                    course_box.html(options).select2({
                        placeholder: "Select a Course",
                        templateSelection: optionFormatSecond,
                        templateResult: optionFormatSecond,
                    });
                }
                else {
                    roll_no_box.val('');
                    SwalWarning('This Center have not Enquiry numer Prefix , Please Assign it.');
                }
            }).catch(function (a) {
                console.log(a);
            });
        }).select2({
            placeholder: "Select a Center",
            templateSelection: optionFormatSecond,
            templateResult: optionFormatSecond,
        });
        if ($('#wallet_system_course_wise').length) {
            course_box.change(function () {
                var course_fee = $(this).find('option:selected').data('course_fee');
                var btn = $('#form').find('button');
                var price = $('#centre_id').find('option:selected').data('wallet');
                $("#fee_amount_first").val(course_fee);
                // alert(price)
                /*if (price < course_fee) {
                    SwalWarning(`Wallet Balance is Low...\n
                                <b class="text-success">Course Fee : ${inr} ${course_fee}</b>\n
                                <b class="text-danger">Wallet Balance : ${inr} ${price}</b>
                                `);
                    btn.prop("disabled", true);
                }
                else {
                    btn.prop("disabled", false);
                }*/
                var course_id = $(this).val();
                var center_id = $('#centre_id').val();
                if(course_id > 0){
                    $.AryaAjax({
                        url: 'student/get-center-course-batches',
                        data: { center_id:center_id, course_id:course_id },
                        dataType: 'json'
                    }).then(function (res) {
                        var options = '<option value=""></option>';
                        if (res.status) {
                            $.each(res.batches, function (index, batche) {
                                options += `<option value="${batche.id}" data-kt-rich-content-subcontent="${batche.duration}">${batche.batch_name}</option>`;
                            });
                        }
                        else {
                            SwalWarning('This Course have not any batch., Please Contact to Admin.');
                        }

                        $("#batch_id").html(options).select2({
                            placeholder: "Select a Batch",
                            templateSelection: optionFormatSecond,
                            templateResult: optionFormatSecond,
                        });
                    }).catch(function (a) {
                        console.log(a);
                    });
                } else {
                    var options = '<option value=""></option>';
                    $("#batch_id").html(options).select2({
                        placeholder: "Select a Batch",
                        templateSelection: optionFormatSecond,
                        templateResult: optionFormatSecond,
                    });
                }
                

            })
        }

        if (login_type == 'center') {
            institue_box.trigger("change");
        }
    }
}); 