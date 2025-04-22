document.addEventListener('DOMContentLoaded', function () {
    const institue_box = $('select[name="center_id"]');
    const table = $('#list_center');
    var dt = table.DataTable({
        searching: true,
        'ajax': {
            'url': ajax_url + 'center/trainers_list?id='+institue_box.val(),
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
            },
            error : function(a,v,c){
                log(a.responseText);
            }
        },
        'columns': [
            { 'data': 'trainer' },
            { 'data': 'trainer_email' },
            { 'data': 'center_name' },
            { 'data': 'rating' },
            { 'data': null }
        ],
        'columnDefs': [
            {
                targets: -1,
                render: function (data, type, row) {
                   
                    return `<div class="btn-group">
                                <a class="btn btn-success btn-xs btn-sm" targets="_blank" href="${base_url }center/trainer_view_report?staff=${row.id}" ><i class="ki-outline ki-copy-success"></i> View Details</a>
                            </div>`;
                }
            }
        ]
    });

    

    institue_box.select2({
        placeholder: "Select a Center",
        templateSelection: optionFormatSecond,
        templateResult: optionFormatSecond,
    }).on('change', function () {
        list_url = ajax_url + 'center/trainers_list?id='+$(this).val();
        table.DataTable().ajax.url(list_url).load();
    });
});