// document.addEventListener('DOMContentLoaded', function (e) {
//     const add_job_form      = document.getElementById('add_job_form');
//     // const department        = $('#department_id');
//     const education        = $('#education_id');


//     const role              = $('#role_id');
//     const temp_role_id      = $('#temp_role_id').val();
//     const temp_key_skills   = $('#temp_key_skills').val();
//     const table             = $('#list_job_role');

//     function reinitializeValidation() {
//         // Re-add skill[] validation
//         validator.addField('key_skills[]', {
//             validators: {
//                 choice: {
//                     min: 1,
//                     message: 'Please select at least one skill'
//                 }
//             }
//         });
//     }

//     if (add_job_form) {
//         // Init form validation rules. For more info check the FormValidation plugin's official documentation:https://formvalidation.io/
//         var validator = FormValidation.formValidation(
//             add_job_form,
//             {
//                 fields: {
//                     employer_id: {
//                         validators: {
//                             notEmpty: {
//                                 message: 'Employer is required'
//                             }
//                         }
//                     },
//                     job_title: {
//                         validators: {
//                             notEmpty: {
//                                 message: 'Job Title is required'
//                             }
//                         }
//                     },
//                     job_highlights: {
//                         validators: { notEmpty: { message: 'Job Highlights is requried' } }
//                     },
//                     industry_id: {
//                         validators:{
//                             notEmpty :{
//                                 message : 'Industry is required.'
//                             }
//                         }
//                     },
//                     // department_id: {
//                     //     validators: {
//                     //         notEmpty: {
//                     //             message: 'Department is required'
//                     //         }
//                     //     }
//                     // },
//                     role_id: {
//                         validators: {
//                             notEmpty: {
//                                 message: 'Role is required'
//                             },
//                         }
//                     },
//                     experience: {
//                         validators: {
//                             notEmpty: {
//                                 message: 'Experience is required'
//                             }
//                         }
//                     },
//                     education_id: {
//                         validators: {
//                             notEmpty: {
//                                 message: 'Education is required'
//                             }
//                         }
//                     },
//                     salary: {
//                         validators: {
//                             notEmpty: {
//                                 message: 'Salary is required'
//                             },
//                         }
//                     },
//                     openings: {
//                         validators: {
//                             notEmpty: {
//                                 message: 'Openings is required'
//                             },
//                         }
//                     },
//                     job_type: {
//                         validators: {
//                             notEmpty: { message: "Job Type is required" },
//                         },
//                     },
//                     employment_type: {
//                         validators: {
//                             notEmpty: { message: "Employment Type is required" },
//                         },
//                     },
//                     preferred_shift: {
//                         validators: {
//                             notEmpty: { message: "Preferred Shift is required" },
//                         },
//                     },
//                     work_location: {
//                         validators: {
//                             notEmpty: { message: "Work Location is required" },
//                         },
//                     },
//                     fluancy_in_english: {
//                         validators: {
//                             notEmpty: { message: "Fluancy In English is required" },
//                         },
//                     },
//                     'key_skills[]': { // Validation for checkboxes
//                         validators: {
//                             choice: {
//                                 min: 1,
//                                 message: 'Please select at least one skill'
//                             }
//                         }
//                     }/*,
//                     state_id: {
//                         validators: {
//                             notEmpty: {
//                                 message: 'State is required'
//                             },
//                         }
//                     },
//                     city_id: {
//                         validators: {
//                             notEmpty: {
//                                 message: 'Distric is required'
//                             },
//                         }
//                     }*/
//                 },


//                 plugins: {
//                     trigger: new FormValidation.plugins.Trigger(),
//                     bootstrap: new FormValidation.plugins.Bootstrap5({
//                         rowSelector: '.form-group',
//                         eleInvalidClass: '',
//                         eleValidClass: ''
//                     })
//                 }
//             }
//         );
//         // Submit button handler
//         add_job_form.addEventListener('submit', function (e) {
//             // Prevent default button action
//             e.preventDefault();
//             var submitButton = $(this).find('button');

//             if (typeof tinymce !== "undefined") {
//                 tinymce.triggerSave(); // Ensures textarea gets updated
//             }

//             // Validate form before submit
//             if (validator) {
//                 // console.log(validator);
//                 validator.validate().then(function (status) {
//                     // console.log(validator);
//                     var formData = new FormData(add_job_form);

//                     if (status == 'Valid') {
//                         $(submitButton).attr('data-kt-indicator', 'on').prop('disabled', true);
//                         axios
//                         .post(
//                             ajax_url + 'employer/job_mgmt',
//                             new FormData(add_job_form)
//                         )
//                         .then(function (e) {
//                             if (e.data.status) {
//                                 toastr.success(e.data.msg);
//                                 setTimeout(function() {
//                                     location.href = location.href;
//                                 }, 1000); // 3000 milliseconds = 3 seconds
//                             } else {
//                                 toastr.error('Something went wrong!');
//                             }
//                         })
//                         .catch(function (t) {
//                             toastr.error('Sorry, looks like there are some errors detected, please try again.');
//                         })
//                         .then(() => {
//                             $(submitButton).removeAttr('data-kt-indicator').prop("disabled", false);
//                         });
//                     }
//                 });
//             }
//         });
//     }
//     // department.change(function () {
//     //     var department_id = $(this).val();
//     //     role.html('');
//     //     $.AryaAjax({
//     //         url: 'employer/get_roles',
//     //         data: { department_id },
//     //         dataType: 'json'
//     //     }).then(function (res) {
//     //         var options = '<option value="">Select Role</option>';
//     //         if (res.status) {
//     //             if (res.roles.length) {
//     //                 $.each(res.roles, function (index, roless) {
//     //                     let sel = '';
//     //                     if(temp_role_id == roless.id){
//     //                         sel = 'selected="selected"';
//     //                     }
//     //                     options += `<option value="${roless.id}" ${sel} >${roless.role}</option>`;
//     //                 });
//     //             }
//     //         }
//     //         else {
//     //             SwalWarning('This Department have not any role.');
//     //         }
//     //         role.html(options);
//     //     }).catch(function (a) {
//     //         console.log(a);
//     //     });
//     // });

//     role.change(function () {
//         var role_id = $(this).val();
//         if(temp_role_id > 0){
//             role_id = temp_role_id;
//         }
//         table.html('');
//         $.AryaAjax({
//             url: 'employer/list-role-skillll',
//             data: { role_id, temp_key_skills }
//         }).then(function (res) {
//             $('#temp_role_id').val(0);
//             table.html(res.html);
//             reinitializeValidation();
//         }).catch(function (a) {
//             console.log(a);
//         });
//     });

//     // if ($("#job_id").val() > 0) {
//     //     department.trigger("change");
//     // }

//     if ($("#job_id").val() > 0) {
//         education.trigger("change");
//     }

//     if (temp_role_id > 0) {
//         role.trigger("change");
//     }
// });

document.addEventListener('DOMContentLoaded', function (e) {
    const add_job_form = document.getElementById('add_job_form');
    const education = $('#education_id');
    const role = $('#role_id');
    const temp_role_id = $('#temp_role_id').val();
    const temp_key_skills = $('#temp_key_skills').val();
    const table = $('#list_job_role');

    function reinitializeValidation() {
        validator.addField('key_skills[]', {
            validators: {
                choice: {
                    min: 1,
                    message: 'Please select at least one skill'
                }
            }
        });
    }

    if (add_job_form) {
        var validator = FormValidation.formValidation(
            add_job_form,
            {
                fields: {
                    employer_id: { validators: { notEmpty: { message: 'Employer is required' } } },
                    job_title: { validators: { notEmpty: { message: 'Job Title is required' } } },
                    job_highlights: { validators: { notEmpty: { message: 'Job Highlights is required' } } },
                    industry_id: { validators: { notEmpty: { message: 'Industry is required.' } } },
                    role_id: { validators: { notEmpty: { message: 'Role is required' } } },
                    experience: { validators: { notEmpty: { message: 'Experience is required' } } },
                    education_id: { validators: { notEmpty: { message: 'Education is required' } } },
                    salary: { validators: { notEmpty: { message: 'Salary is required' } } },
                    openings: { validators: { notEmpty: { message: 'Openings is required' } } },
                    job_type: { validators: { notEmpty: { message: "Job Type is required" } } },
                    employment_type: { validators: { notEmpty: { message: "Employment Type is required" } } },
                    preferred_shift: { validators: { notEmpty: { message: "Preferred Shift is required" } } },
                    work_location: { validators: { notEmpty: { message: "Work Location is required" } } },
                    fluancy_in_english: { validators: { notEmpty: { message: "Fluancy In English is required" } } },
                    'key_skills[]': {
                        validators: {
                            choice: {
                                min: 1,
                                message: 'Please select at least one skill'
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

        // Submit handler with TinyMCE sync
        add_job_form.addEventListener('submit', function (e) {
            e.preventDefault();
            var submitButton = $(this).find('button');

            // ✅ Trigger the editor to update the textarea
            if (typeof tinymce !== "undefined") {
                tinymce.triggerSave(); // Sync content
            }

            // 🧪 Log the content to confirm
            console.log('Job Highlights value:', $('[name="job_highlights"]').val());

            if (validator) {
                validator.validate().then(function (status) {
                    var formData = new FormData(add_job_form);

                    if (status === 'Valid') {
                        $(submitButton).attr('data-kt-indicator', 'on').prop('disabled', true);
                        axios
                            .post(ajax_url + 'employer/job_mgmt', formData)
                            .then(function (e) {
                                if (e.data.status) {
                                    toastr.success(e.data.msg);
                                    setTimeout(function () {
                                        location.href = location.href;
                                    }, 1000);
                                } else {
                                    toastr.error('Something went wrong!');
                                }
                            })
                            .catch(function () {
                                toastr.error('Sorry, looks like there are some errors detected, please try again.');
                            })
                            .then(() => {
                                $(submitButton).removeAttr('data-kt-indicator').prop("disabled", false);
                            });
                    }
                });
            }
        });
    }

    // Role-based skill reloading
    role.change(function () {
        var role_id = $(this).val();
        if (temp_role_id > 0) {
            role_id = temp_role_id;
        }
        table.html('');
        $.AryaAjax({
            url: 'employer/list-role-skillll',
            data: { role_id, temp_key_skills }
        }).then(function (res) {
            $('#temp_role_id').val(0);
            table.html(res.html);
            reinitializeValidation();
        }).catch(function (a) {
            console.log(a);
        });
    });

    if ($("#job_id").val() > 0) {
        education.trigger("change");
    }

    if (temp_role_id > 0) {
        role.trigger("change");
    }
});
