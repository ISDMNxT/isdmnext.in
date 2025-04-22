document.addEventListener('DOMContentLoaded', function (e) {
    const table = $(document).find('#list_packages').DataTable({
        searching: true,
        'ajax': {
            'url': ajax_url + 'employer/packages-list',
            error: function (a, v, c) {
                log(a.responseText)
            }
        },
        'columns': [
            { 'data': null },
            { 'data': 'packages' },
            { 'data': 'packages_details' },
            { 'data': 'interview_charges' },
            { 'data': 'apply_charges' },
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
                    return `<div class="col-md-12"><div class="card text-center p-3">${data}</div></div>`;
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
                                <a href="${base_url}employer/manage-packages?id=${row.id}" class="btn btn-sm btn-light-primary">
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
        $('#list_packages').DeleteEvent('emp_packages', 'Packages');
    });
});
