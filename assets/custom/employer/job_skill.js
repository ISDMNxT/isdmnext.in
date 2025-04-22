document.addEventListener('DOMContentLoaded', function (e) {
    const form          = document.getElementById('job_skill');
    const list_url      = 'employer/list-skill';
    const save_url      = 'employer/add-skill';
    const delete_url    = 'employer/delete-skill';
    const table         = $('#list_job_skill');

    const columns = [
        { 'data': null },
        { 'data': 'skill' },
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
            EditForm(table,'employer/edit-skill','Edit Job Skill');
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
                    skill: {
                        validators: {
                            notEmpty: {
                                message: 'Job Skill Name is required'
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
