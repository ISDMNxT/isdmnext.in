document.addEventListener('DOMContentLoaded', function (e) {
    const list_url = 'course/listar';
    const table = $('#course_list');

    if(login_type == 'admin'){
        const columns = [
            { 'data': 'course_name' },
            { 'data': 'category' },
            { 'data': 'duration' },
            { 'data': 'fees' },
            { 'data': 'royality_fees' },
            { 'data': 'status' },
            { 'data': null }
        ];

        if (table.length) {
            const dt = table.DataTable({
                dom: small_dom,
                buttons: [],
                ajax: {
                    url: ajax_url + list_url,
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
                                if(row.status == 2 && login_type == 'admin'){
                                    btHTML += `<a class="btn btn-success btn-xs btn-sm apps-marks" data-id="${row.id}" ><i class="ki-outline ki-copy-success"></i> Approve</a>
                                    <a class="btn btn-secondary btn-xs btn-sm rejs-marks" data-id="${row.id}" ><i class="ki-outline ki-eraser"></i> Reject</a>`;
                                }
                           
                            btHTML += `<div>`;
                            return btHTML;
                        }
                    }
                ]
            });
            dt.on('draw', function (e) {})
        }
    } else {
        const columns = [
            { 'data': 'course_name' },
            { 'data': 'category' },
            { 'data': 'duration' },
            { 'data': 'status' }
        ];

        if (table.length) {
            const dt1 = table.DataTable({
                dom: small_dom,
                buttons: [],
                ajax: {
                    url: ajax_url + list_url,
                    success: function (d) {
                        // console.log(d);
                        if (d.data && d.data.length) {
                            dt1.clear();
                            dt1.rows.add(d.data).draw();
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
                    }
                ]
            });
            dt1.on('draw', function (e) {})
        }
    }
    
    // var dt = '';
    

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
                        location.href = location.href;
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
                        location.href = location.href;
                    }
                    showResponseError(f);
                });
            }
            else{
                toastr.warning('Request Aborted');
            }
        })
    });
});
