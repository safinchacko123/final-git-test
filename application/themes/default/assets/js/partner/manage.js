
$(document).ready(function () {
    $('.becomePartner').click(function () {
        var clickedBtn = $(this);
        var fdata = {
            vendor_id: $(this).attr('rel')
        }
        $.ajax({
            url: site_url + '/ajax/become_partner',
            type: 'POST',
            data: fdata,
            dataType: 'JSON',
            async: false,
            error: function () {
                //alert('Server error please try after some time')
            },
            success: function (resp) {
                if (resp.status === 'error') {
                    maketoast('info', 'Error', resp.info);
                } else {
                    maketoast('success', 'Success', 'Request forwarded to Admin');
                    $(clickedBtn).parent().html('<span style="padding: 4px" class="alert alert-info">Request is under consideration</span>');
                }
            }
        });
    })
})