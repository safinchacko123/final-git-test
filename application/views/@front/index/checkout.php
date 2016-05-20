<?php 	$sessionData =  $this->session->userdata('locationDetail');
		$userDetail =  $this->session->userdata('userDetail');
		$cartData =  $this->cart->contents(); 
		
		$where4 = array();
	 	$where4['user_id'] = $this->session->userdata('userDetail')['user_id'];
		$shippingResult = $this->business_model->select_data_where_result('gc_shipping_address',$where4);			
		$billingResult = $this->business_model->select_data_where_result('gc_billing_address',$where4);			
		if(empty($shippingResult))
		{	
			echo '<script> var checkoutAdressPopup = "active";  </script>';
		}
?>

<!-- Content Area -->
<div class="container">
 
	<div class="breadcrumb">
		<ul>
			<li><a href="<?php echo site_url('category') ?>"><?php echo !empty($sessionData['location_city'])?$sessionData['location_city']:$sessionData['location_zipcode']; ?></a></li>
			
			<li>Checkout</li>
		</ul>
	</div>
	<div class="clearfix"></div>
	<div class="heading"><h2> <span><?php echo !empty($sessionData['location_city'])?$sessionData['location_city']:$sessionData['location_zipcode']; ?></span></h2></div>
	<!--body area -->
	

	<form method="POST" id="form-checkout" action="<?php echo site_url('order'); ?>" onsubmit="return validateConfirmOrder()" >
	<div class="body-area">
		<div>Modify your order  <button type="button" class="btn btn-default">Modify</button>  <button type="button" data-toggle="modal" data-target="#modal-checkoutNewAddress"  class="btn btn-default">Add new address</button>
		
		</div>
<?php 
	if(isset($cartData) && !empty($cartData)) {  
		$cartData = $this->business_model->cart_order_by_vanture($cartData,'venture_id');
		$delivery_fee_array =  array();
?>			
		
			<table width="100%" border="0" cellspacing="0" cellpadding="0" class="order-table">
			<tr>
				<th>Item</th>
				<th>Price</th>
				<th>Unit</th>
				<th>Amount</th>
			</tr>
<?php	if(isset($cartData[0])) { unset($cartData[0]); }
	//echo "<pre>"; print_r($cartData); echo "</pre>";
				$j=0;
				foreach ($cartData as $venture_id=>$cart)
				{	
					if(!empty($cart))
					{
						$venture_name = $this->business_model->select_coulmn_single_value('company','gc_customers','id',$venture_id);
						$delivery_fee_res = $this->business_model->select_coulmn_single_value('delivery_fee','gc_venture_option','venture_id',$venture_id);
						$delivery_fee_res =  !empty($delivery_fee_res)?$delivery_fee_res:'0';						
						$delivery_fee_array[] = $delivery_fee_res;
						
?>				
			<tr >
				<td colspan="4" style="color:#0D8EFF" >
				<strong><?php echo $venture_name; ?></strong>
				</td>
			</tr>
	<?php 
			$ventureSubTot = array();
			foreach($cart as $singleItem) 
			{
				$ventureSubTot[] = $singleItem['subtotal'];
				
				if(!empty($singleItem['options']['addOns']))
				{
					
					$addOns = array_keys($singleItem['options']['addOns']); 
					//~ foreach($singleItem['options']['addOns'] as $key=>$value)
					//~ {
						//~ $addOns[] = $key;
					//~ }
					
					$addOnsText = ' ('.implode(",",$addOns).') ';
				}
				else{ $addOnsText = ''; }
?>					
				<tr>
					<td class="small-txt"><span><?php echo $singleItem['name']; ?></span><?php echo $addOnsText; ?></td>
					<td><?php echo config_item('myCurrency'); ?><?php echo number_format($singleItem['price'],2,".",","); ?></td>
					<td class="text-center p0"><?php echo $singleItem['qty']; ?></td>
					<td><?php echo config_item('myCurrency'); ?><?php echo number_format($singleItem['subtotal'],2,".",","); ?> </td>
					
				</tr>		
								
<?php 				
			}  ?>
				<tr>
					<td class="small-txt">
					
						<div class="col-sm-8">
							<div class="voucher">
								<a href="#"><img src="<?php echo site_url(); ?>assets/front/images/help.png"></a>
								<input class="form-control" name="" type="text">
								<a href="#" type="button" class="btn btn-default redeem">REDEEM</a>
							</div>
						</div>
					</td>
					<td></td>
					<td class="text-center p0">Delivery: 	</td>
					<td><?php echo config_item('myCurrency'); ?><?php echo number_format($delivery_fee_res,2,".",","); ?> </td>
				</tr>			
				<tr>
					<td class="small-txt">
					
						<div class="delivery-section">
							<p>
							<select required data-name = "<?php echo $venture_name; ?>"  class="paymentAddress-select" name="payment_methood">
								<option value="" >Select payment method</option>
								<option value="cod" >Cash on delivery</option>
								<option value="paypal" >Pay With Paypal</option>
								<option value="paytm" >Pay With Paytm</option>
							</select>
							</p>
							

							<p>
							<select required data-name = "<?php echo $venture_name; ?>" class="shippingAddress-select"  name="shipAddress_<?php echo $j; ?>">
								<option value="" > Shipping Address</option>
								<?php if(!empty($shippingResult)) { $i=0; 
									foreach($shippingResult as $accountAddress) { 
										$adress = $accountAddress['address_l1'].', '.$accountAddress['city'].', '.$accountAddress['state'];
										
									?>
									<option value="<?php echo $accountAddress['id']; ?>" ><?php echo $adress; ?></option>
								<?php $i++; } } ?>								
								
							</select>
							</p>
							<p>
							<select required data-name = "<?php echo $venture_name; ?>"  class="billingAddress-select" name="billingAddress_<?php echo $j; ?>">
								<option value="" > Billing Address</option>
								<?php if(!empty($billingResult)) { $i=0; 
									foreach($billingResult as $billing) { 
										$billingAdress = $billing['address_l1'].', '.$billing['city'].', '.$billing['state'];
										
									?>
									<option value="<?php echo $billing['id']; ?>" ><?php echo $billingAdress; ?></option>
								<?php $i++; } } ?>								
								
							</select>
							</p>							
			
							<div class="clearfix"></div>
							
						</div>					
					</td>
					<td></td>
					<td class="text-center p0">Total: 	</td>
					<td><?php echo config_item('myCurrency'); ?><?php echo number_format(array_sum($ventureSubTot)+$delivery_fee_res,2,".",","); ?> </td>
				</tr>			
<?php			
		} 
		$j++;
	}
	$delivery_fee = !empty($delivery_fee_array)?array_sum($delivery_fee_array):'0';
?>				

		</table>
		
<?php } ?>			
		<div class="row">
			<div class="col-sm-8">
<!--
				<div class="voucher">
					<a href="#"><img src="<?php echo site_url(); ?>assets/front/images/help.png"></a>
					<input class="form-control" name="" type="text">
					<a href="#" type="button" class="btn btn-default redeem">REDEEM</a>
				</div>
-->
			</div>
			<div class="col-sm-4">
				<table width="100%" border="0" cellspacing="0" cellpadding="0" class="total-amount"> 
<!--
					<tr class="large-txt">
						<td>Subtotal:</td>
						<td><?php echo config_item('myCurrency'); ?><?php echo number_format($this->cart->total(),2,".",","); ?> </td>
					</tr>
					<tr>
						<td>Delivery Fee:</td>
						<td><?php echo config_item('myCurrency'); ?><?php echo number_format($delivery_fee,2,".",","); ?> </td>
					</tr>
-->
					<tr class="large-txt">
						<td>Grand Total:</td>
					<td><?php echo config_item('myCurrency'); ?><?php $gtotal =  $this->cart->total()+$delivery_fee; echo  number_format($gtotal,2,".",","); ?></td>
					</tr>
				</table>
			</div>
			
			<div class="clearfix"></div>
			<!--delivery section -->
			<div class="delivery-section">
<!--
				<p>Select Delivery Time: <input name="" type="radio" value=""> <span>Now</span></p>
				<p>Select Payment Method: <input name="" type="radio" value=""> <span>Cash (Pay cash)</span></p>
-->
				<div class="clearfix"></div>
				<div class="special-request">
					<p>Special request:</p>
					<textarea name="" cols="" id="text-note" class="form-control" placeholder="Please enter your special requests or order details here. You can also save your note for express use later." rows=""></textarea>
				</div>
			</div>
			<!--delivery section //-->
			<div class="select-note">
				<select class="form-control" id="note-list" name="note_list" >
					<option value="" >Select Note</option>
					<?php if(!empty($notesData)) { 
						foreach($notesData as $notes)
						{
					?>
							<option value="<?php echo $notes['note_id'] ;?>"><?php echo $notes['note_text'] ;?></option>
					<?php } 
					} ?>	
				</select>				
				
				<a href="javascript:void(0)" id="btn-saveNotes">Save this note</a>
			</div>
			<div class="clearfix"></div>
			<div class="order-confirm-bottom">
				<div class="row">
					<div class="col-sm-9">
						<p>Please check your order details before hitting the "Place Order" button as all orders are processed immediately</p>
					</div>
					<div class="col-sm-3">
						<input type="submit"  class="btn btn-default placeOrderBtn" value="PLACE ORDER" />
						<a style="display:none" href="<?php echo site_url('order'); ?>" type="button" class="btn btn-default"><img src="<?php echo site_url(); ?>assets/front/images/tick2.png"> PLACE ORDER</a>
					</div>
				</div>
		
		</div>
	</div>

</div>
 <!--body area //-->
	</form>
</div>
<div class="clearfix"></div>

<div id="modal-checkoutAddress" tabindex="-1" class="modal fade" role="dialog">
	<div class="modal-dialog"  role="document">
		<!-- Modal content-->
		<div class="modal-content">
			<form class="form-horizontal" id="form-checkoutAddress" action="<?php site_url(); ?>index/save_checkoutAddress" role="form" method="POST">
			<div class="modal-header">
				
<!--				<button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>-->
				<h4 class="modal-title" id="myModalLabel">Shipping & Billing Address</h4>
			</div>
			<div class="modal-body">
				<div class="row checkoutAddress" id="checkoutAddress-error" style="display:none" ></div>
				<div class="row">
					
						<div class="col-sm-6">
							<h2>Billing Address</h2>
							
							<div class="form-group">
								<label class="control-label col-sm-4" for="email">First name:</label>
								<div class="col-sm-8">
									<input type="text" class="form-control" required id="billing_firstname"  name="billing_firstname" >
								</div>
							</div>				
							<div class="form-group">
								<label class="control-label col-sm-4" for="email">Last name:</label>
								<div class="col-sm-8">
									<input type="text" class="form-control" required id="billing_lastname"  name="billing_lastname">
								</div>
							</div>
							<div class="form-group">
								<label class="control-label col-sm-4" for="email">Address 1</label>
								<div class="col-sm-8">
									<input type="text"  class="form-control" required id="billing_address_l1"  name="billing_address_l1">
								</div>
							</div>
							<div class="form-group">
								<label class="control-label col-sm-4" for="email">Address 2</label>
								<div class="col-sm-8">
									<input type="text" class="form-control"  id="billing_address_l2"  name="billing_address_l2">
								</div>
							</div>
							<div class="form-group">
								<label class="control-label col-sm-4" for="email">city</label>
								<div class="col-sm-8">
									<input type="text" class="form-control" required id="billing_city"  name="billing_city">
								</div>
							</div>
							<div class="form-group">
								<label class="control-label col-sm-4" for="email">state</label>
								<div class="col-sm-8">
									<input type="text" class="form-control" required id="billing_state"  name="billing_state">
								</div>
							</div>
							<div class="form-group">
								<label class="control-label col-sm-4" for="email">Zip code</label>
								<div class="col-sm-8">
									<input type="text" class="form-control" required id="billing_zipcode"  name="billing_zipcode">
								</div>
							</div>																			
							<div class="form-group">
								<label class="control-label col-sm-4" for="email">Country</label>
								<div class="col-sm-8">
									<input type="text" class="form-control" required id="billing_country"  name="billing_country">
								</div>
							</div>
		
							<div class="form-group">
								<label class="control-label col-sm-12" for="email">Allow same for shiipng <input type="checkbox" class="form-control" name="chk_same_address" id="chk_same_address"  ></label>
								<!--<div class="col-sm-8">
									
								</div> -->
							</div>							
						</div>

						<div class="col-sm-6">
						<h2>Shipping Address</h2>

							<div class="form-group">
								<label class="control-label col-sm-4" for="email">First name:</label>
								<div class="col-sm-8">
									<input type="text" class="form-control" required id="shipping_firstname"  name="shipping_firstname" >
								</div>
							</div>				
							<div class="form-group">
								<label class="control-label col-sm-4" for="email">Last name:</label>
								<div class="col-sm-8">
									<input type="text" class="form-control" required id="shipping_lastname"  name="shipping_lastname">
								</div>
							</div>

							<div class="form-group">
								<label class="control-label col-sm-4" for="email">Address 1</label>
								<div class="col-sm-8">
									<input type="text" class="form-control" required id="shipping_address_l1"  name="shipping_address_l1">
								</div>
							</div>
							<div class="form-group">
								<label class="control-label col-sm-4" for="email">Address 2</label>
								<div class="col-sm-8">
									<input type="text" class="form-control"  id="shipping_address_l2"  name="shipping_address_l2">
								</div>
							</div>
							<div class="form-group">
								<label class="control-label col-sm-4" for="email">city</label>
								<div class="col-sm-8">
									<input type="text" class="form-control" required id="shipping_city"  name="shipping_city">
								</div>
							</div>
							<div class="form-group">
								<label class="control-label col-sm-4" for="email">state</label>
								<div class="col-sm-8">
									<input type="text" class="form-control" required id="shipping_state"  name="shipping_state">
								</div>
							</div>
							<div class="form-group">
								<label class="control-label col-sm-4" for="email">Zip code</label>
								<div class="col-sm-8">
									<input type="text" class="form-control" required id="shipping_zipcode"  name="shipping_zipcode">
								</div>
							</div>							
							<div class="form-group">
								<label class="control-label col-sm-4" for="email">Country</label>
								<div class="col-sm-8">
									<input type="text" class="form-control" required id="shipping_country"  name="shipping_country">
								</div>
							</div>
						
					</div>
									
					
				</div>
			</div>
			<div class="modal-footer">
				<button type="submit" data-id=""  class="btn btn-primary radius btn-checkoutAddress" > Submit</button>
			</div>
			</form>
		</div>


	</div>

</div>																								
	


<div id="modal-checkoutNewAddress" tabindex="-1" class="modal fade" role="dialog">
	<div class="modal-dialog"  role="document">
		<!-- Modal content-->
		<div class="modal-content">
			<form class="form-horizontal" id="form-checkoutAddress" action="<?php site_url(); ?>index/save_checkoutAddress" role="form" method="POST">
			<div class="modal-header">
				
<!--				<button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>-->
				<h4 class="modal-title" id="myModalLabel">New Shipping Address</h4>
			</div>
			<div class="modal-body">
				<div class="row checkoutAddress" id="checkoutAddress-error" style="display:none" ></div>
				<div class="row">
					
			
						<div class="col-sm-12">
						<h2>Shipping Address</h2>

							<div class="form-group">
								<label class="control-label col-sm-4" for="email">First name:</label>
								<div class="col-sm-8">
									<input type="text" class="form-control" required id="shipping_firstname"  name="shipping_firstname" >
								</div>
							</div>				
							<div class="form-group">
								<label class="control-label col-sm-4" for="email">Last name:</label>
								<div class="col-sm-8">
									<input type="text" class="form-control" required id="shipping_lastname"  name="shipping_lastname">
								</div>
							</div>

							<div class="form-group">
								<label class="control-label col-sm-4" for="email">Address 1</label>
								<div class="col-sm-8">
									<input type="text" class="form-control" required id="shipping_address_l1"  name="shipping_address_l1">
								</div>
							</div>
							<div class="form-group">
								<label class="control-label col-sm-4" for="email">Address 2</label>
								<div class="col-sm-8">
									<input type="text" class="form-control"  id="shipping_address_l2"  name="shipping_address_l2">
								</div>
							</div>
							<div class="form-group">
								<label class="control-label col-sm-4" for="email">city</label>
								<div class="col-sm-8">
									<input type="text" class="form-control" required id="shipping_city"  name="shipping_city">
								</div>
							</div>
							<div class="form-group">
								<label class="control-label col-sm-4" for="email">state</label>
								<div class="col-sm-8">
									<input type="text" class="form-control" required id="shipping_state"  name="shipping_state">
								</div>
							</div>
							<div class="form-group">
								<label class="control-label col-sm-4" for="email">Zip code</label>
								<div class="col-sm-8">
									<input type="text" class="form-control" required id="shipping_zipcode"  name="shipping_zipcode">
								</div>
							</div>							
							<div class="form-group">
								<label class="control-label col-sm-4" for="email">Country</label>
								<div class="col-sm-8">
									<input type="text" class="form-control" required id="shipping_country"  name="shipping_country">
								</div>
							</div>
						
					</div>
									
					
				</div>
			</div>
			<div class="modal-footer">
				<button type="button" class="btn btn-default back-menu-btn" data-dismiss="modal">Cancel</button>
				<button type="submit" data-id=""  class="btn btn-primary radius btn-checkoutAddress" > Submit</button>
			</div>
			</form>
		</div>


	</div>

</div>																								
	
