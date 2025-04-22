document.addEventListener('DOMContentLoaded', function (e) {
    if(login_type == 'master_franchise'){
        const payout_form = document.getElementById('payout_form');
        if (payout_form) {
            // Init form validation rules. For more info check the FormValidation plugin's official documentation:https://formvalidation.io/
            var validator = FormValidation.formValidation(
                payout_form,
                {
                    fields: {
                        amount  : {
                            validators:{
                                notEmpty :{
                                    message : 'Payout Amount is required.'
                                }
                            }
                        },
                        title: {
                            validators: {
                                notEmpty: {
                                    message: 'Title is required'
                                },
                            }
                        },
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
            payout_form.addEventListener('submit', function (e) {
                e.preventDefault();
                var submitButton = $(this).find('button');
                if (validator) {
                    validator.validate().then(function (status) {
                        let remaining   = $("#remaining").val();
                        let amount      = $("#amount").val();

                        if(parseInt(amount) > parseInt(remaining)){
                            Swal.fire({
                                text: "Payout Amount Should be less then Remaning Amount!",
                                icon: "warning",
                                buttonsStyling: !1,
                                confirmButtonText: "Ok, got it!",
                                customClass: {
                                    confirmButton: "btn btn-primary",
                                },
                            });
                            return false;
                        }

                        var formData = new FormData(payout_form);
                        if (status == 'Valid') {
                            $(submitButton).attr('data-kt-indicator', 'on').prop('disabled', true);
                            axios
                                .post(
                                    ajax_url + 'ajax/add_payout_request',
                                    new FormData(payout_form)
                                )
                                .then(function (e) {
                                    if (e.data.status) {
                                        payout_form.reset(),
                                            Swal.fire({
                                                text: "Payout Request Submited Successfully.",
                                                icon: "success",
                                                buttonsStyling: !1,
                                                confirmButtonText: "Ok, got it!",
                                                customClass: {
                                                    confirmButton: "btn btn-primary",
                                                },
                                            });
                                    }
                                    else {
                                        Swal.fire({
                                            text: 'Please Check It.',
                                            html: e.data.html,
                                            icon: "warning",
                                            buttonsStyling: !1,
                                            confirmButtonText: "Ok, got it!",
                                            customClass: {
                                                confirmButton: "btn btn-primary",
                                            },
                                        });
                                    }
                                })
                                .catch(function (t) {
                                    console.log(t);
                                    Swal.fire({
                                        text: "Sorry, looks like there are some errors detected, please try again.",
                                        icon: "error",
                                        buttonsStyling: !1,
                                        confirmButtonText: "Ok, got it!",
                                        customClass: { confirmButton: "btn btn-primary" },
                                    });
                                })
                                .then(() => {
                                    $(submitButton).removeAttr('data-kt-indicator').prop("disabled", false);
                                });
                        }
                    });
                }
            });
        }
    }
    if(login_type == 'admin'){
        const table = $(document).find('#list_center').DataTable({
            searching: true,
            'ajax': {
                'url': ajax_url + 'center/list_finance',
                success: function (d) {
                    // console.log(d);
                    if (d.data && d.data.length) {
                        table.clear();
                        table.rows.add(d.data).draw();
                    }
                    else {
                        toastr.error('Table Data Not Found.');
                        DataTableEmptyMessage(table);
                    }
                },
                error : function(a,v,c){
                    log(a.responseText);
                }
            },
            'columns': [
                { 'data': null },
                { 'data': 'atimestamp' },
                { 'data': 'institute_name' },
                { 'data': 'title' },
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
                    target: 1,
                    render: function (data, type, row) {
                        return `${data}`;
                    }
                },
                {
                    targets: 2,
                    render: function (data, type, row) {
                        if ($.trim(row.institute_name) != ''){
                            return `${data}`;
                        } else {
                            return `Admin`;
                        }
                    }
                },
                {
                    targets: 5,
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
                        var psps = ``;
                        if(row.status == '3'){
                            psps = psps + `<div class="btn-group">
                                <a class="btn btn-success btn-xs btn-sm apps-pay" data-id="${row.id}" >Approve</a>
                                <a class="btn btn-secondary btn-xs btn-sm rejs-pay" data-id="${row.id}" >Reject</a>
                            </div>`;
                        }
                        if(row.status == '2'){
                            psps = psps + `<label class="badge badge-secondary">Rejected</label>`;
                        }
                        if(row.status == '1'){
                            psps = psps + `<label class="badge badge-success">Approved</label>`;
                        }
                        
                        return psps;
                    }
                }
            ]
        });

        $(document).on('click', '.apps-pay', function () {
            var id = $(this).data('id');
            var status = "A";
            SwalWarning('Confirmation','Are you sure you want to Approve Payout Request?',true,'Approve').then((e) => {
                if (e.isConfirmed) {
                    $.AryaAjax({
                        url: 'ajax/payout_request_act',
                        data: { id, status },
                        success_message: 'Payout Request Approved Successfully.'
                    }).then((f) => {
                        if(f.status){
                            $(document).find('#list_center').DataTable().ajax.reload();
                        }
                        showResponseError(f);
                    });
                }
                else{
                    toastr.warning('Request Aborted');
                }
            })
        });

        $(document).on('click', '.rejs-pay', function () {
            var id = $(this).data('id');
            var status = "R";
            SwalWarning('Confirmation','Are you sure you want to Reject Payout Request?',true,'Reject').then((e) => {
                if (e.isConfirmed) {
                    $.AryaAjax({
                        url: 'ajax/payout_request_act',
                        data: { id, status },
                        success_message: 'Payout Request Rejected Successfully.'
                    }).then((f) => {
                        if(f.status){
                            $(document).find('#list_center').DataTable().ajax.reload();
                        }
                        showResponseError(f);
                    });
                }
                else{
                    toastr.warning('Request Aborted');
                }
            })
        });
    }
});