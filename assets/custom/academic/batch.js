function convertDateToDMY(dateString) {
    // Assuming the input is in 'Y-m-d' format (e.g., '2024-09-05')
    const [year, month, day] = dateString.split('-');
    
    // Return the date in 'd-m-Y' format
    return `${day}-${month}-${year}`;
}

function convertTimeTo12HourFormat(timeString) {
    // Split the input time (HH:mm:ss) into its components
    let [hours, minutes] = timeString.split(':');
    
    // Convert the hours to a number
    hours = parseInt(hours);
    
    // Determine AM or PM suffix
    const period = hours >= 12 ? 'PM' : 'AM';
    
    // Convert hours from 24-hour to 12-hour format
    hours = hours % 12 || 12; // If hours is 0 or 12, set it to 12 (12 AM or 12 PM)

    // Return the time in 12-hour format (HH:mm AM/PM)
    return `${String(hours).padStart(2, '0')}:${minutes} ${period}`;
}

document.addEventListener('DOMContentLoaded', function (e) {
    const form          = document.getElementById('btach_add');
    const list_url      = 'academic/list-batch';
    const save_url      = 'academic/add-batch';
    const delete_url    = 'academic/delete-batch';
    const table         = $('#batch_list');
    const institue_box  = $('select[name="center_id"]');
    const course_box    = $('select[name="course_id"]');

    const columns = [
        { 'data': 'batch_name' },
        { 'data': 'center' },
        { 'data': 'course' },
        { 'data': null },
        { 'data': null },
        { 'data': 'duration' },
        { 'data': null }
    ];
    // var dt = '';
    if (table.length) {
        const dt = table.DataTable({
            dom: small_dom,
            buttons: [],
            ajax: {
                url: ajax_url + list_url,
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
                }
            },
            columns: columns,
            columnDefs: [
                {
                    targets: 3,
                    orderable: false,
                    render: function (data, type, row) {
                        if (row.from_date && row.to_date) {
                            
                            return `
                                <lable class="badge badge-dark">Form : ${convertDateToDMY(row.from_date)}</lable>
                                <label class="badge badge-dark mt-1">To : ${convertDateToDMY(row.to_date)}</label>
                            `;
                        }
                        else
                            return `<label class="badge badge-danger">No Date</label>`;
                    }
                },
                {
                    targets: 4,
                    orderable: false,
                    render: function (data, type, row) {
                        if (row.from_time && row.to_time) {
                            return `
                                <lable class="badge badge-dark">Form : ${convertTimeTo12HourFormat(row.from_time)}</lable>
                                <label class="badge badge-dark mt-1">To : ${convertTimeTo12HourFormat(row.to_time)}</label>
                            `;
                        }
                        else
                            return `<label class="badge badge-danger">No Time</label>`;
                    }
                },
                {
                    targets: -1,
                    data: null,
                    orderable: false,
                    className: 'text-end',
                    render: function (data, type, row) {
                        // console.log(data);
                        return `<div class="btn-group">
                                    <buttons class="btn btn-primary btn-xs btn-sm edit-record"><i class="ki-outline ki-pencil"></i> Edit</buttons>
                                    ${deleteBtnRender(0, row.id)}
                                </div>
                                `;
                    }
                }
            ]
        });
        dt.on('draw', function (e) {
            EditForm(table,'academic/edit-batch','Edit Batch');
            const handle = handleDeleteRows(delete_url);
            handle.done(function (e) {
                // console.log(e);
                table.DataTable().ajax.reload();
            });
        })
    }
    if (form) {
        var validator = FormValidation.formValidation(
            form,
            {
                fields: {
                    batch_name: {
                        validators: {
                            notEmpty: {
                                message: 'Batch Name is required'
                            }
                        }
                    },
                    center_id: {
                        validators: {
                            notEmpty: {
                                message: 'Center is required'
                            }
                        }
                    },
                    course_id: {
                        validators: {
                            notEmpty: {
                                message: 'Course is required'
                            }
                        }
                    },
                    from_date: {
                        validators: {
                            notEmpty: {
                                message: 'From Date is required'
                            },
                            callback: {
                                message: 'From Date must be before To Date',
                                callback: function (input) {
                                    const fromDate = new Date(input.value.split('-').reverse().join('-'));
                                    const toDate = new Date(form.querySelector('[name="to_date"]').value.split('-').reverse().join('-'));
                                    return fromDate < toDate;
                                }
                            }
                        }
                    },
                    to_date: {
                        validators: {
                            notEmpty: {
                                message: 'To Date is required'
                            },
                            callback: {
                                message: 'To Date must be after From Date',
                                callback: function (input) {
                                    const fromDate = new Date(form.querySelector('[name="from_date"]').value.split('-').reverse().join('-'));
                                    const toDate = new Date(input.value.split('-').reverse().join('-'));
                                    return toDate > fromDate;
                                }
                            }
                        }
                    },
                    from_time: {
                        validators: {
                            notEmpty: {
                                message: 'From Time is required'
                            },
                            callback: {
                                message: 'From Time must be before To Time',
                                callback: function (input) {
                                    const fromTime = form.querySelector('[name="from_time"]').value;
                                    const toTime = form.querySelector('[name="to_time"]').value;
                                    return fromTime < toTime;
                                }
                            }
                        }
                    },
                    to_time: {
                        validators: {
                            notEmpty: {
                                message: 'To Time is required'
                            },
                            callback: {
                                message: 'To Time must be after From Time',
                                callback: function (input) {
                                    const fromTime = form.querySelector('[name="from_time"]').value;
                                    const toTime = input.value;
                                    return toTime > fromTime;
                                }
                            }
                        }
                    },
                    duration: {
                        validators: {
                            notEmpty: {
                                message: 'Duration is required'
                            }
                        }
                    }
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

        // Add listener for time calculation and validation
        /*form.querySelector('[name="to_time"]').addEventListener('change', function () {
            const fromTime = form.querySelector('[name="from_time"]').value;
            const toTime = this.value;

            if (fromTime && toTime) {
                // Calculate the duration in hours and minutes
                const from = new Date('1970-01-01T' + fromTime);
                const to = new Date('1970-01-01T' + toTime);

                const diffMs = to - from; // Difference in milliseconds
                if (diffMs > 0) {
                    const hours = Math.floor(diffMs / 1000 / 60 / 60);
                    const minutes = Math.floor((diffMs % (1000 * 60 * 60)) / 1000 / 60);
                    const duration = hours + ' H ' + minutes + ' M';
                    form.querySelector('[name="duration"]').value = duration;
                } else {
                    form.querySelector('[name="duration"]').value = '';
                }
            }
        });*/

        // Add listener for date calculation and validation
        // Function to calculate the duration in years, months, and days
        function calculateDuration() {
            const fromDateValue = form.querySelector('[name="from_date"]').value;
            const toDateValue = form.querySelector('[name="to_date"]').value;

            if (fromDateValue && toDateValue) {
                // Parse the dates in d-m-Y format
                const fromParts = fromDateValue.split('-');
                const toParts = toDateValue.split('-');

                // Create Date objects
                const fromDate = new Date(fromParts[2], fromParts[1] - 1, fromParts[0]); // Year, Month (0-indexed), Day
                const toDate = new Date(toParts[2], toParts[1] - 1, toParts[0]);

                // Calculate the difference in years, months, and days
                let diffYears = toDate.getFullYear() - fromDate.getFullYear();
                let diffMonths = toDate.getMonth() - fromDate.getMonth();
                let diffDays = toDate.getDate() - fromDate.getDate();

                // Adjust if days are negative
                if (diffDays < 0) {
                    diffMonths -= 1;
                    const previousMonth = new Date(toDate.getFullYear(), toDate.getMonth(), 0);
                    diffDays += previousMonth.getDate();
                }

                // Adjust if months are negative
                if (diffMonths < 0) {
                    diffYears -= 1;
                    diffMonths += 12;
                }

                let duration = "";
                if(diffYears > 0){
                    duration = `${diffYears} Y, ${diffMonths} M, ${diffDays} D`;
                } else {
                    if (diffMonths > 0) {
                        duration = `${diffMonths} M, ${diffDays} D`;
                    } else {
                        duration = `${diffDays} D`;
                    }
                }

                form.querySelector('[name="duration"]').value = duration;
            }
        }

        // Add event listeners to both from_date and to_date fields
        form.querySelector('[name="from_date"]').addEventListener('change', calculateDuration);
        form.querySelector('[name="to_date"]').addEventListener('change', calculateDuration);

        form.addEventListener('submit', function (e) {
            // Prevent default button action
            e.preventDefault();
            var test = save_ajax(form, save_url, validator);
            test.done(function (data) {
                // console.log(data);
                table.DataTable().ajax.reload();
            })
        });
    }

    institue_box.change(function () {
        var center_id = $(this).val();
        course_box.html('');
        var show = $('#wallet_system_course_wise').length;
        // alert(center_id);
        $.AryaAjax({
            url: 'student/genrate-a-new-rollno-with-center-courses',
            data: { center_id },
            dataType: 'json'
        }).then(function (res) {
            // log(res);
            if (res.status) {
                var options = '<option value=""></option>';
                if (res.courses.length) {
                    // log(res.courses);
                    $.each(res.courses, function (index, course) {
                        options += `<option data-price_show="${show}" value="${course.course_id}" data-course_fee="${course.course_fee}" data-kt-rich-content-subcontent="${course.duration} ${course.duration_type}">${course.course_name}</option>`;
                    });
                }
                course_box.html(options).select2({
                    placeholder: "Select a Course",
                    templateSelection: optionFormatSecond,
                    templateResult: optionFormatSecond,
                });
            }
            else {
                SwalWarning('This Center have not any course.');
            }
        }).catch(function (a) {
            console.log(a);
        });
    }).select2({
        placeholder: "Select a Center",
        templateSelection: optionFormatSecond,
        templateResult: optionFormatSecond,
    });

    if ($('#wallet_system_course_wise').length) {
        course_box.change(function () {
            var course_fee = $(this).find('option:selected').data('course_fee');
            var btn = $('#form').find('button');
            var price = $('#centre_id').find('option:selected').data('wallet');
            // alert(price)
            if (price < course_fee) {
                SwalWarning(`Wallet Balance is Low...\n
                            <b class="text-success">Course Fee : ${inr} ${course_fee}</b>\n
                            <b class="text-danger">Wallet Balance : ${inr} ${price}</b>
                            `);
                btn.prop("disabled", true);
            }
            else {
                btn.prop("disabled", false);
            }
        })
    }

    if (login_type == 'center') {
        institue_box.trigger("change");
    }
});
