document.addEventListener('DOMContentLoaded', function (e) {
    const list_class                = $('#list-master-class');

    if (list_class) {
        var index = 1;
        var dt = list_class.DataTable({
            dom: small_dom,
            ajax: {
                url: ajax_url + 'website/list-master-class',

                success: function (d) {
                    // console.log(d);
                    if (d.data && d.data.length) {
                        dt.clear();
                        dt.rows.add(d.data).draw();
                    }
                    else {
                        toastr.error('Table Data Not Found.');
                        DataTableEmptyMessage(list_class);
                        SwalWarning('Notice', `                        Classes not Found.                    `)
                    }
                },
                'error': function (xhr, error, thrown) {
                    // Custom error handling
                    console.log('DataTables Error:', xhr, error, thrown);

                    // Show an alert or a custom message
                    alert('An error occurred while loading data. Please try again.');
                }

            },
            columns: [
                { 'data': null },
                { 'data': 'subject_name' },
                { 'data': 'title' },
                { 'data': 'staff_name' },
                { 'data': 'plan_date' },
                { 'data': 'notes' },
                { 'data': null },
            ],
            columnDefs: [
                {
                    target: 0,
                    render: function (data, type, row, meta) {
                        return `${meta.row + 1}.`;
                    }
                },
                {
                    targets : 5,
                    render : function(data,type,row){
                        if(row.notes){
                            return `<a href="${base_url}upload/${row.notes}" target="_blank" class="btn btn-info btn-xs btn-sm"><i class="fa fa-eye"></i>File</a>`;
                        }
                        else {
                            return '';
                        }
                    }
                },
                {
                    targets: -1,
                    render: function (data, type, row) {
                            if(row.rating == null){
                                return `<button class="btn btn-sm btn-light-info edit-record" data-id="${row.id}">
                                    Give Rating
                                </button>`;
                            } else {
                                return `Rating - ${row.rating} Star`;
                            }
                            
                            
                       

                    }
                }
            ]
        });
        dt.on('draw', function (e) {
            index = 1;
            list_class.EditForm('website/class-feedback', 'Rating');
            ki_modal.find('.modal-dialog').addClass('modal-lg');
        })
    }
});