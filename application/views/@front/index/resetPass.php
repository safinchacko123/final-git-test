<?php 	$sessionData =  $this->session->userdata('locationDetail');
	//	echo $ip =  $this->business_model->getip();
	//	$ipDetail =  $this->business_model->getAddressFromIP($ip);
	//	echo "<pre>"; print_r($ipDetail); echo "</pre>";
?>

<!-- Content Area -->
<div class="container">
	<div class="breadcrumb">
		<ul>
		  <li><a href="<?php echo site_url(); ?>">Home</a></li>
		  <li>Forgot</li>
		</ul>
	  </div>
	
	<div class="clearfix"></div>
	
<!--	<div class="heading"><h2><span>Register</span></h2></div>-->
  <div class="row"> 
	  
    <div class="col-md-12 col-center">
      <div class="find-store">
        
        <div class="">
<!--          <p class="mB30">Enter your zipcode or select your city to see your Local Stores.</p>-->
          <div class="col-sm-7 col-xs-12 registerleft">
			  <div class="col-xs-12">
				    <div class="error-msg" style="display:none" id="errorMsg"></div>
				<form role="form" method="POST" action="<?php echo site_url('resetPassword'); ?>" onsubmit="return validate_resetPass()" class="signUp-form" id="form-forgot">
				  <div class="formfldwrp row">
					<label class="col-sm-4 col-md-4 col-xs-12 hidden-xs">Password</label>
					<div class="col-sm-8 col-md-8 col-xs-12">
						<input type="password" autocomplete="off" name="password" id="password" class="form-control" placeholder="Password">
					</div>	
					</div>
				<div class="formfldwrp row">
					<label class="col-sm-4 col-md-4 col-xs-12 hidden-xs">Confirm password</label>
					<div class="col-sm-8 col-md-8 col-xs-12">
						<input  type="password" autocomplete="off" name="confirm_password" id="confirm_password"class="form-control" placeholder="Confirm password">
					</div>	
					</div>					
				  <button type="submit"  id="forgotSubmitBtn"  class="btn btn-primary btn-round mB30 pull-left"><i class="fa fa-unlock-alt" style="margin-right:7px;"></i>Submit</button>
				 </form> 
				 
			  </div>	  
          </div>
			
			<div class="col-sm-5 col-xs-12">
				<div class="col-xs-12">
<!--
					<p class="alredylogin2">Go to <a href="<?php echo site_url('login') ?>">Login</a></p>
-->
				</div>	
			</div>
          
          
			<div class="clearfix"></div>
        </div>
      </div>
    </div>
  </div>
</div>
<div class="clearfix"></div>
