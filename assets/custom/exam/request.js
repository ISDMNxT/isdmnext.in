document.addEventListener('DOMContentLoaded',function(e){
    const requestTable  = $('#requestTable');
    const form          = document.getElementById('add_request');
    const institue_box  = $('select[name="center_id"]');
    const course_box    = $('select[name="course_id"]');
    const batch_box     = $('select[name="batch_id"]');
    const studentBox    = $('#student_id');
    
    requestTable.DataTable({
        dom : small_dom,
        ajax : {
            url : ajax_url + `exam/list-requests`,
            error : function(d,v,c){
                warn(d.responseText);
            }
        },
        // order: [[1, 'desc']], // ✅ Sort by Exam Title (or change to a timestamp field if available)
        columns : [
            {'data' : null },
            {'data' : 'exam_title'},
            {'data' : 'center_name'},
            {'data' : 'course_name'},
            {'data' : 'batch_name'},
            {'data' : 'is_admin_approved'},
            {'data' : null }
        ],
    
        columnDefs : [
            {
                targets: 0,
                render: function (data, type, row, meta) {
                    return `${meta.row + 1}.`;
                }
            },
            {
                targets: 5,
                render: function (data, type, row, meta) {
                    if(row.is_admin_approved == 1){
                        return `<lable style="color:Red">Pending</lable>`;
                    }
                    if(row.is_admin_approved == 2){
                        return `<lable style="color:Green">Approved</lable>`;
                    }
                    if(row.is_admin_approved == 3){
                        return `<lable style="color:Orange">Rejected</lable>`;
                    }
                }
            },
            {
                targets: -1,
                orderable: false,
                render: function (data, type, row) {
                    if(login_type == 'center'){
                        if(row.is_admin_approved == 1){
                            return `<div class="btn-group">
                                <a class="btn btn-primary btn-xs btn-sm" href="${base_url}exam/request?id=${row.id}&type=E" ><i class="ki-outline ki-pencil"></i> Edit</a>
                                ${deleteBtnRender(1, row.id)}
                            </div>`;
                        } else {
                            return `<div class="btn-group">
                                ${deleteBtnRender(1, row.id)}
                            </div>`;
                        }
                        
                    } else if(login_type == 'admin') {
                        if(row.is_admin_approved == 2 || row.is_admin_approved == 3){
                            return `<div class="btn-group">
                                ${deleteBtnRender(1, row.id)}
                            </div>`;
                        } else {
                            return `<div class="btn-group">
                                <a class="btn btn-success btn-xs btn-sm" href="${base_url}exam/request?id=${row.id}&type=A" ><i class="ki-outline ki-copy-success"></i> Approve</a>
                                <a class="btn btn-secondary btn-xs btn-sm" href="${base_url}exam/request?id=${row.id}&type=R" ><i class="ki-outline ki-eraser"></i> Reject</a>
                                <a class="btn btn-primary btn-xs btn-sm" href="${base_url}exam/request?id=${row.id}&type=E" ><i class="ki-outline ki-pencil"></i> Edit</a>
                                ${deleteBtnRender(1, row.id)}
                            </div>`;
                        }
                    } else if(login_type == 'master_franchise') {
                        
                            return `<div class="btn-group">
                                
                            </div>`;
                    }
                    
                }
            }    
            
        ]
    }).on('draw', function (e) {
        handleDeleteRows('exam/delete').then((e) => {
            location.href = `${base_url}exam/request`;
        });
    });

    var validation = MyFormValidation(form);
        validation.addField('center_id',{
            validators : {
                notEmpty : {message : 'Please Select a center.'}
            }
        });

        validation.addField('course_id',{
            validators : {
                notEmpty : {message : 'Please Select a course.'}
            }
        });

        validation.addField('batch_id',{
            validators : {
                notEmpty : {message : 'Please Select a batch.'}
            }
        });

        validation.addField('student_id[]',{
            validators : {
                notEmpty : {message : 'Please Select student.'}
            }
        });

        validation.addField('session_id',{
            validators : {
                notEmpty : {message : 'Please Select a session.'}
            }
        });

        validation.addField('exam_title',{
            validators : {
                notEmpty : {message : 'Please Enter An Exam Title.'}
            }
        });
        
        if(login_type == 'admin') {

            validation.addField('passing_percentage',{
                validators : {
                    notEmpty : {message : 'Please Enter Passing Percentage.'}
                }
            });

            validation.addField('duration',{
                validators : {
                    notEmpty : {message : 'Please Enter Duration.'}
                }
            });

            validation.addField('attemp_count',{
                validators : {
                    notEmpty : {message : 'Please Enter Attemp Count.'}
                }
            });

            validation.addField('start_date',{
                validators : {
                    notEmpty : {message : 'Please Select Start Date.'},
                    callback: {
                        message: 'Start Date must be before End Date',
                        callback: function (input) {
                            const fromDate = new Date(input.value.split('-').reverse().join('-'));
                            const toDate = new Date(form.querySelector('[name="end_date"]').value.split('-').reverse().join('-'));
                            return fromDate < toDate;
                        }
                    }
                }
            });

            validation.addField('end_date',{
                validators : {
                    notEmpty : {message : 'Please Enter End Date.'},
                    callback: {
                        message: 'End Date must be after Start Date',
                        callback: function (input) {
                            const fromDate = new Date(form.querySelector('[name="start_date"]').value.split('-').reverse().join('-'));
                            const toDate = new Date(input.value.split('-').reverse().join('-'));
                            return toDate > fromDate;
                        }
                    }
                }
            });

            validation.addField('negative_marking',{
                validators : {
                    notEmpty : {message : 'Please Select Negative Marking.'}
                }
            });

            validation.addField('random_question',{
                validators : {
                    notEmpty : {message : 'Please Select Random Question.'}
                }
            });

            validation.addField('expiry_days',{
                validators : {
                    notEmpty : {message : 'Please Enter Expiry Days.'}
                }
            });

            validation.addField('option_shuffle',{
                validators : {
                    notEmpty : {message : 'Please Select Option Shuffle.'}
                }
            });
        }

    form.addEventListener('submit',function(e){
        e.preventDefault();
        $.AryaAjax({
            url  : 'exam/submit-request',
            data : new FormData(form),
            validation : validation,
            success_message : 'Requestion Submitted Successfully.',
            page_reload : false
        }).then( (e) => {
            toastr.success(`Requestion Submitted Successfully.`);
            location.href = `${base_url}exam/request`;
            //log(e);
        });
    });

    institue_box.change(function () {
        var center_id = $(this).val();
        course_box.html('');
        batch_box.html('');
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
                        options += `<option value="${course.course_id}" data-course_fee="${course.course_fee}" data-kt-rich-content-subcontent="${course.duration} ${course.duration_type}">${course.course_name}</option>`;
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

    course_box.change(function () {
        var course_id = $(this).val();
        var center_id = $('#center_id').val();
        if(course_id > 0){
            $.AryaAjax({
                url: 'student/get-center-course-batches',
                data: { center_id:center_id, course_id:course_id },
                dataType: 'json'
            }).then(function (res) {
                var options = '<option value=""></option>';
                if (res.status) {
                    $.each(res.batches, function (index, batch) {
                        options += `<option value="${batch.id}" data-kt-rich-content-subcontent="${batch.duration}" >${batch.batch_name}</option>`;
                    });
                }
                else {
                    SwalWarning('This Course have not any batch., Please Contact to Admin.');
                }

                $("#batch_id").html(options).select2({
                    placeholder: "Select a Batch",
                    templateSelection: optionFormatSecond,
                    templateResult: optionFormatSecond,
                });
            }).catch(function (a) {
                console.log(a);
            });
        } else {
            var options = '<option value=""></option>';
            $("#batch_id").html(options).select2({
                placeholder: "Select a Batch",
                templateSelection: optionFormatSecond,
                templateResult: optionFormatSecond,
            });
        }
    }).select2({
        placeholder: "Select a Course",
        templateSelection: optionFormatSecond,
        templateResult: optionFormatSecond,
    });

    batch_box.change(function () {
        var batch_id    = $(this).val();
        var course_id   = $('#course_id').val();
        var center_id   = $('#center_id').val();
        if(batch_id > 0){
            $.AryaAjax({
                url: 'student/fetch-student-via-batch',
                data: { center_id:center_id, course_id:course_id, batch_id: batch_id },
                dataType: 'json',
                loading_message: 'Fetch Students'
            }).then(function (data) {
            // console.log(data);
            $(studentBox).html('<option></option>');
            if (data.data) {
                toastr.success(`Total ${data.data.length} stduents found.`);
                var img = '';
                $.each(data.data, function (index, myData) {
                    img = myData.image == null ? 'NULL' : myData.image;
                    $(studentBox).append(`<option value="${myData.student_id}"  data-kt-rich-content-subcontent="${myData.roll_no}" data-kt-rich-content-icon="${img}" >${myData.student_name}</option>`);
                });
                $(studentBox).select2({
                    templateSelection: optionFormatSecond,
                    templateResult: optionFormatSecond
                });
            }
            else {
                toastr.error('Students are not Found in this batch.');
            }
        }).catch(function (a) {
                console.log(a);
            });
        } else {
             $(studentBox).html('<option></option>');
             return false;
        }
    }).select2({
        placeholder: "Select a Batch",
        templateSelection: optionFormatSecond,
        templateResult: optionFormatSecond,
    });
});