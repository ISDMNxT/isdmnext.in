document.addEventListener('DOMContentLoaded', function (e) {
    const delete_url = 'course/delete';
    const form = document.getElementById('add_course');
    var list_url = 'course/list?category=';
    const delet_list_url = 'course/delete_list';
    const save_url = 'course/add';
    const table = $('#course_list');
    const delete_table= $('#deleted_course_list');
    const categorySearch= $('#categorySearch');
    const columns = [
        { 'data': 'course_name' },
        { 'data': 'category' },
        { 'data': 'duration' },
        { 'data': 'fees' },
        { 'data': 'royality_fees' },
        { 'data': 'status' },
        { 'data': null }
    ];
    // var dt = '';
    if (table.length) {
        const dt = table.DataTable({
            dom: small_dom,
            buttons: [],
            ajax: {
                url: ajax_url + list_url + categorySearch.val(),
                success: function (d) {
                    // console.log(d);
                    if (d.data && d.data.length) {
                        dt.clear();
                        dt.rows.add(d.data).draw();
                    }
                    else {
                        toastr.error('Table Data Not Found.');
                        DataTableEmptyMessage(table);
                    }
                },
                error: function (a, b, v) {
                    console.warn(a.responseText);
                }
            },
            columns: columns,
            columnDefs: [
                {
                    targets: 1,
                    render: function (data, type, row) {
                        if(row.category)
                            return `<label class="badge badge-dark">${row.category}</label>`;
                        else
                            return  `<label class="badge badge-danger">Category Deleted</label>`;
                    }
                },
                {
                    targets: 2,
                    render: function (data, type, row) {
                        var badgeClass = duration_badge(row.duration_type, duration_colors);//) ? duration_colors[row.duration_type] : 'danger';
                        return `<lable class="badge badge-${badgeClass}"> ${course_duration_humnize_without_ordinal(row.duration, row.duration_type)}</lable>`;//row.duration+ ` </>`;
                    }
                },
                {
                    targets: 3,
                    render: function (data, type, row) {
                        return (data ? data + ` <i class="fa fa-rupee"></i>` : '-') ;
                    }
                },
                {
                    targets: 4,
                    render: function (data, type, row) {
                        return (data ? data  + ` <i class="fa fa-rupee"></i>` : '-');
                    }
                },
                {
                    targets: 5,
                    render: function (data, type, row, meta) {
                        if(row.status == 2){
                            return `<lable style="color:Red">Pending</lable>`;
                        }
                        if(row.status == 1){
                            return `<lable style="color:Green">Approved</lable>`;
                        }
                        if(row.status == 3){
                            return `<lable style="color:Orange">Rejected</lable>`;
                        }
                    }
                },
                {
                    targets: -1,
                    data: null,
                    orderable: false,
                    className: 'text-end',
                    render: function (data, type, row) {
                        let btHTML = `<div class="btn-group">`;
                        if(row.status == 1){
                            btHTML += `<buttons class="btn btn-secondary btn-xs btn-sm view-record"><i class="ki-outline ki-eye"></i> View</buttons>`;
                        }
                        if(login_type == 'admin'){

                            btHTML += `<buttons class="btn btn-primary btn-xs btn-sm edit-record-nn"><i class="ki-outline ki-pencil"></i> Edit</buttons>`;
                            
                            if(row.hasOwnProperty('parent_id')){
                                btHTML += `<button class="btn btn-info btn-xs btn-sm course-setting"><i class="fa fa-cog"></i></button>`;
                            }
                            if(row.status == 2){
                                btHTML += `<a class="btn btn-success btn-xs btn-sm apps-marks" data-id="${row.id}" ><i class="ki-outline ki-copy-success"></i> Approve</a>
                                <a class="btn btn-secondary btn-xs btn-sm rejs-marks" data-id="${row.id}" ><i class="ki-outline ki-eraser"></i> Reject</a>`;
                            }
                             btHTML += `${deleteBtnRender(0, row.course_id)}`;
                        } else {
                            if(row.status == 2 && login_type == 'center'){
                                btHTML += `${deleteBtnRender(0, row.course_id)}`;
                            }
                        }
                       
                        btHTML += `<div>`;
                        return btHTML;
                    }
                }
            ]
        });
        dt.on('init', function () {
            console.log('DataTable initialized');
        });

        dt.on('draw', function (e) {
            //table.EditForm('course/edit', 'Edit Course');

            $('.edit-record-nn').on('click', function () {
                var rowData = table.DataTable().row($(this).closest('tr')).data();
                if (rowData.hasOwnProperty('course_id')) {
                    $.AryaAjax({
                        url: 'course/edit_course',
                        data: { id: rowData.course_id }
                    }).then((responseee) => {
                        if (responseee.status) {
                           var rowData = responseee.rowData;
                           if (rowData) {
                            var templateSource = document.getElementById('formTemplate');
                            if (templateSource) {
                                Handlebars.registerHelper('eq', function(arg1, arg2) {
                                    return arg1 === arg2;
                                });
                                templateSource = templateSource.innerHTML;
                                var template = Handlebars.compile(templateSource);
                                var formTemplate = template(rowData);
                                myModel('Edit Course', formTemplate, 'course/edit').then((d) => {
                                    // log(d);

                                    if (d.status) {
                                        table.DataTable().ajax.reload();
                                        ki_modal.modal('hide');

                                    }
                                    else {
                                        
                                        if ('errors' in d) {
                                            log(d.errors);
                                            $.each(d.errors, function (i, v) {
                                                toastr.error(v);
                                            });

                                        }
                                        else {
                                            if(d.error){
                                                mySwal('Record not Update.',d.error, 'error');
                                            } else {
                                                mySwal('Something Went Wrong.', 'Record not Update.', 'error');
                                            }
                                            
                                        }
                                    }
                                });
                            }
                            else
                                SwalWarning('Template not found.', `${title} form template not found.`);
                        }
                        }
                    });
                }
            });

            const handle = handleDeleteRows(delete_url);
            $('.course-setting').on('click', function () {
                var rowData = table.DataTable().row($(this).closest('tr')).data();
                // console.log(rowData.course_id);
                if (rowData.hasOwnProperty('course_id') && rowData.hasOwnProperty('parent_id')) {
                    $.AryaAjax({
                        url: 'course/setting_form',
                        data: { id: rowData.course_id },
                        loading_message: 'Loading Edit Form...'
                    }).then((response) => {
                        // console.log(response);
                        showResponseError(response);
                        if (response.status) {
                            myModel('Change Setting', response.html, true);
                        }
                    });
                }
            });

            $('.view-record').on('click', function () {
                var rowData = table.DataTable().row($(this).closest('tr')).data();
                if (rowData.hasOwnProperty('course_id')) {
                    $.AryaAjax({
                        url: 'course/view_course',
                        data: { id: rowData.course_id },
                        loading_message: 'Loading View Course'
                    }).then((response) => {
                        showResponseError(response);
                        if (response.status) {
                            myModel('View Course', response.html, true);
                        }
                    });
                }
            });

            handle.done(function (e) {
                // console.log(e);
                table.DataTable().ajax.reload();
                delete_table.DataTable().ajax.reload();
            });
        });
    }
    if(delete_table.length){
        const ddt = delete_table.DataTable({
            dom: small_dom,
            buttons: [],
            ajax: {
                url: ajax_url + delet_list_url
            },
            columns: columns,
            columnDefs: [
                {
                    targets: 1,
                    render: function (data, type, row) {
                        if(row.category)
                            return `<label class="badge badge-dark">${row.category}</label>`;
                        else
                            return  `<label class="badge badge-danger">Category Deleted</label>`;
                    }
                },
                {
                    targets: 2,
                    render: function (data, type, row) {
                        var badgeClass = duration_badge(row.duration_type, duration_colors);//) ? duration_colors[row.duration_type] : 'danger';
                        return `<lable class="badge badge-${badgeClass}"> ${course_duration_humnize_without_ordinal(row.duration, row.duration_type)}</lable>`;//row.duration+ ` </>`;
                    }
                },
                {
                    targets: 3,
                    render: function (data, type, row) {
                        return (data ? data + ` <i class="fa fa-rupee"></i>` : '-') ;
                    }
                },
                {
                    targets: 4,
                    render: function (data, type, row) {
                        return (data ? data  + ` <i class="fa fa-rupee"></i>` : '-');
                    }
                },
                {
                    targets: 5,
                    render: function (data, type, row, meta) {
                        if(row.status == 2){
                            return `<lable style="color:Red">Pending</lable>`;
                        }
                        if(row.status == 1){
                            return `<lable style="color:Green">Approved</lable>`;
                        }
                        if(row.status == 3){
                            return `<lable style="color:Orange">Rejected</lable>`;
                        }
                    }
                },
                {
                    targets: -1,
                    data: null,
                    orderable: false,
                    className: 'text-end',
                    render: function (data, type, row) {
                        return `<div class="btn-group">                                
                                <button class="btn btn-sm btn-light-primary undelete-btn">
                                    <i class="fa fa-arrow-left"></i> Move to list
                                </button>
                                <button class="btn btn-sm btn-light-danger delete-btn">
                                    <i class="fa fa-trash"></i> Delete
                                </button>
                            </div>`;
                    }
                }
            ]
        });
        ddt.on('draw',function(e){
            delete_table.unDeleteEvent('course', 'Course','course_id');
            $('.delete-btn').click(function () {
                // log(table.DataTable().data)
                var rowData = delete_table.DataTable().row($(this).closest('tr')).data();
                // log(rowData);
                SwalWarning('Confirmation!', 'Are you sure for permanently delete .', true, 'Delete IT').then((r) => {
                    // log(r);
                    if (r.isConfirmed) {
                        $.AryaAjax({
                            url: 'course/param_delete',
                            data: rowData
                        }).then((e) => {
                            if (e.status) {
                                SwalSuccess('Success', 'Deleted Successfully..').then((e) => {
                                    if(e.isConfirmed){
                                        delete_table.DataTable().ajax.reload();
                                    }
                                });
                            }
                            showResponseError(e);
                        });
                    }
                });
            });
        })
    }
    if (form) {
        var validator = FormValidation.formValidation(
            form,
            {
                fields: {
                    course_name: {
                        validators: {
                            notEmpty: {
                                message: 'Enter A Valid Course Name'
                            }
                        }
                    },
                    category_id: {
                        validators: {
                            notEmpty: {
                                message: 'Select a course category'
                            }
                        }
                    },
                    duration: {
                        validators: {
                            notEmpty: {
                                message: 'Please Enter duration'
                            },
                            numeric: {
                                message: 'Please enter a valid Duration.'
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
            var test = save_ajax(form, save_url, validator);
            test.done(function (data) {
                // alert('res');
                // console.log(data);
                table.DataTable().ajax.reload();
            })
        });
    }

    $(document).on('change', '.set-course-parent-certi', function () {
        var id = $(this).data('id');
        var parent_id = $(this).val();
        // alert(id)
        $.AryaAjax({
            url: 'course/update_multi_certi',
            data: { id: id, parent_id: parent_id },
            success_message: 'Setting Update Successfully.'
        }).then((er) => {
            // log(er)
            if (er.status)
                ki_modal.modal('hide')
        });
    });

    $(document).on('click', '.apps-marks', function () {
        var id = $(this).data('id');
        var status = "A";
        SwalWarning('Confirmation','Are you sure you want to Approve this Course?',true,'Approve').then((e) => {
            if (e.isConfirmed) {
                $.AryaAjax({
                    url: 'course/approve-or-reject',
                    data: { id, status },
                    success_message: 'Course Approved Successfully.'
                }).then((f) => {
                    if(f.status){
                        table.DataTable().ajax.reload();
                    }
                    showResponseError(f);
                });
            }
            else{
                toastr.warning('Request Aborted');
            }
        })
    });

    $(document).on('click', '.rejs-marks', function () {
        var id = $(this).data('id');
        var status = "R";
        SwalWarning('Confirmation','Are you sure you want to Reject this Course',true,'Reject').then((e) => {
            if (e.isConfirmed) {
                $.AryaAjax({
                    url: 'course/approve-or-reject',
                    data: { id, status },
                    success_message: 'Course Rejected Successfully.'
                }).then((f) => {
                    if(f.status){
                        table.DataTable().ajax.reload();
                    }
                    showResponseError(f);
                });
            }
            else{
                toastr.warning('Request Aborted');
            }
        })
    });

    $(document).on('change', '.categorySearch', function () {
        list_url = 'course/list?category='+$(this).val();
        table.DataTable().ajax.url(ajax_url + list_url).load();
    });


});
