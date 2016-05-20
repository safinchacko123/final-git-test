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
	<a class="btn" href="<?php echo site_url($this->config->item('admin_folder').'/vendors/export_xml');?>"><i class="icon-download"></i>Export Vendors</a>
	
	
</div>

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
			
			<th><a href="<?php echo site_url($this->config->item('admin_folder').'/vendors/index/lastname/');?>/<?php echo ($field == 'lastname')?$by:'';?>"><?php echo lang('lastname');?>
    <?php if($field == 'lastname'){ echo ($by == 'ASC')?'<i class="icon-chevron-down"></i>':'<i class="icon-chevron-up"></i>';} ?> </a></th>
   
   <th><a href="<?php echo site_url($this->config->item('admin_folder').'/vendors/index/firstname/');?>/<?php echo ($field == 'firstname')?$by:'';?>"><?php echo lang('firstname');?>
    <?php if($field == 'firstname'){ echo ($by == 'ASC')?'<i class="icon-chevron-down"></i>':'<i class="icon-chevron-up"></i>';} ?></a></th>
   
   <th><a href="<?php echo site_url($this->config->item('admin_folder').'/vendors/index/email/');?>/<?php echo ($field == 'email')?$by:'';?>"><?php echo lang('email');?>
    <?php if($field == 'email'){ echo ($by == 'ASC')?'<i class="icon-chevron-down"></i>':'<i class="icon-chevron-up"></i>';} ?></a></th>
   <th>
                            <a href="<?php echo site_url($this->config->item('admin_folder').'/vendors/index/active/');?>/<?php echo ($field == 'active')?$by:'';?>"><?php echo lang('active');?>    
                            <?php if($field == 'active'){ echo ($by == 'ASC')?'<i class="icon-chevron-down"></i>':'<i class="icon-chevron-up"></i>';} ?>
                            </a>
                        </th>
   <th>Ventures</th>
                        <th>
                            <a href="<?php echo site_url($this->config->item('admin_folder').'/vendors/index/created_on/');?>/<?php echo ($field == 'created_on')?$by:'';?>"><?php echo lang('created_on');?>  
                            <?php if($field == 'created_on'){ echo ($by == 'ASC')?'<i class="icon-chevron-down"></i>':'<i class="icon-chevron-up"></i>';} ?>
                            </a>
                        </th>
   <th></th>
		</tr>
	</thead>
	
	<tbody>
		<?php
		$page_links	= $this->pagination->create_links();
		
		if($page_links != ''):?>
		<tr><td colspan="5" style="text-align:center"><?php echo $page_links;?></td></tr>
		<?php endif;?>
		<?php echo (count($vendors) < 1)?'<tr><td style="text-align:center;" colspan="5">There are no vendors</td></tr>':''?>
<?php foreach ($vendors as $customer):?>
		<tr>
			<?php /*<td style="width:16px;"><?php echo  $customer->id; ?></td>*/?>
			<td><?php echo  $customer->lastname; ?></td>
			<td class="gc_cell_left"><?php echo  $customer->firstname; ?></td>
			<td><a href="mailto:<?php echo  $customer->email;?>"><?php echo  $customer->email; ?></a></td>
			<td><a id="<?php echo $customer->id; ?>" onclick="updateStatus('<?php echo $customer->id; ?>');" href="javascript:void(0);"><?php echo ($customer->active == 0) ? '<i class="icon-remove"></i>' : '<i class="icon-ok"></i>'; ?></a></td>
			<td><a href="<?php echo site_url($this->config->item('admin_folder').'/vendors/venture/'.$customer->id); ?>">View Ventures</a></td>
                        <td>
                            <?php echo date('d/m/Y', strtotime($customer->created_on)); ?>
                        </td>
			<td>
				<div class="btn-group" style="float:right">
					<a class="btn" href="<?php echo site_url($this->config->item('admin_folder').'/vendors/form/'.$customer->id); ?>"><i class="icon-pencil"></i> <?php echo lang('edit');?></a>
									
					<a class="btn btn-danger" href="<?php echo site_url($this->config->item('admin_folder').'/vendors/delete/'.$customer->id); ?>" onclick="return areyousure();"><i class="icon-trash icon-white"></i> <?php echo lang('delete');?></a>
				</div>
			</td>
		</tr>
<?php endforeach;
		if($page_links != ''):?>
		<tr><td colspan="5" style="text-align:center"><?php echo $page_links;?></td></tr>
		<?php endif;?>
	</tbody>
</table>