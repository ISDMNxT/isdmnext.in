document.addEventListener('DOMContentLoaded', function (e) {
	$(document).on('click', '.apply-button', function () {
		if($(this).data('type') == 'Accept'){
			render_data($(this).data('param'),$(this).data('type'));
		}
		
    });

    $(document).on('click', '.reject-button', function () {
    	if($(this).data('type') == 'Reject'){
		render_data($(this).data('param'),$(this).data('type'));
		}
    });

    function render_data(params,type) {
        SwalWarning('Confirmation!', `Are you sure you want to <b">${type}</b> this request.`, true, 'Ok').then((e) => {
            if (e.isConfirmed) {
                $.AryaAjax({
				    url: 'website/send_interview_request',
				    data: params
				}).then((res) => {
				    if (res.status) {
                        toastr.success(`Request ${type} Successfully.`);
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