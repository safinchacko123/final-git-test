$(document).ready(function() {

    $('.clsTab').click(function() {
        $('#registerType').val($(this).attr('id'));
        changeHeading($(this).attr('id'));
    });

    changeHeading($('#registerType').val());
    
});

function changeHeading(activeTab) {
    if (activeTab === 'lnkVendor') {
        $('#lnkCust').parent().removeClass('active');
        $('#lnkPartner').parent().removeClass('active');
        $('#lnkVendor').parent().addClass('active');
		$('.company').show();
        $('.pNo').show();
        $('.docs').hide();
        $('.clsHeading').text('Register as Vendor');
        $('#reg-tc').attr('href', $('#site_url').val()+'/terms-vendor');
    } else if (activeTab === 'lnkCust') {
        $('#lnkVendor').parent().removeClass('active');
        $('#lnkPartner').parent().removeClass('active');
        $('#lnkCust').parent().addClass('active');
		$('.company').hide();
        $('.pNo').hide();
        $('.docs').hide();
        $('.clsHeading').text('Register as Customer');
        $('#reg-tc').attr('href', $('#site_url').val()+'/terms');
    } else if (activeTab === 'lnkPartner') {
        $('#lnkVendor').parent().removeClass('active');
        $('#lnkCust').parent().removeClass('active');
        $('#lnkPartner').parent().addClass('active');
        $('.company').show();
        $('.pNo').hide();
        $('.docs').show();
        $('.clsHeading').text('Register as Partner');
        $('#reg-tc').attr('href', $('#site_url').val()+'/terms-partner');
    }
}