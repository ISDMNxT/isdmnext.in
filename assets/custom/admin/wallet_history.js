document.addEventListener('DOMContentLoaded', function () {
    if(login_type == 'admin'){
        const list = $('#list-history');
        const ccid = $('#c_id');
        list.DataTable({
            ajax: {
                url: ajax_url + 'center/wallet-history?id=' + ccid.val()
            },
            'columns': [
                // Specify your column configurations
                { 'data': null },
                { 'data': 'date' },
                { 'data': 'student_name' },
                { 'data': 'status' },
                { 'data': 'description' },
                { 'data': 'amount' },
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
                    targets: 2,
                    render: function (data, type, row, meta) {
                        return `<div class="d-flex flex-stack">      
                        <span>${data} </span>    

                        ${row.url ? '<a href="'+row.url+'" target="_blank" class="btn btn-light btn-sm btn-color-muted fs-7 fw-bold px-5"><i class="fa  fa-eye"></i></a>' : ''}
                    </div>`;
                    }
                },
                {
                    targets: 3,
                    render: function (data, type, row, meta) {
                        return (data == 'credit') ? badge('Credit') : badge('Debit', 'danger');
                    }
                },
                {
                    targets: 5,
                    render: function (data, type, row, meta) {
                        return `${inr} ${row.amount} `;
                    }
                },
                {
                    targets: -1,
                    render: function (data, type, row, meta) {
                        /*<button class="btn btn-sm btn-light-danger delete-ans" data-id="${row.id}">
                                    <i class="fa fa-trash"></i>
                                </button>*/
                        return `<button class="btn btn-sm btn-light-info edit-form-btn" data-id="${row.id}">
                                    <i class="fa fa-edit"></i>
                                </button>`;
                    }
                }
            ]
        }).on('draw', function (e) {
            $(this).EditAjax('ajax/edit_transaction','Wallet','small');
            
        });
        $(document).on('click', '.delete-ans', function () {
            
            ans_id = $(this).data('id');
            SwalWarning('Confirmation!', 'Are you sure you want to delete this Transaction.', true, 'Remove').then((e) => {
                if (e.isConfirmed) {
                    if (ans_id) {
                        $.AryaAjax({
                            url: 'ajax/remove-transaction',
                            data: { id : ans_id, center_id : ccid.val() },
                        }).then((res) => {
                            if (res.status) {
                                toastr.success('Transaction Deleted Successfully..');
                                location.href = location.href;
                            }
                        });
                    }
                    else {
                        toastr.success('Transaction Removed Successfully..');
                        box.remove();
                    }
                }
            });
        });
    } else if(login_type == 'employer'){
        const list = $('#list-history');
        const ccid = $('#c_id');
        list.DataTable({
            ajax: {
                url: ajax_url + 'center/wallet-history?id=' + ccid.val()
            },
            'columns': [
                // Specify your column configurations
                { 'data': null },
                { 'data': 'date' },
                { 'data': 'student_name' },
                { 'data': 'status' },
                { 'data': 'description' },
                { 'data': 'amount' }
            ],
            'columnDefs': [
                {
                    targets: 0,
                    render: function (data, type, row, meta) {
                        return `${meta.row + 1}.`;
                    }
                },
                {
                    targets: 2,
                    render: function (data, type, row, meta) {
                        return `<div class="d-flex flex-stack">      
                        <span>${data} </span>    

                        ${row.url ? '<a href="'+row.url+'" target="_blank" class="btn btn-light btn-sm btn-color-muted fs-7 fw-bold px-5"><i class="fa  fa-eye"></i></a>' : ''}
                    </div>`;
                    }
                },
                {
                    targets: 3,
                    render: function (data, type, row, meta) {
                        return (data == 'credit') ? badge('Credit') : badge('Debit', 'danger');
                    }
                },
                {
                    targets: 5,
                    render: function (data, type, row, meta) {
                        return `${inr} ${row.amount} `;
                    }
                }
            ]
        });
    } else {
        const list = $('#list-history');
        list.DataTable({
            ajax: {
                url: ajax_url + 'center/wallet-history'
            },
            'columns': [
                // Specify your column configurations
                { 'data': null },
                { 'data': 'date' },
                { 'data': 'student_name' },
                { 'data': 'status' },
                { 'data': 'description' },
                { 'data': 'amount' },
                // Add more columns as needed
            ],
            'columnDefs': [
                {
                    targets: 0,
                    render: function (data, type, row, meta) {
                        return `${meta.row + 1}.`;
                    }
                },
                {
                    targets: 2,
                    render: function (data, type, row, meta) {
                        return `<div class="d-flex flex-stack">      
                        <span>${data} </span>    

                        ${row.url ? '<a href="'+row.url+'" target="_blank" class="btn btn-light btn-sm btn-color-muted fs-7 fw-bold px-5"><i class="fa  fa-eye"></i></a>' : ''}
                    </div>`;
                    }
                },
                {
                    targets: 3,
                    render: function (data, type, row, meta) {
                        return (data == 'credit') ? badge('Credit') : badge('Debit', 'danger');
                    }
                },
                {
                    targets: -1,
                    render: function (data, type, row, meta) {
                        return `${inr} ${row.amount} `;
                    }
                }
            ]
        });
    }


    
})