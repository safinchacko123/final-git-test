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
			<div class="col-sm-12 pT6 pB6"><br></div>
			<div class="col-sm-12">
				<select id="location-area" name="area_id">
					<option value="">Select your area</option>
				</select>
			</div>
			
		</div>
		<input type="hidden" id="location-hid-locationName" name="location_hid_locationName"  />
		<input type="hidden" id="location-hid-lat" name="location_hid_lat"  />
		<input type="hidden" id="location-hid-lng" name="location_hid_lng"  />
		<input type="hidden" id="location-hid-iso_code_2" name="location_iso_code_2"  value="IN" />
		<input type="hidden" id="location-hid-country_id" name="location_hid_country_id"  value="99" />
		
		<input type="hidden" id="seller-hid-locationName" name="seller_hid_locationName"  />
		<input type="hidden" id="seller-hid-lat" name="seller_hid_lat"  />
		<input type="hidden" id="seller-hid-lng" name="seller_hid_lng"  />		
		
		<input type="hidden" id="seller-hid-locality" name="seller_hid_locality"  />		
		
	  <input type="submit"  id="location-findButton"  value="FIND" class="btn btn-primary btn-round mB30" />
	  <!--<p>Already have an account? <a href="#">Log In</a></p>-->
	</div>
</form>

