<!-- Content Area -->
<div class="container">
	<div class="breadcrumb">
		<ul>
		  <li><a href="<?php echo site_url();?>">Home</a></li>
		  <li><?php echo $result->title ; ?></li>
		</ul>
	  </div>
	
	<div class="clearfix"></div>
	
	
  <div class="row"> 
	  
    <div class="col-md-12 col-center">
      <div class="body-area">
		<?php echo $result->content;  ?>
      </div>
		
    </div>
  </div>
</div>
<div class="clearfix"></div>

