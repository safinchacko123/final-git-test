/* 
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */


$(document).ready(function () {

    var project_url = $("#project_url").data('value');

    $('#product-add').submit(function () {
        $('[name^="size[name]"').each(function (index) {
            if ($(this).val() == '') {
                $(this).parent().remove();
            }
        });
        $('[name^="size[sale_price]"]').each(function () {
            if ($(this).val() == '') {
                $(this).remove();
            }
        });
    });

    $('input:radio[name="price_option"]').each(function () {
        if ($(this).is(':checked')) {
            $('#price_options_container > div').hide();
            $('#' + $(this).data('id')).show();
        }
    });
    $('input:radio[name="price_option"]').change(function () {
        if ($(this).is(':checked')) {
            $('#price_options_container > div').hide();
            $('#' + $(this).data('id')).show();
        }
    });

    $('#add-sizes').click(function () {
        $('.sizes_container').append('<div class="size_container margin-10"><input type="text" class="textbox inline width-45 textbox-s" name="size[name][]" placeholder="Size / Slice Name"><input type="text" class="textbox inline width-46 textbox-s" name="size[stock][]" placeholder="Max. Items allowed" style=""><br /><input type="text" class="textbox inline width-20 textbox-s" name="size[price][]" placeholder="Price"><input type="text" class="textbox inline width-20 textbox-s" name="size[sale_price][]" placeholder="Sale Price"><a class="btn textbox inline remove-sizes remove-margin"><i class="icon-minus-sign"> </i> Remove</a></div>');
    });


    //Load partnered vendors of partners
    $('table.partners .partneredvendors').click(function () {
        $("#partnered-vendors").html("").load(project_url + 'admin/partners/get_partner_vendors/' + $(this).data('pid'))
                .dialog({
                    modal: true,
                    height: $(window).height() * 80 / 100,
                    width: $(window).width() * 80 / 100,
                    title: "Partnered Vendors",
                }).dialog('open');
        return false;
    });

    $('body').on('click', '#partnered-vendors .actions-container .icon-pencil, .actions-container .icon-remove', function () {
        $(this).parent().parent().find('input, span.share-value, span span').toggle();
    });
    $('body').on('click', '#partnered-vendors .actions-container .icon-ok', function () {
        var share_percentage = $("input[name=share_percentage]").val();
        var curr_element = $(this);
        $.ajax({
            type: 'POST',
            cache: false,
            url: project_url + 'admin/partners/update_share',
            data: {id: curr_element.data('id'), share: share_percentage},
            success: function (data) {
                if (data == 'success') {
                    $(".share-value").text(share_percentage);
                    curr_element.parent().parent().find('input, span.share-value, span span').toggle();
                } else {
                    $('<div></div>').text(data).dialog({
                        title: "Error",
                        modal: true
                    });
                }
            }
        });
    });

    tinymce.init({
        selector: 'textarea#ingredients',
        height: 100,
        elementpath: false,
        menubar: false
    });
    tinymce.init({
        selector: 'textarea#directions',
        height: 200,
        elementpath: false,
        menubar: false
    });
    $('body').on('click', '.remove-sizes', function (e) {
        e.preventDefault();
        var container_length = $('.size_container').length;
        if (container_length > 1) {
            $(this).closest('.size_container').remove();
        } else {
            $('<div></div>').text('Atleast one size/slice should be entered').dialog({
                title: "Error",
                modal: true
            });
        }
    });
    
    
    
        //By Lynn
        
    $( "#releasedPayment-dialog-form" ).dialog({
		autoOpen: false,      
		modal: true,
		show: {
			effect: "blind",
			duration: 1000
		},
		
    });        

        
    $('#btn-orderReleasePayment').click(function () {
		$("#release_amount").val('');
		$("#bank_detail").val('');
		$( "#releasedPayment-dialog-form" ).dialog( "open" );
    });
    
    $('#btn-releasePayment-request').click(function () {
		var project_url = $("#order-project_url").data('value');
		var partnerId = $("#btn-orderReleasePayment").data('partnerid');
		
		if($("#release_amount").val()=='')
		{
			$("#release_amount").focus();
		}
		else if($("#bank_detail").val()=='')
		{
			$("#bank_detail").focus();
		}
		else
		{
			
			$("#releasedPaymentPopup-loader").show();
			$.ajax({
				type: 'POST',
				cache: false,
				url: project_url + 'admin/orders/request_releasePayment',
				data: {partner_id: partnerId, amount: $("#release_amount").val(), bank_detail:$("#bank_detail").val()},
				success: function (data) {
						
					
					$("#releasedPaymentPopup-loader").hide();
					
					
					$( "#releasedPayment-dialog-form form" ).html('Your request has been sent to admin');
					 setTimeout(function(){ $( "#releasedPayment-dialog-form" ).dialog( "close" ); }, 3000);
					
				}
			});
			
		}
		
	});
    
    
    $('#order_status').change(function () {
		var order_status = this.value;
		if(order_status == 'order_disputed' && $("#order-login-status").val()=="Venture")
		{
			$(".order-cancellation-box").fadeIn();
			$("#cancellation_contant").val('');
			
		}
		else
		{
			$(".order-cancellation-box").fadeOut();
		}
	});

});

function validate_orderUpdate_form()
{
	if($("#order-login-status").val()=="Venture")
	{
		var order_status = $("#order_status").val();
		var cancellation_contant = $("#cancellation_contant").val().trim();
		if(order_status == 'order_disputed' && cancellation_contant=='')
		{
			$("#cancellation_contant").focus();
			return false;
		}
	}
}
