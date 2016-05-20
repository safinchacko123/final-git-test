<?php $detail =  $this->session->userdata('locationDetail');
//echo "<pre>"; print_r($detail['seller_hid_locationName']); echo "</pre>";
 ?>
<!-- Content Area -->
<div class="container mT60">
  <div class="row">
    <div class="col-md-11 col-center">
      <div class="category text-center">
        <p class="blue f16 text-left">
			<strong>
				<?php
					
				 if(!empty($detail['location_city'])) { 
						$location = explode(",",$detail['location_city']);
						$location_city = array_shift($location);
						$location_country = array_pop($location);
						
						
						$sellerLocation = explode(",",$detail['seller_hid_locationName']);
						//print_r($sellerLocation);
						$sellerLocation_city = array_shift($sellerLocation);
						
					 }
					?>
<!--				<a class="back-location-default"  href="javascript:void(0)"><?php echo $sellerLocation_city; ?></a> ,-->
				<a class="back-location-1" href="<?php echo site_url('/?type=location'); ?>"><?php echo !empty($location_city)?$location_city:'Select '; ?>, <?php echo $sellerLocation_city; ?></a>, 
				<a class="back-location" href="<?php echo MAIN_URL; ?>?country=else"><?php echo !empty($location_country)?$location_country:' Category'; ?></a>
			</strong>
		</p>
        <div class="row">
          <div class="col-xs-4"><a href="<?php echo site_url('area/3'); ?>"><img src="<?php echo site_url(); ?>assets/front/images/category-food.jpg" alt="" /></a></div>
          <div class="col-xs-4"><a href="<?php echo site_url('area/1'); ?>"><img src="<?php echo site_url(); ?>assets/front/images/category-grocery.jpg" alt="" /></a></div>
          <div class="col-xs-4"><a href="<?php echo site_url('area/2'); ?>"><img src="<?php echo site_url(); ?>assets/front/images/category-pharmacy.jpg" alt="" /></a></div>
        </div>
      </div>
    </div>
  </div>
</div>
<div class="clearfix"></div>
