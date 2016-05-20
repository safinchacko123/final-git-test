<?php $cartData =  $this->cart->contents(); 	?>	
<div class="my-basket productPage-cartSection">	
	<h4><img align="left" src="<?php echo site_url(); ?>assets/front/images/basket-icon.png">&nbsp; My Basket</h4>
<!-- Cart part-->
<?php 
	if(isset($cartData) && !empty($cartData) && $this->cart->total_items()>0) {   
	$cartData = $this->business_model->cart_order_by_vanture($cartData,'venture_id');
	$delivery_fee_array =  array();
	$minAmtarray =  array();
	if(isset($cartData[0])) { unset($cartData[0]); }
	//echo "<pre>"; print_r($cartData); echo "</pre>";
	foreach ($cartData as $venture_id=>$cart)
	{
		if(!empty($cart))
		{
			$venture_name = $this->business_model->select_coulmn_single_value('company','gc_customers','id',$venture_id);
			$min_delivery_amount = $this->business_model->select_coulmn_single_value('min_delivery_amount','gc_venture_option','venture_id',$venture_id);
	
			$delivery_fee = !empty($delivery_fee_res)?$delivery_fee_res:'0';
	
			$delivery_fee_res = $this->business_model->select_coulmn_single_value('delivery_fee','gc_venture_option','venture_id',$venture_id);		
			$delivery_fee_array[] =  !empty($delivery_fee_res)?$delivery_fee_res:'0';
	?>
				<div class="links"><a href="#"><?php echo $venture_name; ?></a> </div>
				<?php
				$singleVentureTotal = array();

				 foreach($cart as $singleItem) 
				{
					$singleVentureTotal[] = $singleItem['subtotal'];
				?>
					<!--item -->
					<div class="item-2">
						<div class="item-name"><?php echo $singleItem['name']; ?></div>
						<div class="item-field"><input id="cart-qty-<?php echo $singleItem['rowid']; ?>" class="form-control cart-qty noZeronumbersOnly" type="text" size="3" value="<?php echo $singleItem['qty']; ?>" /></div>
						<div class="item-price"><?php echo $singleItem['subtotal']; ?> <a id="cart-deleteItem-<?php echo $singleItem['rowid']; ?>" class="cart-deleteItem" href="javascript:void(0)" ><img src="<?php echo site_url(); ?>assets/front/images/remove-icon.png"></a></div>
						<div class="clearfix"></div>
					</div>
					<!--item //-->				
				<?php
				}
				//echo array_sum($singleVentureTotal).' '.$min_delivery_amount."<br>";
				if(array_sum($singleVentureTotal)<$min_delivery_amount)
				{
					$minAmtarray[] = 'Minimum order amount does not reach for '.$venture_name;	
				}
				?>
<?php
		}
	}
	$delivery_fee = !empty($delivery_fee_array)?array_sum($delivery_fee_array):'0';
?>	

<table width="100%" border="0" cellspacing="0" cellpadding="0" class="total">
<tr>
	<td>Subtotal:</td>
	<th><?php echo config_item('myCurrency'); ?><?php echo number_format($this->cart->total(),2,".",","); ?> </th>
</tr>
<tr>
	<td>Delivery Fee:</td>
	<th><?php echo config_item('myCurrency'); ?><?php echo number_format($delivery_fee,2,".",","); ?> </th>
</tr>
<tr>
	<td>Total:</td>
	<th><?php echo config_item('myCurrency'); ?><?php $gTotal =  $this->cart->total()+$delivery_fee;  echo  number_format($gTotal,2,".",","); ?> </th>
</tr>
</table>
<?php if(!empty($minAmtarray)){ ?>
	<a href="javascript:void(0)" type="button" data-target="#min_order_amountDialog" data-toggle="modal" class="btn btn-default confirm cart-confirm-order-btn"><img src="<?php echo site_url(); ?>assets/front/images/tick.png"> Confirm Basket</a>
<?php }else { ?>
<a href="<?php echo site_url('checkout'); ?>" type="button" class="btn btn-default confirm cart-confirm-order-btn"><img src="<?php echo site_url(); ?>assets/front/images/tick.png"> Confirm Basket</a>
<?php } ?>
<div class="clear-basket"><a href="javascript:void(0)" class="clear-cart-basket"><img src="<?php echo site_url(); ?>assets/front/images/small-icon.png"> Clear Basket</a></div>
	
<?php	
	} 
	else 
	{ ?>
		<div class="col-xs-12" ><h3>Cart is empty</h3></div>	
<?php 
	} ?>
<!-- cart part -->
</div>	

 <!-- Modal content-->
	<div class="modal fade text-center abc" id="min_order_amountDialog" role="dialog">
    <div class="modal-dialog">
      <div class="modal-content">
        <div class="modal-body">
			<br>
			<h4 class="text-danger"><strong><?php echo implode('<br>',$minAmtarray); ?></strong></h4>
			<div class="clearfix"></div>
			<br>
			<button type="button" class="btn btn-default btn-lg" data-dismiss="modal">Close</button>
			<div class="clearfix"></div>
			<br>
        </div>     
      </div>      
    </div>
  </div>
   <!-- Modal content-->
