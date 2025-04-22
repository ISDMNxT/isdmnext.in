document.addEventListener('DOMContentLoaded',function(e){
    const table = $('#marksheets');
    var list_url = 'student/list-marksheets-request?center=';
    const searchCenter= $('#searchCenter');
    var dt = table.DataTable({
        ajax : {
            url: ajax_url + list_url + searchCenter.val(),
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
            error : function(a,v,c){
                log(a.responseText);
            }
        },
        columns : [
            {'data' : 'center_name'},
            {'data' : 'student_name'},
            {'data' : 'course_name'},
            {'data' : 'exam_title'},
            {'data' : 'date'},
            {'data' : 'status'},
            {'data' : null}
        ],
        columnDefs : [
            {
                targets: 1,
                render: function (data, type, row, meta) {
                    return `${row.student_name}(${row.roll_no})`;
                }
            },
            {
                targets: 4,
                render: function (data, type, row, meta) {
                    var details = ``;
                        if(row.date != ''){
                            details = details + `<label class="badge badge-info">${row.date}</label>`;
                        }
                        if(row.batch_from_date != ''){
                            details = details + `<br/><label class="badge badge-secondary">Batch From - ${row.batch_from_date}</label>`;
                        }
                        if(row.batch_to_date != ''){
                            details = details + `<br/><label class="badge badge-secondary">Batch To - ${row.batch_to_date}</label>`;
                        }
                        
                        return details;
                }
            },
            {
                targets: 5,
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
                targets : -1,
                orderable : false,
                render : function(data,type,row){
                    if(login_type == 'center'){
                        if(row.status == 1){
                            return `<div class="btn-group">
                                ${deleteBtnRender(1, row.id)}
                            </div>`;
                        } else {
                            return `<div class="btn-group"></div>`;
                        }
                    } else if(login_type == 'admin') {
                        if(row.status == 2 || row.status == 3){
                            return `<div class="btn-group">
                                ${deleteBtnRender(1, row.id)}
                            </div>`;
                        } else {
                            return `<div class="btn-group">
                                <a class="btn btn-success btn-xs btn-sm apps-marks" data-id="${row.id}" ><i class="ki-outline ki-copy-success"></i> Approve</a>
                                <a class="btn btn-secondary btn-xs btn-sm rejs-marks" data-id="${row.id}" ><i class="ki-outline ki-eraser"></i> Reject</a>
                                ${deleteBtnRender(1, row.id, "Marksheet Request")}
                            </div>`;
                        }
                    }
                }
            }
        ]
    }).on('draw',function(){
        handleDeleteRows('student/deletemr').then((e) => {
            location.href = `${base_url}student/generate-marksheet-certificate-request`;
        });
    });

    $(document).on('click', '.apps-marks', function () {
        var id = $(this).data('id');
        var status = "A";
        SwalWarning('Confirmation','Are you sure you want to Approve Generate Marksheet & Certificate?',true,'Approve').then((e) => {
            if (e.isConfirmed) {
                $.AryaAjax({
                    url: 'student/create-marksheet-certificate',
                    data: { id, status },
                    success_message: 'Generate Marksheet & Certificate Approved Successfully.'
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
        SwalWarning('Confirmation','Are you sure you want to Reject Generate Marksheet & Certificate Request?',true,'Reject').then((e) => {
            if (e.isConfirmed) {
                $.AryaAjax({
                    url: 'student/create-marksheet-certificate',
                    data: { id, status },
                    success_message: 'Generate Marksheet & Certificate Rejected Successfully.'
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

    searchCenter.select2({
        placeholder: "Select a Center",
        templateSelection: optionFormatSecond,
        templateResult: optionFormatSecond,
    }).on('change', function () {
        list_url = 'student/list-marksheets-request?center='+$(this).val();
        table.DataTable().ajax.url(ajax_url + list_url).load();
    });
});