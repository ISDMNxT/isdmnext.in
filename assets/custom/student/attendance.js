document.addEventListener('DOMContentLoaded', function (e) {
    const institue_box = $('select[name="center_id"]');
    const course_box = $('select[name="course_id"]');
    const batch_box = $('select[name="batch_id"]');
    const form = document.getElementById('add_form');
    $('.attendance-date').flatpickr({
        maxDate: 'today',
        dateFormat : dateFormat
    });
    if (form) {
        // Init form validation rules. For more info check the FormValidation plugin's official documentation:https://formvalidation.io/
        var validator = FormValidation.formValidation(
            form,
            {
                fields: {
                    attendance_date: {
                        validators: {
                            notEmpty: {
                                message: 'Select a date'
                            }
                        }
                    },
                    center_id: {
                        validators: { notEmpty: { message: 'Please Select a Center' } }
                    },
                    course_id: {
                        validators: { notEmpty: { message: 'Please Select a Course' } }
                    },
                    batch_id: {
                        validators: { notEmpty: { message: 'Please Select a Batch' } }
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
                        $('.view-list').html('<h3 class="text-center"><i class="fa fa-spin fa-spinner"></i> Please wait..</h3>');
                        $(submitButton).attr('data-kt-indicator', 'on').prop('disabled', true);
                        axios
                            .post(
                                ajax_url + 'student/search-student-for-attendance',
                                new FormData(form)
                            )
                            .then(function (e) {
                                console.log(e);
                                if (e.data.status) {
                                    $('.view-list').html(e.data.html);
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
            var show = $('#wallet_system_course_wise').length;
            course_box.html('');
            $.AryaAjax({
                url: 'student/genrate-a-new-rollno-with-center-courses',
                data: { center_id },
                dataType: 'json'
            }).then(function (res) {
                if (res.status) {
                    var options = '<option value=""></option>';
                    if (res.courses.length) {
                        $.each(res.courses, function (index, course) {
                            options += `<option data-price_show="${show}" value="${course.course_id}" data-course_fee="${course.course_fee}" data-kt-rich-content-subcontent="${course.duration} ${course.duration_type}">${course.course_name}</option>`;
                        });
                    }
                    course_box.html(options).select2({
                        placeholder: "Select a Course",
                        templateSelection: optionFormatSecond,
                        templateResult: optionFormatSecond,
                    });
                }
                else {
                    SwalWarning('This Center have not any course , Please contact to Admin.');
                }
            }).catch(function (a) {
                console.log(a);
            });
        }).select2({
            placeholder: "Select a Center",
            templateSelection: optionFormatSecond,
            templateResult: optionFormatSecond,
        });

        course_box.change(function () {
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
        });
    }
    $(document).on('submit', '#submit-attendance-form', function (r) {
        r.preventDefault();
        $.AryaAjax({
            url: 'student/save-attendance',
            data: $(this).serialize(),
        }).then(function (r) {
            // console.log(r);
            // form.reset();
            $('.view-list').html('');
            Swal.fire({
                html: 'Student Attendance Submitted Successfully...',
                icon: 'success'
            });
        });
        // console.log($(this).serialize());
    })

    if (login_type == 'center') {
        institue_box.trigger("change");
    }
});
