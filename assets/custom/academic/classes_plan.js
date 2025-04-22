document.addEventListener('DOMContentLoaded', function (e) {
    const institue_box              = $('select[name="center_id"]');
    const course_box                = $('select[name="course_id"]');
    const batch_box                 = $('select[name="batch_id"]');
    const subject_box               = $('select[name="subject_id"]');
    const staff_box                 = $('select[name="staff_id"]');
    const add_form                  = document.getElementById('class_plan');
    const fileInput                 = $('#notes'); 
    const allowedExtensions         = ['pdf', 'ppt', 'doc'];
    const list_class                = $('#list-master-class');

    fileInput.on('change', function () {
        const file = this.files[0]; // Use `this` to access the file input's native properties
        if (file) {
            const fileName = file.name;
            const fileExtension = fileName.split('.').pop().toLowerCase();

            if (!allowedExtensions.includes(fileExtension)) {
                fileInput.val(''); // Clear the file input using jQuery
                SwalWarning('Notice', 'Invalid file type! Please upload a PDF, PPT, or DOC file.');
            }
        }
    });

    if (add_form) {
        // Init form validation rules. For more info check the FormValidation plugin's official documentation:https://formvalidation.io/
        var validator = FormValidation.formValidation(
            add_form,
            {
                fields: {
                    center_id: {
                        validators: {
                            notEmpty: {
                                message: 'Center is required'
                            }
                        }
                    },
                    course_id: {
                        validators: {
                            notEmpty: {
                                message: 'Course is required'
                            }
                        }
                    },
                    batch_id: {
                        validators: {
                            notEmpty: {
                                message: 'Batch is required'
                            }
                        }
                    },
                    subject_id: {
                        validators: {
                            notEmpty: {
                                message: 'Subject is required'
                            }
                        }
                    },
                    title: {
                        validators: {
                            notEmpty: {
                                message: 'Title is required'
                            }
                        }
                    },
                    staff_id: {
                        validators: {
                            notEmpty: {
                                message: 'Trainer is required'
                            }
                        }
                    },
                    type: {
                        validators: {
                            notEmpty: {
                                message: 'Type is required'
                            }
                        }
                    },
                    plan_date: {
                        validators: {
                            notEmpty: {
                                message: 'Date is required'
                            }
                        }
                    },
                    description: {
                        validators: {
                            notEmpty: {
                                message: 'Description is required'
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

        add_form.addEventListener('submit', function (e) {
            // Prevent default button action
            e.preventDefault();
            var submitButton = $(this).find('button');

            // Validate form before submit
            if (validator) {
                // console.log(validator);
                validator.validate().then(function (status) {
                    // console.log(validator);
                    var formData = new FormData(add_form);

                    if (status == 'Valid') {
                        $(submitButton).attr('data-kt-indicator', 'on').prop('disabled', true);

                        axios
                            .post(
                                ajax_url + 'academic/add_class_plan',
                                new FormData(add_form)
                            )
                            .then(function (e) {
                                console.log(e);
                                if (e.status) {
                                        Swal.fire({
                                            text: "Classes Planed Successfully.",
                                            icon: "success",
                                            buttonsStyling: !1,
                                            confirmButtonText: "Ok, got it!",
                                            customClass: {
                                                confirmButton: "btn btn-primary",
                                            },
                                        });
                                    list_class.DataTable().ajax.reload();
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

    if (list_class) {
        var index = 1;
        var dt = list_class.DataTable({
            dom: small_dom,
            ajax: {
                url: ajax_url + 'academic/list-master-class',

                success: function (d) {
                    // console.log(d);
                    if (d.data && d.data.length) {
                        dt.clear();
                        dt.rows.add(d.data).draw();
                    }
                    else {
                        toastr.error('Table Data Not Found.');
                        DataTableEmptyMessage(list_class);
                        SwalWarning('Notice', `                        Classes not Found..                    `)
                    }
                },
                'error': function (xhr, error, thrown) {
                    // Custom error handling
                    console.log('DataTables Error:', xhr, error, thrown);

                    // Show an alert or a custom message
                    alert('An error occurred while loading data. Please try again.');
                }

            },
            columns: [
                { 'data': null },
                { 'data': 'center_name' },
                { 'data': 'course_name' },
                { 'data': 'subject_name' },
                { 'data': 'title' },
                { 'data': 'staff_name' },
                { 'data': 'plan_date' },
                { 'data': 'notes' },
                { 'data': null },
            ],
            columnDefs: [
                {
                    target: 0,
                    render: function (data, type, row, meta) {
                        return `${meta.row + 1}.`;
                    }
                },
                {
                    targets : 2,
                    render : function(data,type,row){
                        return `${row.course_name}<br/>${row.duration} ${row.duration_type}<br/>${row.batch_name}`;
                    }
                },
                {
                    targets : 7,
                    render : function(data,type,row){
                        if(row.notes){
                            return `<a href="${base_url}upload/${row.notes}" target="_blank" class="btn btn-info btn-xs btn-sm"><i class="fa fa-eye"></i>File</a>`;
                        }
                        else {
                            return '';
                        }
                    }
                },
                {
                    targets: -1,
                    render: function (data, type, row) {
                        // <button class="btn btn-sm btn-light-info edit-record"><i class="fa fa-edit"></i></button>

                        if(is_staff_login == 'N'){
                             return `<button class="btn btn-sm btn-light-danger delete-class" data-id="${row.id}">
                                    <i class="fa fa-trash"></i>
                                </button>`;
                            } else {
                                return ``;
                            }
                       

                    }
                }
            ]
        });
        dt.on('draw', function (e) {
            /*index = 1;
            list_class.EditForm('course/edit-master-subject', 'Edit Subject');

            ki_modal.find('.modal-dialog').addClass('modal-lg');
            list_class.find('.add-paper').on('click', function () {
                rowData = list_class.DataTable().row($(this).closest('tr')).data();
                render_data_paper(rowData);
            })*/
        })
        $(document).on('click', '.delete-class', function () {
            var id = $(this).data('id');
            SwalWarning('Confirmation','Are you sure for delete this class',true,'Delete').then((e) => {
                if (e.isConfirmed) {
                    $.AryaAjax({
                        url: 'academic/delete-master-class',
                        data: { id },
                        success_message: 'Class Deleted Successfully.'
                    }).then((f) => {
                        if(f.status){
                            list_class.DataTable().ajax.reload();
                        }
                        showResponseError(f);
                    });
                }
                else{
                    toastr.warning('Request Aborted');
                }
            })
            
        });
    }

    institue_box.select2({
            placeholder: "Select a Center",
            templateSelection: optionFormatSecond,
            templateResult: optionFormatSecond,
    }).on('change', function () {
        var center_id = $(this).val();
        course_box.html(emptyOption);
        batch_box.html(emptyOption);
        subject_box.html(emptyOption);
        
        if (center_id) {
            $.AryaAjax({
                url: 'student/get_center_courses',
                data: { center_id }
            }).then((rs) => {
                 log(rs);
                if (rs.courses.length) {
                    $.each(rs.courses, (i, course) => {
                        course_box.append(`
                                <option 
                                        value="${course.course_id}" 
                                        data-kt-rich-content-subcontent="${course.duration} ${course.duration_type}" 
                                        >
                                        ${course.course_name}
                                </option>
                            `);
                    })
                    course_box.mySelect2();
                }
            });

            if(is_staff_login == 'N'){
                staff_box.html(emptyOption);
                $.AryaAjax({
                    url: 'center/list-trainer?center='+center_id,
                    data: { }
                }).then((rs) => {
                     log(rs);
                    if (rs.data.length) {
                        $.each(rs.data, (i, tr) => {
                            staff_box.append(`
                                    <option 
                                            value="${tr.id}" 
                                            data-kt-rich-content-subcontent="${tr.role}"
                                            data-kt-rich-content-icon="${tr.image}" 
                                            >
                                            ${tr.name}
                                    </option>
                                `);
                        })
                        staff_box.mySelect2();
                    }
                });
            }
        }
    });

    course_box.select2({
            placeholder: "Select Course",
            templateSelection: optionFormatSecond,
            templateResult: optionFormatSecond,
    }).on('change', function () {
        var center_id = $("#center_id").val();
        var course_id = $(this).val();
        batch_box.html(emptyOption);
        subject_box.html(emptyOption);
        if (course_id) {
            $.AryaAjax({
                url: 'student/get_center_course_batches',
                data: { center_id, course_id }
            }).then((rs) => {
                // log(rs);
                if (rs.batches.length) {
                    $.each(rs.batches, (i, batche) => {
                        batch_box.append(`
                                <option 
                                        value="${batche.id}" 
                                        data-kt-rich-content-subcontent="${batche.duration}" 
                                        >
                                        ${batche.batch_name}
                                </option>
                            `);
                    })
                    batch_box.mySelect2();
                }
            });
        }
    });

    batch_box.select2({
            placeholder: "Select Batch",
            templateSelection: optionFormatSecond,
            templateResult: optionFormatSecond,
    }).on('change', function () {
        var center_id = $("#center_id").val();
        var course_id = $("#course_id").val();
        var batch_id = $(this).val();
        subject_box.html(emptyOption);
        if (batch_id) {
            $.AryaAjax({
                url: 'student/get_course_subjects',
                data: { center_id, course_id, batch_id }
            }).then((rs) => {
                log(rs);
                if (rs.subjects.length) {
                    $.each(rs.subjects, (i, subject) => {
                        subject_box.append(`
                                <option 
                                        value="${subject.id}" 
                                        data-kt-rich-content-subcontent="${subject.subject_code}" 
                                        >
                                        ${subject.subject_name}
                                </option>
                            `);
                    })
                    subject_box.mySelect2();
                }
            });
        }
    });

    if (login_type == 'center') {
        institue_box.trigger("change");
    }

});