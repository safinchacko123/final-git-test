<?php 	$sessionData =  $this->session->userdata('locationDetail'); ?>

<div class="container">
	
	<div class="row" >
		<div class="col-md-12 " >	
			<div class="heading"><?php echo isset($heading)?'<p>'.$heading.'</p>':''; ?></div>
		</div>
		<div class="col-md-12 " >
			<div class="row notifiyPageMessage" >
				<center><p><?php echo $message; ?></p></center>
			</div>
		
		</div>
		<div class="col-md-12 " >			
			<div class="footing"><?php echo isset($footing)?'<p>'.$footing.'</p>':''; ?>	</div>
		</div>
	</div>
	
	<?php if(isset($emailData)) { ?>
	<div class="row " >
	<h5>Note: During the email spam issue . Follow the email box for testing :</h5>	
	
			<div class="col-md-3 " ></div>		
			<div class="col-md-6 emailFormat " >
				<?php echo isset($emailData)?$emailData:''; ?>	
			</div>		
			<div class="col-md-3 " ></div>		
	</div>
	<?php } ?>
</div>
<div class="clearfix"></div>
