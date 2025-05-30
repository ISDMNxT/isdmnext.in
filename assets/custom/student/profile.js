document.addEventListener('DOMContentLoaded', function (d) {
    const update_profile            = document.getElementById('save-student-data');
    const resume_data_form          = document.getElementById('set_resume_data');
    const course_box = $('select[name="course_id"]');
    select2Student('select[name="student_id"]');
    if (update_profile) {
        var validation = MyFormValidation(update_profile);
        validation.addField('name', {
            validators: {
                notEmpty: { message: 'Enter Student Name.' }
            }
        });
        validation.addField('alternative_mobile', {
            validators: {
                regexp: {
                    regexp: /^(?:\+|\d)[\d-\s]+$/,
                    message: 'Please enter a valid contact number.'
                },
                stringLength: {
                    min: 10,
                    max: 15,
                    message: 'The Mobile number must be between 10 and 15 characters.'
                }
            }
        });
        validation.addField('contact_number', {
            validators: {
                notEmpty: {
                    message: 'Please enter a Whatsapp number.'
                },
                regexp: {
                    regexp: /^(?:\+|\d)[\d-\s]+$/,
                    message: 'Please enter a valid Whatsapp number.'
                },
                stringLength: {
                    min: 10,
                    max: 15,
                    message: 'The Whatsapp number must be between 10 and 15 characters.'
                }
            }
        });
        validation.addField('father_name', {
            validators: {
                notEmpty: {
                    message: 'Please enter father name.'
                }
            }
        });
        validation.addField('mother_name', {
            validators: {
                notEmpty: {
                    message: 'Please enter mother name.'
                },
            }
        });
        // validation.addField('email', {
        //     validators: {
        //         regexp: {
        //             regexp: /^[^\s@]+@[^\s@]+\.[^\s@]+$/,
        //             message: "The value is not a valid email address",
        //         },
        //         notEmpty: { message: "Email address is required" },
        //     },
        // });
        validation.addField('address', {
            validators: {
                notEmpty: {
                    message: 'Address is required'
                },
            }
        });
        validation.addField('dob', {
            validators: { notEmpty: { message: 'Date of Birth is requried' } }
        })
        validation.addField('pincode', {
            validators: {
                notEmpty: {
                    message: 'Pincode is required'
                },
                regexp: {
                    regexp: /^[1-9][0-9]{5}$/,
                    message: 'Invalid Pincode format'
                },
                stringLength: {
                    max: 6,
                    message: 'Pincode must be 6 digits'
                }
            }
        });
        $(document).on('submit', '.save-student-data', function (r) {
            r.preventDefault();
            const changed_fields = [];
            $(update_profile).find('input, select, textarea').each(function () {
                const name = $(this).attr('name');
                const current_value = $(this).val();
                const original_value = $(this).attr('data-original');
                if (name && typeof original_value !== 'undefined' && current_value !== original_value) {
                    changed_fields.push(name);
                }
            });
            const formData = new FormData(this);
            formData.append('fields_updated', JSON.stringify(changed_fields));
            console.log('Changed fields:', changed_fields);

            $.AryaAjax({
                url: 'website/update-stuednt-basic-details',
                data: formData,
                success_message: 'Profile Data Updated Successfully..',
                validation: validation
            }).then((d) => {
                if (d.status) {
                    $.each(d.student_data, function (i, v) {
                        if (i == 'status') {
                            if (v)
                                $(`.student-${i}`).addClass('d-none')
                            else
                                $(`.student-${i}`).removeClass('d-none')
                        }
                        else
                            $(`.student-${i}`).text(v);
                    });
                }
            });
        })
    }
    const fee_record = $("#fee-record");
    if (fee_record) {
        var dt = fee_record.DataTable({
            columnDefs: [
                // {
                //     targets: 2,
                //     render: function (ac, b, c) {
                //         return `<div class="d-flex align-items-center flex-wrap">
                //                     <div class="fs-2 fw-bold me-5" id="payment_id_${ac}">${ac}</div>
                //                     <button class="btn btn-icon btn-sm btn-light copy-text list-payment_id" data-text="${ac}">
                //                         <i class="ki-duotone ki-copy fs-2 text-muted"></i>
                //                     </button>
                //                 </div>`;
                //     }
                // },
                {
                    targets: 3,
                    render: function (ad, b, c) {
                        return `${ad} `;
                    }
                }
            ],
            "footerCallback": function (row, data, start, end, display) {
                var api = this.api(),
                    data;
                // Remove the formatting to get integer data for summation
                var intVal = function (i) {
                    return typeof i === "string" ?
                        i.replace(/[\$,]/g, "") * 1 :
                        typeof i === "number" ?
                            i : 0;
                };
                // Total over all pages
                var total = api
                    .column(3)
                    .data()
                    .reduce(function (a, b) {
                        return intVal(a) + intVal(b);
                    }, 0);
                // Total over this page
                var pageTotal = api
                    .column(4, {
                        page: "current"
                    })
                    .data()
                    .reduce(function (a, b) {
                        return intVal(a) + intVal(b);
                    }, 0);
                // Update footer
                
                $(api.column(4).footer()).html(
                    // "$" + pageTotal + " ( $" + total + " total)"
                    `${inr} ${pageTotal}`
                );
            }
        }).on('draw', function (e) {
        });
        // log(dt);
    }
    if (resume_data_form) {
        var validator = FormValidation.formValidation(
            resume_data_form,
            {
                fields: {
                    resume_headline: { // Validation for category dropdown
                        validators: {
                            notEmpty: {
                                message: 'Resume Headline is required.'
                            }
                        }
                    },
                    profile_summary: { // Validation for category dropdown
                        validators: {
                            notEmpty: {
                                message: 'Profile Summary is required.'
                            }
                        }
                    },
                    experience: { // Validation for category dropdown
                        validators: {
                            notEmpty: {
                                message: 'Experience is required.'
                            }
                        }
                    },
                    fluancy_in_english: { // Validation for category dropdown
                        validators: {
                            notEmpty: {
                                message: 'Fluancy In English is required.'
                            }
                        }
                    },
                    'industries[]': { // Validation for checkboxes
                        validators: {
                            choice: {
                                min: 1,
                                message: 'Please select at least one Industry.'
                            }
                        }
                    },
                    'key_skills[]': { // Validation for checkboxes
                        validators: {
                            choice: {
                                min: 1,
                                message: 'Please select at least one key skills.'
                            }
                        }
                    },
                    'pan_languages[]': { // Validation for checkboxes
                        validators: {
                            choice: {
                                min: 1,
                                message: 'Please select at least one languages.'
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

        resume_data_form.addEventListener('submit', function (e) {
            // Prevent default button action
            e.preventDefault();

            // Perform form validation before submitting via AJAX
            validator.validate().then(function (status) {
                if (status === 'Valid') {
                    $.AryaAjax({
                        url: 'website/set-resume-data',
                        data: new FormData(resume_data_form),
                        success_message: 'Data Saved Successfully.',
                        page_reload: true
                    }).then((r) => showResponseError(r));
                }
            });
        });
    }
    /*$(document).on('submit', '.set-resume-data', function (re) {

        re.preventDefault();
        $.AryaAjax({
            url: 'website/set-resume-data',
            data: new FormData(this),
            success_message: 'Password Updated Successfully.',
            page_reload: true
        }).then((r) => showResponseError(r));
    });*/
    $(document).on('change', '.upload-student-docs', function () {
        var id = $(this).closest('table').data('id');
        var fileInput = this;
        var file = fileInput.files[0];

        if (!file) {
            SwalWarning('Please Choose a valid file.');
            return;
        }

        var formData = new FormData();

        formData.append('file', file);
        formData.append('student_id', id);
        formData.append('name',$(fileInput).attr('name'));

        Swal.fire({
            title: 'Uploading...',
            html: '0%',
            allowOutsideClick: false,
            showConfirmButton: false,
            willOpen: () => {
                $.ajax({
                    url: ajax_url+'website/update-student-docs', // Change this to your upload endpoint
                    type: 'POST',
                    data: formData,
                    dataType : 'json',
                    processData: false,
                    contentType: false,
                    xhr: function () {
                        var xhr = new window.XMLHttpRequest();
                        xhr.upload.addEventListener("progress", function (evt) {
                            if (evt.lengthComputable) {
                                var percentComplete = evt.loaded / evt.total * 100;
                                Swal.getContent().querySelector('p').innerHTML = percentComplete.toFixed(2) + '%';
                            }
                        }, false);
                        return xhr;
                    },
                    success: function (response) {
                        console.log(response);
                        if(response.status){
                            // SwalSuccess('Uploaded','File Uploaded Successfully').then( (r) => {
                            //     if(r.isConfirmed){
                                    location.reload();
                            //     }
                            // })
                        }
                        Swal.close();
                    },
                    error: function (xhr, status, error) {
                        Swal.close();
                        // Handle error
                        console.error(xhr.responseText);
                    }
                });
            }
        });
    });
    $(document).on('submit', '.change-student-password', function (re) {
        re.preventDefault();
        $.AryaAjax({
            url: 'website/update-stuednt-password',
            data: new FormData(this),
            success_message: 'Password Updated Successfully.',
            page_reload: true
        }).then((r) => showResponseError(r));
    });
    $(document).on('submit', '.update-student-batch-and-roll-no', function (e) {
        e.preventDefault();
        $.AryaAjax({
            url: 'website/update-student-batch-and-roll_no',
            data: new FormData(this),
            success_message: 'Student Updated Successfully.',
            page_reload: true
        }).then((r) => showResponseError(r));
    });
    $(document).on('click', '.edit-installment-record', function () {
        var fee_id = $(this).data('fee_id');
        $.AryaAjax({
            url: 'website/edit-installment-record',
            data: { fee_id },
        }).then((r) => {
            ki_modal.find('.modal-dialog').addClass('modal-lg');
            myModel('Edit Installment Record', r.html, 'website/update-installment-record').then((r) => {
                if (r.status) {
                    location.reload();
                }
            });
        });
    });
    $(document).on('click', '.delete-installment-record', function () {
        var fee_id = $(this).data('fee_id');
        SwalWarning('Confirmation!', 'Are you sure for delete installment record', true, 'Yes').then((e) => {

            if (e.isConfirmed) {
                $.AryaAjax({
                    url: 'website/delete-installment-record',
                    data: { fee_id },
                    page_reload: true,
                    success_message: 'Installment Record Deleted Successfully..'
                })
            }
        })
    })
    $(document).on('click', '.print-receipt', function () {
        var id = $(this).data('fee_id');
        $.AryaAjax({
            url: 'website/print-fee-record',
            data: { id },
        }).then((res) => {
            var drawerEl = document.querySelector("#kt_drawer_example_advanced");
            drawerEl.setAttribute('data-kt-drawer-width', "{default:'300px', 'md': '900px'}");
            var drawer = KTDrawer.getInstance(drawerEl);
            // drawer.trigger(drawer, "kt.drawer.show"); // trigger show drawer
            drawer.update();
            drawer.show();
            var
                main = $('#kt_drawer_example_advanced'),
                body = main.find('.card-body'),
                title = main.find('.card-title');
            // console.log(title);
            title.html('Fee Receipt');
            body.html(res.html).find('button').on('click', async function () {
                var content = $(this).closest('.card-body').clone();
                content.find('button').remove();
                content = content.html();

                var newWindow = window.open('', '_blank');
                newWindow.document.open();
                await newWindow.document.write(`<html><head><title>Print Receipt</title>`);
                await newWindow.document.write(`<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-EVSTQN3/azprG1Anm3QDgpJLIm9Nao0Yz1ztcQTwFspd3yD65VohhpuuCOmLASjC" crossorigin="anonymous">`);
                // await newWindow.document.write(`<link href="${base_url}assets/plugins/global/plugins.bundle.css" rel="stylesheet" type="text/css">`);
                // await newWindow.document.write(`<link href="${base_url}assets/css/style.bundle.css" rel="stylesheet" type="text/css">`);
                await newWindow.document.write(`</head><body>${content}</body></html>`);

                newWindow.document.close();
                await newWindow.print();
                newWindow.close();
            });
        });
    });

    course_box.select2({
        placeholder: "Select a Course",
        templateSelection: optionFormatSecond,
        templateResult: optionFormatSecond
        ,
    });

    // $(document).on('click', '.student_request', function () {
    //     var id = $(this).data('id');
    //     SwalWarning('Confirmation','Are you sure you want to Send Request?',true,'Send Request').then((e) => {
    //         if (e.isConfirmed) {
    //             $.AryaAjax({
    //                 url: 'student/send-request',
    //                 data: { id },
    //                 success_message: 'Request Sent Successfully.'
    //             }).then((f) => {
    //                 if(f.status){
    //                     location.href = location.href;
    //                 }
    //                 showResponseError(f);
    //             });
    //         }
    //         else{
    //             toastr.warning('Request Aborted');
    //         }
    //     })
    // });

    // Publish Changes After Admin Approval
$(document).on('click', '.publish_student', function () {
    const student_id = $(this).data('id');

    Swal.fire({
        title: 'Confirm Publish',
        text: 'Do you want to publish the approved changes and notify the student?',
        icon: 'info',
        showCancelButton: true,
        confirmButtonText: 'Yes, Publish',
        cancelButtonText: 'Cancel'
    }).then((result) => {
        if (result.isConfirmed) {
            $.post(base_url + "ajax/student/publish_student", { student_id: student_id }, function (res) {
                if (res.status) {
                    toastr.success('Student profile updated and notification sent.');
                    setTimeout(function () {
                        location.reload();
                    }, 1000);
                } else {
                    toastr.error(res.message || 'Failed to publish changes.');
                }
            }, 'json');
        }
    });
});

    
    
    
$(document).ready(function () {
    $('select[name="experience"]').val('');
    $('select[name="fluancy_in_english"]').val('');

    function updateDropdownText(checkboxClass, displayId, buttonTextId, defaultText) {
        let selected = [];
        $(checkboxClass + ':checked').each(function () {
            let labelText = $('label[for="' + $(this).attr('id') + '"]').text();
            selected.push(labelText);
        });
        $(buttonTextId).text(selected.length > 0 ? selected.length + ' selected' : defaultText);
    
        let badges = selected.map(item => `<span class="badge badge-info mr-1">${item}</span>`);
        $(displayId).html(badges.join(''));
    }

    // Clear all dropdown checkboxes on page load
$('.industry-checkbox, .skill-checkbox, .language-checkbox, .role-checkbox').prop('checked', false);

// Reset the visible dropdown text and badges
updateDropdownText('.industry-checkbox', '#selectedIndustries', '#industryDropdownText', 'Select Industry');
updateDropdownText('.skill-checkbox', '#selectedKeySkills', '#keySkillsDropdownText', 'Select Key Skills');
updateDropdownText('.language-checkbox', '#selectedLanguages', '#languageDropdownText', 'Select Languages');
updateDropdownText('.role-checkbox', '#selectedRoles', '#roleDropdownText', 'Select Role');


    $(document).on('change', '.industry-checkbox', () => updateDropdownText('.industry-checkbox', '#selectedIndustries', '#industryDropdownText', 'Select Industry'));
    $(document).on('change', '.skill-checkbox', () => updateDropdownText('.skill-checkbox', '#selectedKeySkills', '#keySkillsDropdownText', 'Select Key Skills'));
    $(document).on('change', '.language-checkbox', () => updateDropdownText('.language-checkbox', '#selectedLanguages', '#languageDropdownText', 'Select Languages'));
    $(document).on('change', '.role-checkbox', () => updateDropdownText('.role-checkbox', '#selectedRoles', '#roleDropdownText', 'Select Role'));

    updateDropdownText('.industry-checkbox', '#selectedIndustries', '#industryDropdownText', 'Select Industry');
    updateDropdownText('.skill-checkbox', '#selectedKeySkills', '#keySkillsDropdownText', 'Select Key Skills');
    updateDropdownText('.language-checkbox', '#selectedLanguages', '#languageDropdownText', 'Select Languages');
    updateDropdownText('.role-checkbox', '#selectedRoles', '#roleDropdownText', 'Select Role');

    window.getSelectedIndustryIDs = () => $('.industry-checkbox:checked').map(function () { return $(this).val(); }).get();
    window.getSelectedRoleIDs = () => $('.role-checkbox:checked').map(function () { return $(this).val(); }).get();
    window.getSelectedKeySkills = () => $('.skill-checkbox:checked').map(function () { return $(this).val(); }).get();
    window.getSelectedLanguages = () => $('.language-checkbox:checked').map(function () { return $(this).val(); }).get();
});


$(document).ready(function () {
    // Send Request To Change Student Details
    $(document).on('click', '.student_request', function () {
        let id = $(this).data('id');
        Swal.fire({
            title: 'Are you sure?',
            text: "You want to request a change for this student's details.",
            icon: 'question',
            showCancelButton: true,
            confirmButtonText: 'Yes, Send Request',
            cancelButtonText: 'Cancel'
        }).then((result) => {
            if (result.isConfirmed) {
                $.post(base_url + "ajax/student/send_request", { id: id }, function (res) {
                    if (res.status) {
                        toastr.success('Change Request Sent Successfully.');
                        setTimeout(function () {
                            location.reload();
                        }, 1000);
                    } else {
                        toastr.error(res.error || 'Something went wrong.');
                    }
                }, 'json');
            }
        });
    });
    

    




})
})