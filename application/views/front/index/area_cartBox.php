<?php $cartData =  $this->cart->contents(); ?>

<?php if(isset($cartData) && !empty($cartData) && $this->cart->total_items()>0) { ?>	
<div class="price"><?php echo config_item('myCurrency'); ?><span id="topCartPrc"><?php echo $this->cart->total(); ?></span></div>
<div class="cart-dropdown">
	<h4><span><?php echo $this->cart->total_items(); ?></span>&nbsp; Costo</h4>
	<div class="clearfix"></div>
	<div class="next-delivery">
		<div class="row">
			<div class="col-sm-8">Next develivery within 2 hours</div>
				<div class="col-sm-4">Free</div>
			</div>
		</div>
	<div class="first-delivery">Your first delivery is free</div>
	<div class="area-cartItem-scrillBox" >
<?php 
if(isset($cartData) && !empty($cartData) && $this->cart->total_items()>0) {   
	$cartData = $this->business_model->cart_order_by_vanture($cartData,'venture_id');
	$delivery_fee_array =  array();
	$minAmtarray =  array();
	if(isset($cartData[0])) { unset($cartData[0]); }
	foreach ($cartData as $venture_id=>$cart)
	{
		if(!empty($cart))
		{
			$venture_name = $this->business_model->select_coulmn_single_value('company','gc_customers','id',$venture_id);
			$delivery_fee_res = $this->business_model->select_coulmn_single_value('delivery_fee','gc_venture_option','venture_id',$venture_id);		
			$min_delivery_amount = $this->business_model->select_coulmn_single_value('min_delivery_amount','gc_venture_option','venture_id',$venture_id);
			$delivery_fee_array[] =  !empty($delivery_fee_res)?$delivery_fee_res:'0';			
	?>		<!--item -->
			<div class="first-delivery"><strong><?php echo $venture_name; ?></strong></div>
			<?php
			$singleVentureTotal = array();
			foreach($cart as $singleItem) 
			{
				$singleVentureTotal[] = $singleItem['subtotal'];
			?>		
				<div class="add-item">
					<div class="number"><?php echo $singleItem['qty']; ?></div>
					<div class="item-img"><img src="<?php echo $singleItem['options']['product_image']; ?>" height="44px" width="44px" /></div>
					<div class="des"><?php echo $singleItem['name']; ?><br>30 ct</div>
					<div class="item-price2"><?php echo config_item('myCurrency'); ?><?php echo $singleItem['subtotal']; ?> </div>
					<div class="clearfix"></div>
				</div>
			<?php
			}

			if(array_sum($singleVentureTotal)<$min_delivery_amount)
			{
				$minAmtarray[] = 'Minimum order amount does not reach for '.$venture_name;	
			}
			?>		
		<!--item //-->
<?php
		}
	}
}
$delivery_fee = !empty($delivery_fee_array)?array_sum($delivery_fee_array):'0';
?>	
</div>
	<!--item //-->
	<table width="100%" border="0" cellspacing="0" cellpadding="0" class="total">
		<tr>
			<td>Subtotal:</td>
			<th><?php echo config_item('myCurrency'); ?><?php echo $this->cart->total(); ?> </th>
		</tr>
		<tr>
			<td>Delivery Fee:</td>
			<th><?php echo config_item('myCurrency'); ?><?php echo $delivery_fee; ?></th>
		</tr>
		<tr>
			<td>Total:</td>
			<th><?php echo config_item('myCurrency'); ?><?php echo $this->cart->total()+$delivery_fee; ?></th>
		</tr>
	</table>
	<?php if(!empty($minAmtarray)){ ?>
	<a href="javascript:void(0)" data-target="#min_order_amountDialog" data-toggle="modal" class="btn btn-default checkout-btn"> Checkout <span><?php echo config_item('myCurrency'); ?><?php echo $this->cart->total()+$delivery_fee; ?> </span></a>
	<?php }else { ?>
	<a href="<?php echo site_url(); ?>checkout" type="button" class="btn btn-default checkout-btn"> Checkout <span><?php echo config_item('myCurrency'); ?><?php $grandTotal = $this->cart->total()+$delivery_fee;  echo $grandTotal; ?> </span></a>
	<?php } ?>

</div>
<script>
var grandTotal = "<?php echo $this->cart->total()+$delivery_fee; ?>";

 document.getElementById("topCartPrc").innerHTML = grandTotal;
</script>


<?php
	}
else 
	{ ?>
	<div class="price">0</div>
	<div class="cart-dropdown">
	<div class="first-delivery">Your cart is empty</div>
</div>
<?php 
	} ?>	
