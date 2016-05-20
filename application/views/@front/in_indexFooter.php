
<footer class="container-fluid">
  <div class="container">
    <div class="row">
      <p>©2015 Dropneed, all rights reserved. Dropneed® is a registered trademarks of HIBRIUS, UAE.</p>
    </div>
  </div>
</footer>
<link rel="stylesheet" href="<?php echo site_url(); ?>assets/front/css/jcf.css">
<?php 	$currentClass =  $this->router->fetch_class(); 		$currentMethod =  $this->router->fetch_method(); ?>
<script>
var currentClass ="<?php echo $currentClass; ?>";
var currentMethod ="<?php echo $currentMethod; ?>";
var  BASE_URL = "<?php echo site_url(); ?>";
var	myCurrency = "<?php echo config_item('myCurrency'); ?>";
//alert(myCurrency);

 </script>
<!-- jQuery (necessary for Bootstrap's JavaScript plugins) --> 
<script src="https://ajax.googleapis.com/ajax/libs/jquery/1.11.3/jquery.min.js"></script> 
<script src="http://maps.googleapis.com/maps/api/js?libraries=places&key=AIzaSyDfpsSR840RUvgYdOus05KsuVKfujiiarA"></script>
<script src="<?php echo site_url(); ?>assets/front/js/jquery.geocomplete.js"></script>
<!-- Include all compiled plugins (below), or include individual files as needed --> 
<script src="<?php echo site_url(); ?>assets/front/js/bootstrap.min.js"></script> 
<!-- Custom Forms -->
<!-- Custom Forms -->
<script src="<?php echo site_url(); ?>assets/front/js/jcf.js"></script> 
<script src="<?php echo site_url(); ?>assets/front/js/jcf.select.js"></script> 
<script src="<?php echo site_url(); ?>assets/front/js/in_custom.js"></script> 

</body>
</html>
