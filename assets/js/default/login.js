$(document).ready(function() {

    $('.clsTab').click(function() {
        $('#loginType').val($(this).attr('id'));
        changeHeading($(this).attr('id'));
    });

    changeHeading($('#loginType').val());
});

function changeHeading(activeTab) {
    if (activeTab === 'lnkVendor') {
        $('#lnkCust').parent().removeClass('active');
        $('#lnkPartner').parent().removeClass('active');
        $('#lnkVendor').parent().addClass('active');
        $('.clsHeading').text('Login as Vendor');
    } else if (activeTab === 'lnkCust') {
        $('#lnkVendor').parent().removeClass('active');
        $('#lnkPartner').parent().removeClass('active');
        $('#lnkCust').parent().addClass('active');
        $('.clsHeading').text('Login as Customer');
    } else if (activeTab === 'lnkPartner') {
        $('#lnkVendor').parent().removeClass('active');
        $('#lnkCust').parent().removeClass('active');
        $('#lnkPartner').parent().addClass('active');
        $('.clsHeading').text('Login as Partner');
    }
}