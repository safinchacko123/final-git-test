<?php 	
	$sessionData =  $this->session->userdata('locationDetail');
	$where1 = array();
	$where2 = array();
	$where1['id'] = $this->session->userdata('userDetail')['user_id'];
	$where2['user_id'] = $this->session->userdata('userDetail')['user_id'];
	$where3['customer_id'] = $this->session->userdata('userDetail')['user_id'];
	$userResult = $this->business_model->select_data_where('gc_customers',$where1);	
	$billingResult = $this->business_model->select_data_where_result('gc_billing_address',$where2);	
	$orderResult = $this->business_model->select_data_where_result('gc_orders',$where3);	
	
	$where4 = array();
	$where4['user_id'] = $this->session->userdata('userDetail')['user_id'];
	$shippingResult = $this->business_model->select_data_where('gc_shipping_address',$where4);	
	
	$where5 = array();
	$where5['user_id'] = $this->session->userdata('userDetail')['user_id'];
	$cardResult = $this->business_model->select_data_where('gc_cardDetail',$where5);		


	//$countries = $this->business_model->select_all_data('gc_countries');
	//echo "<pre>"; print_r($userResult); echo "</pre>";
?>
<!-- Content Area -->
<div class="container">
	<div class="breadcrumb">
		<ul>
			<li><a href="<?php echo site_url(); ?>">Home</a></li>
			<li>Account</li>
		</ul>
	</div>
	<div class="clearfix"></div>
	<div class="heading"><h2><span>Account</span></h2></div>
	<!--body area -->
	<div class="body-area">
		<div class="row">
			<div class="col-sm-3 pull-left col-xs-12">
				<div class="my-basket">
					<ul class="accountmenu myAccount-menu">
						<li><a data-target="tab-1" href="javascript:void(0)" class="active"><i class="fa fa-chevron-right"></i>Account</a></li>
						<li><a id="ma-orderTab-link" data-target="tab-2" href="javascript:void(0)"><i class="fa fa-chevron-right"></i>Order History</a></li>
						<li><a data-target="tab-3" href="javascript:void(0)"><i class="fa fa-chevron-right"></i>Addresses</a></li>
						<li><a data-target="tab-4" href="javascript:void(0)"><i class="fa fa-chevron-right"></i>Payment</a></li>
					</ul>
					<div class="clearfix"></div>
				</div>
			</div>
			<div id="tab-1" class="col-sm-9 pull-left col-xs-12 myAccount-tab">
				<div class="white-bg">
					<h2 class="accounthd">Your Account</h2>
					<div class="accountinfo">
						<h2>Account Information<a href="javascript:void(0)" id="account-changeBtn" data-target="account" class="btnchange"><i class="fa fa-pencil"></i>Change</a></h2>
						<div class="account-Info" >
							<div class="acinfoitem">
								<span class="lbltxt">Email:</span><span class="lblinfo"><?php echo !empty($userResult['email'])?$userResult['email']:''; ?></span><span class="notverified"></span>
							</div>
							<div class="acinfoitem">
								<span class="lbltxt">Password:</span><span class="lblinfo">*******</span>
							</div>
							<div class="acinfoitem">
								<span class="lbltxt">Email Notification:</span><span class="lblinfo label-emailnotification" id="label-emailnotification"><?php echo ($userResult['emailNotification']==1)?'Yes':'No'; ?></span>
							</div>
							
						</div>
						<div class="account-Edit editSection" >
							<div class="acinfoitem">
							<?php if(empty($userResult['facebook_id']) && empty($userResult['googleplus_id'])) { ?>
							
								<span class="lbltxt  col-xs-3">Email:</span><span class="lblinfo inputField"><?php echo !empty($userResult['email'])?$userResult['email']:''; ?></span><span class="notverified"></span>
							
							<?php }else { ?>
								<span class="lbltxt  col-xs-3">Email:</span><span class="lblinfo inputField"><input type="text" class="cls-req" id="email" name="email" value="<?php echo !empty($userResult['email'])?$userResult['email']:''; ?>"  /></span><span class="notverified"></span>
							<?php } ?>
							</div>
							<div class="acinfoitem">
								<span class="lbltxt  col-xs-3">Password:</span><span class="lblinfo "><input type="password" class="cls-req" id="password" name="password" value="<?php echo !empty($userResult['password'])?$userResult['password']:''; ?>"  /></span>
							</div>
							<div class="acinfoitem">
								<span class="lbltxt  col-xs-3">Confirm Password:</span><span class="lblinfo"><input type="password" id="confirm_password" name="confirm_password" value="<?php echo !empty($userResult['password'])?$userResult['password']:''; ?>" /></span>
							</div>
							<div class="acinfoitem">
								<span class="lbltxt  col-xs-3">Email Notification:</span><span class="lblinfo "><input id="emailnotification" <?php echo ($userResult['emailNotification']==1)?'checked':''; ?> type="checkbox"/><span class="">Yes</span>
							</div>
							<div class="acinfoitem">
								<span class="lbltxt"><input class="btn btn-primary" type="button" id="account-save" value="Save changes" /> <a href="javascript:void(0)" data-target="account"  class="btnCancel" >Cancel</a></span>
							</div>
						</div>
						<br>
						<h2>Personal Information<a href="javascript:void(0)" id="personal-changeBtn" data-target="personal" class="btnchange"><i class="fa fa-pencil"></i>Change</a></h2>
						<div class="personal-Info" >
							<div class="acinfoitem">
								<span class="lbltxt">First Name:</span><span class="lblinfo" id="personalLabel-firstname"><?php echo !empty($userResult['firstname'])?$userResult['firstname']:''; ?></span>
							</div>
							<div class="acinfoitem">
								<span class="lbltxt">Last Name:</span><span class="lblinfo" id="personalLabel-lastname"><?php echo !empty($userResult['lastname'])?$userResult['lastname']:''; ?></span>
							</div>
							<div class="acinfoitem">
								<span class="lbltxt">Phone:</span><span class="lblinfo" id="personalLabel-phone"><?php echo !empty($userResult['phone'])?$userResult['phone']:''; ?></span><span class="verified"></span>
							</div>
<!--
							<div class="acinfoitem">
								<span class="lbltxt">City:</span><span class="lblinfo city" id="personalLabel-city"><?php echo !empty($userResult['city'])?$userResult['city']:''; ?></span>
							</div>
-->
						</div>	
						<div class="personal-Edit editSection"  >
							<form id="form-personalInfo" method="POST" >
								<div class="acinfoitem">
									<span class="lbltxt  col-xs-3">First Name:</span><span class="lblinfo"><input type="text" name="firstname"  value="<?php echo !empty($userResult['firstname'])?$userResult['firstname']:''; ?>" /></span>
								</div>
								<div class="acinfoitem">
									<span class="lbltxt  col-xs-3">Last Name:</span><span class="lblinfo"><input type="text" name="lastname"   value="<?php echo !empty($userResult['lastname'])?$userResult['lastname']:''; ?>" /></span>
								</div>
								<div class="acinfoitem">
									<span class="lbltxt  col-xs-3">Phone:</span><span class="lblinfo"><input type="text" name="phone" class="numbersOnly"  value="<?php echo !empty($userResult['phone'])?$userResult['phone']:''; ?>" /></span>
								</div>

								<div class="acinfoitem">
									<span class="lbltxt "><input class="btn btn-primary" type="button" id="personalInfo-save" value="Save changes" /> <a href="javascript:void(0)" data-target="personal"  class="btnCancel" >Cancel</a></span>
								</div>		
							</form>					
						</div>						
					</div>
					<div class="clearfix"></div>
				</div>
			</div>
 
			<div id="tab-2" style="display:none" class="col-sm-9 pull-left col-xs-12 myAccount-tab">
				<div class="white-bg">
					<h2 class="accounthd">Your Orders History</h2>
					<div class="accountinfo">
						<table cellspacing="0" cellpadding="0" border="0" width="100%" class="order-table">
							<tbody>
								<tr>
									<th>Order Number</th>
									<th>Order Date</th>
									<th>Amount</th>
									<th>Status</th>
									<th>Store Name</th>
									<th>Address Delivered</th>
								</tr>
						<?php 
						
							if(!empty($orderResult))
							{
								foreach($orderResult as $order)
								{
									$where1 = array();
									//echo "<pre>"; print_r($order); echo "</pre>";
						?>
								
								<tr>
									<td class="small-txt"><?php echo $order['order_number']; ?></td>
									<td class="small-txt"><?php echo date("Y-m-d",strtotime($order['ordered_on'])); ?></td>
									<td class="small-txt"><?php echo config_item('myCurrency'); ?><?php echo $order['total']; ?></td>
									<td class="small-txt"><?php echo $order['status']; ?></td>
									<td class="small-txt"><?php echo $order['company']; ?></td>
									<td class="small-txt"><?php
									if(empty($order['ship_address1']))
									{
										echo implode(", ",$shippingResult);
									}	
									else
									{
										echo   $order['ship_address1'].', '.$order['ship_address2'].', '.$order['ship_city'].', '.$order['ship_zip'];
									}
										
									 ?></td>
								</tr>
					
					<?php 		}
							} ?>
							</tbody>
						</table>
					</div> 
					<div class="clearfix"></div>
				</div>
			</div>
			<div id="tab-3" style="display:none" class="col-sm-9 pull-left col-xs-12 myAccount-tab">
				<div class="white-bg">
					<h2 class="accounthd">Your Addresses</h2>
					<div class="accountinfo" id="addressSection-shpiing">
						<?php $this->load->view('front/index/myAccount/shippingAdress'); ?>
					</div> 
					<div class="clearfix"></div>
				</div>
			</div>
			<div  id="tab-4" style="display:none" class="col-sm-9 pull-left col-xs-12 myAccount-tab">
				<div class="white-bg">
					<h2 class="accounthd">Your Payment</h2>
					<div class="accountinfo">
						<?php if(isset($cardResult) && !empty($cardResult)) { ?>
						
						<h3><i class="fa fa-credit-card-alt"></i><span class="ctype"><?php echo $cardResult['cardType']; ?></span> ending in <span class="clnm" id="cardDetail-cardLastDigit"><?php echo $cardResult['cardLastDigit']; ?></span></h3>
						<div class="acinfoitem">
							<span class="lbltxt">Expires:</span><span class="lblinfo" id=""><em id="cardDetail-card_date"><?php echo $cardResult['day']; ?></em>/<em id="cardDetail-card_year"><?php echo $cardResult['year']; ?></em></span>
						</div>
						<br>
						<h2>Name on card<a href="javascript:void(0)" id="cardDetail-changeBtn" data-target="cardDetail" class="btnchange"><i class="fa fa-pencil"></i>Change</a></h2>
						<div class="cardDetail-Info" >
							<div class="acinfoitem">
								<span class="lbltxt">First Name:</span><span class="lblinfo" id="cardDetail-firstname"><?php echo !empty($cardResult['firstname'])?$cardResult['firstname']:''; ?></span>
							</div>
							<div class="acinfoitem">
								<span class="lbltxt">Last Name:</span><span class="lblinfo"  id="cardDetail-lastname"><?php echo !empty($cardResult['lastname'])?$cardResult['lastname']:''; ?></span>
							</div>
						</div>
						<div class="cardDetail-Edit editSection" >
							<form id="form-cardDetail" method="POST" >
								<div class="acinfoitem">
									<span class="lbltxt col-xs-3">First Name:</span><span class="lblinfo"><input type="text" name="firstname"   value="<?php echo !empty($cardResult['firstname'])?$cardResult['firstname']:''; ?>" /></span>
								</div>
								<div class="acinfoitem">
									<span class="lbltxt col-xs-3">Last Name:</span><span class="lblinfo"><input type="text" name="lastname"   value="<?php echo !empty($cardResult['lastname'])?$cardResult['lastname']:''; ?>" /></span>
								</div>	
								<div class="acinfoitem">
									<span class="lbltxt col-xs-3">Card number:</span><span class="lblinfo"><input type="text" name="cardLastDigit" id="cardLastDigit-text"  value="" placeholder="Enter Card number" /></span>
								</div>									
								
								<div class="acinfoitem">
									<span class="lbltxt col-xs-3">Date:</span><span class="lblinfo col-xs-1">
										<select name="card_date" id="card_date">
											<?php for($i=1;$i<=31;$i++) {
												
													if($cardResult['day']==$i)
														$selectedDate = "selected";
													else
														$selectedDate = "";
												 ?>
											<option <?php echo $selectedDate; ?> value="<?php echo $i; ?>" ><?php echo $i; ?></option>
											<?php } ?>
										</select>	
									</span>
								</div>	
								<div class="acinfoitem">
									<span class="lbltxt col-xs-3">Year:</span><span class="lblinfo col-xs-1">
										<select name="card_year" id="card_date">
											<?php for($i=date("Y");$i<=2050;$i++) { 
												
													if($cardResult['year']==$i)
														$selectedDate = "selected";
													else
														$selectedDate = "";												
												?>
											<option <?php echo $selectedDate; ?> value="<?php echo $i; ?>" ><?php echo $i; ?></option>
											<?php } ?>
										</select>	
									</span>
								</div>																
								<div class="acinfoitem">
									<span class="lbltxt col-xs-3"><input class="btn btn-primary" type="button" id="cardDetail-save" value="Save changes" /> <a href="javascript:void(0)" data-target="cardDetail"  class="btnCancel" >Cancel</a></span>
								</div>																
							</form>
						</div>	
						<?php } ?>
						
						<br>
						<h3>Billing Address</h3>
						<?php //echo '<pre>'; print_r($billingResult); echo '</pre>';
						 if(!empty($billingResult)) {	$i=0; ?>
						<?php foreach($billingResult as $billingAddress) { ?>	
						<div class="billingAddress_<?php echo $i; ?>-Info" >
							<div class="acinfoitem">
								<h2>Address <?php echo $i+1; ?><a href="javascript:void(0)" id="billingAddress_<?php echo $i; ?>-changeBtn" data-target="billingAddress_<?php echo $i; ?>" class="btnchange"><i class="fa fa-pencil"></i>Change</a></h2>
							</div>						
							<div class="acinfoitem">
								<span class="lbltxt">First Name:</span><span class="lblinfo" id="billingAddress_<?php echo $i; ?>-firstname"><?php echo !empty($billingAddress['firstname'])?$billingAddress['firstname']:''; ?></span>
							</div>
							<div class="acinfoitem">
								<span class="lbltxt">Last Name:</span><span class="lblinfo" id="billingAddress_<?php echo $i; ?>-lastname"><?php echo !empty($billingAddress['lastname'])?$billingAddress['lastname']:''; ?></span>
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
									<span class="lbltxt col-xs-3">First Name:</span><span class="lblinfo"><input type="text" name="firstname"  value="<?php echo !empty($billingAddress['firstname'])?$billingAddress['firstname']:''; ?>" /></span>
								</div>
								<div class="acinfoitem">
									<span class="lbltxt col-xs-3">Last Name:</span><span class="lblinfo"><input type="text" name="lastname"   value="<?php echo !empty($billingAddress['lastname'])?$billingAddress['lastname']:''; ?>" /></span>
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
									<span class="lbltxt"><input  type="button" class="billingAdress-update btn btn-primary" data-target="billingAddress_<?php echo $i; ?>" id="billingAddress_<?php echo $i; ?>-save" value="Save changes" /> <a href="javascript:void(0)" data-target="billingAddress_<?php echo $i; ?>"  class="btnCancel" >Cancel</a></span>
								</div>											
							</form>
						</div>
						
						<?php } $i++; }
						else
						{
						 ?>
						<div class="billingAddress_0-Info" >
							<div class="acinfoitem">
								<h2>Address 1<a href="javascript:void(0)" id="billingAddress_0-changeBtn" data-target="billingAddress_0" class="btnchange"><i class="fa fa-pencil"></i>Change</a></h2>
							</div>								
							<div class="acinfoitem">
								<span class="lbltxt">First Name:</span><span class="lblinfo" id="billingAddress_0-firstname"></span>
							</div>
							<div class="acinfoitem">
								<span class="lbltxt">Last Name:</span><span class="lblinfo" id="billingAddress_0-lastname"></span>
							</div>							
						
							<div class="acinfoitem">
								<span class="lbltxt">Address line 1:</span><span class="lblinfo"id="billingAddress_0-address_l1" ></span>
							</div>
							<div class="acinfoitem">
								<span class="lbltxt">Address line 2:</span><span class="lblinfo" id="billingAddress_0-address_l2"></span>
							</div>
							<div class="acinfoitem">
								<span class="lbltxt">City:</span><span class="lblinfo" id="billingAddress_0-city"></span>
							</div>
							<div class="acinfoitem">
								<span class="lbltxt">State:</span><span class="lblinfo" id="billingAddress_0-state"></span>
							</div>
							<div class="acinfoitem">
								<span class="lbltxt">Zip Code:</span><span class="lblinfo" id="billingAddress_0-zipcode"></span>
							</div>
							<div class="acinfoitem">
								<span class="lbltxt">Country:</span><span class="lblinfo" id="billingAddress_0-country"></span>
							</div>
						</div>
						<div class="billingAddress_0-Edit editSection" >
							<form id="form-billingAddress_0" method="POST" >
								<input type="hidden" name="submitType" id="submitType_0" value="insert" />
								<input type="hidden"  id="billingAddress_0-billingId" name="id" value="" />
								<div class="acinfoitem">
									<span class="lbltxt  col-xs-3">First Name:</span><span class="lblinfo"><input type="text" name="firstname"  value="" /></span>
								</div>
								<div class="acinfoitem">
									<span class="lbltxt col-xs-3">Last Name:</span><span class="lblinfo"><input type="text" name="lastname"   value="" /></span>
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
									<span class="lbltxt"><input  type="button" class="billingAdress-update btn btn-primary" data-target="billingAddress_0" id="billingAddress_0-save" value="Save changes" /> <a href="javascript:void(0)" data-target="billingAddress_0"  class="btnCancel" >Cancel</a></span>
								</div>											
							</form>
						</div>
						<?php 
						}
						?>
					</div>
					<div class="clearfix"></div>
				</div>
			</div>
		</div>
	</div>
	<!--body area //-->
</div>
<div class="clearfix"></div>
<?php if($_GET['type']=="order")  { ?>
	<script> 
		var orderTab = 'active';
		//jQuery(".myAccount-menu li a").removeClass('active');
	//jQuery(this).find('a').addClass('active');
	var appBanners = document.getElementsByClassName('myAccount-tab'), i;

	for (var i = 0; i < appBanners.length; i ++) {
		appBanners[i].style.display = 'none';
	}	
	document.getElementById('tab-2').style.display = 'block';
	//jQuery(".myAccount-tab").hide();
	//jQuery("#tab-2").show();	
	 </script>
<?php } ?>	
