document.addEventListener('DOMContentLoaded', function (e) {
	$(document).on('click', '.db_matches', function () {
		var studentIds = $(this).data('id');
		var jobid = $(this).data('jobid');
		render_data(studentIds,jobid);
    });

    function render_data(studentIds,jobid) {
        $.AryaAjax({
            url: 'employer/get_db_matched_students',
            data: { studentIds, jobid }
        }).then((e) => {
            var drawerEl = document.querySelector("#kt_drawer_view_details_box");
            KTDrawer.getInstance(drawerEl, { overlay: true }).hide();
            drawerEl.setAttribute('data-kt-drawer-width', "{default:'300px', 'md': '900px'}");
            var main = mydrawer(`DB Matches Student List`);
            if (e.status) {
                main.find('.card-body').removeClass('d-flex flex-column justify-content-between mt-9 bgi-no-repeat bgi-size-cover bgi-position-x-center pb-0');
            }
            else {
                main.find('.card-body').addClass('d-flex flex-column justify-content-between mt-9 bgi-no-repeat bgi-size-cover bgi-position-x-center pb-0');
            }
            main.find('.card-body').html(e.html);

            main.find('.card-body').find('.send_request').on('click', function (e) {
                e.preventDefault();
                var quesData = ($(this).closest('tr').data('param'));
                SwalWarning('Confirmation!',
                        `Are you sure you want to send Interview Request to <b class="text-success">${quesData.student_name}</b>.`, true, 'Ok, Delete It.').then((e) => {
                            if (e.isConfirmed) {
                                $.AryaAjax({
                                    url: 'employer/send_interview_request',
                                    data: quesData,
                                }).then((res) => {
                                    if (res.status) {
                                        toastr.success('Interview Request Send Successfully..');
                                    }
                                    else
                                        toastr.error('Something went wrong please try again.');
                                });
                                render_data(studentIds,jobid);
                            }
                        });
            });
           
            ki_modal.on('hidden.bs.modal', function () {
                ki_modal.find('form').off('submit');
                ki_modal.find('.modal-footer').find('.add-answer').remove();
                ki_modal.find('.modal-dialog').removeClass('modal-lg');
            })
        });
    }

    $(document).on('click', '.total_applied', function () {
		var studentIds = $(this).data('id');
		var jobid = $(this).data('jobid');
		render_data_new(studentIds,jobid);
    });

    function render_data_new(studentIds,jobid) {
        $.AryaAjax({
            url: 'employer/get_applied_students',
            data: { studentIds, jobid }
        }).then((e) => {
            var drawerEl = document.querySelector("#kt_drawer_view_details_box");
            KTDrawer.getInstance(drawerEl, { overlay: true }).hide();
            drawerEl.setAttribute('data-kt-drawer-width', "{default:'300px', 'md': '900px'}");
            var main = mydrawer(`DB Matches Student List`);
            if (e.status) {
                main.find('.card-body').removeClass('d-flex flex-column justify-content-between mt-9 bgi-no-repeat bgi-size-cover bgi-position-x-center pb-0');
            }
            else {
                main.find('.card-body').addClass('d-flex flex-column justify-content-between mt-9 bgi-no-repeat bgi-size-cover bgi-position-x-center pb-0');
            }
            main.find('.card-body').html(e.html);

            main.find('.card-body').find('.shortlist_request').on('click', function (e) {
                e.preventDefault();
                var quesData = ($(this).closest('tr').data('shortlist'));
                SwalWarning('Confirmation!',
                        `Are you sure you want to shortlist?`, true, 'Yes').then((e) => {
                            if (e.isConfirmed) {
                                $.AryaAjax({
                                    url: 'employer/recive_interview_request',
                                    data: quesData,
                                }).then((res) => {
                                    if (res.status) {
                                        toastr.success('Shortlisted Successfully..');
                                    }
                                    else
                                        toastr.error('Something went wrong please try again.');
                                });
                                render_data_new(studentIds,jobid);
                            }
                        });
            });

            main.find('.card-body').find('.reject_request').on('click', function (e) {
                e.preventDefault();
                var quesData = ($(this).closest('tr').data('reject'));
                SwalWarning('Confirmation!',
                        `Are you sure you want to Reject?`, true, 'Yes').then((e) => {
                            if (e.isConfirmed) {
                                $.AryaAjax({
                                    url: 'employer/recive_interview_request',
                                    data: quesData,
                                }).then((res) => {
                                    if (res.status) {
                                        toastr.success('Rejected Successfully..');
                                    }
                                    else
                                        toastr.error('Something went wrong please try again.');
                                });
                                render_data_new(studentIds,jobid);
                            }
                        });
            });
           
            ki_modal.on('hidden.bs.modal', function () {
                ki_modal.find('form').off('submit');
                ki_modal.find('.modal-footer').find('.add-answer').remove();
                ki_modal.find('.modal-dialog').removeClass('modal-lg');
            })
        });
    }
});