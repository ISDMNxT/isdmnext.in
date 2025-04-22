document.addEventListener('DOMContentLoaded', function (e) {
	const send_notification_new 			= document.getElementById('send_notification_new');
	const institue_type 					= $('select[name="center_type"]');
	const institue_box 						= $('select[name="center_id"]');
	const student_type 						= $('select[name="student_type"]');
	const student_box 						= $('select[name="student_id"]');
	const staff_type 						= $('select[name="staff_type"]');
	const staff_box 						= $('select[name="staff_id"]');

	if (send_notification_new) {
        // Init form validation rules. For more info check the FormValidation plugin's official documentation:https://formvalidation.io/
        var validatorCenterSelect = FormValidation.formValidation(
            send_notification_new,
            {
                fields: {
                    center_type: {
                        validators:{
                            notEmpty :{
                                message : 'Center Type is required.'
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
                    notify_type: {
                        validators: {
                            notEmpty: {
                                message: 'Notification Type is required'
                            }
                        }
                    },
                    title: {
                        validators: {
                            notEmpty: {
                                message: 'Title is required'
                            }
                        },
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

        var validatorCenterSimple = FormValidation.formValidation(
            send_notification_new,
            {
                fields: {
                    center_type  : {
                        validators:{
                            notEmpty :{
                                message : 'Center Type is required.'
                            }
                        }
                    },
                    notify_type: {
                        validators: {
                            notEmpty: {
                                message: 'Notification Type is required'
                            }
                        }
                    },
                    title: {
                        validators: {
                            notEmpty: {
                                message: 'Title is required'
                            }
                        },
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

        var validatorStudentSelect = FormValidation.formValidation(
            send_notification_new,
            {
                fields: {
                    student_type: {
                        validators:{
                            notEmpty :{
                                message : 'Student Type is required.'
                            }
                        }
                    },
                    student_id: {
                        validators: {
                            notEmpty: {
                                message: 'Student is required'
                            }
                        }
                    },
                    notify_type: {
                        validators: {
                            notEmpty: {
                                message: 'Notification Type is required'
                            }
                        }
                    },
                    title: {
                        validators: {
                            notEmpty: {
                                message: 'Title is required'
                            }
                        },
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

        var validatorStudentSimple = FormValidation.formValidation(
            send_notification_new,
            {
                fields: {
                    student_type: {
                        validators:{
                            notEmpty :{
                                message : 'Student Type is required.'
                            }
                        }
                    },
                    notify_type: {
                        validators: {
                            notEmpty: {
                                message: 'Notification Type is required'
                            }
                        }
                    },
                    title: {
                        validators: {
                            notEmpty: {
                                message: 'Title is required'
                            }
                        },
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

        var validatorStudentCenter = FormValidation.formValidation(
            send_notification_new,
            {
                fields: {
                    student_type: {
                        validators:{
                            notEmpty :{
                                message : 'Student Type is required.'
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
                    notify_type: {
                        validators: {
                            notEmpty: {
                                message: 'Notification Type is required'
                            }
                        }
                    },
                    title: {
                        validators: {
                            notEmpty: {
                                message: 'Title is required'
                            }
                        },
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

        var validatorStudentCourse = FormValidation.formValidation(
            send_notification_new,
            {
                fields: {
                    student_type: {
                        validators:{
                            notEmpty :{
                                message : 'Student Type is required.'
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
                    notify_type: {
                        validators: {
                            notEmpty: {
                                message: 'Notification Type is required'
                            }
                        }
                    },
                    title: {
                        validators: {
                            notEmpty: {
                                message: 'Title is required'
                            }
                        },
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

        var validatorStudentBatch = FormValidation.formValidation(
            send_notification_new,
            {
                fields: {
                    student_type: {
                        validators:{
                            notEmpty :{
                                message : 'Student Type is required.'
                            }
                        }
                    },
                    batch_id: {
                        validators: {
                            notEmpty: {
                                message: 'Batch is required'
                            }
                        }
                    },
                    notify_type: {
                        validators: {
                            notEmpty: {
                                message: 'Notification Type is required'
                            }
                        }
                    },
                    title: {
                        validators: {
                            notEmpty: {
                                message: 'Title is required'
                            }
                        },
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

        var validatorStaffSelect = FormValidation.formValidation(
            send_notification_new,
            {
                fields: {
                    staff_type: {
                        validators:{
                            notEmpty :{
                                message : 'Staff Type is required.'
                            }
                        }
                    },
                    staff_id: {
                        validators: {
                            notEmpty: {
                                message: 'Staff is required'
                            }
                        }
                    },
                    notify_type: {
                        validators: {
                            notEmpty: {
                                message: 'Notification Type is required'
                            }
                        }
                    },
                    title: {
                        validators: {
                            notEmpty: {
                                message: 'Title is required'
                            }
                        },
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

        var validatorStaffSimple = FormValidation.formValidation(
            send_notification_new,
            {
                fields: {
                    staff_type: {
                        validators:{
                            notEmpty :{
                                message : 'Staff Type is required.'
                            }
                        }
                    },
                    notify_type: {
                        validators: {
                            notEmpty: {
                                message: 'Notification Type is required'
                            }
                        }
                    },
                    title: {
                        validators: {
                            notEmpty: {
                                message: 'Title is required'
                            }
                        },
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

        var validatorStaffCenter = FormValidation.formValidation(
            send_notification_new,
            {
                fields: {
                    staff_type: {
                        validators:{
                            notEmpty :{
                                message : 'Staff Type is required.'
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
                    notify_type: {
                        validators: {
                            notEmpty: {
                                message: 'Notification Type is required'
                            }
                        }
                    },
                    title: {
                        validators: {
                            notEmpty: {
                                message: 'Title is required'
                            }
                        },
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

        var validatorStaffRole = FormValidation.formValidation(
            send_notification_new,
            {
                fields: {
                    staff_type: {
                        validators:{
                            notEmpty :{
                                message : 'Staff Type is required.'
                            }
                        }
                    },
                    role: {
                        validators: {
                            notEmpty: {
                                message: 'Role is required'
                            }
                        }
                    },
                    notify_type: {
                        validators: {
                            notEmpty: {
                                message: 'Notification Type is required'
                            }
                        }
                    },
                    title: {
                        validators: {
                            notEmpty: {
                                message: 'Title is required'
                            }
                        },
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

        var validatorStaffCenterRole = FormValidation.formValidation(
            send_notification_new,
            {
                fields: {
                    staff_type: {
                        validators:{
                            notEmpty :{
                                message : 'Staff Type is required.'
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
                    role: {
                        validators: {
                            notEmpty: {
                                message: 'Role is required'
                            }
                        }
                    },
                    notify_type: {
                        validators: {
                            notEmpty: {
                                message: 'Notification Type is required'
                            }
                        }
                    },
                    title: {
                        validators: {
                            notEmpty: {
                                message: 'Title is required'
                            }
                        },
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

        /*send_notification_new.addEventListener('submit', function (e) {
            // Prevent default button action
            e.preventDefault();
            var submitButton = $(this).find('button');
            var validator = '';
            $('.invalid-feedback').html("");
            $('.is-invalid').html("");
            $('.is-valid').html("");

            // Validation logic based on receiver_user value
		    if ($('#receiver_user').val() == 'center') {
		        validator = $('#center_type').val() == 'selected' ? validatorCenterSelect : validatorCenterSimple;
		    } else if ($('#receiver_user').val() == 'student') {
		        switch ($('#student_type').val()) {
		            case 'selected':
		                validator = validatorStudentSelect;
		                break;
		            case 'center':
		                validator = validatorStudentCenter;
		                break;
		            case 'course':
		                validator = validatorStudentCourse;
		                break;
		            case 'batch':
		                validator = validatorStudentBatch;
		                break;
		        }
		    } else if ($('#receiver_user').val() == 'staff') {
		        switch ($('#staff_type').val()) {
		            case 'selected':
		                validator = validatorStaffSelect;
		                break;
		            case 'center':
		                validator = validatorStaffCenter;
		                break;
		            case 'role':
		                validator = validatorStaffRole;
		                break;
		            case 'center_role':
		                validator = validatorStaffCenterRole;
		                break;
		        }
		    }

            // Validate form before submit
            if (validator) {
                // console.log(validator);
                validator.validate().then(function (status) {
                    // console.log(validator);
                    var formData = new FormData(send_notification_new);
                    if (status == 'Valid') {

                        $(submitButton).attr('data-kt-indicator', 'on').prop('disabled', true);
                        axios
                            .post(
                                ajax_url + 'website/send-notification',
                                formData
                            )
                            .then(function (e) {
                                if (e.data.status) {
                                    
                                    mySwal('', 'Notification Sent Successfully.').then((res) => {
		                                if (res.isConfirmed)
		                                        location.reload();
		                                    else
		                                        location.reload();
		                            });
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
        });*/

		$(document).on('submit', '#send_notification_new', function (e) {
		    e.preventDefault();
		    var validator = '';
            $('.invalid-feedback').html("");
            $('.is-invalid').html("");
            $('.is-valid').html("");

            // Validation logic based on receiver_user value
		    if ($('#receiver_user').val() == 'center') {
		        validator = $('#center_type').val() == 'selected' ? validatorCenterSelect : validatorCenterSimple;
		    } else if ($('#receiver_user').val() == 'student') {
		        switch ($('#student_type').val()) {
		            case 'selected':
		                validator = validatorStudentSelect;
		                break;
		            case 'center':
		                validator = validatorStudentCenter;
		                break;
		            case 'course':
		                validator = validatorStudentCourse;
		                break;
		            case 'batch':
		                validator = validatorStudentBatch;
		                break;
		            default:
		                validator = validatorStudentSimple;
		                break;
		        }
		    } else if ($('#receiver_user').val() == 'staff') {
		        switch ($('#staff_type').val()) {
		            case 'selected':
		                validator = validatorStaffSelect;
		                break;
		            case 'center':
		                validator = validatorStaffCenter;
		                break;
		            case 'role':
		                validator = validatorStaffRole;
		                break;
		            case 'center_role':
		                validator = validatorStaffCenterRole;
		                break;
		            default:
		                validator = validatorStaffSimple;
		                break;
		        }
		    }

		    // Validate form before submit
            if (validator) {
                validator.validate().then(function (status) {
                	var formData = new FormData(send_notification_new);
                    if (status == 'Valid') {
                    	$.AryaAjax({
					        data: formData,
					        url: 'website/send-notification',
					        success_message: 'Notification Sent Successfully.',
					        page_reload: true
					    }).then((e) => showResponseError(e));
                    }
                });
            }
		});
    }

	institue_type.on('change', function () {
        var center_type = $(this).val();
        //institue_box.html(emptyOption);
        if(center_type == 'selected'){
    		$("#center_id_div").show();
    	} else {
    		$("#center_id_div").hide();
    	}
    });

	institue_box.select2({
        placeholder: "Select a Center",
        templateSelection: optionFormatSecond,
        templateResult: optionFormatSecond,
    }).on('change', function () {
    });

    student_type.on('change', function () {
        var type = $(this).val();
        $("#studentDiv").hide();
        $("#centerDiv").hide();
        $("#courseDiv").hide();
        $("#batchDiv").hide();
        if(type == 'selected'){
    		$("#studentDiv").show();
    	}
    	if(type == 'center'){
    		$("#centerDiv").show();
    	}
    	if(type == 'course'){
    		$("#courseDiv").show();
    	} 
    	if(type == 'batch'){
    		$("#batchDiv").show();
    	} 
    });

    student_box.select2({
        placeholder: "Select Student",
        templateSelection: optionFormatSecond,
        templateResult: optionFormatSecond,
    }).on('change', function () {
    });

    staff_type.on('change', function () {
        var type = $(this).val();
        $("#staffDiv").hide();
        $("#centerDiv").hide();
        $("#roleDiv").hide();
        if(type == 'selected'){
    		$("#staffDiv").show();
    	}
    	if(type == 'center'){
    		$("#centerDiv").show();
    	}
    	if(type == 'role'){
    		$("#roleDiv").show();
    	} 
    	if(type == 'center_role'){
    		$("#centerDiv").show();
    		$("#roleDiv").show();
    	} 
    });

    staff_box.select2({
        placeholder: "Select Staff",
        templateSelection: optionFormatSecond,
        templateResult: optionFormatSecond,
    }).on('change', function () {
    });
});