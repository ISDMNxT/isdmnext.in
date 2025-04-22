document.addEventListener('DOMContentLoaded', function (e) {
    const table = $(document).find('#list_jobs').DataTable({
        searching: true,
        'ajax': {
            'url': ajax_url + 'employer/list_jobs',
            error: function (a, v, c) {
                log(a.responseText)
            }
        },
        'columns': [
            { 'data': null },
            { 'data': 'job_title' },
            { 'data': 'center_name' },
            { 'data': 'industry' },
            // { 'data': 'department' },
            { 'data': 'education' },
            { 'data': 'role' },
            { 'data': 'openings' },
            { 'data': null }
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
                orderable: false,
                render: function (data, type, row) {
                    return `${data}`;
                }
            },
            {
                targets: 2,
                orderable: false,
                render: function (data, type, row) {
                    return `<label class="text-dark">${data}</label>`;
                }
            },
            {
                targets: -1,
                data: null,
                orderable: false,
                printable: false,
                className: 'text-end',
                render: function (data, type, row) {
                    return `<div class="btn-group">
                                <a href="${base_url}employer/job_mgmt?id=${row.id}&type=V" class="btn btn-sm btn-info">
                                    <i class="fa fa-eye"></i>
                                </a>
                                <a href="${base_url}employer/job_mgmt?id=${row.id}&type=E" class="btn btn-sm btn-light-primary">
                                    <i class="fa fa-edit"></i>
                                </a>
                                <button class="btn btn-sm btn-light-danger delete-btn">
                                    <i class="fa fa-trash"></i>
                                </button>
                            </div>`;
                }
            }
        ]
    })
    table.on('draw', function () {
        $('#list_jobs').DeleteEvent('jobs', 'Job');
    });
});
