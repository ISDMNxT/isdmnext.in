document.addEventListener('DOMContentLoaded', function (e) {
    var list_url = 'center/list-staff?center=';
    const searchCenter= $('#searchCenter');
    const table = $('#list_center');
    var dt = table.DataTable({
        searching: true,
        'ajax': {
            'url': ajax_url + list_url + searchCenter.val(),
            success: function (d) {
                if (d.data && d.data.length) {
                    dt.clear();
                    dt.rows.add(d.data).draw();
                }
                else {
                    toastr.error('Table Data Not Found.');
                    DataTableEmptyMessage(table);
                }
            },
            error: function (a, v, c) {
                log(a.responseText)
            }
        },
        'columns': [
            { 'data': 'name' },
            { 'data': 'email' },
            { 'data': 'contact_number' },
            { 'data': 'role' },
            { 'data': 'center_name' },
            { 'data': null }
            // Add more columns as needed
        ],
        'columnDefs': [
            {
                targets: 0,
                printable: false,
                render: function (data, type, row) {
                    return `<label class="text-dark">${data}</label>`;
                }
            },
            {
                targets: 1,
                printable: false,
                render: function (data, type, row) {
                    return `<a href="mailto:${data}">${data}</a>`;
                }
            },
            {
                targets: 2,
                printable: false,
                render: function (data, type, row) {
                    return `<a href="tel:${data}">${data}</a>`;
                }
            },
            {
                targets: 3,
                render: function (data, type, row) {
                    return `<span class="fs-3 text-dark text-center fw-bold">${data} </span>`;
                }
            },
            {
                targets: 4,
                printable: false,
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
                    if(login_type == 'master_franchise'){
                        return `<div class="btn-group">
                                
                            </div>`;
                    } else {
                        return `<div class="btn-group">
                                <a href="${base_url}center/add-staff?id=${btoa(row.id)}" class="btn btn-sm btn-light-primary">
                                    <i class="fa fa-edit"></i>
                                </a>
                                <button class="btn btn-sm btn-light-danger delete-btn">
                                    <i class="fa fa-trash"></i>
                                </button>
                            </div>`;
                    }
                    
                }
            }
        ]
    }).on('draw', function () {
        $('#list_center').DeleteEvent('centers', 'Center');
    });

    searchCenter.select2({
        placeholder: "Select a Center",
        templateSelection: optionFormatSecond,
        templateResult: optionFormatSecond,
    }).on('change', function () {
        list_url = 'center/list-staff?center='+$(this).val();
        table.DataTable().ajax.url(ajax_url + list_url).load();
    });
});
