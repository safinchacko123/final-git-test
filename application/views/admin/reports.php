<div class="row">
	<div class="span6">
		<h3><?php echo lang('sales');?></h3>
	</div>
	<div class="span6">
            <form method="post" action="<?php echo base_url('admin/reports/export_sales_xml'); ?>" id="form-id" class="form-inline pull-right">
			<?php 
						 $vendorDropdown = '<select id="vendorDropdown" name="vendorDropdown">';
						 $vendorDropdown .= '<option value="0">Select Vendor</option>';

						foreach($vendorList as $vendor) 
						{ 
							if(isset($term->vendorDropdown) && $vendor->id == $term->vendorDropdown)
							{
								$sel = 'selected="selected"';
							}
							else
							{
								$sel = '';
							}


							$vendorDropdown .= '<option value="'.$vendor->id.'" '.$sel.'>'.$vendor->company.'</option>';
						
						}
						$vendorDropdown .= '</select>';
						echo $vendorDropdown;
						?>


			<select name="year" id="sales_year">
				<?php foreach($years as $y):?>
					<option value="<?php echo $y;?>"><?php echo $y;?></option>
				<?php endforeach;?>
			</select>
			<input class="btn btn-primary" type="button" value="<?php echo lang('get_monthly_sales');?>" onclick="get_monthly_sales()"/>
                        <input class="btn btn-primary" id="sales_report" type="button" value="<?php echo lang('export_sales_report');?>" />
		</form>
	</div>
</div>

<div class="row">
	<div class="span12" id="sales_container"></div>
</div>


<script type="text/javascript">

$(document).ready(function(){

	get_monthly_sales();
	$('input:button').button();
	
});

var form = document.getElementById("form-id");
document.getElementById("sales_report").addEventListener("click", function () {
  form.submit();
});

function get_monthly_sales()
{
	show_animation();
	$.post('<?php echo site_url($this->config->item('admin_folder').'/reports/sales');?>',{vendor:$('#vendorDropdown').val(),year:$('#sales_year').val()}, function(data){
		$('#sales_container').html(data);
		setTimeout('hide_animation()', 500);
	});
}

function show_animation()
{
	$('#saving_container').css('display', 'block');
	$('#saving').css('opacity', '.8');
}

function hide_animation()
{
	$('#saving_container').fadeOut();
}

</script>

<div id="saving_container" style="display:none;">
	<div id="saving" style="background-color:#000; position:fixed; width:100%; height:100%; top:0px; left:0px;z-index:100000"></div>
	<img id="saving_animation" src="<?php echo base_url('assets/img/storing_animation.gif');?>" alt="saving" style="z-index:100001; margin-left:-32px; margin-top:-32px; position:fixed; left:50%; top:50%"/>
	<div id="saving_text" style="text-align:center; width:100%; position:fixed; left:0px; top:50%; margin-top:40px; color:#fff; z-index:100001"><?php echo lang('loading');?></div>
</div>