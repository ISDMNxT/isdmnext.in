document.addEventListener('DOMContentLoaded', function (e) {
    const table = $(document).find('#list_employer').DataTable({
        searching: true,
        'ajax': {
            'url': ajax_url + 'employer/list_employer',
            error: function (a, v, c) {
                log(a.responseText)
            }
        },
        'columns': [
            { 'data': null },
            { 'data': 'name' },
            { 'data': 'institute_name' },
            { 'data': 'email' },
            { 'data': 'contact_number' },
            { 'data': 'wallet' },
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
                printable: false,
                render: function (data, type, row) {
                    return `<label class="text-dark">${data}</label>`;
                }
            },
            {
                targets: 3,
                printable: false,
                render: function (data, type, row) {
                    return `<a href="mailto:${data}">${data}</a>`;
                }
            },
            {
                targets: 4,
                printable: false,
                render: function (data, type, row) {
                    return `<a href="tel:${data}">${data}</a>`;
                }
            },
            {
                targets: -2,
                render: function (data, type, row) {
                    //return `${inr} ${numberFormat(data)}`;
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
                                <a href="${base_url}employer/employer_profile/${btoa(row.id)}" class="btn btn-sm btn-info">
                                    <i class="fa fa-eye"></i>
                                </a>
                                <button class="btn btn-sm btn-light-primary edit-form-btn" data-id="${row.id}">
                                    <i class="fa fa-edit"></i>
                                </button>
                                <button class="btn btn-sm btn-light-danger delete-btn">
                                    <i class="fa fa-trash"></i>
                                </button>
                            </div>`;
                }
            }
        ]
    })
    table.on('draw', function () {

        $('#list_employer').DeleteEvent('centers', 'Employer')
            .EditAjax('employer/edit-form', 'Employer');
        table.on('click', '.load-wallet', function () {
            let centre = $('#list_employer').DataTable().row($(this).closest('tr')).data();
            myModel(`Load Wallet of <b class="text-success">${centre.institute_name}</b>`, `
                    <div class="d-flex flex-column mb-8 fv-row">
                        <label class="required">Description</label>
                        <input type="number" class="form-control form-control-solid" placeholder="Enter Amount" name="amount" />
                    </div>
                    <div class="d-flex flex-column mb-8 fv-row">
                        <label>Description</label>
                        <textarea class="form-control w-100" rows="3" placeholder="Description"></textarea>
                    </div>
            `).then((e) => {

               
                const inputEl = $(e.modal).find('[name="amount"]');
                const inputEq = $(e.modal).find('[name="title"]');
                $(e.modal).find('form').submit(r => {
                    r.preventDefault();
                    let amount          = inputEl.val();
                    let description     = $(e.modal).find('textarea').val();

                    if($.trim(amount) == ''){
                        SwalWarning(`Please Enter Amount ${inr}`);
                        return false;
                    }

                    if(amount < 100){
                        SwalWarning(`Please Enter amount minimum 100 ${inr}`);
                        return false;
                    }

                    if (amount) {
                        $.AryaAjax({
                            url: 'employer/employer-wallet-load',
                            data: {
                                name: centre.institute_name,
                                centre_id: centre.id,
                                amount: amount,
                                description: description,
                                closing_balance: centre.wallet,
                            }
                        }).then(result => {
                            SwalSuccess('Wallet Loaded Successfully.').then(ok => {
                                if (ok.isConfirmed) {
                                    $('#list_employer').DataTable().ajax.reload();
                                    ki_modal.modal('hide');
                                    location.href = location.href;
                                }
                            });
                        });
                    }
                });
            });
        });
    });
});
