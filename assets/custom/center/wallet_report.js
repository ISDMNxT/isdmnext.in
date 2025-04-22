document.addEventListener('DOMContentLoaded', function (e) {        
        const list = $('#list-history');
        const ccid = $('#c_id');
        const search_datee = $('#search_datee');
        list.DataTable({
            ajax: {
                url: ajax_url + 'center/wallet_report?search_datee='+search_datee.val()
            },
            'columns': [
                // Specify your column configurations
                { 'data': null },
                { 'data': 'date' },
                { 'data': 'center_name' },
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

        var start = moment().subtract(29, "days");
        var end = moment();

        function cb(start, end) {
            $(".search_datee").html(start.format("MMMM D, YYYY") + " - " + end.format("MMMM D, YYYY"));
        }

        $(".search_datee").daterangepicker({
            startDate: start,
            endDate: end,
            locale: {
                format: "YYYY-MM-DD"
            },
            ranges: {
            "Today": [moment(), moment()],
            "Yesterday": [moment().subtract(1, "days"), moment().subtract(1, "days")],
            "Last 7 Days": [moment().subtract(6, "days"), moment()],
            "Last 30 Days": [moment().subtract(29, "days"), moment()],
            "This Month": [moment().startOf("month"), moment().endOf("month")],
            "Last Month": [moment().subtract(1, "month").startOf("month"), moment().subtract(1, "month").endOf("month")]
            }
        }, cb);

        cb(start, end);
});