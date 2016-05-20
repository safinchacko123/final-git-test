<?php
	$where4 = array();
	$where4['user_id'] = $this->session->userdata('userDetail')['user_id'];
	$shippingResult = $this->business_model->select_data_where_result('gc_billing_address',$where4);	
	//echo '<pre>'; print_r($shippingResult); echo '</pre>';
?>


<!--<a href="javascript:void(0)" id="billingAddress-addnewBtn" data-target="billingAddress-Add" class="btnaddnew"><i class="fa fa-pencil"></i>Add new address</a>-->

<br>

<div class="clearfix"></div>

<div <?php echo !empty($shippingResult)?'style="display:none"':''; ?> class="billingAddress-Add " >
	<div class="acinfoitem">
		<h2>New Address</h2>
	</div>
	<form id="form-billingAddressInsert" method="POST" >
		<input type="hidden" name="submitType" id="submitType" value="insert" />
		<input type="hidden"  id="billingAddress-billingId" name="id" value="" />

		<div class="acinfoitem">
			<span class="lbltxt col-xs-3">Firstname:</span><span class="lblinfo"><input type="text" name="firstname"   value="" /></span>
		</div>							
		<div class="acinfoitem">
			<span class="lbltxt col-xs-3">Lastname:</span><span class="lblinfo"><input type="text" name="lastname"   value="" /></span>
		</div>	
		<div class="acinfoitem">
			<span class="lbltxt col-xs-3">Address line 1:</span><span class="lblinfo"><input type="text" name="address_l1"   value="" /></span>
		</div>
		<div class="acinfoitem">
			<span class="lbltxt col-xs-3">Address line 2:</span><span class="lblinfo"><input type="text" name="address_l2"   value="" /></span>
		</div>
		<div class="acinfoitem">
			<span class="lbltxt col-xs-3">City:</span><span class="lblinfo"><input type="text" name="city"   value="" /></span>
		</div>
		<div class="acinfoitem">
			<span class="lbltxt col-xs-3">State:</span><span class="lblinfo"><input type="text" name="state"   value="" /></span>
		</div>
		<div class="acinfoitem">
			<span class="lbltxt col-xs-3">Zip Code:</span><span class="lblinfo"><input type="text" name="zipcode"   value="" /></span>
		</div>
		<div class="acinfoitem">
			<span class="lbltxt col-xs-3">Country:</span><span class="lblinfo"><input type="text" name="country"   value="" /></span>
		</div>						
		<div class="acinfoitem">
			<span class="lbltxt"><input  type="button" class="billingAddress-update btn btn-primary" data-target="billingAddressInsert" id="billingAddress-save" value="Save changes" /></span>
		</div>											
	</form>
</div>						
<br><br>
<div class="clearfix"></div>



<?php //echo '<pre>'; print_r($billingResult); echo '</pre>';
 if(!empty($shippingResult)) { 	$i=0; ?>
<?php foreach($shippingResult as $billingAddress) { ?>	
<div class="billingAddress_<?php echo $i; ?>-Info" >
	<div class="acinfoitem">
		<h2>Address <?php echo $i+1; ?><a href="javascript:void(0)" id="billingAddress_<?php echo $i; ?>-changeBtn" data-target="billingAddress_<?php echo $i; ?>" class="btnchange"><i class="fa fa-pencil"></i>Change</a></h2>
	</div>						
						
	<div class="acinfoitem">
		<span class="lbltxt">First name:</span><span class="lblinfo"id="billingAddress_<?php echo $i; ?>-firstname" ><?php echo !empty($billingAddress['firstname'])?$billingAddress['firstname']:''; ?></span>
	</div>	
	<div class="acinfoitem">
		<span class="lbltxt">Last name:</span><span class="lblinfo"id="billingAddress_<?php echo $i; ?>-lastname" ><?php echo !empty($billingAddress['lastname'])?$billingAddress['lastname']:''; ?></span>
	</div>		
	<div class="acinfoitem">
		<span class="lbltxt">Address line 1:</span><span class="lblinfo"id="billingAddress_<?php echo $i; ?>-address_l1" ><?php echo !empty($billingAddress['address_l1'])?$billingAddress['address_l1']:''; ?></span>
	</div>
	<div class="acinfoitem">
		<span class="lbltxt">Address line 2:</span><span class="lblinfo" id="billingAddress_<?php echo $i; ?>-address_l2"><?php echo !empty($billingAddress['address_l2'])?$billingAddress['address_l2']:''; ?></span>
	</div>
	<div class="acinfoitem">
		<span class="lbltxt">City:</span><span class="lblinfo" id="billingAddress_<?php echo $i; ?>-city"><?php echo !empty($billingAddress['city'])?$billingAddress['city']:''; ?></span>
	</div>
	<div class="acinfoitem">
		<span class="lbltxt">State:</span><span class="lblinfo" id="billingAddress_<?php echo $i; ?>-state"><?php echo !empty($billingAddress['state'])?$billingAddress['state']:''; ?></span>
	</div>
	<div class="acinfoitem">
		<span class="lbltxt">Zip Code:</span><span class="lblinfo" id="billingAddress_<?php echo $i; ?>-zipcode"><?php echo !empty($billingAddress['zipcode'])?$billingAddress['zipcode']:''; ?></span>
	</div>
	<div class="acinfoitem">
		<span class="lbltxt">Country:</span><span class="lblinfo" id="billingAddress_<?php echo $i; ?>-country"><?php echo !empty($billingAddress['country'])?$billingAddress['country']:''; ?></span>
	</div>
</div>
<div class="billingAddress_<?php echo $i; ?>-Edit editSection" >
	<form id="form-billingAddress_<?php echo $i; ?>" method="POST" >
		<input type="hidden" name="submitType" id="submitType_<?php echo $i; ?>"  value="update" />
		
		<input type="hidden" id="billingAddress_<?php echo $i; ?>-billingId" name="id" value="<?php echo !empty($billingAddress['id'])?$billingAddress['id']:''; ?>" />

		<div class="acinfoitem">
			<span class="lbltxt col-xs-3">Firstname:</span><span class="lblinfo"><input type="text" name="firstname"   value="<?php echo !empty($billingAddress['firstname'])?$billingAddress['firstname']:''; ?>" /></span>
		</div>							
		<div class="acinfoitem">
			<span class="lbltxt col-xs-3">Lastname:</span><span class="lblinfo"><input type="text" name="lastname"   value="<?php echo !empty($billingAddress['lastname'])?$billingAddress['lastname']:''; ?>" /></span>
		</div>								
		<div class="acinfoitem">
			<span class="lbltxt col-xs-3">Address line 1:</span><span class="lblinfo"><input type="text" name="address_l1"   value="<?php echo !empty($billingAddress['address_l1'])?$billingAddress['address_l1']:''; ?>" /></span>
		</div>
		<div class="acinfoitem">
			<span class="lbltxt col-xs-3">Address line 2:</span><span class="lblinfo"><input type="text" name="address_l2"   value="<?php echo !empty($billingAddress['address_l2'])?$billingAddress['address_l2']:''; ?>" /></span>
		</div>
		<div class="acinfoitem">
			<span class="lbltxt col-xs-3">City:</span><span class="lblinfo"><input type="text" name="city"   value="<?php echo !empty($billingAddress['city'])?$billingAddress['city']:''; ?>" /></span>
		</div>
		<div class="acinfoitem">
			<span class="lbltxt col-xs-3">State:</span><span class="lblinfo"><input type="text" name="state"   value="<?php echo !empty($billingAddress['state'])?$billingAddress['state']:''; ?>" /></span>
		</div>
		<div class="acinfoitem">
			<span class="lbltxt col-xs-3">Zip Code:</span><span class="lblinfo"><input type="text" name="zipcode"   value="<?php echo !empty($billingAddress['zipcode'])?$billingAddress['zipcode']:''; ?>" /></span>
		</div>
		<div class="acinfoitem ">
			<span class="lbltxt col-xs-3">Country:</span><span class="lblinfo"><input type="text" name="country"   value="<?php echo !empty($billingAddress['country'])?$billingAddress['country']:''; ?>" /></span>
		</div>						
		<div class="acinfoitem ">
			<span class="lbltxt"><input  type="button" class="billingAddress-update btn btn-primary" data-target="billingAddress_<?php echo $i; ?>" id="billingAddress_<?php echo $i; ?>-save" value="Save changes" /> <a href="javascript:void(0)" data-target="billingAddress_<?php echo $i; ?>"  class="btnCancel" >Cancel</a></span>
		</div>											
	</form>
</div>

<?php $i++; }  
$style='style="display:none"';
}

?>						
<div class="clearfix"></div>
