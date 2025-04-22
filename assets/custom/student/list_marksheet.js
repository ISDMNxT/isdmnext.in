document.addEventListener('DOMContentLoaded', function (e) {
    const table = $('#marksheets');

    var dt = table.DataTable({
        ajax: {
            url: ajax_url + 'student/list-marksheets',
            error: function (a, v, c) {
                log(a.responseText);
            }
        },
        order: [], // 🔥 This disables DataTables’ default sort
        columns: [
            { 'data': 'roll_no' },
            { 'data': 'student_name' },
            { 'data': 'course_name' },
            { 'data': 'duration' },
            { 'data': 'center_name' },
            { 'data': null }
        ],
        columnDefs: [
            {
                targets: -1,
                orderable: false,
                render: function (data, type, row) {
                    let bb = `<div class="btn-group">${generate_link_btn(row.result_id, 'marksheet')}`;

                    if (login_type == 'admin') {
                        bb = bb + `${deleteBtnRender(1, row.result_id, 'Marksheet')}`;
                    }

                    bb = bb + `</div>`;
                    return bb;
                }
            }
        ]
    }).on('draw', function () {
        handleDeleteRows('student/delete-marksheet');
    });
});
