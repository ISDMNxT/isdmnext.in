document.addEventListener('DOMContentLoaded', function (e) {
    const exam_table = $('#list-master-subjects');
    
    if (exam_table) {
        var index = 1;
        var rowData = [];
        var dt = exam_table.DataTable({
            dom: small_dom,
            ajax: {
                url: ajax_url + 'course/list-master-subjects',

                success: function (d) {
                    // console.log(d);
                    if (d.data && d.data.length) {
                        dt.clear();
                        dt.rows.add(d.data).draw();
                    }
                    else {
                        toastr.error('Table Data Not Found.');
                        DataTableEmptyMessage(exam_table);
                        SwalWarning('Notice', `Subjects not Found.`)
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
                { 'data': 'subject_code' },
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
                    targets: -1,
                    render: function (data, type, row) {
                        return `
                                <div class="btn-group">
                                <button class="pulse pulse-primary btn btn-outline btn-outline-dashed btn-outline-primary btn-active-light-primary our-question-list">Add Questions</button>
                                <button class="btn btn-info paper-question-list">Set Paper</button>
                                </div>`;
                    }
                }
            ]
        });
        dt.on('draw', function (e) {
            ki_modal.find('.modal-dialog').addClass('modal-lg');
            exam_table.find('.our-question-list').on('click', function () {
                rowData = exam_table.DataTable().row($(this).closest('tr')).data();
                rowData.popupType = "question";
                console.log(rowData);
                render_data(rowData);
            });

            exam_table.find('.paper-question-list').on('click', function () {
                rowData = exam_table.DataTable().row($(this).closest('tr')).data();
                rowData.popupType = "paper";
                render_data_new(rowData);
            })
        });

    }

    function render_data(rowData) {
        $.AryaAjax({
            url: 'exam/list-questions',
            data: rowData
        }).then((e) => {
            var drawerEl = document.querySelector("#kt_drawer_view_details_box");
            KTDrawer.getInstance(drawerEl, { overlay: true }).hide();
            drawerEl.setAttribute('data-kt-drawer-width', "{default:'300px', 'md': '900px'}");
            var footer = `<div class="card-footer">
                            <button class="btn btn-primary"><i class="fa fa-plus"></i> Add Question</button>
                            <button class="btn btn-info"><i class="fa fa-plus"></i> Upload Question</button>
                            <a target="_blank" href="${base_url}upload/SampleQuestion.xls">Download Sample Question Excelsheet</a>
                        </div> `;
            var main = mydrawer(`${rowData.subject_name}'s Questions list`);
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
                                <input type="hidden" name="question_id" value="${quesData.id}">
                                <input type="hidden" name="subject_id" value="${rowData.id}">
                                <div class="form-group">
                                    <lable class="form-label required">Enter Question</lable>
                                    <textarea class="form-control" placeholder="Enter Question" name="question" id="question">${quesData.question}</textarea>
                                </div>
                                <div class="form-group mt-4">
                                    <lable class="form-label required">Question Type</lable>
                                    <select class="form-control" name="question_type" id="question_type" >
                                        <option value="" >Select Question Type</option>
                                        <option value="objective" >Objective</option>
                                        <option value="truefalse" >True/False</option>
                                        <option value="fillintheblanks" >Fill in the blanks</option>
                                        <option value="subjective" >Subjective</option>
                                    </select>
                                </div>
                                <div class="form-group mt-4">
                                    <lable class="form-label required">Difficulty Level</lable>
                                    <select class="form-control" name="difficulty_level" id="difficulty_level">
                                        <option value="">Please Select</option>
                                        <option value="easy">Easy</option>
                                        <option value="medium">Medium</option>
                                        <option value="hard">Hard</option>
                                    </select>
                                </div>
                                <div class="form-group">
                                    <label class="form-label required">Marks</label>
                                    <input name="marks" id="marks" class="form-control" placeholder="Marks" step="0.01" type="number" required="required" value="${quesData.marks}">
                                </div>
                                <div class="form-group">
                                    <label class="form-label required">Negative Marks</label>
                                    <input name="negative_marks" id="negative_marks" class="form-control" placeholder="Negative Marks" step="0.01" type="number" required="required" value="${quesData.negative_marks}">
                                </div>
                                <div class="answer-area mt-4" data-kt-buttons="true">
                                </div>
                            `, false).then((res) => {

                                $('#question_type').val(quesData.question_type);  // Setting the question type
                                $('#difficulty_level').val(quesData.difficulty_level);

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
                                    data: { ques_id: quesData.id },
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
            main.find('.card').addClass('card-image').append(footer).find('button.btn-primary').on('click', function () {
                ki_modal.attr('data-bs-backdrop', "static");
                ki_modal.find('.modal-dialog').addClass('modal-lg');
                ki_modal.find('.modal-footer').prepend(`<button class="btn btn-outline hover-rotate-end btn-outline-dashed btn-outline-warning add-answer" type="button"><i class="fa fa-plus"></i> Add Choices</button>                    
                `);
                myModel('Add A New Question', `
                    <div class="form-group">
                        <lable class="form-label required">Enter Question</lable>
                        <input type="hidden" name="subject_id" value="${rowData.id}">
                        <textarea class="form-control" placeholder="Enter Question" name="question" id="question"></textarea>
                    </div>
                    <div class="form-group mt-4">
                        <lable class="form-label required">Question Type</lable>
                        <select class="form-control" name="question_type" id="question_type" >
                            <option value="" >Select Question Type</option>
                            <option value="objective" >Objective</option>
                            <option value="truefalse" >True/False</option>
                            <option value="fillintheblanks" >Fill in the blanks</option>
                            <option value="subjective" >Subjective</option>
                        </select>
                    </div>
                    <div class="form-group mt-4">
                        <lable class="form-label required">Difficulty Level</lable>
                        <select class="form-control" name="difficulty_level" id="difficulty_level">
                            <option value="">Please Select</option>
                            <option value="easy">Easy</option>
                            <option value="medium">Medium</option>
                            <option value="hard">Hard</option>
                        </select>
                    </div>
                    <div class="form-group">
                        <label class="form-label required">Marks</label>
                        <input name="marks" id="marks" class="form-control" placeholder="Marks" step="0.01" type="number" required="required">
                    </div>
                    <div class="form-group">
                        <label class="form-label required">Negative Marks</label>
                        <input name="negative_marks" id="negative_marks" class="form-control" placeholder="Negative Marks" step="0.01" type="number" required="required">
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
            main.find('.card').find('button.btn-info').on('click', function () {
                ki_modal.attr('data-bs-backdrop', "static");
                ki_modal.find('.modal-dialog').addClass('modal-lg');
                myModel('Upload New Questions', `
                    <div class="form-group">
                        <lable class="form-label required">Upload Csv File</lable>
                        <input type="hidden" name="subject_id" value="${rowData.id}">
                        <input type="file" name="question_file" id="question_file" accept=".xls, .xlsx" />
                    </div>`, false).then((e) => {
                    e.form.on('submit', function (e) {
                        e.preventDefault();
                        save_question_answer_file(this);
                    })
                });
            });
            ki_modal.on('hidden.bs.modal', function () {
                //ki_modal.find('form').setAttribute('enctype', 'multipart/form-data');
                ki_modal.find('form').off('submit');
                ki_modal.find('.modal-footer').find('.add-answer').remove();
                ki_modal.find('.modal-dialog').removeClass('modal-lg');
            })
        });
    }

    function render_data_new(rowData) {
        $.AryaAjax({
            url: 'exam/paper-list',
            data: rowData
        }).then((e) => {
            var drawerEl = document.querySelector("#kt_drawer_view_details_box");
            KTDrawer.getInstance(drawerEl, { overlay: true }).hide();
            drawerEl.setAttribute('data-kt-drawer-width', "{default:'300px', 'md': '900px'}");
            var footer = ``;
            var main = mydrawer(`${rowData.subject_name}'s Paper list`);
            if (e.status) {
                main.find('.card-body').removeClass('d-flex flex-column justify-content-between mt-9 bgi-no-repeat bgi-size-cover bgi-position-x-center pb-0');
            }
            else {
                main.find('.card-body').addClass('d-flex flex-column justify-content-between mt-9 bgi-no-repeat bgi-size-cover bgi-position-x-center pb-0');
            }
            main.find('.card-body').html(e.html).find('.set-questions').on('click', function (e) {
                e.preventDefault();
                var paperData = ($(this).closest('div').data('param'));
                    ki_modal.attr('data-bs-backdrop', "static");
                    ki_modal.find('.modal-dialog').addClass('modal-lg');
                
                rowData.paperData = paperData;
                loadModel(`${rowData.subject_name}'s Question List`, 'exam/list-questions', rowData).then((res) => {
                    console.log(res)
                            
                    /*res.form.on('submit', function (e) {
                        e.preventDefault();
                        save_question_answer(this);
                    })*/
                });
            });
            main.find('.card').addClass('card-image').append(footer);
            ki_modal.on('hidden.bs.modal', function () {
                ki_modal.find('form').off('submit');
                ki_modal.find('.modal-footer').find('.add-answer').remove();
                ki_modal.find('.modal-dialog').removeClass('modal-lg');
            })
        });
    }

    function save_question_answer(form){
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

    function save_question_answer_file(form){
        const fileInput = document.getElementById('question_file');
        const file = fileInput.files[0];

        // 1. Check if a file is selected
        if (!file) {
            alert('Please select a file.');
            event.preventDefault();
            return;
        }

        // 2. Check file type (MIME type or extension)
        const allowedExtensions = ['xls', 'xlsx'];
        const fileExtension = file.name.split('.').pop().toLowerCase();
        if (!allowedExtensions.includes(fileExtension)) {
            alert('Invalid file type. Only .xls and .xlsx files are allowed.');
            event.preventDefault();
            return;
        }

        // 3. Check file size (e.g., max 2MB)
        const maxSize = 20 * 1024 * 1024; // 2MB in bytes
        if (file.size > maxSize) {
            alert('File size exceeds the maximum limit of 20MB.');
            event.preventDefault();
            return;
        }
        var SendData = new FormData(form);
        $.AryaAjax({
            url: 'exam/manage-question-with-answers-file',
            data: SendData,
            success_message: `Questions Added Successfully in <b>${rowData.exam_title}</b>`
        }).then((res) => {
            showResponseError(res);
            // log(res);
            if (res.status) {
                ki_modal.modal('hide');
                render_data(rowData);
            }
        });
    }
    
    $(document).on('change', '.is-right', function () {
        ki_modal.find('.parent-ans').removeClass('active');
        $(this).closest('.parent-ans').addClass('active');
    })
    $(document).on('click','.edit-ans',function(){
        var box = $(this).closest('label');
        var old_answer = $(box).find('.ans').val();
        var answer = prompt("Enter your Choice:",old_answer);
        answer = answer.trim();
        if (answer !== null && answer !== "") {
            var list = $('.answer-area .ans');
            var i = 0;
            list.each(function () {
                if (answer == ($(this).val()) && i == 0) {
                    i = 1;
                    toastr.error(`<b>'${answer}' Answer is already exists.</b>`)
                    return;
                }
            })
            if (i == 0) {
                box.find('.ans').val(answer);
                box.find('.ans-title').text(answer);
                toastr.success('Answer Updated.. Now save Form.');
            }
        }
    })
    $(document).on('click', '.delete-ans', function () {
        var box = $(this).closest('label'),
            ans_id = $(this).siblings('.ans_id').val();
        SwalWarning('Confirmation!', 'Are you sure you want to delete this Answer.', true, 'Remove').then((e) => {
            if (e.isConfirmed) {
                if (ans_id) {
                    $.AryaAjax({
                        url: 'exam/remove-answer',
                        data: { id : ans_id },
                    }).then((res) => {
                        if (res.status) {
                            toastr.success('Answer Deleted Successfully..');
                            box.remove();
                            render_data(rowData);
                        }
                    });
                }
                else {
                    toastr.success('Answer Removed Successfully..');
                    box.remove();
                }
            }
        });
    })
    $(document).on('click', '.add-answer', function () {
        var answer = prompt("Enter your Choice:");
        answer = answer.trim();
        if (answer !== null && answer !== "") {
            var list = $('.answer-area .ans');
            var i = 0;
            list.each(function () {
                if (answer == ($(this).val()) && i == 0) {
                    i = 1;
                    toastr.error(`<b>'${answer}' Answer is already exists.</b>`)
                    return;
                }
            })
            if (i == 0) {
                template('template', {
                    answer: answer,
                    parent_class: '',
                    is_chcked: '',
                    answer_id: ''
                }).then((e) => {
                    $('.answer-area').append(e);
                });
            }
        }
    })



    
});