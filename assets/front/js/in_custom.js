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
	else if(jQuery("#location-area").val().trim() == '')
	{
		jQuery("#location-alert").html('<strong>Warrning ! <stronng>Please select your area.');
		jQuery("#location-alert").show(function(){
			setTimeout(function(){ jQuery("#location-alert").fadeOut('slow'); }, 3000);
		})
		return false;		
	}
}


function height1(){
	var he1= $(window).height(); 
	var wd=$(window).width();
	
	if(wd > 1024){
		
	 cc= he1-40;           
	   $(".carousel-inner img").height(cc);
   }
   else{}
}   

jQuery(document).ready(function(){
	
		jcf.replaceAll();
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
			//alert("selected");					
				//console.log(result.geometry.location.lat());
				jQuery("#location-hid-lat").val(result.geometry.location.lat());
				//console.log(result.geometry.location.lng());
				jQuery("#location-hid-lng").val(result.geometry.location.lng());
				//console.log(result);
				
				var latitude = result.geometry.location.lat();
				var longitute = result.geometry.location.lng();
					
				var formData  = {lat:latitude,lng:longitute};
				var extendedUrl="frontAjax/ajax_fetchVantureList";
				var base_url = (BASE_URL) ? BASE_URL : '';	
				var response = callAjaxIn(formData, extendedUrl, base_url) ;
				jQuery("#mainLoadingdiv").show();

				response.success(function (data) {
					
					var obj=JSON.parse(data); 
					if(obj.hasRecord=='Y')
					{
						jQuery("#location-area").next("span").find("span").html("Select your area"); 
						jQuery("#location-area").empty();
						//jQuery("#location-area").append('<option value="">Select your area</option>');
						jQuery("#location-area").append(obj.ulHTML);
						//jQuery('.headerSearch-HelpBox').dropdown('toggle');
						 
					}
					jQuery("#mainLoadingdiv").hide();
					
				});
				
				
		});
		
});

jQuery(window).load(function() {
	 jQuery('#outCountryDialog').modal('show');
	 jQuery('#outCountryDialog').modal({backdrop: 'static',keyboard: false});
});

jQuery(document).ready(function(){
	height1();
});

jQuery(window).resize(function(){
	height1();
});

/* Seller controller */
jQuery(document).on('change','#location-area',function(){
	var lat = jQuery(this).find(':selected').attr('data-lat');
	var lng = jQuery(this).find(':selected').attr('data-lng');
	var locality = jQuery(this).find(':selected').attr('data-locality');
	
	var text = jQuery(this).find("option:selected").text();
	jQuery("#seller-hid-lat").val(lat);
	jQuery("#seller-hid-lng").val(lng);
	jQuery("#seller-hid-locationName").val(text);
	jQuery("#seller-hid-locality").val(locality);
	
});

/* Common */
function callAjaxIn(jsonEncode, extendUrl, useBaseUrl) {
  
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


