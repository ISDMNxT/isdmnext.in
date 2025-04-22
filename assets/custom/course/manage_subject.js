document.addEventListener('DOMContentLoaded', function (e) {

    const add_form = document.getElementById('subject_add_master');
    const list_subjects = $('#list-master-subjects');
    const delete_list_subjects = $('#list-deleted-master-subjects');
    // console.log(add_form);
    if (add_form) {
        // Init form validation rules. For more info check the FormValidation plugin's official documentation:https://formvalidation.io/
        var validator = FormValidation.formValidation(
            add_form,
            {
                fields: {
                    subject_name: {
                        validators: {
                            notEmpty: {
                                message: 'Subject Name is required'
                            }
                        }
                    },
                    subject_code: {
                        validators: {
                            notEmpty: {
                                message: 'Subject Code is required'
                            }
                        }
                    },
                    study_material: {
                        validators: {
                            file: {
                                extension: 'pdf,mp4',
                                type: 'application/pdf,video/mp4',
                                maxSize: 10485760, // 10 MB
                                message: 'The selected file is not valid. Allowed types: pdf, mp4. Maximum size: 10 MB.'
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

        add_form.addEventListener('submit', function (e) {
            // Prevent default button action
            e.preventDefault();
            var submitButton = $(this).find('button');

            // Validate form before submit
            if (validator) {
                // console.log(validator);
                validator.validate().then(function (status) {

                    const fileInput = document.getElementById('study_material');
                
                    // Define validation parameters
                    const allowedExtensions = ['pdf', 'mp4'];
                    const maxFileSize = 10 * 1024 * 1024; // 2 MB
                    const maxFiles = 5; // Max number of files
                    
                    // Get the list of files
                    const files = fileInput.files;

                        // Validate each file
                    for (let i = 0; i < files.length; i++) {
                        const file = files[i];

                        // Get the file extension
                        const fileExtension = file.name.split('.').pop().toLowerCase();

                        // Validate file extension
                        if (!allowedExtensions.includes(fileExtension)) {
                            alert(`Invalid file type: ${file.name}. Only ${allowedExtensions.join(', ')} are allowed.`);
                            return false;
                        }

                        // Validate file size
                        if (file.size > maxFileSize) {
                            alert(`File too large: ${file.name}. Maximum allowed size is 10 MB.`);
                            return false;
                        }
                    }

                    // console.log(validator);
                    var formData = new FormData(add_form);

                    if (status == 'Valid') {
                        $(submitButton).attr('data-kt-indicator', 'on').prop('disabled', true);

                        axios
                            .post(
                                ajax_url + 'course/add-master-subject',
                                new FormData(add_form)
                            )
                            .then(function (e) {
                                console.log(e);
                                if (e.data.status) {
                                    add_form.reset(),
                                        Swal.fire({
                                            text: "Subject Submited Successfully.",
                                            icon: "success",
                                            buttonsStyling: !1,
                                            confirmButtonText: "Ok, got it!",
                                            customClass: {
                                                confirmButton: "btn btn-primary",
                                            },
                                        });
                                    list_subjects.DataTable().ajax.reload();
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

    if (list_subjects) {
        var index = 1;
        var dt = list_subjects.DataTable({
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
                        DataTableEmptyMessage(list_subjects);
                        SwalWarning('Notice', `                        Subjects not Found..                    `)
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
                { 'data': 'study_material' },
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
                    targets: 3,
                    render: function(data, type, row) {
                        if (row.study_material) {
                            // Split the study_material string into an array using commas
                            const files = row.study_material.split(',');
                            // Generate links for each file
                            return files.map(file => {
                                const trimmedFile = file.trim(); // Trim spaces
                                return `
                                    <div style="display: flex; gap: 10px; align-items: center;">
                                        <!-- View Icon -->
                                        <a href="${base_url}upload/study_material/${trimmedFile}" target="_blank" class="btn btn-info btn-xs btn-sm" title="View File">
                                            <i class="fa fa-eye"></i>
                                        </a>
                                        <!-- Delete Icon -->
                                        <button class="btn btn-danger btn-xs btn-sm" title="Delete File" onclick="deleteFile(${row.id},'${trimmedFile}')">
                                            <i class="fa fa-trash"></i>
                                        </button>
                                    </div>
                                `;
                            }).join('&nbsp;'); // Separate links with line breaks
                        } else {
                            return '';
                        }
                    }
                },
                {
                    targets: -1,
                    render: function (data, type, row) {
                        return `
                                <button class="btn btn-sm btn-light-info edit-record">
                                    <i class="fa fa-edit"></i>
                                </button>
                                <button class="btn btn-sm btn-light-danger delete-subject" data-id="${row.id}">
                                    <i class="fa fa-trash"></i>
                                </button>
                                <button type="button" class="btn btn-info btn-xs btn-sm add-paper">Create Paper</button>`;

                    }
                }
            ]
        });
        dt.on('draw', function (e) {
            index = 1;
            list_subjects.EditForm('course/edit-master-subject', 'Edit Subject');

            ki_modal.find('.modal-dialog').addClass('modal-lg');
            list_subjects.find('.add-paper').on('click', function () {
                rowData = list_subjects.DataTable().row($(this).closest('tr')).data();
                render_data_paper(rowData);
            })
        })
        $(document).on('click', '.delete-subject', function () {
            var id = $(this).data('id');
            SwalWarning('Confirmation','Are you sure for delete this subject',true,'Delete').then((e) => {
                if (e.isConfirmed) {
                    $.AryaAjax({
                        url: 'course/delete-master-subject',
                        data: { id },
                        success_message: 'Subject Deleted Successfully..'
                    }).then((f) => {
                        if(f.status){
                            list_subjects.DataTable().ajax.reload();
                            delete_list_subjects.DataTable().ajax.reload();
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

    window.deleteFile = function(id,fileName) {

        SwalWarning('Confirmation',`Are you sure you want to delete the file "${fileName}"?`,true,'Delete').then((e) => {
            if (e.isConfirmed) {
                $.AryaAjax({
                    url: 'course/delete_master_subject_file',
                    data: { id, file_name: fileName },
                    success_message: 'File deleted successfully!'
                }).then((f) => {
                    if(f.status){
                        list_subjects.DataTable().ajax.reload();
                    }
                    showResponseError(f);
                });
            }
            else{
                toastr.warning('Request Aborted');
            }
        });
    }

    if (delete_list_subjects) {
        var index = 1;
        var ddt = delete_list_subjects.DataTable({
            dom: small_dom,
            ajax: {
                url: ajax_url + 'course/list-deleted-master-subjects'
            },
            columns: [
                { 'data': null },
                { 'data': 'subject_name' },
                { 'data': 'subject_code' },
                { 'data': 'study_material' },
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
                    targets : 3,
                    render : function(data,type,row){
                        if(row.study_material){
                            return `<a href="${base_url}upload/${row.study_material}" target="_blank" class="btn btn-info btn-xs btn-sm"><i class="fa fa-eye"></i>File</a>`;
                        }
                        else {
                            return '';
                        }
                    }
                },
                {
                    targets: -1,
                    render: function (data, type, row) {
                        return `<div class="btn-group">                                
                                <button class="btn btn-sm btn-light-primary undelete-btn">
                                    <i class="fa fa-arrow-left"></i> Move to list
                                </button>
                                <button data-id="${row.id}" class="btn btn-sm btn-light-danger parma-delete-subject">
                                    <i class="fa fa-trash"></i> Delete
                                </button>
                            </div>`;
                    }
                }
            ]
        });
        ddt.on('draw',function(r){
            delete_list_subjects.unDeleteEvent('subject_master', '','id');
        })
        $(document).on('click', '.parma-delete-subject', function () {
            var id = $(this).data('id');
            SwalWarning('Confirmation','Are you sure for delete this subject',true,'Delete').then((e) => {
                if (e.isConfirmed) {
                    $.AryaAjax({
                        url: 'course/parma-master-subject-delete',
                        data: { id },
                        success_message: 'Subject Deleted Successfully..'
                    }).then((f) => {
                        if(f.status){
                            delete_list_subjects.DataTable().ajax.reload();
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

    /*subject_type.on('change', function () {
        var divclass = (this.value);
        $('.both_box').slideUp('fast');

        $('.' + divclass + '_box').slideDown('fast')
        $('.' + divclass + '_box').find('input:eq(1)').focus();
    });*/

    function render_data_paper(rowData) {
        $.AryaAjax({
            url: 'exam/paper-list',
            data: rowData
        }).then((e) => {
            var drawerEl = document.querySelector("#kt_drawer_view_details_box");
            KTDrawer.getInstance(drawerEl, { overlay: true }).hide();
            drawerEl.setAttribute('data-kt-drawer-width', "{default:'300px', 'md': '900px'}");
            var footer = `<div class="card-footer">
                            <button class="btn btn-primary"><i class="fa fa-plus"></i>Add Paper</button>
                        </div>`;
            var main = mydrawer(`${rowData.subject_name}'s Paper List`);
            if (e.status) {
                main.find('.card-body').removeClass('d-flex flex-column justify-content-between mt-9 bgi-no-repeat bgi-size-cover bgi-position-x-center pb-0');
            }
            else {
                main.find('.card-body').addClass('d-flex flex-column justify-content-between mt-9 bgi-no-repeat bgi-size-cover bgi-position-x-center pb-0');
            }
            main.find('.card-body').html(e.html).find('.edit,.delete').on('click', function (e) {
                // if($(this).hasClass())
                e.preventDefault();
                var paperData = ($(this).closest('div').data('param'));
                // log(quesData);
                if ($(this).hasClass('edit')) {
                    ki_modal.attr('data-bs-backdrop', "static");
                    ki_modal.find('.modal-dialog').addClass('modal-lg');
                    
                    myModel('<i class="fa fa-edit"></i> Edit Paper', `
                                <input type="hidden" name="paper_id" value="${paperData.id}">
                                <div class="form-group">
                        <lable class="form-label required">Paper Name</lable>
                        <input type="text" name="paper_name" id="paper_name" class="form-control" placeholder="Paper name" value="${paperData.paper_name}">
                    </div>
                    <div class="form-group mt-4">
                        <lable class="form-label required">Paper Type</lable>
                        <select class="form-control" name="paper_type" id="paper_type" >
                            <option value="" >Select Paper Type</option>
                            <option value="theortical" >Theortical</option>
                            <option value="practical" >Practical</option>
                        </select>
                    </div>
                    <div class="form-group">
                        <label class="form-label required">Paper Duration (Minuts)</label>
                        <input name="paper_duration" id="paper_duration" class="form-control" placeholder="Paper Duration" step="1" type="number" required="required" value="${paperData.paper_duration}">
                    </div>
                    <div class="form-group">
                        <label class="form-label required">Total Marks</label>
                        <input name="total_marks" id="total_marks" class="form-control" placeholder="Total Marks" step="1" type="number" required="required" value="${paperData.total_marks}">
                    </div>`, false).then((res) => {
                                $('#paper_type').val(paperData.paper_type);

                        res.form.on('submit', function (e) {
                            e.preventDefault();
                            save_paper(this);
                        })
                    });
                }
                else {
                    SwalWarning('Confirmation!',
                        `Are you sure you want delete <b class="text-success">${paperData.paper_name}</b> Paper.`, true, 'Ok, Delete It.').then((e) => {
                            if (e.isConfirmed) {
                                // alert('OK');
                                $.AryaAjax({
                                    url: 'exam/delete-paper',
                                    data: { p_id: paperData.id },
                                }).then((res) => {
                                    if (res.status) {
                                        toastr.success('Paper Deleted Successfully..');
                                        ki_modal.modal('hide');
                                        render_data_paper(rowData);
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
               
                myModel('Add A New Paper', `
                    <div class="form-group">
                        <lable class="form-label required">Paper Name</lable>
                        <input type="text" name="paper_name" id="paper_name" class="form-control" placeholder="Paper name" value="">
                    </div>
                    <div class="form-group mt-4">
                        <lable class="form-label required">Paper Type</lable>
                        <select class="form-control" name="paper_type" id="paper_type" >
                            <option value="" >Select Paper Type</option>
                            <option value="theortical" >Theortical</option>
                            <option value="practical" >Practical</option>
                        </select>
                    </div>
                    <div class="form-group">
                        <label class="form-label required">Paper Duration (Minuts)</label>
                        <input name="paper_duration" id="paper_duration" class="form-control" placeholder="Paper Duration" step="1" type="number" required="required">
                    </div>
                    <div class="form-group">
                        <label class="form-label required">Total Marks</label>
                        <input name="total_marks" id="total_marks" class="form-control" placeholder="Total Marks" step="1" type="number" required="required">
                    </div>`, false).then((e) => {
                    e.form.on('submit', function (e) {
                        e.preventDefault();
                        save_paper(this);
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

    function save_paper(form){
        var SendData = new FormData(form);
        var data = [];

        var paper_name = $(form).find('#paper_name').val();
        var paper_type = $(form).find('#paper_type').val();
        var paper_duration = $(form).find('#paper_duration').val();
        var total_marks = $(form).find('#total_marks').val();
        
        SendData.append('subject_id', rowData.id);
        // Validate the Question Type field
        if (!paper_name) {
            SwalWarning('Please enter paper name.');
            return false;
        }

        if (!paper_type) {
            SwalWarning('Please select paper type.');
            return false;
        }

        if (!paper_duration) {
            SwalWarning('Please enter paper duration in minuts.');
            return false;
        }

        if (!total_marks) {
            SwalWarning('Please enter total marks.');
            return false;
        }

        $.AryaAjax({
            url: 'exam/manage-papers',
            data: SendData,
            success_message: `Paper Added Successfully in <b>${rowData.subject_name}</b>`
        }).then((res) => {
            showResponseError(res);
            // log(res);
            if (res.status) {
                ki_modal.modal('hide');
                render_data_paper(rowData);
            }
        });
    }

});