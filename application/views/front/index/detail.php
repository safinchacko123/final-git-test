<?php $sessionData =  $this->session->userdata('locationDetail'); 
$min_order_amount = !empty($ventureResult['min_delivery_amount'])?$ventureResult['min_delivery_amount']:'0';
$cardData = array();
$cardData['min_order_amount'] = $min_order_amount;

	$sellerLocation = explode(",",$sessionData['seller_hid_locationName']);
	$area_name = array_shift($sellerLocation);	
?>
<!-- Content Area -->
  <script>
	window.onload = function() {
		var venture_id = "<?php echo $sessionData['venture_id']; ?>";
		tab_product_listing(venture_id,type='all',name='All');
	};
  </script>
  <input type="hidden" value="<?php echo $sessionData['venture_id']; ?>" id="productPage_venture_id" />
<!-- Content Area -->
<div class="container">
 
	<div class="breadcrumb">
		<ul>
			<li><a href="<?php echo site_url('category') ?>"><?php echo !empty($sessionData['location_city'])?current(explode(",",$sessionData['location_city'])):$sessionData['location_zipcode']; ?></a></li>
			<li><?php echo $sessionData['category_name']; ?></li>
			<li><a href="<?php echo site_url('area')?>"><?php echo $area_name; ?></a></li>
			<li><?php echo $sessionData['venture_name']; ?></li>
		</ul>
	</div>
 
	<div class="clearfix"></div>
	<div class="heading"><h2><?php echo $sessionData['venture_name']; ?>, <span><?php echo !empty($sessionData['location_city'])?current(explode(",",$sessionData['location_city'])):$sessionData['location_zipcode']; ?>, <?php echo $sessionData['area_name']; ?></span></h2></div>

	<div class="top-links">
		<?php
		$result = $this->business_model->return_venture_cuisine($sessionData['venture_id'],'id');	
		if(!empty($result)) 
		{
			$cuisineArr = implode(" | ",$result);
			//echo '<div class="cuisineSelection row">'.$cuisineArr.'</div>';
			echo '<a href="javascript:void(0)">'.$cuisineArr.'</a>';
		} 		
		?>	
		
		
	</div>
 
	<!--body area -->
	<div class="body-area">
		<div class="row">
			<div class="col-sm-3 pull-right col-xs-12">
				<?php $this->load->view('front/index/product_cart',$cardData); ?>
			</div>
			<div class="col-sm-9 pull-left col-xs-12">
				<div class="white-bg">
					<div class="product-logo">
						<?php 	
						if(!empty($ventureResult['customer_logo']))
						{	
							$venturelogoPath = dirname(dirname(dirname(dirname(dirname(__FILE__))))).'/uploads/images/venturelogo/'.$ventureResult['customer_logo'];
							if(file_exists($venturelogoPath))
							{
							?>
								<img height="155px" width="148px" src="<?php echo site_url(); ?>/uploads/images/venturelogo/<?php echo $ventureResult['customer_logo']; ?>"  />
						<?php
							}
						}
						else 
						{ ?>	
							<img height="155px" width="148px"  src="<?php echo site_url(); ?>assets/front/images/deault_food_image.png"  />
						<?php 	
						} ?>
						
						
						
					</div>
					<div class="user-review">
						<div class="star"><img src="<?php echo site_url(); ?>assets/front/images/star.png"> <img src="<?php echo site_url(); ?>assets/front/images/star.png"> <img src="<?php echo site_url(); ?>assets/front/images/star.png"> <img src="<?php echo site_url(); ?>assets/front/images/star.png"> <img src="<?php echo site_url(); ?>assets/front/images/star.png"></div>
						<h3>User Reviews</h3>
						<p>Be the first to review this<br>restaurent</p>
					</div>
					<table class="table delivery-table"> 
						<thead> </thead>
						<tbody>
							<tr><td>Avg. Delivery Time</td><th><?php echo !empty($ventureResult['avg_delivery_time'])?$ventureResult['avg_delivery_time']:'0'; ?> min</th></tr>
							<tr><td>Delivery Fee</td><th><?php echo config_item('myCurrency'); ?><?php echo !empty($ventureResult['delivery_fee'])?$ventureResult['delivery_fee']:'0'; ?> </th></tr>
							<tr><td>Min. Order Amount </td><th><?php echo config_item('myCurrency'); ?><?php echo !empty($ventureResult['min_delivery_amount'])?$ventureResult['min_delivery_amount']:'0'; ?> </th></tr>
							<tr>
								<td colspan="2" class="last-row"><img src="<?php echo site_url(); ?>assets/front/images/location-icon.png"> See min. delivery amounts for all regions</td> 
							</tr> 
						</tbody> 
					</table>
					<div class="clearfix"></div>
				</div>
				
				<div id="horizontalTab">
				<ul>
					<li><a href="#tab-1"><img src="<?php echo site_url(); ?>assets/front/images/menu-icon.png"> Menu</a></li>
					<li><a href="#tab-2"><img src="<?php echo site_url(); ?>assets/front/images/review-icon.png"> User Reviews</a></li>
					<li><a href="#tab-3"><img src="<?php echo site_url(); ?>assets/front/images/delivery-icon.png"> Delivery Details</a></li>
				</ul>
				<div id="tab-1">        
					<div class="row">      
						<div class="col-sm-4">          
							<div class="menu-listing">           
								<ul class="product-catType">
									<li data-type="all" class="active"><a href="javascript:void(0)">All</a></li>
									<li data-type="most_selling"><a href="javascript:void(0)">Most Selling</a></li>
									<li data-type="promotions" ><a href="javascript:void(0)">Promotions</a></li>
								</ul>           
							</div>            
						</div>            
						<div class="col-sm-8">           
							<div class="most-selling product-listing-section">
								
							</div>
						</div>
					</div>      
				</div>
				<div id="tab-2">
					<p>Quisque sodales sodales lacus pharetra bibendum. Etiam commodo non velit ac rhoncus. Mauris euismod purus sem, ac adipiscing quam laoreet et. Praesent vulputate ornare sem vel scelerisque. Ut dictum augue non erat lacinia, sed lobortis elit gravida. Proin ante massa, ornare accumsan ultricies et, posuere sit amet magna. Praesent dignissim, enim sed malesuada luctus, arcu sapien sodales sapien, ut placerat eros nunc vel est. Donec tristique mi turpis, et sodales nibh gravida eu. Etiam odio risus, porttitor non lacus id, rhoncus tempus tortor. Curabitur tincidunt molestie turpis, ut luctus nibh sollicitudin vel. Sed vel luctus nisi, at mattis metus. Aenean ultricies dolor est, a congue ante dapibus varius. Nulla at auctor nunc. Curabitur accumsan feugiat felis ut pretium. Praesent semper semper nisi, eu cursus augue.</p>
				</div>
				<div id="tab-3">
					<p>Mauris facilisis elit ut sem eleifend accumsan molestie sit amet dolor. Pellentesque dapibus arcu eu lorem laoreet, vitae cursus metus mattis. Nullam eget porta enim, eu rutrum magna. Duis quis tincidunt sem, sit amet faucibus magna. Integer commodo, turpis consequat fermentum egestas, leo odio posuere dui, elementum placerat eros erat id augue. Nullam at eros eget urna vestibulum malesuada vitae eu mauris. Aliquam interdum rhoncus velit, quis scelerisque leo viverra non. Suspendisse id feugiat dui. Nulla in aliquet leo. Proin vel magna sed est gravida rhoncus. Mauris lobortis condimentum nibh, vitae bibendum tortor vehicula ac. Curabitur posuere arcu eros.</p>
				</div>
			</div>
		</div>
	</div>
</div>
 <!--body area //-->
</div>
<div class="clearfix"></div>
