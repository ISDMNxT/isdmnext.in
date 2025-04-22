document.addEventListener('DOMContentLoaded', function (e) {
	$(document).on('click', '.apply-button', function () {
		render_data($(this).data('param'));
    });

    function render_data(params) {
        SwalWarning('Confirmation!', `Are you sure you want to <b">Apply</b> this job.`, true, 'Ok').then((e) => {
            if (e.isConfirmed) {
                $.AryaAjax({
				    url: 'website/received_interview_request',
				    data: params
				}).then((res) => {
				    if (res.status) {
                        toastr.success(`Apply Successfully.`);
                    }
                    else
                        toastr.error('Something went wrong please try again.');

                    setTimeout(() => {
					    location.href = location.href;
					}, 1000); // Runs after 3 seconds
				});
            }
        });
    }
});