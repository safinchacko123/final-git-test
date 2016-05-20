<?php $sessionData =  $this->session->userdata('locationDetail'); ?>

<!-- Content Area -->
<script>
var sellerMap_lat  = "<?php echo $sessionData['location_hid_lat']; ?>";
var sellerMap_lng  = "<?php echo $sessionData['location_hid_lng']; ?>";
var  iso_code_2 = "<?php echo $sessionData['location_iso_code_2']; ?>"; 
</script>
<div class="container">
  <div class="breadcrumb">
    <ul>
      <li><a   href="<?php echo site_url('category') ?>"><?php echo !empty($sessionData['location_city'])?current(explode(",",$sessionData['location_city'])):$sessionData['location_zipcode']; ?></a></li>
      <li><?php echo $sessionData['category_name']; ?></li>
    </ul>
  </div>
  <div class="seller-selection">
    <h2 class="mB30 mT0"><?php echo $search_line_div; ?></h2>
    
		<div class="mB40 search-map">
<!--
      <div class="col-1 cols">
        <input type="text" class="form-control cityMap" placeholder="Enter your address">
      </div>

      <div class="col-2 cols text-center">OR</div>
-->      
	<form onsubmit="return action_areaSelection()" action="<?php echo site_url('area'); ?>" method="POST" >
      <div class="col-3 cols">
		  
        <select id="seller-areaName"  name="seller-areaName">
			<option value="">Select your area</option>
          <?php  if(!empty($areaSelectResult)) 
				{
					foreach($areaSelectResult as $area)
					{
						//echo "<pre>ghdfg"; print_r($area); echo "</pre>"; 
						echo '<option data-lat="'.$area['latitude'].'" data-lng="'.$area['longitude'].'" value="'.$area['venture_id'].'" >'.$area['address'].'</option>';
					}
				}
          ?>
          
        </select>
      </div>
		<input type="hidden" id="seller-hid-locationName" name="seller_hid_locationName"  />
		<input type="hidden" id="seller-hid-lat" name="seller_hid_lat"  />
		<input type="hidden" id="seller-hid-lng" name="seller_hid_lng"  />
		<div class="col-4 cols"><input type="submit"   class="btn btn-primary" value="<?php echo $search_btn_value; ?>" /></div>
		<!--
			  <div class="col-4 cols"><a href="javascript:void(0)" onclick="action_areaSelection()" class="btn btn-primary"><?php echo $search_btn_value; ?></a></div>
		-->

		</form>
      <div class="clearfix"></div>
    </div>
    
    <div class="clearfix"></div>
    <h3 class="text-center light mB30">Select your location on the map</h3>
  </div>
</div>
<div class="clearfix"></div>

<!-- Map -->
<div class="map" id="dvMap" width="100%" height="375">
 
</div>


