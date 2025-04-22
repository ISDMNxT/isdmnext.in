document.addEventListener('DOMContentLoaded', function (e) {
    const add_form = document.getElementById('create-marksheet');
    const institue_box = $('select[name="center_id"]');
    const student_box = $('select[name="student_id"]');
    const course_id = $('input[type="hidden"][name="course_id"]');
    const course_duration = $('input[type="hidden"][name="course_duration"]');
    const course_duration_type = $('input[type="hidden"][name="course_duration_type"]');
    const duration = $('select[name="duration"]');
    const exam = $('select[name="exam_id"]');
    const admit_card_id = $('#admit_card_id');
    const button = $('#publish_btn');
    
    if (add_form) {
        var validator = FormValidation.formValidation(
            add_form,
            {
                fields: {
                    center_id: {
                        validators: {
                            notEmpty: {
                                message: "Select a center"
                            }
                        }
                    },
                    duration: {
                        validators: {
                            notEmpty: {
                                message: 'Select a duration'
                            }
                        }
                    },
                    student_id: {
                        validators: {
                            notEmpty: {
                                message: 'Student is required'
                            }
                        }
                    },
                    exam_id: {
                        validators: {
                            notEmpty: {
                                message: 'Exam is required'
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

        $(document).on('submit', '#create-marksheet', function (e) {
            e.preventDefault();
            var that = this;
            if (validator) {
                validator.validate().then(function (status) {
                    if (status == 'Valid') {
                        $.AryaAjax({
                            url : 'student/generate-marksheet-certificate',
                            data : new FormData(that),
                            success_message : 'Marksheet & Certificate Request Sent Successfully..',
                            page_reload : true
                        }).then( (r) => log(r));
                    }
                });
            }
        });

        institue_box.select2({
            placeholder: "Select a Center",
            templateSelection: optionFormatSecond,
            templateResult: optionFormatSecond,
        }).on('change', function () {
            // alert('yes');
            var center_id = $(this).val();
            student_box.html(emptyOption);
            course_duration_type.val('');
            course_duration.val('');
            course_id.val('');
            duration.html(emptyOption);
            exam.html(emptyOption);

            if (center_id) {
                $.AryaAjax({
                    url: 'student/fetch_student_via_center',
                    data: { center_id }
                }).then((rs) => {
                    if (rs.data.length) {
                        $.each(rs.data, (i, student) => {
                            student_box.append(`
                                    <option 
                                            value="${student.student_id}" 
                                            data-kt-rich-content-subcontent="${student.roll_no}" 
                                            data-kt-rich-content-icon="${student.image}" 
                                            data-course_duration="${student.duration}" 
                                            data-course_duration_type="${student.duration_type}" 
                                            data-course_id="${student.course_id}"
                                            data-course_name="${student.course_name}">
                                            ${student.student_name}
                                    </option>
                                `);
                        })
                        student_box.mySelect2();
                    }
                });
            }
        });

        student_box.on('change', function () {
            const student_id = $(this).val();
            course_duration_type.val('');
            course_duration.val('');
            course_id.val('');
            duration.html(emptyOption);
            exam.html(emptyOption);

            if (student_id) {
                const option = $(this).find('option:selected');
                const duration_val = option.data("course_duration");
                const duration_type_val = option.data("course_duration_type");
                const course_id_val = option.data("course_id");
                const course_name = option.data('course_name');
                course_duration_type.val(duration_type_val);
                course_duration.val(duration_val);
                course_id.val(course_id_val);
                admit_card_id.val('');
                // alert(duration_type_val);
                $.AryaAjax({
                    data: {
                        duration: duration_val,
                        duration_type: duration_type_val,
                        course_id: course_id_val,
                        student_id: student_id,
                        center_id: institue_box.val(),
                        course_name: course_name
                    },
                    url: 'student/marksheet-validation'
                }).then((rs) => {
                    log(rs);
                    admit_card_id.val(rs.admit_card_id);
                    if (rs.options.length) {
                       
                        $.each(rs.options, (i, d) => {
                            duration.append(`
                                <option
                                 data-admit_card_id="${rs.admit_card_id}"
                                value="${d.id}"
                                    ${d.isCreated ? 'disabled data-subtitle-class="text-danger" ' : ''}
                                    data-kt-rich-content-subcontent="${d.sub_label}">
                                    ${d.label}
                                </option>
                            `);
                        })
                        duration.mySelect2();
                    }
                });
            }
        });

        duration.on('change', function () {
            var duration = $(this).val();
            exam.html(emptyOption);
            
            if (duration) {
                $.AryaAjax({
                    data: {
                        center_id: $("#center_id").val(),
                        course_id: course_id.val(),
                        student_id: $("#student_id").val(),
                    },
                    url: 'student/get-student-exams'
                }).then((rs) => {
                    if (rs.options.length) {
                       
                        $.each(rs.options, (i, d) => {
                            exam.append(`<option value="${d.id}" data-kt-rich-content-subcontent="${d.start_date} - ${d.end_date}" >${d.exam_title}</option>`);
                        })
                        exam.mySelect2();
                    }
                });
            }
        });

        if (login_type == 'center') {
            institue_box.trigger("change");
        }
    }
    button.prop('disabled', false);
});
