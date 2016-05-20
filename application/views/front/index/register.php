<?php 	$sessionData =  $this->session->userdata('locationDetail'); ?>

   
<!-- Content Area -->
<div class="container">
	<div class="breadcrumb">
		<ul>
		  <li><a href="<?php echo site_url(); ?>">Home</a></li>
		  <li>Register</li>
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
				  <div class="formfldwrp row">
					<label class="col-sm-4 pull-left" style="padding-top:5px;">Register with</label>
					<div class="col-sm-8 pull-left">
						<a href="<?php echo site_url('index/facebook_login'); ?>" class="facebookbox"><i class="fa fa-facebook"></i></a>
						<a href="<?php echo site_url('index/social_login/google'); ?>" class="gplusbox"><i class="fa fa-google-plus"></i></a>	
					</div>	
				</div>
				  <div class="registeror">Or</div>
				  <div class="error-msg" style="display:none" id="errorMsg">Your email address is not valid.</div>
			<form role="form" method="POST" id="form-register" action="<?php echo site_url('index/register'); ?>" onsubmit="return validate_signupForm()" class="signUp-form">
			<input type="hidden" value="0" id="validEmailStatus" />
				<div class="formfldwrp row">
					<label class="col-sm-4 col-md-4 col-xs-12 hidden-xs">Name</label>
					<div class="col-sm-8 col-md-8 col-xs-12">
						<input type="text" name="name" id="name" maxlength="10" class="form-control" placeholder="Name">
					</div>	
				</div>
				  <div class="formfldwrp row">
					<label class="col-sm-4 col-md-4 col-xs-12 hidden-xs">Surname</label>
					<div class="col-sm-8 col-md-8 col-xs-12">
						<input type="text"  name="surname" id="surname" class="form-control" placeholder="Surname">
					</div>	
				</div>
				  <div class="formfldwrp row">
					<label class="col-sm-4 col-md-4 col-xs-12 hidden-xs">Email</label>
					<div class="col-sm-8 col-md-8 col-xs-12">
						<input type="email"  name="email" id="email" autocomplete="off" class="form-control" placeholder="Email">
						<img src="<?php echo site_url(); ?>assets/front/images/email-loader.gif" style="display:none" id="email-loader" />
					</div>	
				</div>
				  <div class="formfldwrp row">
					<label class="col-sm-4 col-md-4 col-xs-12 hidden-xs">Password</label>
					<div class="col-sm-8 col-md-8 col-xs-12">
						<input type="password"  name="password" id="password" maxlength="15" class="form-control" placeholder="Password">
					</div>	
				</div>
				  <div class="formfldwrp row">
					<label class="col-sm-4 col-md-4 col-xs-12 hidden-xs">Confirm Password</label>
					<div class="col-sm-8 col-md-8 col-xs-12">
						<input type="password"  name="confirm_password" id="confirm_password" maxlength="15" class="form-control" placeholder="Confirm Password">
					</div>	
				</div>
				<div class="formfldwrp row">
					<label class="col-sm-4 col-md-4 col-xs-12 hidden-xs">City</label>
					<div class="col-sm-8 col-md-8 col-xs-12">
						<input type="text" id="cityName" name="cityName" class="form-control" placeholder="City">
					</div>	
				</div>
				  <div class="formfldwrp row">
					<label class="col-sm-4 col-md-4 col-xs-12 hidden-xs">Mobile</label>
					<div class="col-sm-8 col-md-8 col-xs-12">
						<input type="text"  name="mobile_number" id="mobile_number" class="form-control numbersOnly" placeholder="Mobile">
					</div>	
				</div>
				  
				  <p class="prvpolicy text-left">By clicking Register button below, you agree to our <a href="#">Term &amp; Privacy Plocy</a> and that you have fully read and understand them</p>
				  <br>
				  <button type="submit" id="registerSubmitBtn" class="btn btn-primary btn-round mB30 pull-left"><i class="fa fa-lock" style="margin-right:7px;"></i>REGISTER</button>
				</form>  
			  </div>	  
          </div>
			
			<div class="col-sm-5 col-xs-12">
				<div class="col-xs-12">
					<p class="alredylogin">Already have an account? <a href="<?php echo site_url('login') ?>">Log In</a></p>
				</div>	
			</div>
          
          
			<div class="clearfix"></div>
        </div>
      </div>
    </div>
  </div>
</div>
<div class="clearfix"></div>

