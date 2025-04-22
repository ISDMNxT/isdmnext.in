document.addEventListener('DOMContentLoaded', function (e) {
    const list_url = 'student/list_student_request';
    const table = $('#student_list');
    const columns = [
        { 'data': 'student_name' },
        { 'data': 'center_name' },
        { 'data': 'date' },
        { 'data': 'status' },
        { 'data': null }
    ];
    // var dt = '';
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
                    targets: 0,
                    render: function (data, type, row) {
                        return `${row.student_name}<label class="badge badge-dark">${row.roll_no}</label>`;
                    }
                },
                {
                    targets: 3,
                    render: function (data, type, row, meta) {
                        if(row.status == 1){
                            return `<lable style="color:Red">Pending</lable>`;
                        }
                        if(row.status == 2){
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
                            if(row.status == 1 && login_type == 'admin'){
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

    $(document).on('click', '.apps-marks', function () {
        var id = $(this).data('id');
        var status = "A";
        SwalWarning('Confirmation','Are you sure you want to Approve this Request?',true,'Approve').then((e) => {
            if (e.isConfirmed) {
                $.AryaAjax({
                    url: 'student/approve-or-reject',
                    data: { id, status },
                    success_message: 'Request Approved Successfully.'
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
        SwalWarning('Confirmation','Are you sure you want to Reject this Request',true,'Reject').then((e) => {
            if (e.isConfirmed) {
                $.AryaAjax({
                    url: 'student/approve-or-reject',
                    data: { id, status },
                    success_message: 'Request Rejected Successfully.'
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
