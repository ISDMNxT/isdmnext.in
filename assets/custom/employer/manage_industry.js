document.addEventListener('DOMContentLoaded', function (e) {
    const form          = document.getElementById('job_industry');
    const list_url      = 'employer/list-industry';
    const save_url      = 'employer/add-industry';
    const delete_url    = 'employer/delete-industry';
    const table         = $('#list_job_industry');

    const columns = [
        { 'data': null },
        { 'data': 'industry' },
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
                }
            },
            columns: columns,
            columnDefs: [
                {
                    targets: 0,
                    orderable: false,
                    render: function (data, type, row, meta) {
                        return `${meta.row + 1}.`;
                    }
                },
                {
                    targets: -1,
                    data: null,
                    orderable: false,
                    className: 'text-end',
                    render: function (data, type, row) {
                        // console.log(data);
                        return `<div class="btn-group">
                                    <buttons class="btn btn-primary btn-xs btn-sm edit-record"><i class="ki-outline ki-pencil"></i> Edit</buttons>
                                    ${deleteBtnRender(1, row.id)}
                                </div>
                                `;
                    }
                }
            ]
        });
        dt.on('draw', function (e) {
            EditForm(table,'employer/edit-industry','Edit Industry');
            const handle = handleDeleteRows(delete_url);
            handle.done(function (e) {
                // console.log(e);
                table.DataTable().ajax.reload();
            });
        })
    }
    if (form) {
        var validator = FormValidation.formValidation(
            form,
            {
                fields: {
                    industry: {
                        validators: {
                            notEmpty: {
                                message: 'Industry Name is required'
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
                table.DataTable().ajax.reload();
            })
        });
    }
});
