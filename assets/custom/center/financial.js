document.addEventListener('DOMContentLoaded', function (e) {
    if(parseInt($("#master_id").val()) > 0){
        const table = $('#list_center');
        var dt = table.DataTable({
            searching: true,
            'ajax': {
                'url': ajax_url + 'center/list_finance?master_id='+$("#master_id").val(),
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
                { 'data': null },
                { 'data': 'atimestamp' },
                { 'data': 'title' },
                { 'data': 'institute_name' },
                { 'data': 'total_amount' },
                { 'data': 'amount' },
                { 'data': 'wallet_status' }
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
                        var details = ``;
                        if(row.type != ''){
                            let replacedString = row.type.replace("_", " ");
    
                            // Capitalize the first letter of each word
                            replacedString = replacedString.replace(/\b\w/g, function(match) {
                                return match.toUpperCase();
                            });

                            details = details + `<label class="badge badge-info">${replacedString}</label>`;
                        }
                        if(row.title != ''){
                            details = details + `<br/><label class="badge badge-warning">${row.title}</label>`;
                        }
                        if($.trim(row.description) != ''){
                            details = details + `<br/><label class="badge badge-secondary">${row.description}</label>`;
                        }
                        
                        return details;
                    }
                },
                {
                    targets: 3,
                    render: function (data, type, row) {
                        if ($.trim(row.institute_name) != ''){
                            return `${data}`;
                        } else {
                            return `Admin`;
                        }
                    }
                },
                {
                    targets: 4,
                    render: function (data, type, row) {
                            return `${inr} ${numberFormat(data)}`;
                    }
                },
                {
                    targets: 5,
                    render: function (data, type, row) {
                        if(row.wallet_status == 'credit'){
                            return `<label class="text-success">${inr} ${numberFormat(data)}</label>`;
                        } else {
                            return `<label class="text-danger">${inr} ${numberFormat(data)}</label>`;
                        }
                    }
                },
                {
                    targets: 6,
                    render: function (data, type, row) {
                        if(row.wallet_status == 'credit'){
                            return `<label class="badge badge-success">${data}</label>`;
                        } else {
                            var psps = ``;
                            if(row.status == '3'){
                                psps = psps + `<label class="badge badge-danger">${data}</label>&nbsp;<label class="badge badge-warning">Pending</label>`;
                            }
                            if(row.status == '2'){
                                psps = psps + `<label class="badge badge-danger">${data}</label>&nbsp;<label class="badge badge-secondary">Rejected</label>`;
                            }
                            if(row.status == '1'){
                                psps = psps + `<label class="badge badge-danger">${data}</label>`;
                            }
                            
                            return psps;
                        }
                        
                    }
                }
            ]
        });
    } else {
        const table = $(document).find('#list_center').DataTable({
            searching: true,
            'ajax': {
                'url': ajax_url + 'center/list',
                error: function (a, v, c) {
                    log(a.responseText)
                }
            },
            'columns': [
                // Specify your column configurations
                { 'data': 'rollno_prefix' },
                { 'data': 'center_number' },
                { 'data': 'institute_name' },
                { 'data': 'name' },
                { 'data': 'email' },
                { 'data': 'contact_number' },
                { 'data': 'wallet' },
                { 'data': null }
                // Add more columns as needed
            ],
            'columnDefs': [
                {
                    target: 0,
                    render: function (data, type, row) {
                        return `${data}`;
                    }
                },
                {
                    targets: 3,
                    printable: false,
                    render: function (data, type, row) {
                        return `<label class="text-dark">${data}</label>`;
                    }
                },
                {
                    targets: 4,
                    printable: false,
                    render: function (data, type, row) {
                        return `<a href="mailto:${data}">${data}</a>`;
                    }
                },
                {
                    targets: 5,
                    printable: false,
                    render: function (data, type, row) {
                        return `<a href="tel:${data}">${data}</a>`;
                    }
                },
                {
                    targets: 6,
                    render: function (data, type, row) {
                            return `${inr} ${numberFormat(data)}`;
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
                                    <button class="btn btn-sm btn-primary w-100 p-1 load-wallet">Add Wallet</button>
                                    <a href="${base_url}admin/wallet-history?id=${row.id}" target="_blank" class="btn btn-sm btn-info">
                                        <i class="fa fa-eye"></i>
                                    </a>
                                </div>`;
                    }
                }
            ]
        })
        table.on('draw', function () {
            if (wallet_system) {
                $('#list_center thead th').eq(6).text('Wallet');
                table.on('click', '.load-wallet', function () {
                    let centre = $('#list_center').DataTable().row($(this).closest('tr')).data();
                    myModel(`Load Wallet of <b class="text-success">${centre.name}</b>`, `
                            <!--begin::Input wrapper-->
                            <div class="d-flex flex-column mb-8 fv-row">
                                <!--begin::Label-->
                                <label class="d-flex align-items-center fs-6 fw-semibold mb-2">
                                    <span class="required">Options</span>
                            
                                    <span class="ms-1" data-bs-toggle="tooltip" title="Select an option.">
                                        <i class="ki-duotone ki-information text-gray-500 fs-7"><span class="path1"></span><span class="path2"></span><span class="path3"></span></i>
                                    </span>
                                </label>
                                <!--end::Label-->
                            
                                <!--begin::Buttons-->
                                <div class="d-flex flex-stack gap-5 mb-3">
                                    <button type="button" class="btn btn-light-primary w-100" data-kt-docs-advanced-forms="interactive">100</button>
                                    <button type="button" class="btn btn-light-primary w-100" data-kt-docs-advanced-forms="interactive">500</button>
                                    <button type="button" class="btn btn-light-primary w-100" data-kt-docs-advanced-forms="interactive">1000</button>
                                </div>
                                <!--begin::Buttons-->
                            
                                <input type="text" class="form-control form-control-solid" placeholder="Enter Amount" name="amount" />
                            </div>
                            <!--end::Input wrapper-->
                            <div class="d-flex flex-column mb-8 fv-row">
                                <label>Description</label>
                                <textarea class="form-control w-100" rows="3" placeholder="Description"></textarea>
                            </div>

                `).then((e) => {

                        const options = $(e.modal).find('[data-kt-docs-advanced-forms="interactive"]');
                        const inputEl = $(e.modal).find('[name="amount"]');
                        // log(inputEl)
                        options.on('click', e => {
                            e.preventDefault();
                            inputEl.val($(e.target).html());
                        });

                        $(e.modal).find('form').submit(r => {
                            r.preventDefault();
                            let amount = inputEl.val();
                            let description = $(e.modal).find('textarea').val();
                            if (amount) {
                                // amount min value 100
                                if (amount >= 100) {
                                    $.AryaAjax({
                                        url: 'ajax/centre-wallet-load',
                                        data: {
                                            name: centre.name,
                                            centre_id: centre.id,
                                            amount: amount,
                                            description: description,
                                            closing_balance: centre.wallet,
                                        }
                                    }).then(result => {
                                        SwalSuccess('Wallet Loaded Successfully..').then(ok => {
                                            if (ok.isConfirmed) {
                                                $('#list_center').DataTable().ajax.reload();
                                                ki_modal.modal('hide');
                                                location.href = location.href;
                                            }
                                        });
                                    });
                                }
                                else
                                    SwalWarning(`Please Enter amount minimum 100 ${inr}`);
                            }
                            else {
                                SwalWarning(`Please Enter Amount ${inr}`)

                            }
                        });


                    });
                })
            }
        });
    }
});
