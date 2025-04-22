document.addEventListener('DOMContentLoaded', function (r) {
    const list_student_exams = $('#list-student-exams');
    const institue_box = $('select[name="center_id"]');
    const student_box = $('select[name="student_id"]');
    if(list_student_exams){
        var index = 1;
        var dt = list_student_exams.DataTable({
                dom: small_dom,
                ajax: {
                    url: ajax_url + 'exam/student-exams-list?c_id='+institue_box.val()+'&s_id='+student_box.val(),
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
                columns: [
                    { 'data': null },
                    { 'data': 'student_name' },
                    { 'data': 'subject_name' },
                    { 'data': 'center_name' },
                    { 'data': 'course_name' },
                    { 'data': 'exam_title' },
                    { 'data': 'attempt_time' },
                    { 'data': 'percentage' },
                    { 'data': null }
                ],
                columnDefs: [
                    {
                        targets: 0,
                        render: function (data, type, row, meta) {
                            return `${meta.row + 1}.`;
                        }
                    },
                    {
                        targets: 1,
                        render: function (data, type, row) {
                            return `${row.student_name}<br/><label class="badge badge-secondary">${row.roll_no}</label`;//row.duration+ ` </>`;
                        }
                    },
                    {
                        targets: 2,
                        render: function (data, type, row) {
                            return `${row.subject_name}<br/><label class="badge badge-warning">${row.paper_type}</label>`;//row.duration+ ` </>`;
                        }
                    },
                    {
                        targets: 6,
                        render: function (data, type, row) {
                            if(row.status == 1){
                                return `-`;
                            }
                            if(row.status == 2){
                                return `<label class="badge badge-secondary">${row.attempt_time}</label>`;
                            }
                           
                        }
                    },
                    {
                        targets: 7,
                        render: function (data, type, row) {
                            if(row.status == 1){
                                return ``;
                            }
                            if(row.status == 2){
                                return `<label class="badge badge-secondary">Paper Marks - ${row.paper_total_marks}</label>
                                <br/><label class="badge badge-secondary">Student Marks - ${row.student_total_marks}</label>
                                <br/><label class="badge badge-secondary">Result - ${row.percentage}%</label>`;
                            }
                           
                        }
                    },
                    {
                        targets: -1,
                        orderable: false,
                        render: function (data, type, row) {
                            var returnString = ''; // Default empty string
        
                            // Check if marksheet_id is 'unknown'
                            if (row.marksheet_id === null) {
                                returnString = `<div class="btn-group">`;
                                
                                // Condition for practical papers with status 1
                                if (row.status == '1' && row.paper_type == 'PRACTICAL') {
                                    returnString += `<label class="badge badge-secondary">Paper Marks - ${row.paper_total_marks}</label>
                                    &nbsp;<a style="cursor: pointer;" title="Set Mark" class="badge badge-primary edit-record">Set Mark</a>`;
                                }
                                
                                // Condition for practical papers with status 2
                                if (row.status == '2' && row.paper_type == 'PRACTICAL') {
                                    returnString += `<a style="cursor: pointer;" title="Reset Marks" class="badge badge-info reset-marks" data-id="${row.id}">Reset Marks</a>`;
                                }
                                
                                // Condition for theoretical papers with status 2 and admin login
                                if (row.status == '2' && row.paper_type == 'THEORTICAL' && typeof login_type !== 'undefined' && login_type == 'admin') {
                                    returnString += `<a style="cursor: pointer;" title="Reset Exam" class="badge badge-info reset-exam" data-id="${row.id}">Reset Exam</a>`;
                                }
                                
                                returnString += `</div>`;
                            }
                            
                            // Ensure a value is always returned
                            return returnString; 
                        }
                    }
                ]
            });
    }
    
    dt.on('draw', function (e) {
        index = 1;
        list_student_exams.EditForm('exam/set-practical-marks', 'Set Parctical Mark');
        ki_modal.find('.modal-dialog').addClass('modal-lg');
    });

    $(document).on('click', '.reset-exam', function () {
        var id = $(this).data('id');
        SwalWarning('Confirmation','Are you sure you want to reset exam?',true,'Reset Exam').then((e) => {
            if (e.isConfirmed) {
                $.AryaAjax({
                    url: 'exam/reset-exam',
                    data: { id },
                    success_message: 'Exam Reset Successfully.'
                }).then((f) => {
                    if(f.status){
                        list_student_exams.DataTable().ajax.reload();
                    }
                    showResponseError(f);
                });
            }
            else{
                toastr.warning('Request Aborted');
            }
        })
        
    });

    $(document).on('click', '.reset-marks', function () {
        var id = $(this).data('id');
        SwalWarning('Confirmation','Are you sure you want to reset exam?',true,'Reset Marks').then((e) => {
            if (e.isConfirmed) {
                $.AryaAjax({
                    url: 'exam/reset-marks',
                    data: { id },
                    success_message: 'Marks Reset Successfully.'
                }).then((f) => {
                    if(f.status){
                        list_student_exams.DataTable().ajax.reload();
                    }
                    showResponseError(f);
                });
            }
            else{
                toastr.warning('Request Aborted');
            }
        })
        
    });
    function render_datan(rowData) {
        $.AryaAjax({
            url: 'exam/list-questions',
            data: rowData
        }).then((e) => {
            var drawerEl = document.querySelector("#kt_drawer_view_details_box");
            KTDrawer.getInstance(drawerEl, { overlay: true }).hide();
            drawerEl.setAttribute('data-kt-drawer-width', "{default:'300px', 'md': '900px'}");
            var footer = `<div class="card-footer">
                            <button class="btn btn-primary"><i class="fa fa-plus"></i> Add Question</button>
                        </div>`;
            var main = mydrawer(`${rowData.exam_title}'s Questions list`);
            if (e.status) {
                main.find('.card-body').removeClass('d-flex flex-column justify-content-between mt-9 bgi-no-repeat bgi-size-cover bgi-position-x-center pb-0');
            }
            else {
                main.find('.card-body').addClass('d-flex flex-column justify-content-between mt-9 bgi-no-repeat bgi-size-cover bgi-position-x-center pb-0');
            }
            main.find('.card-body').html(e.html).find('.edit,.delete').on('click', function (e) {
                // if($(this).hasClass())
                e.preventDefault();
                var quesData = ($(this).closest('table').data('param'));
                // log(quesData);
                if ($(this).hasClass('edit')) {
                    ki_modal.attr('data-bs-backdrop', "static");
                    ki_modal.find('.modal-dialog').addClass('modal-lg');
                    ki_modal.find('.modal-footer').prepend(`<button class="btn btn-outline hover-rotate-end btn-outline-dashed btn-outline-warning add-answer" type="button"><i class="fa fa-plus"></i> Add Choices</button>                    
                `);
                    $.each(quesData.answers, function (i, v) {
                        $.extend(v, {
                            answer_id: i
                        });
                        template('template', v).then((re) => {
                            $('.answer-area').append(re);
                        });
                    })
                    myModel('<i class="fa fa-edit"></i> Edit Question', `
                                <input type="hidden" name="question_id" value="${quesData.ques_id}">
                                <div class="form-group">
                                    <lable class="form-label required">Enter Question</lable>
                                    <textarea class="form-control" placeholder="Enter Question" name="question">${quesData.question}</textarea>
                                </div>
                                <div class="answer-area mt-4" data-kt-buttons="true">
                                </div>
                            `, false).then((res) => {
                        res.form.on('submit', function (e) {
                            e.preventDefault();
                            save_question_answer(this);
                        })
                    });
                }
                else {
                    SwalWarning('Confirmation!',
                        `Are you sure you want delete <b class="text-success">${quesData.question}</b> Question.`, true, 'Ok, Delete It.').then((e) => {
                            if (e.isConfirmed) {
                                // alert('OK');
                                $.AryaAjax({
                                    url: 'exam/delete-question',
                                    data: { ques_id: quesData.ques_id },
                                }).then((res) => {
                                    if (res.status) {
                                        toastr.success('Question Deleted Successfully..');
                                        ki_modal.modal('hide');
                                        render_data(rowData);
                                    }
                                    else
                                        toastr.error('Something went wrong please try again.');
                                });
                            }
                        })
                }
            });
            main.find('.card').addClass('card-image').append(footer).find('button').on('click', function () {
                ki_modal.attr('data-bs-backdrop', "static");
                ki_modal.find('.modal-dialog').addClass('modal-lg');
                ki_modal.find('.modal-footer').prepend(`<button class="btn btn-outline hover-rotate-end btn-outline-dashed btn-outline-warning add-answer" type="button"><i class="fa fa-plus"></i> Add Choices</button>                    
                `);
                myModel('Add A New Question', `
                    <div class="form-group">
                        <lable class="form-label required">Enter Question</lable>
                        <textarea class="form-control" placeholder="Enter Question" name="question"></textarea>
                    </div>
                    <div class="answer-area mt-4" data-kt-buttons="true">
                    </div>
                `, false).then((e) => {
                    e.form.on('submit', function (e) {
                        e.preventDefault();
                        save_question_answer(this);
                    })
                });
            });
            ki_modal.on('hidden.bs.modal', function () {
                ki_modal.find('form').off('submit');
                ki_modal.find('.modal-footer').find('.add-answer').remove();
                ki_modal.find('.modal-dialog').removeClass('modal-lg');
            })
        });
    }
    function save_question_answern(form){
        var SendData = new FormData(form);
        var data = [];
        var ans = $(form).find('.ans');
        $(form).find('.is-right').each(function (i) {
            data.push({
                answer: $(ans[i]).val(),
                is_right: $(this).is(':checked') ? 1 : 0
            });
        });
        if (ans.length < 2) {
            SwalWarning('Enter Atleast Two and more answers.');
            return false;
        }
        if ($(form).find('.is-right:checked').length == 0) {
            SwalWarning('Choose A Right Answer.');
            return false;
        }
        SendData.append('exam_id', rowData.exam_id);
        SendData.append('answer_list', JSON.stringify(data));
        $.AryaAjax({
            url: 'exam/manage-question-with-answers',
            data: SendData,
            success_message: `Question Added Successfully in <b>${rowData.exam_title}</b>`
        }).then((res) => {
            showResponseError(res);
            // log(res);
            if (res.status) {
                ki_modal.modal('hide');
                render_data(rowData);
            }
        });
    }

    institue_box.select2({
        placeholder: "Select a Center",
        templateSelection: optionFormatSecond,
        templateResult: optionFormatSecond,
    }).on('change', function () {
        var center_id = $(this).val();
        student_box.html(emptyOption);
       
        if (center_id) {
            const list_url = ajax_url + 'exam/student-exams-list?c_id='+center_id+'&s_id='+student_box.val();
            list_student_exams.DataTable().ajax.url(list_url).load();

            $.AryaAjax({
                url: 'student/fetch_student_via_center',
                data: { center_id }
            }).then((rs) => {
                if (rs.data.length) {
                    $.each(rs.data, (i, student) => {
                        student_box.append(`
                                <option 
                                        value="${student.student_id}" 
                                        data-kt-rich-content-subcontent="${student.roll_no}" 
                                        data-kt-rich-content-icon="${student.image}" 
                                        data-course_duration="${student.duration}" 
                                        data-course_duration_type="${student.duration_type}" 
                                        data-course_id="${student.course_id}"
                                        data-course_name="${student.course_name}">
                                        ${student.student_name}
                                </option>
                            `);
                    })
                    student_box.mySelect2();
                }
            });
        }
    });

    student_box.on('change', function () {
        const student_id = $(this).val();
        const center_id = institue_box.val()
        if (student_id) {
            
            const list_url1 = ajax_url + 'exam/student-exams-list?c_id='+center_id+'&s_id='+student_id;
            list_student_exams.DataTable().ajax.url(list_url1).load();        
                
        }
    });
    
    if (login_type == 'center') {
        institue_box.trigger("change");
    }
})
