<!-- overlay div -->
<div   class="location-overlay"></div>
<!-- overlay div -- > 
<!-- Content Area -->
<div class="container mT60">
  <div class="row">
    <div class="col-md-8 col-center">
      <div class="find-store text-center">
        <div class="heading">Groceries, Food, Pharmacy delivered in 1 hour at your drop step.</div>
			<form id="form-location-selection" method="POST" action="<?php echo site_url('category'); ?>" onsubmit="return validate_location_selection()" >
				<div class="content">
					<div id="location-alert" style="display:none" class="alert alert-warning">
						<strong>Warning!</strong> Indicates a warning that might need attention.
					</div>
				  <p class="mB30">Enter your zipcode or select your city to see your Local Stores.</p>
				  <div class="row mB40">
					
					<div class="col-sm-12">
						<input type="text"  value="<?php //echo !empty($locationDetail)?$locationDetail['location_city']:''; ?>"  id="location-city"  name="location_city" class="form-control city" placeholder="Zipcode / City">
					</div>
				  </div>
					<input type="hidden" id="location-hid-locationName" name="location_hid_locationName"  />
					<input type="hidden" id="location-hid-lat" name="location_hid_lat"  />
					<input type="hidden" id="location-hid-lng" name="location_hid_lng"  />
					<input type="hidden" id="location-hid-iso_code_2" name="location_iso_code_2"  value="<?php echo isset($_COOKIE['country_code'])?$_COOKIE['country_code']:''; ?>" />
					<input type="hidden" id="location-hid-country_id"  name="location_hid_country_id"  value="<?php echo $country_id; ?>" />
				  <input type="submit"  id="location-findButton"  value="FIND" class="btn btn-primary btn-round mB30" />
				  <!--<p>Already have an account? <a href="#">Log In</a></p>-->
				</div>
			</form>
      </div>
    </div>
  </div>
</div>
<div class="clearfix"></div>
<script>	var  iso_code_2 = "<?php echo $countryDetail->iso_code_2; ?>"; </script>
