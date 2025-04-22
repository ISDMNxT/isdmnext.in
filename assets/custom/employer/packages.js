document.addEventListener('DOMContentLoaded', function (e) {
    $('.subscribe-btn').click(function() {
        var planName = $(this).data('plan');
        var planId = $(this).data('id');
        $.AryaAjax({
            url: 'employer/subscribe',
            data: { planName, planId }
        }).then((e) => {
            if(e.status)
            $('#subscription-message').html('<div class="alert alert-success">Successfully subscribed to ' + planName + '!</div>');
            else
            $('#subscription-message').html('<div class="alert alert-danger">Subscription failed. Please try again.</div>');
            setTimeout(function() {
                location.href = location.href;
            }, 1000); 
        });
    });
});