<h3><span><?php echo $heading; ?></span></h3>
<?php
//	echo "<pre>hj"; print_r($productResult); echo "</pre>";
if(isset($productResult) && !empty($productResult)) { 
	$i=1;
	foreach($productResult as $product)
	{
		$images = (array) json_decode($product['images']);
		$array = array_values($images);									 
?>
<div class="row">
	<div class="product-box">
		<div class="col-sm-5">
			<div class="product-img"><img src="<?php echo site_url(); ?>uploads/images/small/<?php echo $array[0]->filename; ?>"></div>
		</div>
		<div class="col-sm-7">
			<div class="pro-des">
				<h4><a class="openAdonPupupBtn" data-index="<?php echo $i?>" href="javascript:void(0)" data-toggle="modal" data-target="#myModal-<?php echo $i?>" ><?php echo $product['name']; ?></a></h4>
				<p><?php echo $product['description']; ?></p>
				<div class="add-price">
					<input type="text" id="productList-qty-<?php echo $i?>" size="3" value="1" class="noZeronumbersOnly" /> 
					<a class="openAdonPupupBtn" data-index="<?php echo $i?>" href="javascript:void(0)" data-toggle="modal" data-target="#myModal-<?php echo $i?>" ><img src="<?php echo site_url(); ?>assets/front/images/plus-icon.jpg"></a> <?php echo config_item('myCurrency'); ?><?php echo $product['price']; ?></span> 
				</div>
			</div>
		</div>
	</div>
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


<?php
	$i++; 
	}
} ?>
