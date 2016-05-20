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
		  <li>Login</li>
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
					<label class="col-sm-4 pull-left" style="padding-top:5px;">Log In using</label>
					<div class="col-sm-8 pull-left">
						<a href="<?php echo site_url(); ?>index/facebook_login" class="facebookbox"><i class="fa fa-facebook"></i></a>
						<a href="<?php echo site_url(); ?>index/social_login/google" class="gplusbox"><i class="fa fa-google-plus"></i></a>	
					</div>	
				</div>
				  <div class="registeror">Or</div>
						<?php echo isset($errorMessage)?$errorMessage:''; ?>
				<form role="form" class="signUp-form" id="form-login" method="POST" >
				  <div class="formfldwrp row">
					<label class="col-sm-4 col-md-4 col-xs-12 hidden-xs">Email</label>
					<div class="col-sm-8 col-md-8 col-xs-12">
						<input type="text" name="loginEmail" id="loginEmail" value="<?php echo isset($_COOKIE['email'])?$_COOKIE['email']:'' ?>" class="form-control" placeholder="Email">
					</div>	
				</div>
				  <div class="formfldwrp row">
					<label class="col-sm-4 col-md-4 col-xs-12 hidden-xs">Password</label>
					<div class="col-sm-8 col-md-8 col-xs-12">
						<input type="password" name="loginPassword" id="loginPassword"  value="<?php echo isset($_COOKIE['password'])?$_COOKIE['password']:'' ?>" class="form-control" placeholder="Password">
					</div>	
				</div>
				 <div class="formfldwrp row">
					<label class="col-sm-4 col-md-4 col-xs-12 hidden-xs">&nbsp;</label>
					<div class="col-sm-8 col-md-8 col-xs-12">
						<input  type="checkbox" id="remember_me" name="remember_me"  <?php echo isset($_COOKIE['email'])?'checked':'' ?>  placeholder="Password"><span class="checktxt">Remember me</span><a href="<?php echo site_url('forgot') ?>" class="forgotpass">Forgot password?</a>
					</div>	
				</div>
				  
				  <button type="submit" name="submit" id="loginSubmitBtn"   class="btn btn-primary btn-round mB30 pull-left"><i class="fa fa-unlock-alt" style="margin-right:7px;"></i>Login</button>
				</form>  
			  </div>	  
          </div>
			
			<div class="col-sm-5 col-xs-12">
				<div class="col-xs-12">
					<p class="alredylogin2">Do not have an account? <a href="<?php echo site_url('register') ?>">Register</a></p>
				</div>	
			</div>
         
			<div class="clearfix"></div>
        </div>
      </div>
    </div>
  </div>
</div>
<div class="clearfix"></div>

