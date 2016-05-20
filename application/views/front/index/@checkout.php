<?php 	$sessionData =  $this->session->userdata('locationDetail');
		$userDetail =  $this->session->userdata('userDetail');
		$cartData =  $this->cart->contents(); 
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
	<div class="body-area">
		<div>Modify your order  <button type="button" class="btn btn-default">Modify</button></div>
<?php 
	if(isset($cartData) && !empty($cartData)) {  
		$cartData = $this->business_model->cart_order_by_vanture($cartData,'venture_id');
		$delivery_fee_array =  array();
?>			
		<form method="POST" action="<?php echo site_url('order'); ?>" >
			<table width="100%" border="0" cellspacing="0" cellpadding="0" class="order-table">
			<tr>
				<th>Item</th>
				<th>Price</th>
				<th>Unit</th>
				<th>Amount</th>
			</tr>
<?php	if(isset($cartData[0])) { unset($cartData[0]); }
				foreach ($cartData as $venture_id=>$cart)
				{	
					if(!empty($cart))
					{
						$venture_name = $this->business_model->select_coulmn_single_value('company','gc_customers','id',$venture_id);
						$delivery_fee_res = $this->business_model->select_coulmn_single_value('delivery_fee','gc_venture_option','venture_id',$venture_id);
		
						$delivery_fee_array[] =  !empty($delivery_fee_res)?$delivery_fee_res:'0';						
						
?>				
			<tr >
				<td colspan="4" style="color:#0D8EFF" >
				<strong><?php echo $venture_name; ?></strong>
				</td>
			</tr>
	<?php 	foreach($cart as $singleItem) 
			{
			
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
			}
		} 
	}
	$delivery_fee = !empty($delivery_fee_array)?array_sum($delivery_fee_array):'0';
?>				

		</table>
		</form>
<?php } ?>			
		<div class="row">
			<div class="col-sm-8">
				<div class="voucher">
					<a href="#"><img src="<?php echo site_url(); ?>assets/front/images/help.png"></a>
					<input class="form-control" name="" type="text">
					<a href="#" type="button" class="btn btn-default redeem">REDEEM</a>
				</div>
			</div>
			<div class="col-sm-4">
				<table width="100%" border="0" cellspacing="0" cellpadding="0" class="total-amount"> 
					<tr class="large-txt">
						<td>Subtotal:</td>
						<td><?php echo config_item('myCurrency'); ?><?php echo number_format($this->cart->total(),2,".",","); ?> </td>
					</tr>
					<tr>
						<td>Delivery Fee:</td>
						<td><?php echo config_item('myCurrency'); ?><?php echo number_format($delivery_fee,2,".",","); ?> </td>
					</tr>
					<tr class="large-txt">
						<td>Total Amount:</td>
					<td><?php echo config_item('myCurrency'); ?><?php $gtotal =  $this->cart->total()+$delivery_fee; echo  number_format($gtotal,2,".",","); ?></td>
					</tr>
				</table>
			</div>
			
			<div class="clearfix"></div>
			<!--delivery section -->
			<div class="delivery-section">
				<p>Select Delivery Time: <input name="" type="radio" value=""> <span>Now</span></p>
				<p>Select Payment Method: <input name="" type="radio" value=""> <span>Cash (Pay cash)</span></p>
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
					<a href="<?php echo site_url('order'); ?>" type="button" class="btn btn-default"><img src="<?php echo site_url(); ?>assets/front/images/tick2.png"> PLACE ORDER</a>
				</div>
			</div>
		</div>
	</div>
</div>
 <!--body area //-->
 
</div>
<div class="clearfix"></div>

