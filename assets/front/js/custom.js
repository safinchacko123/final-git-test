jQuery('.my-basket').scrollToFixed(); 
jQuery('.header').scrollToFixed(); 
//jQuery('.filter-panel').scrollToFixed(); 

jQuery(window).resize(function(){
	height1();
});

jQuery(window).load(function() {

	jQuery("#mainLoadingdiv").fadeOut();
		
	if (typeof checkoutAdressPopup !== "undefined") {
		jQuery("#modal-checkoutAddress").modal('show');
	}
	

	jQuery("#flexisel").show();
	
	jQuery('.faqwrp dt').click(function(){
		jQuery(this).parent('.faqwrp').find('dt, dd').removeClass('active');
		jQuery(this).next('dd').addClass('active');
		jQuery(this).addClass('active');
	});
	
	
	jQuery("#pagination > a").each(function() {
		var g = window.location.href.slice(window.location.href.indexOf('?'));
		var href = jQuery(this).attr('href');
		jQuery(this).attr('href', href+g);
	});
	
	
	/* Area controller */
	if(currentClass=='index' && currentMethod =='area')
	{		
		
		var minOrderAmount =  1 *parseInt(jQuery("#minOrderAmount").val());
		var maxOrderAmount =  1 *parseInt(jQuery("#maxOrderAmount").val());
		
		/* Amount range */
		
		var tooltipRange = jQuery('<div id="tooltip" class="arrow-bg">'+maxOrderAmount+'</div>').css({position: 'absolute',top: -30,left: -7});
		jQuery( "#slider-range-min" ).slider({
			range: "min",
			value: maxOrderAmount,
			min: minOrderAmount,
			max: maxOrderAmount,
			slide: function( event, ui ) {
			jQuery( "#amount" ).val( "$" + ui.value );
			 tooltipRange.text(ui.value);
			},
			change: function( event, ui ) {
				tringerOn_listing_leftSearchBar();
			}
		}).find(".ui-slider-handle").append(tooltipRange).hover(function() {
			tooltipRange.show()
		});
		jQuery( "#amount" ).val( "$0"); 
		//jQuery( "#amount" ).val( "$" + jQuery( "#slider-range-min" ).slider( "value" ) );
		
		callbackAvgTimeSlider('init');
		/* ratting range */
		jQuery( "#ratingSlider" ).slider({
			min: 0,
			max: 5,
			range: "min",
			value: 0,
			slide: function( event, ui ) {
				//select[ 0 ].selectedIndex = ui.value - 1;
			},
			change: function( event, ui ) {
				if(ui.value==0)
					jQuery("#selected_rating").val('');
				else					
					jQuery("#selected_rating").val(ui.value);					
					
				tringerOn_listing_leftSearchBar();
				
			}
		}).find(".ui-slider-handle").append(tooltip).hover(function() {
			tooltip.show()
		});	
	}	
});

jQuery(document).ready(function(){
	/* common controller */
	height1();
	jQuery('.numbersOnly').keyup(function () { 
		this.value = this.value.replace(/[^0-9\.]/g,'');
	});		

	//jQuery( document ).tooltip();
	
	jcf.replaceAll();
	
	jQuery("#flexisel").hide();
	
	/* Footer controller */		
	jQuery("#flexisel").flexisel({
        visibleItems: 7,
        animationSpeed: 1000,
        autoPlay: true,
		clone:false,
        autoPlaySpeed: 3000,            
        pauseOnHover: true,
        enableResponsiveBreakpoints: true,
        responsiveBreakpoints: { 
            portrait: { 
                changePoint:480,
                visibleItems: 1
            }, 
            landscape: { 
                changePoint:640,
                visibleItems: 3
            },
            tablet: { 
                changePoint:768,
                visibleItems: 5
            }
        }
    });  	
		

	
	/* Seller controller */
	jQuery("#cityName").geocomplete();
	/* location controller */		
	if(currentClass=='index' && currentMethod =='location')
	{	
		jQuery(".city").geocomplete({
		  
		  country: iso_code_2,
		   types:["geocode", "establishment"]
		   //types:["(cities)","geocode"]
		 
		}).bind("geocode:result", function (event, result) {
			//console.log(result);
			//$.log("Result: " + result.formatted_address);
			//console.log("Result: " + result.formatted_address);
			jQuery("#location-hid-locationName").val(result.formatted_address);
		})
		.bind("geocode:error", function (event, status) {
			//console.log("ERROR: " + status);
		})
		.bind("geocode:multiple", function (event, results) {
		   //console.log("Multiple: " + results.length + " results found");
		}).bind("geocode:result", function (event, result) {
									
				//console.log(result.geometry.location.lat());
				jQuery("#location-hid-lat").val(result.geometry.location.lat());
				//console.log(result.geometry.location.lng());
				jQuery("#location-hid-lng").val(result.geometry.location.lng());
				//console.log(result);
		});
	}
	
	/* myAccount controller */
	jQuery( "#myAccountTabs" ).tabs().addClass( "ui-tabs-vertical ui-helper-clearfix" );
    jQuery( "#myAccountTabs li" ).removeClass( "ui-corner-top" ).addClass( "ui-corner-left" );
    
    if(typeof orderTab !== 'undefined' && currentMethod =='myAccount')
	{
		
			jQuery(".myAccount-menu li a").removeClass('active');
			jQuery("#ma-orderTab-link").addClass('active');
			jQuery(".myAccount-tab").hide();
			jQuery("#tab-2").show();	
		
	}
    
   /* Detail controller */
	jQuery('#horizontalTab').responsiveTabs({
		active: 0,
		startCollapsed: 'accordion',
		collapsible: 'accordion',
		rotate: false,
		setHash: true
	});
	
	//~ jQuery( ".imageEnlargeModel" ).dialog({
	//~ autoOpen: false,
	//~ show: {
		//~ effect: "blind",
		//~ duration: 1000
	//~ },
	//~ hide: {
		//~ effect: "explode",
		//~ duration: 1000
	//~ }
	//~ });	
	
    
	jQuery('.rating').on('rating.change', function (event, value, caption) {
		var rate_id = jQuery(this).prop('id');
		var pure_id = rate_id.substring(6);
		jQuery.post(BASE_URL+'frontAjax/ajax_saveProductRating', {score: value, pid: pure_id},
			function (data) {
				jQuery('#' + rate_id).rating('refresh', {
					showClear: false,
					showCaption: false,
					disabled: true
				});
				jQuery.notify("Your rating saved successfully", "success");	
			});
		//alert(value);
		//console.log(pure_id);
	});
	
	
	
    
});
	
/* Seller page Map */
if(currentClass=='index' && currentMethod =='seller')
{
	/* Map on page load */
	var centerLat = sellerMap_lat;
	var centerLng = sellerMap_lng;
	function initialize() {
		//alert(centerLat+" "+centerLng);
		var mapProp = {
			center:new google.maps.LatLng(centerLat,centerLng),
			zoom:14,
			mapTypeId:google.maps.MapTypeId.ROADMAP
		};
		var map=new google.maps.Map(document.getElementById("dvMap"),mapProp);
	}
	google.maps.event.addDomListener(window, 'load', initialize);
	/* Map on page load */
	    //~ $(function(){
		//~ var options = {
        //~ map: ".map",
        //~ details: "form ",
        //~ markerOptions: {
        //~ draggable: true
        //~ }
        //~ };
        //~ 
        //~ $(".cityMap").geocomplete(options);
		//~ 
		//~ });
		/* Area textbox autocomplete */
		jQuery(".cityMap").geocomplete({
			//types: ['(cities)']
			country: iso_code_2,
			types:["geocode", "establishment"]
		}).bind("geocode:result", function (event, result) {
			//console.log(result);
			//$.log("Result: " + result.formatted_address);
			//console.log("Result: " + result.formatted_address);
			jQuery("#location-hid-locationName").val(result.formatted_address);
		})
		.bind("geocode:error", function (event, status) {
			//console.log("ERROR: " + status);
		})
		.bind("geocode:multiple", function (event, results) {
			//console.log("Multiple: " + results.length + " results found");
		})
		.bind("geocode:result", function (event, result) {						
			//console.log(result.geometry.location.lat());
			jQuery("#location-hid-lat").val(result.geometry.location.lat());
			//console.log(result.geometry.location.lng());
			jQuery("#location-hid-lng").val(result.geometry.location.lng());
			//console.log(result);
		});
 
}

/* Location controller */
jQuery(document).on('keyup','#location-zipcode',function(){
	jQuery("#location-city").val('');
});
jQuery(document).on('keyup','#location-city',function(){
	jQuery("#location-zipcode").val('');	
});
/* Seller controller */
jQuery(document).on('change','#seller-areaName',function(){
	var lat = jQuery(this).find(':selected').attr('data-lat');
	var lng = jQuery(this).find(':selected').attr('data-lng');
	var text = jQuery(this).find("option:selected").text();
	jQuery("#seller-hid-lat").val(lat);
	jQuery("#seller-hid-lng").val(lng);
	jQuery("#seller-hid-locationName").val(text);
	
});

var delay = (function(){
  var timer = 0;
  return function(callback, ms){
    clearTimeout (timer);
    timer = setTimeout(callback, ms);
  };
})();
/* Area controller */
jQuery(document).on('keyup','#form-restaurant-listing-searchBox input[type=text]',function(changeEvent){	
	//jQuery(".ventureLisingLoader").show();
	delay(function(){
	tringerOn_listing_leftSearchBar();
	}, 500 );
});
jQuery(document).on('change','#form-restaurant-listing-searchBox input[type=checkbox]',function(changeEvent){	
	//console.log(changeEvent);
	tringerOn_listing_leftSearchBar();
	
});
jQuery(document).on('change','#form-restaurant-listing-searchBox select',function(changeEvent){	
	//console.log(changeEvent);
	tringerOn_listing_leftSearchBar();
	
});
jQuery(document).on('hover focus click','.dropdown-menu',function(){
	jQuery(".cart-dropdown").show();
});
jQuery(document).on('click','#seeAllCuisinesLink',function(){
	
	jQuery("#seeAllCuisinesLink").hide();
	jQuery(".allCuisine").show();
});

//~ jQuery(document).on('mouseover','.white-wrapper',function(){
	//~ jQuery('.filter a').focus();
//~ });

/* Detail controller */
jQuery(document).on('keyup','.noZeronumbersOnly',function(){
		
		var splitVal =  jQuery(this).val().split(".");
		//console.log(splitVal.length);
		if (jQuery(this).val() > 100)
		{
			jQuery(this).val('100');
		}
		else if(splitVal.length > 1)
		{
			jQuery(this).val(splitVal[0]);
		}		
		else
		{
			this.value = this.value.replace(/[^0-9\.]/g,'');
		}
});	

jQuery(document).on('click','.productList-plusBtn',function(){
	//~ var index = this.id.split('-');
	//~ index = index[2];
	//~ var increamentValue = parseInt(jQuery("#productList-qty-"+index).val())+1 ;
	//~ 
	//~ 
	//~ jQuery("#productList-qty-"+index).val(increamentValue);
	//~ var value = parseInt(increamentValue);
	//~ alert(value +' '+ index);
	//~ jQuery("#popupProduct-qty-"+index).val(value);
});
jQuery(document).on('click','.cart-addToBasketBtm',function(e){

	var index = jQuery(this).attr('data-id');
	var product_price =  jQuery("#popupProduct-qty-"+index).val();
	//alert(product_price);
	if(product_price=='')
	{
		jQuery("#popupProduct-qty-"+index).focus();
		e.preventDefault();
	}
	else if(product_price=='0')
	{
		jQuery("#addOnPopUPError-"+index).show(function(){
			jQuery("#addOnPopUPError-"+index).html('Quantity must be greater than 0');
			setTimeout(function(){ jQuery("#addOnPopUPError-"+index).fadeOut('slow'); }, 3000);
		});
	}
	else
	{
		
		
		var formData  = jQuery("#popupProductModel-form-"+index).serializeArray();
		var extendedUrl="frontAjax/ajax_add_to_cart";
		var base_url = (BASE_URL) ? BASE_URL : '';	
		var response = callAjax(formData, extendedUrl, base_url) ;
		jQuery(".productPage-cartSection").html('<div class="cart-loader" ><img src="'+BASE_URL+'assets/front/images/cartLoader.gif" /></div>');
		response.success(function (data) {
				var obj=JSON.parse(data); 
				jQuery(".productPage-cartSection").html(obj.cartBoxHtml);
				jQuery('#myModal-'+index).modal('hide');
		});	
		
		/* Fly to cart code start */
		if(currentClass=='search')
		{
			var cart = jQuery('#dLabel');
			var imgtodrag = jQuery(this).parent('.item').find("img").eq(0);
			if (imgtodrag) {
					//jQuery(".cart-dropdown").show();
				var imgclone = imgtodrag.clone()
					.offset({
					top: imgtodrag.offset().top,
					left: imgtodrag.offset().left
				})
					.css({
					'opacity': '0.5',
						'position': 'absolute',
						'height': '150px',
						'width': '150px',
						'z-index': '999999'
				})
					.appendTo(jQuery('body'))
					.animate({
					'top': cart.offset().top + 10,
						'left': cart.offset().left + 10,
						'width': 75,
						'height': 75
				}, 1000, 'easeInOutExpo');
				
				setTimeout(function () {
					
					cart.effect("shake", {
						times: 2
					}, 200);
					// Update icon count
					var topCartIconCount =  jQuery("#topCartIconCount").html();
					topCartIconCount =  parseInt(topCartIconCount)+1;
					jQuery("#topCartIconCount").html(topCartIconCount);                
					
				}, 1500);
				
				
				imgclone.animate({
					'width': 0,
						'height': 0
				}, function () {
					jQuery(this).detach()
				});
				
						
				
			}
        }
		/* Fly to cart code end */
	}
});



jQuery(document).on('click','.btn-topCartIcon',function(){
	
			var topCartIconCount =  jQuery("#topCartIconCount").html();
	var formData  = {'topCartIconCount':topCartIconCount} ;
	var extendedUrl="frontAjax/ajax_topCartBox";
	var base_url = (BASE_URL) ? BASE_URL : '';	
	var response = callAjax(formData, extendedUrl, base_url) ;
	//jQuery(".productPage-cartSection").html('<div class="cart-loader" ><img src="'+BASE_URL+'assets/front/images/cartLoader.gif" /></div>');
	response.success(function (data) {
			var obj=JSON.parse(data); 
			jQuery(".area_cartBox").html(obj.cartBoxHtml);
			//jQuery("#dLabel").model('show');
	});	
	
});

jQuery(document).on('click','.adon-checkbox',function(){
	
		var i = 0;
		var index = jQuery(this).attr('data-index');
		var adonName = jQuery(this).attr('data-adonName');
		var adonCount = jQuery(this).attr('data-adonCount');
		var addonIndex = jQuery(this).attr('data-addonIndex');
		//alert(addonIndex);
		//alert(index+' '+adonName+' '+adonCount);
		//alert(jQuery('.adon-checkbox.'+adonName+':checked').length);
		
		if (jQuery('.adon-checkbox.'+adonName+':checked').length <= adonCount) {
			
		}
		else
		{		
			
			//jQuery.notify('You can select maximum '+adonCount+' item', "info");
			jQuery("#addOnPopUPError-"+index).show(function(){
					jQuery("#addOnPopUPError-"+index).html('You can select maximum '+adonCount+' item');
					setTimeout(function(){ jQuery("#addOnPopUPError-"+index).fadeOut('slow'); }, 3000);
			});
			//jQuery(this).prop( "checked", false );
			jQuery('.adon-checkbox:checked').each(function(data,item){
				var eachItemIndex = jQuery(item).attr('data-addonIndex');
				if(addonIndex != eachItemIndex)
				{
					
					jQuery(item).prop( "checked", false );
					return false
				}
			});
			
		}
		
		
		var ids = [];
			jQuery('.adon-checkbox:checked').each(function(){
			// var str = jQuery(this).prop('id');
			var val = jQuery(this).val();
			//ids[i] = str.substring(str.lastIndexOf('-')+1);
			ids[i] = val;
			i++;
		});
		
		var sum = ids.reduce(function(a, b) { return Number(a) + Number(b); }, 0);
		var oldValue = jQuery("#product_main_price-"+index).val();
		var newValue = Number(sum) + Number(oldValue);
			
		var price = myCurrency+''+newValue.toFixed(2);
			
		jQuery("#productPriceLabel-"+index).html(price);
		jQuery("#product_price-"+index).val(newValue); 
		/*validation */
		//alert(jQuery('.adon-checkbox.'+adonName+':checked').length);
	
    
});

jQuery(document).on('click','.openAdonPupupBtn',function(){	 
	
	var index = jQuery(this).attr('data-index');
	
	var productList_qty = jQuery("#productList-qty-"+index).val(); 
	//alert(productList_qty);
	
	jQuery("#popupProductModel-form-"+index)[0].reset();
	jQuery("#product_price-1-"+index).val(jQuery("#product_main_price-"+index).val()*productList_qty);
	var price = jQuery("#product_main_price-"+index).val()*productList_qty;
	var price1 = myCurrency+''+price;
	jQuery("#productPriceLabel-"+index).html(price1);
	jQuery("#popupProduct-qty-"+index).val(productList_qty);
});

jQuery(document).on('click','.clear-cart-basket',function(){	 
		
	var formData  = {type:'clear'};
	 
	var extendedUrl="frontAjax/ajax_clear_cart_item";
	var base_url = (BASE_URL) ? BASE_URL : '';	
	var response = callAjax(formData, extendedUrl, base_url) ;
	jQuery(".productPage-cartSection").html('<div class="cart-loader" ><img src="'+BASE_URL+'assets/front/images/cartLoader.gif" /></div>');
	response.success(function (data) {
			var obj=JSON.parse(data); 
			jQuery(".productPage-cartSection").html(obj.cartBoxHtml);
			jQuery('#myModal-'+index).modal('hide');
    });		
});

jQuery(document).on('keyup','.productPage-cartSection .cart-qty',function(){
	//var numbers = /^[0-9]+$/;  /* For mobile number */
	if(this.value != '') {	
	
		var index = this.id;
		//console.log(index);
		index =  index.split('-');
		var rowId = index[2];
		var formData  = {rowid:rowId,qty:this.value};
		delay(function(){	 
			var extendedUrl="frontAjax/ajax_update_cart_item";
			var base_url = (BASE_URL) ? BASE_URL : '';	
			var response = callAjax(formData, extendedUrl, base_url) ;
			jQuery(".productPage-cartSection").html('<div class="cart-loader" ><img src="'+BASE_URL+'assets/front/images/cartLoader.gif" /></div>');
			response.success(function (data) {
					var obj=JSON.parse(data); 
					jQuery(".productPage-cartSection").html(obj.cartBoxHtml);
					jQuery('#myModal-'+index).modal('hide');
			});
		}, 1000 );
	}
});

jQuery(document).on('click','.productPage-cartSection .cart-deleteItem',function(){
	var index = this.id;
	index =  index.split('-');
	var rowId = index[2];
	var formData  = {rowid:rowId};
	
	var extendedUrl="frontAjax/ajax_delete_cart_item";
	var base_url = (BASE_URL) ? BASE_URL : '';	
	var response = callAjax(formData, extendedUrl, base_url) ;
	jQuery(".productPage-cartSection").html('<div class="cart-loader" ><img src="'+BASE_URL+'assets/front/images/cartLoader.gif" /></div>');

	response.success(function (data) {
            var obj=JSON.parse(data); 
			jQuery(".productPage-cartSection").html(obj.cartBoxHtml);
			jQuery('#myModal-'+index).modal('hide');
    });
});

jQuery(document).on('click','.product-catType li',function(){
	jQuery('.product-catType li').removeClass('active');
	jQuery(this).addClass('active');
	var type = jQuery(this).attr('data-type');
	var name = jQuery(this).html();
	var venture_id = jQuery("#productPage_venture_id").val();
	tab_product_listing(venture_id,type,name);	
});

jQuery(document).on('mouseover','.imageEnlargeModelLink',function(){
	jQuery( ".imageEnlargeModel" ).show();
});

jQuery(document).on('mouseout','.imageEnlargeModelLink',function(){
	jQuery( ".imageEnlargeModel" ).hide();
});

/* Checkout controller */
jQuery(document).on('click','#btn-saveNotes',function(){
	
	if(jQuery('#text-note').val() != '')
	{
		var formData  = {note:jQuery('#text-note').val()};
		jQuery("#email-loader").show();
		var extendedUrl="frontAjax/ajax_insertNewNote";
		var base_url = (BASE_URL) ? BASE_URL : '';	
		var response = callAjax(formData, extendedUrl, base_url) ;
		response.success(function (data) {
			var obj=JSON.parse(data);
			var inserted_id = obj.id;
			var _option = '<option value="'+inserted_id+'">'+jQuery('#text-note').val()+'</option>';
			jQuery('#note-list').append(_option);
			  
			//jQuery('#text-note').val('');
			jQuery("#email-loader").hide();
			jQuery.notify("Your note saved successfully", "success");
			
		});			
	}
	else
	{
		jQuery('#text-note').focus()
	}
});

jQuery(document).on('change','#note-list',function(){
	if(this.value !='')
	{
		var thisvalue = jQuery(this).find("option:selected").text();
		jQuery('#text-note').val(thisvalue);
	}
	else
	{
		jQuery('#text-note').val('');
	}
});
/* Register controller */
jQuery(document).on('keyup','#form-register #email',function(){
	
	var formData  = {email:this.value};
	var email = this.value;
	var re = /^(([^<>()[\]\\.,;:\s@\"]+(\.[^<>()[\]\\.,;:\s@\"]+)*)|(\".+\"))@((\[[0-9]{1,3}\.[0-9]{1,3}\.[0-9]{1,3}\.[0-9]{1,3}\])|(([a-zA-Z\-0-9]+\.)+[a-zA-Z]{2,}))$/;
    var chk_val = re.test(email);
    if(chk_val==true)
    {
		var extendedUrl="index/ajax_checkValideEmail";
		var base_url = (BASE_URL) ? BASE_URL : '';	
		var response = callAjax(formData, extendedUrl, base_url) ;
		jQuery("#email-loader").show();

		response.success(function (data) {
			var obj=JSON.parse(data); 
			if(obj.emailValidStatus=='N')
			{
				jQuery("#errorMsg").html('Email address already exist');
				jQuery("#errorMsg").show(function(){	setTimeout(function(){ jQuery("#errorMsg").fadeOut('slow'); }, 3000); 	});
				jQuery("#validEmailStatus").val(0);
			}
			else
			{
				jQuery("#validEmailStatus").val(1);
			}
			jQuery("#email-loader").hide();
			//jQuery('#myModal-'+index).modal('hide');
		});
	}
});
/* Forgot controller */
jQuery(document).on('keyup','#form-forgot #email',function(){
	
	jQuery('#forgotSubmitBtn').attr('disabled',true);
	jQuery('#forgotSubmitBtn').addClass('disabled');
	var formData  = {email:this.value};
	var email = this.value;
	if(email=='')
	{
		jQuery('#forgotSubmitBtn').attr('disabled',false);
		jQuery('#forgotSubmitBtn').removeClass('disabled');
	}

	var re = /^(([^<>()[\]\\.,;:\s@\"]+(\.[^<>()[\]\\.,;:\s@\"]+)*)|(\".+\"))@((\[[0-9]{1,3}\.[0-9]{1,3}\.[0-9]{1,3}\.[0-9]{1,3}\])|(([a-zA-Z\-0-9]+\.)+[a-zA-Z]{2,}))$/;
    var chk_val = re.test(email);
    if(chk_val==true)
    {
		var extendedUrl="index/ajax_checkValideEmail";
		var base_url = (BASE_URL) ? BASE_URL : '';	
		var response = callAjax(formData, extendedUrl, base_url) ;
		jQuery("#email-loader").show();

		response.success(function (data) {
			var obj=JSON.parse(data); 
			if(obj.emailValidStatus=='Y')
			{
				jQuery("#errorMsg").html('Email address does not exist');
				jQuery("#errorMsg").show(function(){	setTimeout(function(){ jQuery("#errorMsg").fadeOut('slow'); }, 3000); 	});
				jQuery('#forgotSubmitBtn').attr('disabled',true);
				jQuery('#forgotSubmitBtn').addClass('disabled');
			}
			else
			{
				jQuery("#errorMsg").hide();
				jQuery('#forgotSubmitBtn').attr('disabled',false);
				jQuery('#forgotSubmitBtn').removeClass('disabled');
			}
			jQuery("#email-loader").hide();
			//jQuery('#myModal-'+index).modal('hide');
		});
	}
	
});

/* myAccount */
jQuery(document).on('click','.myAccount-menu li',function(){ 
	var target = jQuery(this).find('a').attr('data-target');
	jQuery(".myAccount-menu li a").removeClass('active');
	jQuery(this).find('a').addClass('active');	
	jQuery(".myAccount-tab").hide();
	jQuery("#"+target).show();	
});

jQuery(document).on('click','.btnchange',function(){ 
	var target = jQuery(this).attr('data-target');	
	jQuery("."+target+"-Info").hide();
	jQuery("."+target+"-Edit").show();
	
	jQuery("#"+target+"-changeBtn").hide();
});

jQuery(document).on('click','.btnaddnew',function(){ 
	var target = jQuery(this).attr('data-target');	
	jQuery("."+target).show();
	//jQuery("."+target).hide();
	//jQuery("#"+target+"-changeBtn").show();
});

jQuery(document).on('click','.btnCancel',function(){ 
	var target = jQuery(this).attr('data-target');	
	jQuery("."+target+"-Info").show();
	jQuery("."+target+"-Edit").hide();
	
	jQuery("#"+target+"-changeBtn").show();
});

jQuery(document).on('click','#account-save',function(){
	var myData ={};
	//~ if(jQuery("#password").val().trim() != '')
	//~ {

	//~ }
	if(jQuery('#emailnotification').is(":checked"))
	{
		myData['emailnotification'] = '1';
		jQuery("#label-emailnotification").html('Yes');
	}
	else
	{
		myData['emailnotification'] = '0';
		jQuery("#label-emailnotification").html('No');
	}
	
	if(jQuery("#password").val().trim()=='')
	{
		jQuery.notify("Please enter password", "info");
	}
	else if(jQuery("#confirm_password").val().trim()=='')
	{
		jQuery.notify("Please enter confirm password", "info");
	}	
	else if(jQuery("#password").val().trim() != jQuery("#confirm_password").val().trim()) {
		
		jQuery.notify("Confirm password does not match", "info");
	}
	else
	{
		myData['password'] = jQuery("#password").val();
	
		
		var myJsonString =JSON.parse(JSON.stringify(myData));
		//console.log(myJsonString);
		var extendedUrl="frontAjax/ajax_accountUpdate";
		var base_url = (BASE_URL) ? BASE_URL : '';	
		var response = callAjax(myJsonString, extendedUrl, base_url);
		response.success(function (data) {
			var obj=JSON.parse(data); 
			if(obj.updateStatus=='Y')
			{
				if(jQuery('#emailnotification').is(":checked"))
				{
					jQuery(".label-emailnotification").html('Yes');
					jQuery("#emailnotification").prop( "checked", true );
				}
				else	
				{
					jQuery(".label-emailnotification").html('No');
					jQuery("#emailnotification").prop( "checked", false );
				}			
				jQuery.notify("Changes done", "success");				
				jQuery(".account-Info").show();
				jQuery(".account-Edit").hide();	
				jQuery("#account-changeBtn").show();			
			}
			else
			{
				jQuery.notify("No changes applied", "info");
				//~ jQuery(".account-Info").show();
				//~ jQuery(".account-Edit").hide();	
				//~ jQuery("#account-changeBtn").show();			
			}
		});
	}
});

jQuery(document).on('click','#personalInfo-save',function(){
	var formData  = jQuery("#form-personalInfo").serializeArray();
	var extendedUrl="frontAjax/ajax_personalInfoUpdate";
	var base_url = (BASE_URL) ? BASE_URL : '';	
	var response = callAjax(formData, extendedUrl, base_url);
	
	var	dataObj = {};
	jQuery(formData).each(function(i, field){
	  dataObj[field.name] = field.value;
	});
	
	if(dataObj.firstname=="")
	{
		jQuery.notify("Please enter firstname", "info");
	}
	else if(dataObj.lastname=="")
	{
		jQuery.notify("Please enter lastname", "info");
	}	
	else if(dataObj.phone=="")
	{
		jQuery.notify("Please enter phone", "info");
	}	
	else
	{
	
		response.success(function (data) {
			var obj=JSON.parse(data); 
			
			if(obj.updateStatus=='Y')
			{
				jQuery.each(formData,function(id,data){
					//console.log(data);
					jQuery("#personalLabel-"+data.name).html(data.value);
				});	
				jQuery.notify("Changes done", "success");
				
				jQuery(".personal-Info").show();
				jQuery(".personal-Edit").hide();	
				jQuery("#personal-changeBtn").show();			
			}
			else
			{
				jQuery.notify("No changes applied", "info");
				//~ jQuery.notify("Changes done", "success");
				//~ 
				//~ jQuery(".personal-Info").show();
				//~ jQuery(".personal-Edit").hide();
				//~ jQuery("#personal-changeBtn").show();				
			}
		});	
	}	
});




jQuery(document).on('click','.accountAddress-update',function(){
	var target = jQuery(this).attr('data-target');
	
	var formData  = jQuery("#form-"+target).serializeArray();
	var extendedUrl="frontAjax/ajax_accountAddressUpdate";
	var base_url = (BASE_URL) ? BASE_URL : '';	
	
	
	var	dataObj = {};
	jQuery(formData).each(function(i, field){
	  dataObj[field.name] = field.value;
	});	
	
	if(dataObj.address_l1=="")	{	jQuery.notify("Please enter address_l1", "info");	}
	else if(dataObj.city=="")	{	jQuery.notify("Please enter city", "info");		}	
	else if(dataObj.state=="")	{	jQuery.notify("Please enter state", "info");	}	
	else if(dataObj.zipcode=="")	{	jQuery.notify("Please enter zipcode", "info");	}	
	else if(dataObj.country=="")	{	jQuery.notify("Please enter country", "info");	}				
	else
	{
		var response = callAjax(formData, extendedUrl, base_url);
		response.success(function (data) {
			var obj=JSON.parse(data); 
			
			if(obj.updateStatus=='Y')
			{
				jQuery.each(formData,function(id,data){
					jQuery("#"+target+"-"+data.name).html(data.value);
				});	
				jQuery.notify("Changes done", "success");
				jQuery("#addressSection-shpiing").html(obj.html);
				//~ jQuery("."+target+"-Info").show();
				//~ jQuery("."+target+"-Edit").hide();	
				//~ jQuery("#"+target+"-changeBtn").show();	
				//~ //alert(obj.id);	
				//~ jQuery("#"+target+"-billingId").val(obj.id);						
			}
			else
			{
				jQuery.notify("No changes applied", "info");
				
				jQuery("."+target+"-Info").show();
				jQuery("."+target+"-Edit").hide();
				jQuery("#"+target+"-changeBtn").show();				
			}
			
			if(dataObj.submitType=='insert')
			{
				jQuery("#submitType_0").val('update');
			}
		});
		
	}
	
});


jQuery(document).on('click','.billingAddress-update',function(){
	var target = jQuery(this).attr('data-target');
	
	var formData  = jQuery("#form-"+target).serializeArray();
	var extendedUrl="frontAjax/ajax_billingAdressUpdate";
	var base_url = (BASE_URL) ? BASE_URL : '';	
	
	
	var	dataObj = {};
	jQuery(formData).each(function(i, field){
	  dataObj[field.name] = field.value;
	});	
	
	if(dataObj.firstname=="")	{	jQuery.notify("Please enter firstname", "info");	}
	else if(dataObj.lastname=="")	{	jQuery.notify("Please enter lastname", "info");	}
	else if(dataObj.address_l1=="")	{	jQuery.notify("Please enter address_l1", "info");	}
	else if(dataObj.city=="")	{	jQuery.notify("Please enter city", "info");		}	
	else if(dataObj.state=="")	{	jQuery.notify("Please enter state", "info");	}	
	else if(dataObj.zipcode=="")	{	jQuery.notify("Please enter zipcode", "info");	}	
	else if(dataObj.country=="")	{	jQuery.notify("Please enter country", "info");	}				
	else
	{
		var response = callAjax(formData, extendedUrl, base_url);
		response.success(function (data) {
			var obj=JSON.parse(data); 
			
			if(obj.updateStatus=='Y')
			{
				jQuery.each(formData,function(id,data){
					jQuery("#"+target+"-"+data.name).html(data.value);
				});	
				jQuery.notify("Changes done", "success");
				jQuery("#addressSection-billing").html(obj.html);
				jQuery("."+target+"-Info").show();
				jQuery("."+target+"-Edit").hide();	
				jQuery("#"+target+"-changeBtn").show();	
				//~ //alert(obj.id);	
				//~ jQuery("#"+target+"-billingId").val(obj.id);						
			}
			else
			{
				jQuery.notify("No changes applied", "info");
				
				jQuery("."+target+"-Info").show();
				jQuery("."+target+"-Edit").hide();
				jQuery("#"+target+"-changeBtn").show();				
			}
			
			if(dataObj.submitType=='insert')
			{
				jQuery("#submitType_0").val('update');
			}
		});
		
	}
	
});

jQuery(document).on('click','.1billingAdress-update',function(){
	var target = jQuery(this).attr('data-target');
	var formData  = jQuery("#form-"+target).serializeArray();
	
	
	var	dataObj = {};
	jQuery(formData).each(function(i, field){
	  dataObj[field.name] = field.value;
	});	
	//alert(dataObj.submitType);
	var extendedUrl="frontAjax/ajax_billingAdressUpdate";
	var base_url = (BASE_URL) ? BASE_URL : '';	
	
	if(dataObj.firstname=="")
	{
		jQuery.notify("Please enter firstname", "info");
	}
	else if(dataObj.lastname=="")
	{
		jQuery.notify("Please enter lastname", "info");
	}		
	else if(dataObj.address_l1=="")
	{
		jQuery.notify("Please enter address_l1", "info");
	}	
	else if(dataObj.city=="")
	{
		jQuery.notify("Please enter city", "info");
	}	
	else if(dataObj.state=="")
	{
		jQuery.notify("Please enter state", "info");
	}	
	else if(dataObj.zipcode=="")
	{
		jQuery.notify("Please enter zipcode", "info");
	}	
	else if(dataObj.country=="")
	{
		jQuery.notify("Please enter country", "info");
	}				
	else
	{

		
		var response = callAjax(formData, extendedUrl, base_url);
		response.success(function (data) {
			var obj=JSON.parse(data); 
			
			if(obj.updateStatus=='Y')
			{
				jQuery.each(formData,function(id,data){
					jQuery("#"+target+"-"+data.name).html(data.value);
				});	
				jQuery.notify("Changes done", "success");
				jQuery("."+target+"-Info").show();
				jQuery("."+target+"-Edit").hide();	
				jQuery("#"+target+"-changeBtn").show();	
				//alert(obj.id);	
				jQuery("#"+target+"-billingId").val(obj.id);						
			}
			else
			{
				jQuery.notify("Changes done", "success");
				
				jQuery("."+target+"-Info").show();
				jQuery("."+target+"-Edit").hide();
				jQuery("#"+target+"-changeBtn").show();				
			}
			
			if(dataObj.submitType=='insert')
			{
				jQuery("#submitType_0").val('update');
			}
		});
	}
	
});

jQuery(document).on('click','#cardDetail-save',function(){
	var formData  = jQuery("#form-cardDetail").serializeArray();
	var extendedUrl="frontAjax/ajax_cardDetailUpdate";
	var base_url = (BASE_URL) ? BASE_URL : '';	
	var response = callAjax(formData, extendedUrl, base_url);
	
	var	dataObj = {};
	jQuery(formData).each(function(i, field){
	  dataObj[field.name] = field.value;
	});
	

	if(dataObj.firstname=="")
	{
		jQuery.notify("Please enter firstname", "info");
	}
	else if(dataObj.lastname=="")
	{
		jQuery.notify("Please enter lastname", "info");
	}		
	else if(jQuery("#cardLastDigit-text").val().length<16 )
	{
		jQuery.notify("Card number must be 16 digit", "info");		
	}
	else
	{
		
		response.success(function (data) {
			var obj=JSON.parse(data); 
			
			if(obj.updateStatus=='Y')
			{
				jQuery.each(formData,function(id,data){
					if(data.name=='cardLastDigit')
					{
						var digName = data.value;
						var lastFive = digName.substr(digName.length - 4); 
						jQuery("#cardDetail-"+data.name).html(lastFive);
					}
					else
					{
						jQuery("#cardDetail-"+data.name).html(data.value);
					}
				});	
				
				
				
				jQuery.notify("Changes done", "success");
				
				jQuery(".cardDetail-Info").show();
				jQuery(".cardDetail-Edit").hide();	
				jQuery("#cardDetail-changeBtn").show();			
			}
			else
			{
				jQuery.notify("Changes done", "success");
				
				jQuery(".cardDetail-Info").show();
				jQuery(".cardDetail-Edit").hide();
				jQuery("#cardDetail-changeBtn").show();				
			}
		});
	}
	
});

jQuery(document).on('keyup','#headerSearch-text',function(event){
	var value =this.value;
	//delay(function(){
		
		var formData  = {headerText:value};
		var extendedUrl="frontAjax/ajax_returnKeywordResult";
		var base_url = (BASE_URL) ? BASE_URL : '';	
		var response = callAjax(formData, extendedUrl, base_url) ;
		jQuery("#email-loader").show();

		response.success(function (data) {
			
			var obj=JSON.parse(data); 
			if(obj.hasRecord=='Y')
			{
				jQuery(".headerSearch-HelpBox").empty();
				jQuery(".headerSearch-HelpBox").append(obj.ulHTML);
				jQuery('.headerSearch-HelpBox').show();
				
				//jQuery('.headerSearch-HelpBox').dropdown('toggle');
				 
			}
			
		});
	//}, 500 );
});

jQuery(document).on('click','.headerSearch-HelpBox li',function(e){	
	var value = jQuery(this).attr('data-value');
	var product_id =  jQuery(this).attr('data-id');
	var product_type =  jQuery(this).attr('data-type');
	var product_name =  jQuery(this).attr('data-name');
	jQuery("#headerSearch-text").val(product_name);
	jQuery('.headerSearch-HelpBox').hide();
	jQuery('#selected_id').val(product_id);
	jQuery('#selected_type').val(product_type);
	//~ if(currentClass=='search')
	//~ {
		//~ jQuery('form#form-searchPage-filter').submit();
	//~ }
});

/* Search page */
jQuery(document).on('click','.search-vanture_list',function(e){	 
	jQuery('form#form-searchPage-filter').submit();
});
jQuery(document).on('click','.search-price_list',function(e){
	//~ jQuery(".search-price_list").prop( "checked", false );
	//~ jQuery(this).prop( "checked", true );
		 
	jQuery('form#form-searchPage-filter').submit();
});

jQuery(document).on('click','.search-category_list',function(e){
	jQuery(".search-category_list").prop( "checked", false );
	jQuery(this).prop( "checked", true );	 
	jQuery('form#form-searchPage-filter').submit();
});

jQuery(document).on('click','.search-cuisine_list',function(e){	 
	jQuery('form#form-searchPage-filter').submit();
});

jQuery(document).on('click','.search-deliveryFee_list',function(e){
	jQuery(".search-deliveryFee_list").prop( "checked", false );
	jQuery(this).prop( "checked", true );
	jQuery('form#form-searchPage-filter').submit();
});

jQuery(document).on('click','.search-deliveryTime_list',function(e){
	jQuery(".search-deliveryTime_list").prop( "checked", false );
	jQuery(this).prop( "checked", true );
	jQuery('form#form-searchPage-filter').submit();
});

jQuery(document).on('click','.search-paymentMethod_list',function(e){
	jQuery(".search-paymentMethod_list").prop( "checked", false );
	jQuery(this).prop( "checked", true );
	jQuery('form#form-searchPage-filter').submit();
});

jQuery(document).on('click','.search-rating_list',function(e){
//	jQuery(".search-rating_list").prop( "checked", false );
//	jQuery(this).prop( "checked", true );
	jQuery('form#form-searchPage-filter').submit();
});

jQuery(document).on('mouseout','.headerSearch-HelpBox',function(){
	jQuery(".headerSearch-HelpBox").hide();
});
jQuery(document).on('mouseover','.headerSearch-HelpBox',function(){
	jQuery( ".headerSearch-HelpBox" ).show();
});

jQuery(document).on('click','.btn-cancelOrder',function(){
		var product_id =  jQuery(this).attr('data-id');
		//alert(product_id);
		jQuery("#model-cancelOrder-product_id").val(product_id);
});

jQuery(document).on('click','.btn-cancelOrder-confirm',function(){
	var product_id = jQuery("#model-cancelOrder-product_id").val();
	var contant = jQuery("#model-cancelOrder-contant").val();
	var formData  = {id:product_id,cancellation_contant:contant};
	var extendedUrl="frontAjax/ajax_cancelOrder";
	var base_url = (BASE_URL) ? BASE_URL : '';	
	var response = callAjax(formData, extendedUrl, base_url) ;
	jQuery("#email-loader").show();

	response.success(function (data) {
		
		var obj=JSON.parse(data); 
		if(obj.hadDone=='Y')
		{
			jQuery(".cancelOrderBodyPart").html('<div class="alert alert-success">  <strong>Success!</strong> Your order cancel successfuly.</div>');
			jQuery(".modal-title").html('Confirm !');
			jQuery(".btn-cancelOrder-confirm").hide();
			jQuery(".back-menu-btn").hide();
			
			setTimeout(function(){ location.reload(); }, 1000);
			 
		}
		
	});	
});

jQuery(document).on('click','#chk_same_address',function(){
	var formData  = jQuery("#form-checkoutAddress").serializeArray();
	
	if(jQuery(this).is(':checked'))
	{
		jQuery("#shipping_firstname").val(jQuery("#billing_firstname").val());
		jQuery("#shipping_lastname").val(jQuery("#billing_lastname").val());
		jQuery("#shipping_address_l1").val(jQuery("#billing_address_l1").val());
		jQuery("#shipping_address_l2").val(jQuery("#billing_address_l2").val());
		jQuery("#shipping_city").val(jQuery("#billing_city").val());
		jQuery("#shipping_state").val(jQuery("#billing_state").val());
		jQuery("#shipping_country").val(jQuery("#billing_country").val());
		jQuery("#shipping_zipcode").val(jQuery("#billing_zipcode").val());
	}
	else
	{
		jQuery("#shipping_firstname").val('');
		jQuery("#shipping_lastname").val('');		
		jQuery("#shipping_address_l1").val('');
		jQuery("#shipping_address_l2").val('');
		jQuery("#shipping_city").val('');
		jQuery("#shipping_state").val('');
		jQuery("#shipping_country").val('');
		jQuery("#shipping_zipcode").val('');
	}	
		
	
});

//~ jQuery(document).on('keyup','#headerSearch-text',function(){
	//~ if(currentClass=='search' && this.value =='')
	//~ {
		//~ jQuery('form#form-searchPage-filter').submit();
	//~ }	
//~ });

function validate_checkoutAddress()
{
	if(jQuery("#chk_same_address").is(':checked'))
	{
		jQuery("#shipping_firstname").val(jQuery("#billing_firstname").val());
		jQuery("#shipping_lastname").val(jQuery("#billing_lastname").val());
		jQuery("#shipping_address_l1").val(jQuery("#billing_address_l1").val());
		jQuery("#shipping_address_l2").val(jQuery("#billing_address_l2").val());
		jQuery("#shipping_city").val(jQuery("#billing_city").val());
		jQuery("#shipping_state").val(jQuery("#billing_state").val());
		jQuery("#shipping_country").val(jQuery("#billing_country").val());
		jQuery("#shipping_zipcode").val(jQuery("#billing_zipcode").val());
	}	
}



function processRating(val, attrVal){
	//alert(attrVal);
  	var formData  = {rating:val,product_id:attrVal};
	var extendedUrl="frontAjax/ajax_saveProductRating";
	var base_url = (BASE_URL) ? BASE_URL : '';	
	var response = callAjax(formData, extendedUrl, base_url) ;
	jQuery("#email-loader").show();
	response.success(function (data) {
		var obj=JSON.parse(data); 
		if(obj.isInsert=='Y')
		{
			jQuery.notify("Your rating saved successfully", "success");
		//	jQuery("#search-rating-section-"+attrVal).hide();
		}
	});
}

function validateConfirmOrder()
{
	var formData  = jQuery("#form-checkout").serializeArray();
	var	dataObj = {};
	jQuery(formData).each(function(i, field){
	  dataObj[field.name] = field.value;
	});	
	
	
	jQuery('.paymentAddress-select').each(function(){
        var sickfull        = jQuery(this).val();
        var errormessage    = "";
        var name =  jQuery(this).attr('data-name');

        if (!sickfull)
            errormessage = "Please select payment Address for "+name;

        if (errormessage != "") {
            jQuery.notify(errormessage, "info");
            return false;
        }
		
    });
	
	jQuery('.shippingAddress-select').each(function(){
        var sickfull        = jQuery(this).val();
        var errormessage    = "";
        var name =  jQuery(this).attr('data-name');

        if (!sickfull)
            errormessage = "Please select shipping Address for "+name;

        if (errormessage != "") {
            jQuery.notify(errormessage, "info");
            return false;
        }
       
    });
    
	jQuery('.billingAddress-select').each(function(){
        var sickfull        = jQuery(this).val();
        var errormessage    = "";
        var name =  jQuery(this).attr('data-name');

		
        if (!sickfull)
            errormessage = "Please select billing Address for "+name;
		
        if (errormessage != "") {
            jQuery.notify(errormessage, "info");
            return false;
           
        }
       
    });    	
	

	
}

function callbackAvgTimeSlider(type)
{
	
	/* Delivery time range */
		var tooltipAVGRange = jQuery('<div id="tooltip" class="arrow-bg"class="arrow-bg" >Any</div>').css({position: 'absolute',top: -30,left: -7});
		jQuery( "#slider" ).slider({
			min: 1,
			max: 4,
			range: "min",
			value: 4,
			slide: function( event, ui ) {
					if(ui.value==1)
					{	 tooltipAVGRange.text('30');	}
					else if(ui.value==2)
					{	tooltipAVGRange.text('45');	}
					else if(ui.value==3)
					{	tooltipAVGRange.text('60');	}
					else if(ui.value==4)
					{	tooltipAVGRange.text('Any');	}				
				
			},
			create: function( event, ui ) {  tooltipAVGRange.text('Any'); },
			change: function( event, ui ) {
					if(ui.value==1)
					{
						jQuery("#hid_avg_deliveryTime").val(30);
						 tooltipAVGRange.text('30');
					}
					else if(ui.value==2)
					{
						jQuery("#hid_avg_deliveryTime").val(45);
						tooltipAVGRange.text('45');
					}
					else if(ui.value==3)
					{
						jQuery("#hid_avg_deliveryTime").val(60);
						tooltipAVGRange.text('60');
					}
					else if(ui.value==4)
					{
						jQuery("#hid_avg_deliveryTime").val('');
						tooltipAVGRange.text('Any');
					}
					
					if(type=='init')
					{
						tringerOn_listing_leftSearchBar();
					}
			}
		}).find(".ui-slider-handle").append(tooltipAVGRange).hover(function() {
			tooltipAVGRange.show()
		});
		
}



/* Location controller */
function validate_location_selection()
{	
	if(jQuery("#location-city").val().trim() == '' )
	{
		jQuery("#location-city").focus();
		//jQuery("#location-zipcode").addClass('error');
		return false;
	}
	else if(jQuery("#location-hid-locationName").val().trim() == '')
	{	
		jQuery("#location-alert").html('<strong>Warrning ! <stronng>Please select valid address on zipcode from list.');
		jQuery("#location-alert").show(function(){
			setTimeout(function(){ jQuery("#location-alert").fadeOut('slow'); }, 3000);
		})
		return false;
	}
}
/* Register controller */
function validate_signupForm()
{
	var re = /^(([^<>()[\]\\.,;:\s@\"]+(\.[^<>()[\]\\.,;:\s@\"]+)*)|(\".+\"))@((\[[0-9]{1,3}\.[0-9]{1,3}\.[0-9]{1,3}\.[0-9]{1,3}\])|(([a-zA-Z\-0-9]+\.)+[a-zA-Z]{2,}))$/;
    var chk_val = re.test(jQuery("#email").val());
	var pattern = /^\d{10}$/; /* For mobile number */
	
	if(jQuery("#name").val().trim()=='')
	{	jQuery("#name").focus();	jQuery("#errorMsg").html('Please enter name');	jQuery("#errorMsg").show(function(){	setTimeout(function(){ jQuery("#errorMsg").fadeOut('slow'); }, 3000); 	});	return false;		}
	else if(jQuery("#surname").val().trim()=='')
	{	jQuery("#surname").focus();	jQuery("#errorMsg").html('Please enter surname');	jQuery("#errorMsg").show(function(){	setTimeout(function(){ jQuery("#errorMsg").fadeOut('slow'); }, 3000); 	});	return false;	}
	else if(jQuery("#email").val().trim()=='')
	{	jQuery("#email").focus();	jQuery("#errorMsg").html('Please enter email');		jQuery("#errorMsg").show(function(){	setTimeout(function(){ jQuery("#errorMsg").fadeOut('slow'); }, 3000); 	});		return false;	}
	else  if (chk_val == false)
    {	jQuery("#email").focus();	jQuery("#errorMsg").html('Please enter valid email');		jQuery("#errorMsg").show(function(){	setTimeout(function(){ jQuery("#errorMsg").fadeOut('slow'); }, 3000); 	});	return false;	}
	else if(jQuery("#password").val().trim()=='')
	{	jQuery("#password").focus();	jQuery("#errorMsg").html('Please enter password');	jQuery("#errorMsg").show(function(){	setTimeout(function(){ jQuery("#errorMsg").fadeOut('slow'); }, 3000); 	});		return false;	}	
	else if(jQuery("#confirm_password").val().trim()=='')
	{	jQuery("#confirm_password").focus();	jQuery("#errorMsg").html('Please enter confirm password');		jQuery("#errorMsg").show(function(){	setTimeout(function(){ jQuery("#errorMsg").fadeOut('slow'); }, 3000); 	});		return false;	}	
	else if(jQuery("#password").val().trim() != jQuery("#confirm_password").val().trim())
	{	jQuery("#confirm_password").focus();	jQuery("#errorMsg").html('Password and confirm password does not match');	jQuery("#errorMsg").show(function(){	setTimeout(function(){ jQuery("#errorMsg").fadeOut('slow'); }, 3000); 	});		return false;	}
	else if(jQuery("#cityName").val().trim()=='')
	{	jQuery("#cityName").focus();	jQuery("#errorMsg").html('Please enter city');		jQuery("#errorMsg").show(function(){	setTimeout(function(){ jQuery("#errorMsg").fadeOut('slow'); }, 3000); 	});		return false;	}	
	else if(jQuery("#mobile_number").val().trim()=='')
	{	jQuery("#mobile_number").focus();	jQuery("#errorMsg").html('Please enter mobile number');		jQuery("#errorMsg").show(function(){	setTimeout(function(){ jQuery("#errorMsg").fadeOut('slow'); }, 3000); 	});		return false;	}
	//else  if(!pattern.test(mobile)) {	jQuery("#mobile_number").focus();	jQuery("#errorMsg").html('Please enter 10 digit number');	jQuery("#errorMsg").show(function(){	setTimeout(function(){ jQuery("#errorMsg").fadeOut('slow'); }, 3000); 	});	return false;	}	
	else if(jQuery("#validEmailStatus").val()==0)
	{	jQuery("#email").focus();	jQuery("#errorMsg").html('Email already exist');	jQuery("#errorMsg").show(function(){	setTimeout(function(){ jQuery("#errorMsg").fadeOut('slow'); }, 3000); 	});		return false;	}
	
	
	
}
/* Forgot controller */
function validate_forgot()
{
	var re = /^(([^<>()[\]\\.,;:\s@\"]+(\.[^<>()[\]\\.,;:\s@\"]+)*)|(\".+\"))@((\[[0-9]{1,3}\.[0-9]{1,3}\.[0-9]{1,3}\.[0-9]{1,3}\])|(([a-zA-Z\-0-9]+\.)+[a-zA-Z]{2,}))$/;
    var chk_val = re.test(jQuery("#email").val());	
	if(jQuery("#email").val().trim()=='')
	{	jQuery("#email").focus();	jQuery("#errorMsg").html('Please enter email');	jQuery("#errorMsg").show(function(){	setTimeout(function(){ jQuery("#errorMsg").fadeOut('slow'); }, 3000); 	});		return false;	}
	else  if (chk_val == false)
    {	jQuery("#email").focus();	jQuery("#errorMsg").html('Please enter valid email');	jQuery("#errorMsg").show(function(){	setTimeout(function(){ jQuery("#errorMsg").fadeOut('slow'); }, 3000); 	});		return false;	}	
}
/* ResetPassword controller */
function validate_resetPass()
{
	if(jQuery("#password").val().trim()=='')
	{	jQuery("#password").focus();	jQuery("#errorMsg").html('Please enter password');	jQuery("#errorMsg").show(function(){	setTimeout(function(){ jQuery("#errorMsg").fadeOut('slow'); }, 3000); 	});	return false;	}	
	else if(jQuery("#confirm_password").val().trim()=='')
	{	jQuery("#confirm_password").focus();	jQuery("#errorMsg").html('Please enter confirm password');	jQuery("#errorMsg").show(function(){	setTimeout(function(){ jQuery("#errorMsg").fadeOut('slow'); }, 3000); 	});		return false;	}	
	else if(jQuery("#password").val().trim() != jQuery("#confirm_password").val().trim())
	{	jQuery("#confirm_password").focus();	jQuery("#errorMsg").html('Password and confirm password does not match');	jQuery("#errorMsg").show(function(){	setTimeout(function(){ jQuery("#errorMsg").fadeOut('slow'); }, 3000); 	});		return false;	}
}

function tringerOn_listing_leftSearchBar()
{
	var formData  = jQuery("#form-restaurant-listing-searchBox").serializeArray();
	var extendedUrl="/restaurantList_searchBy";
	var base_url = (BASE_URL) ? BASE_URL+currentClass : '';		
	jQuery(".ventureLisingLoader").show();
	//jQuery(".restaurant_list-ajaxSection").html('<div class="list-loader" ><img src="'+BASE_URL+'assets/front/images/list-loader.gif" /></div>');
	jQuery.ajax({
		url: base_url + extendedUrl,
		type: "POST",
		datatype: 'json',
		data: formData,
		beforeSend: function () {
		  jQuery(".ventureLisingLoader").show();
		  //jQuery(".restaurant_list-ajaxSection").html('<div class="list-loader" ><img src="'+BASE_URL+'assets/front/images/list-loader.gif" /></div>');
		},
		success: function (data) {

			var obj=JSON.parse(data);
			if(obj.hasRecordStatus=='Y')
			{
				jQuery(".restaurant_list-ajaxSection").html('');
				if(jQuery("#hid-load_more").val()==1)
				{
					jQuery(".restaurant_list-ajaxSection").append(obj.html);	
				}else {
					jQuery(".restaurant_list-ajaxSection").html(obj.html);	
				}
				//jQuery(".restaurant_list-ajaxSection").append(obj.qry);	
				jQuery("#hid-load_more").val(0);
				jQuery("#hid_ofset").val(obj.ofset);
				jQuery("#hid_limit").val(obj.limit);
			}
			else
			{
				jQuery(".restaurant_list-ajaxSection").html('<h3>List not found</h3>');
				//jQuery(".restaurant_list-ajaxSection").append(obj.qry);	
			}
			jQuery("#filter-action-area").html(obj.filterChecksHTML);
			
		},complete: function(){
   jQuery(".ventureLisingLoader").fadeOut();
			},
	});
}
/* Area controller */
function clearThisFilter(id)
{
	
	if(id=='restaurant-restaurantName' || id== 'all')	{	jQuery("#restaurant-restaurantName").val('');	}
	if(id== 'listing_delivery_area' || id== 'all')	{
		jQuery("#listing_delivery_area option:first-child").prop("selected", true);		
		jQuery("#listing_delivery_area").next("span").find("span").html("Select Delivery area"); 
		
	}
	if(id== 'amount' || id== 'all')
	{
		/* Amount range */
			var minOrderAmount =  1 *parseInt(jQuery("#minOrderAmount").val());
		var maxOrderAmount =  1 *parseInt(jQuery("#maxOrderAmount").val());
		//alert(minOrderAmount +" "+ maxOrderAmount);
		jQuery( "#amount" ).val( "$0"); 
		var tooltipRange = jQuery('<div id="tooltip" class="arrow-bg">'+maxOrderAmount+'</div>').css({position: 'absolute',top: -30,left: -7});
		tooltipRange.text(maxOrderAmount);
		jQuery( "#slider-range-min" ).slider("destroy");
		jQuery( "#slider-range-min" ).slider({
			range: "min",
			value: maxOrderAmount,
			min: minOrderAmount,
			max: maxOrderAmount,
			slide: function( event, ui ) {
			jQuery( "#amount" ).val( "$" + ui.value );
			 tooltipRange.text(ui.value);
			},
			change: function( event, ui ) {
				tringerOn_listing_leftSearchBar();
				console.log("ssss");
			}
		}).find(".ui-slider-handle").append(tooltipRange).hover(function() {
			tooltipRange.show()
		});
		//jQuery( "#amount" ).val( "$" + jQuery( "#slider-range-min" ).slider( "value" ) );
	}
	if(id== 'hid_avg_deliveryTime' || id== 'all')
	{
		jQuery( "#hid_avg_deliveryTime" ).val('');
		/* Delivery time range */
		//var tooltipAVGRange = jQuery('<div id="tooltip" class="arrow-bg"class="arrow-bg" >0</div>').css({position: 'absolute',top: -30,left: -7});
		
		
		
		callbackAvgTimeSlider('init');
	}
	if(id == 'listing_payment_method' || id== 'all')
	{
		jQuery("#listing_payment_method option:first-child").prop("selected", true);
		jQuery("#listing_payment_method").next("span").find("span").html("Select Payment method"); 
	}
	if(id== 'cuisine_fav' || id== 'all')
	{
		jQuery(".cuisine_fav").prop( "checked", false );
	}
	if(id== 'selected_rating' || id== 'all')
	{
		jQuery("#selected_rating").val('');
		jQuery( "#ratingSlider" ).slider("destroy");
		jQuery( "#ratingSlider" ).slider({
			min: 0,
			max: 5,
			range: "min",
			value: 0,
			slide: function( event, ui ) {
				//select[ 0 ].selectedIndex = ui.value - 1;
			},
			change: function( event, ui ) {
				if(ui.value==0)
					jQuery("#selected_rating").val('');
				else					
					jQuery("#selected_rating").val(ui.value);					
					
				tringerOn_listing_leftSearchBar();
			}
		});
	}

	if(id== 'now_open' || id== 'all')
	{
		jQuery("#now_open").prop( "checked", false );
	}	

	if(id== 'with_promotion' || id== 'all')
	{
		jQuery("#with_promotion").prop( "checked", false );
	}	

	if(id== 'delivery_fee' || id== 'all')
	{
		jQuery("#delivery_fee option:first-child").prop("selected", true);
		jQuery("#delivery_fee").next("span").find("span").html("ALL"); 
	}		
	
	tringerOn_listing_leftSearchBar();	
	 
}
	

function loadmore()
{
	jQuery("#hid-load_more").val('1');
	alert(jQuery("#hid-load_more").val());
}

function changeOrder(type)
{
	if(type=='Alfabet')
	{
		if(jQuery("#hid-sort-order").val() == "asc")
		{
			jQuery("#hid-sort-order").val('desc');
			jQuery("#orderStatment").html('Z-A');
		}
		else
		{
			jQuery("#hid-sort-order").val('asc');
			jQuery("#orderStatment").html('A-Z');
		}
		tringerOn_listing_leftSearchBar();
	}
	
		
}

/* Common */
function callAjax(jsonEncode, extendUrl, useBaseUrl) {
  
  var base_url = (useBaseUrl) ? useBaseUrl : '';
  var returnData = '';
  // alert("site url"+jQuery('#controllerName').val());  
  return jQuery.ajax({
	  url: base_url + extendUrl,
	  type: "POST",
	  datatype: 'json',
	  data: jsonEncode,
	  beforeSend: function () {
	  
	  jQuery('.loader').show();
	  },
	  success: function (data) {
		jQuery('.loader').fadeOut();
	  }
  });
}

function height1(){
	var he1= $(window).height(); 
	var wd=jQuery(window).width();
	
	if(wd > 1024)
	{
		cc= he1- 200;           
		jQuery(".carousel-inner img").height(cc);
	}
	else{}
}

function action_areaSelection()
{
	if(jQuery("#seller-areaName").val() == "")
	{
		jQuery("#seller-areaName").focus();
		return false;
		//window.location=BASE_URL+"area/"+jQuery("#seller-areaName").val();
	}
}
/* Detail controller */
function tab_product_listing(venture_id,type,name)	
{
	var formData  = {venture_id:venture_id,selected_type:type,headingName:name};
	var extendedUrl="frontAjax/ajax_tab_product_listing";
	var base_url = (BASE_URL) ? BASE_URL : '';	
	var response = callAjax(formData, extendedUrl, base_url) ;
	jQuery(".product-listing-section").html('<div class="productlist-loader" ><img src="'+BASE_URL+'assets/front/images/productLoader.gif" /></div>');
	response.success(function (data) {
            var obj=JSON.parse(data); 
			jQuery(".product-listing-section").html(obj.productBoxHtml);
			
    });
}

function checkAddonCount(addOnName,totalCount)
{
	if (jQuery('.adon-checkbox.'+addOnName+'.input[type=checkbox]:checked').length > totalCount) {		
		jQuery.notify("Not Allowed", "info");
	}
}


 /** Function : Custom validation using classes **/
function validation()
   {
	 
       var bool=bool1=bool2=true;
       $jas('.cls-req,.cls-int').removeClass("required");
       $jas('.cls-req').each(function(){
           var th=$jas(this);
           var req_val=th.val().trim();
           if(req_val=='')
           {
			  
               bool=false;
               th.addClass("required");
           }
       });
       $jas('.cls-int').each(function(){
           var th=$jas(this);
           var int_val=th.val().trim();
           var minlength=th.attr('min-length');
           var maxlength=th.attr('max-length');
           var chk_val=myIsNaN(int_val);
           if(int_val!='')
           {
                if(int_val.length<minlength || int_val.length>maxlength)
                {
                    th.val('');
                    bool=false
                    th.attr('placeholder','Please enter the value between '+minlength+' and '+maxlength);
                }
           }
           if(chk_val==true)
           {
               th.val('');
               bool=false;
               th.attr('placeholder','Integer value allow')
               th.addClass("required");
           }


       });
       $jas('.cls-email-val').each(function(){
           var th=$jas(this);
           var email_chk=th.val().trim();
           bool1=validateEmail(email_chk,th);
       });

       $jas('.cls-confirm').each(function(){
           var th=$jas(this);
           var con_id=th.attr('con-with');
           if($jas('#'+con_id).val()!=th.val())
           {
                th.val('');
                bool2=false;
                th.attr('placeholder','Password is not matching');
                th.addClass("required");
           }
       });

       if(bool==true && bool1!=true)
       bool=bool1;
       else if(bool==true && bool1==true)
       bool=bool2;
	
       return bool;
   }

function validateEmail(email,th) { 
    var re = /^(([^<>()[\]\\.,;:\s@\"]+(\.[^<>()[\]\\.,;:\s@\"]+)*)|(\".+\"))@((\[[0-9]{1,3}\.[0-9]{1,3}\.[0-9]{1,3}\.[0-9]{1,3}\])|(([a-zA-Z\-0-9]+\.)+[a-zA-Z]{2,}))$/;
    var chk_val= re.test(email);
    if(chk_val==false)
    {   
        bool=false;         
        if(th.parent().find('.return_msg').length == 0) 
        {
            th.after('<span class="return_msg">Email is not valid<span>');
            th.addClass("required");
        }
    }
    else
    {
        th.removeClass("required");    
        th.parent().find('span').replaceWith('');
    }    
    return chk_val;  
}

function clearSearchPageFilter(actions,value)
{
	
	jQuery("#search-"+actions+"-"+value).prop( "checked", false );
	
	//~ if(actions=="productName" || actions=="all")
	//~ {
		//~ jQuery("#get-name").val('');
		//~ 
	//~ }
	//~ if(actions=='ventureList' || actions=="all")
	//~ {
		//~ jQuery("#chkVantureList-"+value).prop( "checked", false );
	//~ }
	//~ if(actions=='priceList' || actions=="all")
	//~ {
		//~ jQuery("#chKPriceList-"+value).prop( "checked", false );
	//~ }	
	
	jQuery('form#form-searchPage-filter').submit();
}

function searchProductOrderBy(sort_name,sort_type)
{
	jQuery("#hid-sort_name").val(sort_name);
	jQuery("#hid-sort_type").val(sort_type);
	jQuery('form#form-searchPage-filter').submit();
}
