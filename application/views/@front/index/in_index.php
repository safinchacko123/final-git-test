<section class="container-fluid">
  <div class="row">
    <div id="carousel-example-generic" class="carousel slide" data-ride="carousel"> 
      <!-- Indicators -->
      <ol class="carousel-indicators">
        <li data-target="#carousel-example-generic" data-slide-to="0" class="active"></li>
        <li data-target="#carousel-example-generic" data-slide-to="1"></li>
        <li data-target="#carousel-example-generic" data-slide-to="2"></li>
      </ol>
      
      <!-- Wrapper for slides -->
      <div class="carousel-inner" role="listbox">
        <div class="item active"> <img src="<?php echo site_url(); ?>assets/front/images/bg_02.jpg" alt=""/>
          <div class="carousel-caption">
            <h1>One stop for all your needs,</h1>
            <h4>Grocery, Restaurant Food, Pharamacy</h4>
          </div>
        </div>
        <div class="item"> <img src="<?php echo site_url(); ?>assets/front/images/bg_02.jpg" alt=""/>
          <div class="carousel-caption">
            <h1>One stop for all your needs,</h1>
            <h4>Grocery, Restaurant Food, Pharamacy</h4>
          </div>
        </div>
        <div class="item"> <img src="<?php echo site_url(); ?>assets/front/images/bg_02.jpg" alt=""/>
          <div class="carousel-caption">
            <h1>One stop for all your needs,</h1>
            <h4>Grocery, Restaurant Food, Pharamacy</h4>
          </div>
        </div>      
      </div>     
      <!-- Controls --> 
      <a class="left carousel-control" href="#carousel-example-generic" role="button" data-slide="prev"> <span class="glyphicon glyphicon-chevron-left" aria-hidden="true"></span> <span class="sr-only">Previous</span> </a> <a class="right carousel-control" href="#carousel-example-generic" role="button" data-slide="next"> <span class="glyphicon glyphicon-chevron-right" aria-hidden="true"></span> <span class="sr-only">Next</span> </a> </div>
  </div>
</section>
<!-- Popup -->
<div class="container mT60 find-city-home">
  <div class="row">
    <div class="col-md-8 col-sm-8 col-center">
      <div class="find-store text-center">
     
		<?php 
			$data = array();
			$data['countryDetail'] = $countryDetail;
			$data['country_id'] = $country_id;
			$this->load->view('front/index/form_loactionFind',$data); ?>
      </div>
    </div>
  </div>
</div>
<!-- Popup Ends -->
<script>	var  iso_code_2 = "IN"; </script>


