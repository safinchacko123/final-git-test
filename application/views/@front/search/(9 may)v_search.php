<?php 
$sessionData =  $this->session->userdata('locationDetail');
$userDetail = $this->session->userdata('userDetail');
$latitude = $sessionData['seller_hid_lat'];
$longitude = $sessionData['seller_hid_lng'];
//echo "<pre>"; print_r($sessionData); echo "</pre>"; 
// $ventureDetail =  $this->business_model->get_all_ventures($latitude,$longitude);
// echo "<pre>producResult"; print_r($producResult); echo "</pre>";
// echo "<pre>ventureDetail"; print_r($ventureDetail); echo "</pre>";
?>
<!-- Content Area -->
<div class="container">
	<div class="row">
		<div class="col-sm-6">
			<div class="breadcrumb">
				<?php if(!empty($sessionData['location_city'])) { 
						$location = explode(",",$sessionData['location_city']);
						$location_city = array_shift($location);
						$location_country = array_pop($location);

						$sellerLocation = explode(",",$sessionData['seller_hid_locationName']);
						$sellerLocation_city = array_shift($sellerLocation);
						
					 }
					?>				
				<ul>
					<li>Search</li>
					<li><a href="<?php echo  !empty($sessionData['category_id'])?site_url('area'):'javascript:void(0)' ; ?>"><?php echo !empty($sessionData['category_name'])?$sessionData['category_name']:'All Categoty'; ?></a></li>
					<li><a href="<?php echo site_url('category'); ?>"><?php echo !empty($sellerLocation_city)?$sellerLocation_city:''; ?></a></li>
					<li><?php echo (isset($_GET['name']) && !empty($_GET['name']))?$_GET['name']:''; ?></li>
				</ul>
			</div>
		</div>
		<div class="col-sm-offset-3 col-sm-2">   
<!--
			<div class="welcome">   <span>Hi Hydyr</span><br>
				<a href="#;">  Your Account</a>
			</div>
-->
	</div>
    <div class="col-sm-1">
	
    </div>
</div>
<div class="clearfix"></div>

<!--
<div class="heading"><h2>Search for <span><?php echo isset($_GET['name'])?$_GET['name']:''; ?></span></h2></div>
-->

    <div class="row">
        <div class="col-sm-3">
            <div class="left-panel">
			<form id="form-searchPage-filter" method="GET"	action="<?php echo site_url('search'); ?>" >
				<input type="hidden" id="page" value="search" name="page" />
				<input type="hidden" id="hid-name" value="<?php echo isset($_GET['name'])?$_GET['name']:''; ?>" name="name" />
				<input type="hidden" id="hid-sort_name" value="<?php echo isset($_GET['sort_name'])?$_GET['sort_name']:''; ?>" name="sort_name" />
				<input type="hidden" id="hid-sort_type" value="<?php echo isset($_GET['sort_type'])?$_GET['sort_type']:''; ?>" name="sort_type" />
				<?php /* if(isset($_GET)) { 
					//echo '<pre>'; print_r($_GET); echo '</pre>';
					
					//~ foreach($_GET as $key=>$value)
					//~ {
						//~ if($key != 'selected_type' && $key != 'vanture_list' && $key != 'price_list' && $key != 'category_list'  )
							//~ echo '<input type="hidden" name="'.$key.'" id="get-'.$key.'" value="'.$value.'" />';
					//~ 
					//~ }
				} */ ?>
				<input style="display:none" type="submit" value="click" />
<!--
				<div class="divider">
					<div class="form-group search-textBox">   
					
						<input type="text" value="<?php echo isset($_GET['name'])?$_GET['name']:''; ?>" placeholder="Search for a product or Venture" class="form-control" name="name" autocomplete="off" id="headerSearch-text">
						<ul class="headerSearch-HelpBox dropdown-menu" >
					
						</ul>
					</div>
				</div>
				<hr>
-->

				<div class="divider checkbox-list">
					<h4 class="widget-title">Category</h4>	
				<?php 	$allCategory =  $this->business_model->select_all_data('gc_categories'); 
						foreach($allCategory as $category)
						{
							if(isset($_GET['category_list'])  && in_array($category['id'],$_GET['category_list']))
							{
								$checked = "checked";
							}
							else
							{
								$checked = "";
							}
					?>
						<div class="checkbox"> <label> <input <?php echo $checked;  ?> type="checkbox" value="<?php echo $category['id'];  ?>" id="search-category_list-<?php echo $category['id']; ?>"  name="category_list[]" class="search-category_list" /><?php echo $category['name'];  ?></label>  </div>	
					<?php		
						}
					 ?>
					
				</div>
				<hr>

				<?php    if(isset($_GET['category_list'])  && in_array(3,$_GET['category_list'])) { $style = 'display:block'; }else { $style = 'display:none'; } ; ?>
				<div style="<?php echo $style; ?>"  class="divider checkbox-list">				   
						<h4 class="widget-title">Cuisine Selection</h4>
						<?php $allCuisine =  $this->business_model->select_all_data('gc_cuisine'); 
							$i=1;
							$cuisineNames =  array();
							foreach($allCuisine as $cuisine)
							{
								$cuisineNames[] = $cuisine['cuisine_name'];
								if($i==1)
									echo '<div class="shortCuisine" >';
								if($i==5)
								{
									echo '<a id="seeAllCuisinesLink" style="font-size:12px; color:#669934;" href="javascript:void(0)"><img src="'.site_url().'assets/front/images/arrow.png"> See all cuisines</a>';
									echo '</div><div style="display:none" class="allCuisine" >';
								}
								
								if($_GET['page']=='search' && isset($_GET['cuisine_list'])  && in_array($cuisine['cuisine_id'],$_GET['cuisine_list']))
								{
									$checked = "checked";
								}
								else
								{
									$checked = "";
								}																
						?>
								<div class="checkbox"> <label> <input class="search-cuisine_list" id="search-cuisine_list-<?php echo $cuisine['cuisine_id']; ?>" class=""  <?php echo $checked;  ?>  type="checkbox" name="cuisine_list[]" value="<?php echo $cuisine['cuisine_id']; ?>"  /><?php echo $cuisine['cuisine_name']; ?> </label> 
								<!--<span class="pull-right" id="cuisineCount_<?php echo $cuisine['cuisine_name']; ?>">(0)</span> -->
								</div>
						<?php																
								$i++;								
							}	
							echo '</div>';
						?>											
					</div>

				<div class="divider checkbox-list">
					<h4 class="widget-title">Venture list</h4>
					<?php if(!empty($ventureNames)) { 
					foreach($ventureNames as $venture_id=>$ventureName)
					{

						
						if($_GET['page']=='search' && isset($_GET['vanture_list'])  && in_array($venture_id,$_GET['vanture_list']))
						{
							$checked = "checked";
						}
						else 
						if(isset($index_vanture_list)  && in_array($venture_id,$index_vanture_list))
						{
							$checked = "checked";
						}
						else
						{
							$checked = "";
						}
					?>
					<div class="checkbox"> <label> <input <?php echo $checked;  ?> type="checkbox" id="search-vanture_list-<?php echo $venture_id; ?>" value="<?php echo $venture_id; ?>" name="vanture_list[]" class="search-vanture_list" /><?php echo $ventureName; ?></label>  </div>
					<?php } } ?>
				</div>
				<hr>
				
				<div class="divider checkbox-list">
					<h4 class="widget-title">Price</h4>	
					<?php  if(isset($_GET['price_list'])  && in_array('0-19',$_GET['price_list'])) { $checked = "checked"; }else{ $checked = "";  } 	?>			
					<div class="checkbox"> <label> <input <?php echo $checked;  ?> type="checkbox" value="0-19" id="search-price_list-0-19"  name="price_list[]" class="search-price_list" /><?php echo config_item('myCurrency'); ?>0 - <?php echo config_item('myCurrency'); ?>19</label>  </div>
					<?php  if(isset($_GET['price_list'])  && in_array('13-39',$_GET['price_list'])) { $checked = "checked"; }else{ $checked = "";  } 	?>			
					<div class="checkbox"> <label> <input <?php echo $checked;  ?> type="checkbox" value="13-39" id="search-price_list-13-39"  id="chKPriceList-13-39"  name="price_list[]" class="search-price_list" /><?php echo config_item('myCurrency'); ?>19 - <?php echo config_item('myCurrency'); ?>39</label>  </div>
					<?php  if(isset($_GET['price_list'])  && in_array('39-59',$_GET['price_list'])) { $checked = "checked"; }else{ $checked = "";  } 	?>			
					<div class="checkbox"> <label> <input <?php echo $checked;  ?> type="checkbox" value="39-59" id="search-price_list-39-59"  name="price_list[]" class="search-price_list" /><?php echo config_item('myCurrency'); ?>39 - <?php echo config_item('myCurrency'); ?>59</label>  </div>
					<?php  if(isset($_GET['price_list'])  && in_array('59-79',$_GET['price_list'])) { $checked = "checked"; }else{ $checked = "";  } 	?>			
					<div class="checkbox"> <label> <input <?php echo $checked;  ?> type="checkbox" value="59-79"  id="search-price_list-59-79"  name="price_list[]" class="search-price_list" /><?php echo config_item('myCurrency'); ?>59 - <?php echo config_item('myCurrency'); ?>79</label>  </div>
					<?php  if(isset($_GET['price_list'])  && in_array('79-109',$_GET['price_list'])) { $checked = "checked"; }else{ $checked = "";  } 	?>			
					<div class="checkbox"> <label> <input <?php echo $checked;  ?> type="checkbox" value="79-109" id="search-price_list-79-109"    name="price_list[]" class="search-price_list" /><?php echo config_item('myCurrency'); ?>79 - <?php echo config_item('myCurrency'); ?>109</label>  </div>
					<?php  if(isset($_GET['price_list'])  && in_array('109-139',$_GET['price_list'])) { $checked = "checked"; }else{ $checked = "";  } 	?>			
					<div class="checkbox"> <label> <input <?php echo $checked;  ?> type="checkbox" value="109-139" id="search-price_list-109-139"    name="price_list[]" class="search-price_list" /><?php echo config_item('myCurrency'); ?>109 - <?php echo config_item('myCurrency'); ?>139</label>  </div>		
				</div>
				<hr>

	<div class="divider checkbox-list">
						<h4 class="widget-title">Rating</h4>	
						<?php  if(isset($_GET['rating_list'])  && in_array('5',$_GET['rating_list']) ) { $checked = "checked"; }else{ $checked = "";  } 	?>			
							<div class="checkbox"> <label> <input <?php echo $checked;  ?> type="checkbox" value="5" id="search-rating_list-5"  name="rating_list[]" class="search-rating_list" />
								<div class="" >
									<img src="<?php echo site_url(); ?>assets/front/images/<?php echo !empty($ratingDetail)?'star3.png':'star2.png'; ?>">
									<img src="<?php echo site_url(); ?>assets/front/images/<?php echo !empty($ratingDetail)?'star3.png':'star2.png'; ?>">
									<img src="<?php echo site_url(); ?>assets/front/images/<?php echo !empty($ratingDetail)?'star3.png':'star2.png'; ?>">
									<img src="<?php echo site_url(); ?>assets/front/images/<?php echo !empty($ratingDetail)?'star3.png':'star2.png'; ?>">
									<img src="<?php echo site_url(); ?>assets/front/images/<?php echo !empty($ratingDetail)?'star3.png':'star2.png'; ?>">
								</div>  
						</label>  </div>
						<?php  if(isset($_GET['rating_list'])  && in_array('4',$_GET['rating_list'])) { $checked = "checked"; }else{ $checked = "";  } 	?>			
						<div class="checkbox"> <label> <input <?php echo $checked;  ?> type="checkbox" value="4" id="search-rating_list-4"  name="rating_list[]" class="search-rating_list" />
								<div class="" >
									<img src="<?php echo site_url(); ?>assets/front/images/<?php echo !empty($ratingDetail)?'star3.png':'star2.png'; ?>">
									<img src="<?php echo site_url(); ?>assets/front/images/<?php echo !empty($ratingDetail)?'star3.png':'star2.png'; ?>">
									<img src="<?php echo site_url(); ?>assets/front/images/<?php echo !empty($ratingDetail)?'star3.png':'star2.png'; ?>">
									<img src="<?php echo site_url(); ?>assets/front/images/<?php echo !empty($ratingDetail)?'star3.png':'star2.png'; ?>">
								</div>  						
						
						</label>  </div>												
						<?php  if(isset($_GET['rating_list'])  && in_array('3',$_GET['rating_list'])) { $checked = "checked"; }else{ $checked = "";  } 	?>			
						<div class="checkbox"> <label> <input <?php echo $checked;  ?> type="checkbox" value="3" id="search-rating_list-3"  name="rating_list[]" class="search-rating_list" />
								<div class="" >
									<img src="<?php echo site_url(); ?>assets/front/images/<?php echo !empty($ratingDetail)?'star3.png':'star2.png'; ?>">
									<img src="<?php echo site_url(); ?>assets/front/images/<?php echo !empty($ratingDetail)?'star3.png':'star2.png'; ?>">
									<img src="<?php echo site_url(); ?>assets/front/images/<?php echo !empty($ratingDetail)?'star3.png':'star2.png'; ?>">
								</div>  						
						
						</label>  </div>																
						<?php  if(isset($_GET['rating_list'])  && in_array('2',$_GET['rating_list'])) { $checked = "checked"; }else{ $checked = "";  } 	?>			
						<div class="checkbox"> <label> <input <?php echo $checked;  ?> type="checkbox" value="2" id="search-rating_list-2"  name="rating_list[]" class="search-rating_list" />
						
								<div class="" >
									<img src="<?php echo site_url(); ?>assets/front/images/<?php echo !empty($ratingDetail)?'star3.png':'star2.png'; ?>">
									<img src="<?php echo site_url(); ?>assets/front/images/<?php echo !empty($ratingDetail)?'star3.png':'star2.png'; ?>">
								</div>  						
						</label>  </div>																
						<?php  if(isset($_GET['rating_list'])  && in_array('1',$_GET['rating_list'])) { $checked = "checked"; }else{ $checked = "";  } 	?>			
						<div class="checkbox"> <label> <input <?php echo $checked;  ?> type="checkbox" value="1" id="search-rating_list-1"  name="rating_list[]" class="search-rating_list" />
								<div class="" >
									<img src="<?php echo site_url(); ?>assets/front/images/<?php echo !empty($ratingDetail)?'star3.png':'star2.png'; ?>">
								</div>  						
						
						</label>  </div>																
					</div>					
					<hr>	

					
					
					<div class="divider checkbox-list">
						<h4 class="widget-title">Delivery Fee</h4>	
						<?php // if(isset($_GET['deliveryFee_list'])  && '' == $_GET['deliveryFee_list']) { $checked = "checked"; }else{ $checked = "";  } 	?>			
						<!--<div class="checkbox"> <label> <input <?php echo $checked;  ?> type="checkbox" value="" id="search-deliveryFee_list-any"  name="deliveryFee_list" class="search-deliveryFee_list" />Both</label>  </div>-->
						<?php  if(isset($_GET['deliveryFee_list'])  && 'free' == $_GET['deliveryFee_list']) { $checked = "checked"; }else{ $checked = "";  } 	?>			
						<div class="checkbox"> <label> <input <?php echo $checked;  ?> type="checkbox" value="free" id="search-deliveryFee_list-free"  name="deliveryFee_list" class="search-deliveryFee_list" />Free</label>  </div>
						<?php  if(isset($_GET['deliveryFee_list'])  && 'paid' == $_GET['deliveryFee_list']) { $checked = "checked"; }else{ $checked = "";  } 	?>			
						<div class="checkbox"> <label> <input <?php echo $checked;  ?> type="checkbox" value="paid" id="search-deliveryFee_list-paid"  name="deliveryFee_list" class="search-deliveryFee_list" />Paid</label>  </div>												
					</div>
					<hr>
					<div class="divider checkbox-list">
						<h4 class="widget-title">Delivery Time</h4>	
						
						<?php  if(isset($_GET['deliveryTime_list'])  && '30' == $_GET['deliveryTime_list']) { $checked = "checked"; }else{ $checked = "";  } 	?>			
						<div class="checkbox"> <label> <input <?php echo $checked;  ?> type="checkbox" value="30" id="search-deliveryTime_list-30"  name="deliveryTime_list" class="search-deliveryTime_list" />30 min</label>  </div>
						<?php  if(isset($_GET['deliveryTime_list'])  && '45' == $_GET['deliveryTime_list']) { $checked = "checked"; }else{ $checked = "";  } 	?>			
						<div class="checkbox"> <label> <input <?php echo $checked;  ?> type="checkbox" value="45" id="search-deliveryTime_list-45"  name="deliveryTime_list" class="search-deliveryTime_list" />45 min</label>  </div>												
						<?php  if(isset($_GET['deliveryTime_list'])  && '60' == $_GET['deliveryTime_list']) { $checked = "checked"; }else{ $checked = "";  } 	?>			
						<div class="checkbox"> <label> <input <?php echo $checked;  ?> type="checkbox" value="60" id="search-deliveryTime_list-60"  name="deliveryTime_list" class="search-deliveryTime_list" />60 min</label>  </div>												
												
					</div>					
					<hr>					
											
					<div class="divider checkbox-list">
						<h4 class="widget-title">Payment method</h4>	
						<?php  if(isset($_GET['paymentMethod_list'])  && 'cash_on_delivery' == $_GET['paymentMethod_list']) { $checked = "checked"; }else{ $checked = "";  } 	?>			
						<div class="checkbox"> <label> <input <?php echo $checked;  ?> type="checkbox" value="cash_on_delivery" id="search-paymentMethod_list-cash_on_delivery"  name="paymentMethod_list" class="search-paymentMethod_list" />Cash on delivery</label>  </div>
						<?php  if(isset($_GET['paymentMethod_list'])  && 'card_on_delivery' == $_GET['paymentMethod_list']) { $checked = "checked"; }else{ $checked = "";  } 	?>			
						<div class="checkbox"> <label> <input <?php echo $checked;  ?> type="checkbox" value="card_on_delivery" id="search-paymentMethod_list-card_on_delivery"  name="paymentMethod_list" class="search-paymentMethod_list" />Credit/Debit Card on delivery</label>  </div>												
						<?php  if(isset($_GET['paymentMethod_list'])  && 'paypal' == $_GET['paymentMethod_list']) { $checked = "checked"; }else{ $checked = "";  } 	?>			
						<div class="checkbox"> <label> <input <?php echo $checked;  ?> type="checkbox" value="paypal" id="search-paymentMethod_list-paypal"  name="paymentMethod_list" class="search-paymentMethod_list" />Paypal</label>  </div>																
						<?php  if(isset($_GET['paymentMethod_list'])  && 'paytm' == $_GET['paymentMethod_list']) { $checked = "checked"; }else{ $checked = "";  } 	?>			
						<div class="checkbox"> <label> <input <?php echo $checked;  ?> type="checkbox" value="paytm" id="search-paymentMethod_list-paytm"  name="paymentMethod_list" class="search-paymentMethod_list" />Paytm</label>  </div>																
						<?php  if(isset($_GET['paymentMethod_list'])  && 'card_online' == $_GET['paymentMethod_list']) { $checked = "checked"; }else{ $checked = "";  } 	?>			
						<div class="checkbox"> <label> <input <?php echo $checked;  ?> type="checkbox" value="card_online" id="search-paymentMethod_list-card_online"  name="paymentMethod_list" class="search-paymentMethod_list" />Credit/Debit Card online</label>  </div>																												
					</div>					
					<hr>												

					

              </form> 
            </div>
        </div>
        <div class="col-sm-6 search-product-listing">
             <!--white-wrapper -->
            <div class="white-wrapper">
            
				<div class="filter"><h3>Filtered by:</h3>
					<?php echo $filterChecksHTML; ?>
					<?php $searchedCategoty =  $this->session->userdata('searchedCategoty'); ?>

					<!--<a class="reset" href="<?php echo site_url('search/?page=search').'&name='.$_GET['name'].'&category_list[]='.$searchedCategoty; ?>">Reset filters</a>-->


				</div>
				<!--
				<div class="sort">
					<h3>Sort by:</h3>
				
					<a onclick="searchProductOrderBy('name','<?php echo ($_GET['sort_type']=='asc')?'desc':'asc' ?>')" href="#" class="glyphicon glyphicon-arrow-<?php echo ($_GET['sort_type']=='asc')?'up':'down' ?>">Name</a>
					<a onclick="searchProductOrderBy('price','<?php echo ($_GET['sort_type']=='asc')?'desc':'asc' ?>')" href="#" class="glyphicon glyphicon-arrow-<?php echo ($_GET['sort_type']=='asc')?'up':'down' ?>">Price</a>
				</div>				
				-->
				<!--search result box -->
<?php 	if(!empty($producResult)) {
			$i = 0;
			foreach($producResult as $product)
			{	
				$images = (array) json_decode($product['images']);
				$array = array_values($images);	
				/* rating  */
				$where1 = array();
				$where1['product_id'] = $product['id'];
				$where1['user_id'] = $userDetail['user_id'];
				$ratingDetail = $this->business_model->select_data_where('gc_product_rating',$where1);	
				
				?>		
				<div class="search-product-box">
					<div class="product-star" >
					<?php 
						//echo $product['rating_avg']."    -".$product['id']."<br>";
						
						for($j = 1;$j <=5; $j++)
						{
							if($product['rating_avg']==0)
							{
								echo '<img src="'.site_url().'assets/front/images/empty_star.png">';
							}
							else if($product['rating_avg']>=$j)
							{
								echo '<img src="'.site_url().'assets/front/images/star3.png">';
							}
							else
							{
								echo '<img src="'.site_url().'assets/front/images/empty_star.png">';
							}
						}
					 ?>
					</div>

					<div class="product-pic" data-index="<?php echo $i?>" data-toggle="modal" data-target="#myModal-<?php echo $i?>" ><img src="<?php echo site_url(); ?>uploads/images/small/<?php echo $array[0]->filename; ?>"></div>
					<div class="product-review">
						<h1 data-index="<?php echo $i?>" data-toggle="modal" data-target="#myModal-<?php echo $i?>"><?php echo $product['name']; ?></h1>
						<p><?php echo $product['description']; ?></p>
						<div class="clearfix"></div>
						<div class="price-confirmation">
							<span>Price : <?php echo config_item('myCurrency'); ?><?php echo $product['price']; ?></span>
							<div class="button-wrapper">
								<button class="btn btn-danger btn-sm openAdonPupupBtn" data-index="<?php echo $i?>" data-toggle="modal" data-target="#myModal-<?php echo $i?>" title="Add to basket"><span class="glyphicon glyphicon-shopping-cart"></span> <span class="items">Add to cart</span></button>
								<input type="hidden" id="productList-qty-<?php echo $i?>" size="3" value="1" class="noZeronumbersOnly" /> 
							</div>
						</div>
					    <?php //if(!empty($userDetail) && empty($ratingDetail)) {
							// print_r($ratingDetail['rating'] );
							if(empty($userDetail)) {
								$ratingDisable = "true";
								$ratingTitle = "Please login to rate ";
							}
							else if(isset($ratingDetail['rating']))
							{
								$ratingDisable = "true";
								$ratingTitle = "You have already rated this product";
							}
							else 
							{
								$ratingDisable = "false";
								$ratingTitle ='';
							}
							 ?>
						<div class="search-rating-section" id="search-rating-section-<?php echo $product['id']; ?>">
							<a class="rateThisProduct" id="rateThisProduct" href="javascript:void(0)" >Rate this product</a>
							<?php if(empty($userDetail)) { echo '<a href="'.site_url('login/search').'/?'.$_SERVER["QUERY_STRING"].'" >'; } ?>
							<div class="ratingProduct"> 
								<!--	<input name="rating" value="0" id="rating_star" class="rating_star" type="hidden" productID="<?php echo $product['id']; ?>" />-->
								<input   data-disabled="<?php echo $ratingDisable; ?>" title="<?php echo $ratingTitle; ?>" value="<?php echo isset($ratingDetail['rating'])?$ratingDetail['rating']:'0'; ?>" type="number" id="input-<?php echo $product['id']; ?>" name="rating" class="rating" min=0 max=5 step=0.5 data-size="xs" >
							</div>
							<?php if(empty($userDetail)) { echo '</a>'; } ?>
						</div>	
						
					</div>
					<div class="clearfix"></div>
				</div>

<div id="myModal-<?php echo $i?>" tabindex="-1" class="modal fade" role="dialog">
	
	<form id="popupProductModel-form-<?php echo $i?>" method="POST" >
		<input type="hidden" name="venture_id" value="<?php echo isset($product['added_by_cust'])?$product['added_by_cust']:$product['added_by']; ?>" />
		<input type="hidden" name="product_id" value="<?php echo $product['id']; ?>" />
		<input type="hidden" name="product_name" value="<?php echo $product['name']; ?>" />
		<input type="hidden" id="product_price-<?php echo $i?>" name="product_price" value="<?php echo $product['price']; ?>" />
		<input type="hidden" id="product_main_price-<?php echo $i?>" value="<?php echo $product['price']; ?>" />
		
		<div class="modal-dialog"  role="document">
			<!-- Modal content-->
			<div class="modal-content">
				<div class="modal-header">
					
					<button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
					<h4 class="modal-title" id="myModalLabel">Add to Basket</h4>
				</div>
				<div class="modal-body">
					<div class="row addOnPopUPError" id="addOnPopUPError-<?php echo $i; ?>" style="display:none" ></div>
					<div class="row">
						<div class="col-sm-4">
							<div class="product-img"> <img class="imageEnlargeModelLink" src="<?php echo site_url(); ?>uploads/images/small/<?php echo $array[0]->filename; ?>" ></div>
							
							<div class="imageEnlargeModel" id="imageEnlargeModel-<?php echo $i?>" tabindex="-1" >
								<img src="<?php echo site_url(); ?>uploads/images/medium/<?php echo $array[0]->filename; ?>">            
							</div>							
							
							<div class="zoom"><a  href="javascript:void(0)"   ><img src="<?php echo site_url(); ?>assets/front/images/zoom.png"> Hover to enlarge</a></div>
						</div>
						<div class="col-sm-8">
							<!--product info -->
							<div class="product-info">
								<h1><?php echo $product['name']; ?></h1>
								<span class="popupProduct-price" id="productPriceLabel-<?php echo $i?>" ><?php echo config_item('myCurrency'); ?><?php echo $product['price']; ?></span> 
								<input type="number" id="popupProduct-qty-<?php echo $i; ?>" min="1"  class="form-control noZeronumbersOnly"   name="popupProduct_qty"  size="3" value="1" /><p>UNIT(S)</p>
							</div>
							<!--product info //-->
						</div>
					</div>		
					<br>
					<!--checkbox section -->
				<?php 
				if(!empty($product['addons'])) { 
					$addons = unserialize($product['addons']);
					foreach($addons as $addOn)
					{
						$nameArr = explode(" ",$addOn['mainaddonsname']);
						if(count($nameArr) > 1)
						{
							$name = strtolower(implode("_",$nameArr));
						}
						else
						{
							$name =  strtolower($addOn['mainaddonsname']);
						}
						//echo $name;
						
					?>												
					<div class="check-box-section">
						<h2>Your Choice Of <?php echo $addOn['mainaddonsname']; ?> <span> (Select maximum of <?php echo $addOn['mainaddoncnt']; ?> item<?php if($addOn['mainaddoncnt']>1) { ?>(s)<?php } ?>)</span></h2>
						<div class="row">
						<?php 	$j=1;
						foreach($addOn['subaddons'] as $singleItem) { ?>														
							<div class="col-sm-3">
								<div class="checkbox-txt">
									<input type="checkbox" data-addonIndex="<?php echo $j; ?>" data-index="<?php echo $i?>" data-adonName="<?php echo $name; ?>" data-adonName="<?php echo $name; ?>" data-adonCount="<?php echo $addOn['mainaddoncnt']; ?>"  class="adon-checkbox <?php echo $name; ?>" name="addOnPrice[<?php echo $name; ?>][]" value="<?php echo $singleItem['subaddonsprice']; ?>" /> <p><?php echo $singleItem['subaddonsname']; ?> [<?php echo $singleItem['subaddonsprice']; ?>]</p>
								</div>
							</div>
						<?php $j++; } ?>
							
						</div>
					</div>
				<?php	
					}
				} ?>												
				   <!--checkbox section //-->		
				</div>
				<div class="modal-footer">
					<!--<button type="button" class="btn btn-default" data-dismiss="modal">Back to menu</button>-->
					<button type="button" class="btn btn-default back-menu-btn" data-dismiss="modal">Back to menu</button>
					<button type="button" data-id="<?php echo $i; ?>"  class="btn btn-primary radius cart-addToBasketBtm" > <img src="<?php echo site_url(); ?>assets/front/images/basket-icon2.png">Add to basket</button>
				</div>
			</div>
	
	
		</div>
	</form>
</div>																								
				
				
				        
	<?php $i++;
			}
		}
		else
		{
		 ?>				
		 
		 <div class="note"><span>Not product found as per selected items</span></div>
		 <?php
		 
		 }?>
				<!--search result box //-->
            
            </div>
            <!--white-wrapper //-->
			<div class="row" ><?php echo $this->pagination->create_links(); ?></div>		
		</div>
		
		<div class="col-sm-3 pull-right">
				<?php $this->load->view('front/index/product_cart'); ?>
		</div>
	</div>
 
	</div>
<div class="clearfix"></div>
