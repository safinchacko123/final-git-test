<?php 
	
	$ventureList  = $this->business_model->return_cart_ventureList();
		
	$cuisineCount = array();
	$orderAmountArr = array(); 
	$i=1;
	foreach($finalResult as $row)
	{ 
		//	echo "<pre>"; print_r($row); echo "</pre>";	
		$orderAmountArr[] = !empty($row['min_delivery_amount'])?$row['min_delivery_amount']:'0';
		$venture_name = $this->business_model->select_coulmn_single_value('company','gc_customers','id',$venture_id);
			
?>
<!-- Modal content-->
<div class="modal fade text-center" id="multipleVenturesDialog-<?php echo $i; ?>" role="dialog">
<div class="modal-dialog">
  <div class="modal-content">
	<div class="modal-body">
		<br>
		<h4 class="text-danger"><strong>Are you sure to shopping with multiple venture ? </h4>
		<div class="clearfix"></div>
		<br>
		<button type="button" class="btn btn-default btn-lg" data-dismiss="modal">Cancel</button>
		<a href="<?php echo site_url(); ?>detail/<?php echo $row['user_id']; ?>" class="btn btn-default btn-lg" >Continue</a>
		<div class="clearfix"></div>
		<br>
	</div>     
  </div>      
</div>
</div>
<!-- Modal content-->
<!--search result box -->
<div class="search-product-box">
	<div class="product-star">
		<img src="<?php echo site_url(); ?>assets/front/images/star3.png">
		<img src="<?php echo site_url(); ?>assets/front/images/star3.png">
		<img src="<?php echo site_url(); ?>assets/front/images/star3.png">
		<img src="<?php echo site_url(); ?>assets/front/images/star3.png">
		<img src="<?php echo site_url(); ?>assets/front/images/star3.png">
	</div>
	<div class="product-pic col-md-3 col-sm-3 col-xs-12 ">
	<?php 	
	if(!empty($row['customer_logo']))
	{	
		$venturelogoPath = dirname(dirname(dirname(dirname(dirname(__FILE__))))).'/uploads/images/venturelogo/'.$row['customer_logo'];
		if(file_exists($venturelogoPath))
		{
		?>
			<img src="<?php echo site_url(); ?>/uploads/images/venturelogo/<?php echo $row['customer_logo']; ?>"  />
	<?php
		}
	}
	else 
	{ ?>	
		<img src="<?php echo site_url(); ?>assets/front/images/deault_food_image.png"  />
	<?php 	
	} ?>	 	
	</div>
	<div class="product-review col-md-9 col-sm-9 col-xs-12">
		<h1>
			<?php
			
			 if(in_array($row['user_id'],$ventureList) || empty($ventureList)) {   ?>
				<a href="<?php echo site_url(); ?>detail/<?php echo $row['user_id']; ?>" ><?php echo $row['company']; ?> </a>
			<?php }else{ ?>
				<a data-target="#multipleVenturesDialog-<?php echo $i; ?>" data-toggle="modal"  href="javascript:void(0)" ><?php echo $row['company']; ?> </a>
			<?php } ?>
		</h1>
			<?php
				$result = $this->business_model->return_venture_cuisine($row['user_id'],'result');	
				if(!empty($result)) 
				{
					$ids = array();
					foreach($result as $res)
					{	
						if(in_array($res->cuisine_name,$cuisineNames))
						{
							//echo $res->cuisine_name;
							$cuisineCount[$res->cuisine_name][] = 1;
						}
						$ids[] = $res->cuisine_name; 
					}
					$cuisineArr = implode(" | ",$ids);
					echo '<div class="cuisineSelection">'.$cuisineArr.'</div>';
				} 
			?>
		<div class="clearfix"></div>
		<ul>
			<li>Min. Order Amount <span><?php echo config_item('myCurrency'); ?><?php echo !empty($row['min_delivery_amount'])?$row['min_delivery_amount']:'0' ?> </span></li>
			<li>Avg. Delivery Time <span><?php echo !empty($row['avg_delivery_time'])?$row['avg_delivery_time']:'0' ?> Min</span></li>
			<li>Delivery Fee <span><?php echo config_item('myCurrency'); ?><?php echo !empty($row['delivery_fee'])?$row['delivery_fee']:'0' ?> </span></li>
		</ul>
	</div>
	<div class="clearfix"></div>
</div>        
<!--search result box //-->
	
<?php 
		$i++;
	}
	//echo "vvbv<pre>"; print_r($orderAmountArr); echo "</pre>"; 
	$minOrderAmount = min($orderAmountArr);
	$maxOrderAmount = max($orderAmountArr);  
	
?>

<script>
<?php 

if(isset($cuisineCount) && !empty($cuisineCount)) {
foreach($cuisineCount as $key=>$cousine)
{
	$count = count($cousine);
	?>
		document.getElementById("cuisineCount_<?php echo $key; ?>").innerHTML = "(<?php echo $count; ?>)";

<?php }
} ?>

window.onload = function() {
	
	document.getElementById("lableMinAmt-First").innerHTML = "<?php echo $minOrderAmount; ?>";
	document.getElementById("lableMinAmt-last").innerHTML = "<?php echo $maxOrderAmount; ?>";
	document.getElementById("minOrderAmount").value = "<?php echo $minOrderAmount; ?>";
	document.getElementById("maxOrderAmount").value = "<?php echo $maxOrderAmount; ?>";
};

</script>


