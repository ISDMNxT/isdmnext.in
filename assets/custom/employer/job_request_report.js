document.addEventListener('DOMContentLoaded', function (e) {
    const table = $(document).find('#list_jobs').DataTable({
        searching: true,
        'ajax': {
            'url': ajax_url + 'employer/job_request_report',
            error: function (a, v, c) {
                log(a.responseText)
            }
        },
        'columns': [
            { 'data': null },
            { 'data': 'institute_name' },
            { 'data': 'student_name' },
            { 'data': null },
            { 'data': 'job_date' },
            { 'data': 'status' }
        ],
        'columnDefs': [
            {
                targets: 0,
                render: function (data, type, row, meta) {
                    return `${meta.row + 1}.`;
                }
            },
            {
                target: 1,
                render: function (data, type, row) {
                    return `${data}`;
                }
            },
            {
                targets: 2,
                render: function (data, type, row) {
                    return `${data}`;
                }
            },
            {
                targets: 3,
                render: function (data, type, row) {
                     return `${row.job_title}<br/>${row.experience} years<br/>${row.job_type}`;
                }
            },
            {
                targets: 4,
                render: function (data, type, row) {
                    return `${data}`;
                }
            },
            {
                targets: -1,
                render: function (data, type, row) {
                    if(row.status == 'pending'){
                        return `<lable style="color:Red">Pending</lable>`;
                    }

                    if(row.status == 'approved'){
                        return `<lable style="color:Green">Accepted</lable>`;
                    }

                    if(row.status == 'pending'){
                        return `<lable style="color:Orange">Rejected</lable>`;
                    }
                    
                }
            }
        ]
    });
});
