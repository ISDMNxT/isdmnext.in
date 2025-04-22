document.addEventListener('DOMContentLoaded', function (e) {

var myttttable = $(document).find('#list-students');
    var dt = myttttable.DataTable({
        searching: true,
        'ajax': {
            'url': ajax_url + 'student/listenquiry',
            'data': {
            },
            'type': 'POST',
            success: function (d) {
                // console.log(d);
                if (d.data && d.data.length) {
                    dt.clear();
                    dt.rows.add(d.data).draw();
                }
                else {
                    toastr.error('Table Data Not Found.');
                    DataTableEmptyMessage(myttttable);
                    SwalWarning('Notice', `
                        <b>Enquiries are not found.</b>
                    `)
                }
            },
            'error': function (xhr, error, thrown) {
                // Custom error handling
                console.log('DataTables Error:', xhr, error, thrown);
                // Show an alert or a custom message
                alert('An error occurred while loading data. Please try again.');
            }
        },
        'columns': [
            // Specify your column configurations
            { 'data': 'enquiry_no' },
            { 'data': 'estimatedjoin_date' },
            { 'data': 'followup_date' },
            { 'data': 'student_name' },
            { 'data': 'contact_number' },
            { 'data': 'email' },
            { 'data': 'center_name' },
            { 'data': 'course_name' },
            { 'data': 'batch_name' },
            { 'data': 'father_name' },
            { 'data': 'mother_name' },
            { 'data': 'STATE_NAME' },
            { 'data': 'DISTRICT_NAME' },
            { 'data': 'address' },
            { 'data': 'pincode' },
            { 'data': 'enquiry_type' },
            { 'data': null }
            // Add more columns as needed
        ],
        'columnDefs': [
            {
                targets: 0,
                render: function (data) {
                    return `<div class="d-flex align-items-center flex-wrap">
                                <div class="f fw-bold me-5 copy-text-data">${data}</div>
                            </div>`;
                }
            },
            {
                targets: 1,
                render: function (data, type, row) {
                    return `<label class="badge badge-info">${data}</label>`;
                }
            },
            {
                targets: 2,
                render: function (data, type, row) {
                    return `<label class="badge badge-info">${data}</label>`;
                }
            },
            {
                targets: 3,
                render: function (data, type, row) {
                    return `${data}`;
                }
            },
            {
                targets: 4,
                render: function (data, type, row) {
                    return `<label class="badge badge-info">${data}</label>`;
                }
            },
            {
                targets: 7,
                render: function (data, type, v) {
                    var badgeClass = duration_badge(v.duration_type);
                    var duration = course_duration_humnize_without_ordinal(v.duration, v.duration_type);
                    return `<label class="badge badge-dark">${data}</label>
                            <label class="badge badge-${badgeClass}">${duration}</label>
                            `;
                }
            },
            {
                targets: 11,
                render: function (data, type, row) {
                    return `<label class="badge badge-info text-capitalize">${row.STATE_NAME}</label>`;
                }
            },
            {
                targets: 12,
                render: function (data, type, row) {
                    return `<label class="badge badge-info text-capitalize">${row.DISTRICT_NAME}</label>`;
                }
            },
            {
                targets: 13,
                render: function (data, type, row) {
                    return `<label class="badge badge-info text-capitalize">${row.address}</label>`;
                }
            },
            {
                targets: 15,
                render: function (data, type, row) {
                    return `<label class="badge badge-danger">${data}</label>
                        <label class="badge badge-info text-capitalize">Via <b> &nbsp;${row.added_by}</b></label>`;
                }
            },
            {
                targets: -1,
                data: null,
                orderable: false,
                className: 'text-end',
                render: function (data, type, row) {
                    var student_id = row.student_id;
                    // console.log(data);
                    var button_html = `<div class="btn-group" data-id="${student_id}">`;
                    button_html += `<a href="${base_url}student/admission?enq=${student_id}" target="_blank" class="btn btn-light-primary btn-sm">
                                        Covert Into Admission</a>`;

                    return `${button_html}</div>`;
                }
            }
        ]
    }).on('draw', function (e) {
       
    });
}); 