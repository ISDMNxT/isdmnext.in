document.addEventListener('DOMContentLoaded', function (e) {
    var list_url = 'center/list-master-franchise';
    const table = $('#list_center');
    var dt = table.DataTable({
        searching: true,
        'ajax': {
            'url': ajax_url + list_url,
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
            { 'data': 'institute_name' },
            { 'data': 'email' },
            { 'data': 'contact_number' },
            { 'data': 'center_full_address' },
            { 'data': 'wallet' },
            { 'data': null }
            // Add more columns as needed
        ],
        'columnDefs': [
            {
                targets: 0,
                printable: false,
                render: function (data, type, row) {
                    return `${data}`;
                }
            },
            {
                targets: 1,
                printable: false,
                render: function (data, type, row) {
                    return `<label class="text-dark">${data}</label>`;
                }
            },
            {
                targets: 2,
                printable: false,
                render: function (data, type, row) {
                    return `<a href="mailto:${data}">${data}</a>`;
                }
            },
            {
                targets: 3,
                printable: false,
                render: function (data, type, row) {
                    return `<a href="tel:${data}">${data}</a>`;
                }
            },
            {
                targets: 4,
                render: function (data, type, row) {
                    return `<span class="text-center">${data} </span>`;
                }
            },
            {
                targets: -2,
                render: function (data, type, row) {
                    return `<span class="fs-3 text-dark text-center fw-bold">${inr} ${numberFormat(data)} </span><button class="btn btn-sm btn-primary w-100 p-1 load-wallet">&nbsp;<i class="fa fa-plus"></i></button>`;
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
                                <buttons class="btn btn-secondary btn-xs btn-sm view-recordnnn" data-id="${row.id}"><i class="ki-outline ki-eye"></i>Center</buttons>
                                <a class="btn btn-secondary btn-xs btn-sm view-recordnnn" href="${base_url}center/financial?id=${row.id}" target="_blank"><i class="ki-outline ki-eye"></i>Transaction</a>
                                <a href="${base_url}center/add-master-franchise?id=${btoa(row.id)}" class="btn btn-sm btn-light-primary">
                                    <i class="fa fa-edit"></i>Edit
                                </a>
                                <a class="btn btn-sm btn-light-danger delete-btn">
                                    <i class="fa fa-trash"></i>Delete
                                </a>
                            </div>`;
                }
            }
        ]
    }).on('draw', function () {
        $('#list_center').DeleteEvent('centers', 'Center');

        $('.view-recordnnn').on('click', function () {
            var rr = $(this).data('id');
            if (parseInt(rr) > 0) {
                $.AryaAjax({
                    url: 'center/view_institute',
                    data: { id: rr },
                    loading_message: 'Loading View Institute'
                }).then((response) => {
                    showResponseError(response);
                    if (response.status) {
                        myModel('View Institute', response.html, true);
                    }
                });
            }
        });

        table.on('click', '.load-wallet', function () {
            let centre          =   $('#list_center').DataTable().row($(this).closest('tr')).data();
            let modalTitle      =   `Add Other Income of <b class="text-success">${centre.institute_name}</b>`;
            let modalBody       =   `<div class="d-flex flex-column mb-8 fv-row">
                                        <label class="required">Amount</label>
                                        <input type="number" class="form-control form-control-solid" placeholder="Enter Amount" name="amount" />
                                    </div>
                                    <div class="d-flex flex-column mb-8 fv-row">
                                        <label class="required">Title</label>
                                        <input type="text" class="form-control form-control-solid" placeholder="Enter Title" name="title" />
                                    </div>
                                    <div class="d-flex flex-column mb-8 fv-row">
                                        <label>Description</label>
                                        <textarea  class="form-control w-100" rows="3" placeholder="Description"></textarea>
                                    </div>`;
            myModel(modalTitle,modalBody).then((e) => {
                const inputEl = $(e.modal).find('[name="amount"]');
                const inputEQ = $(e.modal).find('[name="title"]');
                $(e.modal).find('form').submit(r => {
                    r.preventDefault();
                    let amount = inputEl.val();
                    let title = inputEQ.val();
                    let description = $(e.modal).find('textarea').val();

                    if($.trim(amount) == ''){
                        SwalWarning(`Please Enter Amount ${inr}`);
                        return false;
                    }

                    if(amount < 100){
                        SwalWarning(`Please Enter amount minimum 100 ${inr}`);
                        return false;
                    }

                    if($.trim(title) == ''){
                        SwalWarning(`Please Enter Title`);
                        return false;
                    }

                    if (amount && title) {
                        $.AryaAjax({
                            url: 'ajax/master-wallet-load',
                            data: {
                                name: centre.institute_name,
                                centre_id: centre.id,
                                title: title,
                                amount: amount,
                                description: description
                            }
                        }).then(result => {
                            SwalSuccess('Other Income Added Successfully.').then(ok => {
                                if (ok.isConfirmed) {
                                    $('#list_center').DataTable().ajax.reload();
                                    ki_modal.modal('hide');
                                    location.href = location.href;
                                }
                            });
                        });
                    }
                });
            });
        })
    });

    
});
