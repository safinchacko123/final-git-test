<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="utf-8">
<meta http-equiv="X-UA-Compatible" content="IE=edge">
<meta name="viewport" content="width=device-width, initial-scale=1">
<!-- The above 3 meta tags *must* come first in the head; any other head content must come *after* these tags -->
<title>Dropneed</title>

<!-- Bootstrap -->
<link href="<?php echo site_url(); ?>assets/front/css/bootstrap.min.css" rel="stylesheet">

<!-- HTML5 shim and Respond.js for IE8 support of HTML5 elements and media queries -->
<!-- WARNING: Respond.js doesn't work if you view the page via file:// -->
<!--[if lt IE 9]>
      <script src="https://oss.maxcdn.com/html5shiv/3.7.2/html5shiv.min.js"></script>
      <script src="https://oss.maxcdn.com/respond/1.4.2/respond.min.js"></script>
    <![endif]-->
<link href="<?php echo site_url(); ?>assets/front/css/style.css" rel="stylesheet" type="text/css"/>
<link href='https://fonts.googleapis.com/css?family=Lobster|Merriweather:400,400italic,700' rel='stylesheet' type='text/css'>
<link href='https://fonts.googleapis.com/css?family=Marcellus+SC' rel='stylesheet' type='text/css'>
<link href='https://fonts.googleapis.com/css?family=Roboto:400,300,500,700,700italic,400italic' rel='stylesheet' type='text/css'>
<link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/font-awesome/4.5.0/css/font-awesome.min.css">
<link href="<?php echo site_url(); ?>assets/front/js/ui/jquery-ui.css" rel="stylesheet">
<link href="<?php echo site_url(); ?>assets/front/css/responsive-tabs.css" rel="stylesheet">
<?php 	$currentClass =  $this->router->fetch_class(); 		$currentMethod =  $this->router->fetch_method(); ?>
<script>
var currentClass ="<?php echo $currentClass; ?>";
var currentMethod ="<?php echo $currentMethod; ?>";
</script>
</head>
<body>
<!-- Loading div -->
    <div  id="mainLoadingdiv" class="mainLoadigArea">
		<div class="loading-inner" >			
				<span class="clr_red"><img src="<?php echo site_url(); ?>assets/front/images/main-loader.gif" width="25px" height="25px"  />Loading . . .</span> 
		</div>
    </div>
<!--loading div -- >  
<!-- Header -->
<div class="container-fluid header">
  <div class="row">
    <div class="col-md-2 col-sm-3"> <a class="navbar-brand" href="<?php echo site_url(); ?>"><img  src="<?php echo site_url(); ?>assets/front/images/logo-small.png"></a> </div>
    <div class="col-md-6 col-sm-9">
      <div class="input-group header-search">
        <input type="text" class="form-control" placeholder="Search for a product, category or brand">
        <span class="input-group-btn">
        <button class="btn search-btn" type="button">Go!</button>
        </span> </div>
    </div>
    <div class="col-md-4 col-sm-12">
      <div class="navbar-header">
        <button type="button" class="navbar-toggle collapsed" data-toggle="collapse" data-target="#navbar" aria-expanded="false" aria-controls="navbar"> <span class="sr-only">Toggle navigation</span> <span class="icon-bar"></span> <span class="icon-bar"></span> <span class="icon-bar"></span> </button>
      </div>
      <div id="navbar" class="navbar-collapse collapse navbar-right">
        <ul class="nav nav-justified">
          <li><a href="<?php echo site_url(); ?>"><i class="icon"><img src="<?php echo site_url(); ?>assets/front/images/home-icon.png" /></i>Home</a></li>
          <li><a href="<?php echo site_url(); ?>help"><i class="icon"><img src="<?php echo site_url(); ?>assets/front/images/help-icon.png" /></i>Help</a></li>
          <li><a href="<?php echo site_url(); ?>how_it_work"><i class="icon"><img src="<?php echo site_url(); ?>assets/front/images/how-dropneed-icon.png" /></i>How dropneed works</a></li>
          <?php $userDetail = $this->session->userdata('userDetail');
			if(empty($userDetail))	{
           ?>
			<li><a href="<?php echo site_url('login'); ?><?php echo !empty($currentMethod)?'/'.$currentMethod:''; ?>"><span class="btn login-btn">Login</span></a></li>
			<?php } 
			else { 	?>
				<li><a href="<?php echo site_url('logout'); ?>"><span class="btn login-btn">logout</span></a></li>
			<?php } ?>
        </ul>
      </div>
    </div>
  </div>
</div>
<div class="clearfix"></div>
