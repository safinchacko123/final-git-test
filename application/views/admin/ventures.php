<script type="text/javascript">
function areyousure()
{
	return confirm('<?php echo "Are you sure you want to delete this vendor ?"; ?>');
}

function updateStatus(vendor_id) {   
            $.ajax({
                type: 'POST',
                cache : false, 
                url: '<?php echo base_url(); ?>admin/vendors/updateVendorStatus/',
                data:  { vendor_id : vendor_id } , 
                success: function(data) { 
                    if( data == 'false') {
                        alert('Please try after some time');
                    } else {
                        $('#' +vendor_id).html( data );                   
                    }
                } 
            }); 
}

</script>
<div class="btn-group pull-right">
	<a class="btn" href="<?php echo site_url($this->config->item('admin_folder').'/vendors/export_xml');?>"><i class="icon-download"></i>Export Ventures</a></div>
	<table class="table table-striped">
	<thead>
		<tr>
			
			<?php
			if($by=='ASC')
			{
				$by='DESC';
			}
			else
			{
				$by='ASC';
			}
			?>
			
			<th><a href="<?php echo site_url($this->config->item('admin_folder').'/vendors/ventures/lastname/');?>/<?php echo ($field == 'lastname')?$by:'';?>"><?php echo lang('lastname');?>
				<?php if($field == 'lastname'){ echo ($by == 'ASC')?'<i class="icon-chevron-up"></i>':'<i class="icon-chevron-down"></i>';} ?> </a></th>
			
			<th><a href="<?php echo site_url($this->config->item('admin_folder').'/vendors/ventures/firstname/');?>/<?php echo ($field == 'firstname')?$by:'';?>"><?php echo lang('firstname');?>
				<?php if($field == 'firstname'){ echo ($by == 'ASC')?'<i class="icon-chevron-up"></i>':'<i class="icon-chevron-down"></i>';} ?></a></th>
			
			<th><a href="<?php echo site_url($this->config->item('admin_folder').'/vendors/ventures/email/');?>/<?php echo ($field == 'email')?$by:'';?>"><?php echo lang('email');?>
				<?php if($field == 'email'){ echo ($by == 'ASC')?'<i class="icon-chevron-up"></i>':'<i class="icon-chevron-down"></i>';} ?></a></th>
			<th><?php echo lang('active');?></th>
			<th></th>
		</tr>
	</thead>
	
	<tbody>
		<?php
		$page_links	= $this->pagination->create_links();
		
		if($page_links != ''):?>
		<tr><td colspan="5" style="text-align:center"><?php echo $page_links;?></td></tr>
		<?php endif;?>
		<?php echo (count($ventures) < 1)?'<tr><td style="text-align:center;" colspan="5">There are no ventures</td></tr>':''?>
<?php foreach ($ventures as $customer):?>
		<tr>
					<td><?php echo  $customer->lastname; ?></td>
			<td class="gc_cell_left"><?php echo  $customer->firstname; ?></td>
			<td><a href="mailto:<?php echo  $customer->email;?>"><?php echo  $customer->email; ?></a></td>
			<td><a id="<?php echo $customer->id; ?>" onclick="updateStatus('<?php echo $customer->id; ?>');" href="javascript:void(0);"><?php echo ($customer->active == 0) ? '<i class="icon-remove"></i>' : '<i class="icon-ok"></i>'; ?></a></td>
			
		</tr>
<?php endforeach;
		if($page_links != ''):?>
		<tr><td colspan="5" style="text-align:center"><?php echo $page_links;?></td></tr>
		<?php endif;?>
	</tbody>
</table>