<?php 

$this->load->model('Page_model'); // I know this is not the right place to do this. This is for a very urgent fix and this page is probably gonna get deleted on the next updates as the content pages are to be managed from admin
$page = $this->Page_model->get_page(5);

?><!-- Content Area -->
<div class="container">
	<div class="breadcrumb">
		<ul>
		  <li><a href="<?php echo site_url();?>">Home</a></li>
		  <li>FAQ</li>
		</ul>
	  </div>
	
	<div class="clearfix"></div>
	
	<div class="heading"><h2><span>Frequently Asked Questions</span></h2></div>
  <div class="row"> 
	  
    <div class="col-md-12 col-center">
      <div class="body-area">
      
      
		  <?php 
		  if (stripos($page->content, "[qn]") !== false) {
		    $admintags = array("[qn]", "[ans]", "[/qn]", "[/ans]");
		    $htmltags = array('<dt class=""><span><i class="fa fa-plus-square-o"></i><i class="fa fa-minus-square-o"></i></span>', '<dd class=""><i class="fa fa-hand-o-right"></i>', '</dt>', '</dd>');
		    $finalhtml = str_ireplace($admintags, $htmltags, $page->content);
			    
		    echo $finalhtmlappnd = '<dl class="faqwrp">' . $finalhtml . '</dl>';
		} else {
		    echo $page->content;
		}
		  
		  /*<dl class="faqwrp">
			  <dt class="active">
				  <span><i class="fa fa-plus-square-o"></i><i class="fa fa-minus-square-o"></i></span>
				  Lorem ipsum dolor sit amet, consectetur adipiscing elit
			  </dt>
			  <dd class="active">
				  <i class="fa fa-hand-o-right"></i>Suspendisse eu lobortis felis. Ut tincidunt, velit et suscipit gravida, ligula massa condimentum lacus, vitae rhoncus metus leo in ligula. Aenean id volutpat tortor, efficitur semper est. Praesent orci dolor, lobortis nec quam pretium, tempor eleifend urna. In id lectus imperdiet, eleifend odio quis, malesuada mi. In porta in arcu non rhoncus.
			  </dd>
			  <dt>
				  <span><i class="fa fa-plus-square-o"></i><i class="fa fa-minus-square-o"></i></span>
				  Lorem ipsum dolor sit amet, consectetur adipiscing elit
			  </dt>
			  <dd>
				  <i class="fa fa-hand-o-right"></i>Suspendisse eu lobortis felis. Ut tincidunt, velit et suscipit gravida, ligula massa condimentum lacus, vitae rhoncus metus leo in ligula. Aenean id volutpat tortor, efficitur semper est. Praesent orci dolor, lobortis nec quam pretium, tempor eleifend urna. In id lectus imperdiet, eleifend odio quis, malesuada mi. In porta in arcu non rhoncus.
			  </dd>
			  <dt>
				  <span><i class="fa fa-plus-square-o"></i><i class="fa fa-minus-square-o"></i></span>
				  Lorem ipsum dolor sit amet, consectetur adipiscing elit
			  </dt>
			  <dd>
				  <i class="fa fa-hand-o-right"></i>Suspendisse eu lobortis felis. Ut tincidunt, velit et suscipit gravida, ligula massa condimentum lacus, vitae rhoncus metus leo in ligula. Aenean id volutpat tortor, efficitur semper est. Praesent orci dolor, lobortis nec quam pretium, tempor eleifend urna. In id lectus imperdiet, eleifend odio quis, malesuada mi. In porta in arcu non rhoncus.
			  </dd>
			  <dt>
				  <span><i class="fa fa-plus-square-o"></i><i class="fa fa-minus-square-o"></i></span>
				  Lorem ipsum dolor sit amet, consectetur adipiscing elit
			  </dt>
			  <dd>
				  <i class="fa fa-hand-o-right"></i>Suspendisse eu lobortis felis. Ut tincidunt, velit et suscipit gravida, ligula massa condimentum lacus, vitae rhoncus metus leo in ligula. Aenean id volutpat tortor, efficitur semper est. Praesent orci dolor, lobortis nec quam pretium, tempor eleifend urna. In id lectus imperdiet, eleifend odio quis, malesuada mi. In porta in arcu non rhoncus.
			  </dd>
			  <dt>
				  <span><i class="fa fa-plus-square-o"></i><i class="fa fa-minus-square-o"></i></span>
				  Lorem ipsum dolor sit amet, consectetur adipiscing elit
			  </dt>
			  <dd>
				  <i class="fa fa-hand-o-right"></i>Suspendisse eu lobortis felis. Ut tincidunt, velit et suscipit gravida, ligula massa condimentum lacus, vitae rhoncus metus leo in ligula. Aenean id volutpat tortor, efficitur semper est. Praesent orci dolor, lobortis nec quam pretium, tempor eleifend urna. In id lectus imperdiet, eleifend odio quis, malesuada mi. In porta in arcu non rhoncus.
			  </dd>
		  </dl>      */ ?>  
      </div>
		
    </div>
  </div>
</div>
<div class="clearfix"></div>