<?php 

function sort_url($labelName, $by, $sort, $sorder, $admin_folder, $productId)
{	
	if ($sort == $by)
	{
		if ($sorder == 'asc')
		{
			$sort	= 'desc';
			$icon	= ' <i class="icon-chevron-up"></i>';
		}
		else
		{
			$sort	= 'asc';
			$icon	= ' <i class="icon-chevron-down"></i>';
		}
	}
	else
	{
		$sort	= 'asc';
		$icon	= '';
	}
		
	$return = site_url($admin_folder.'/products/review/'.$productId.'/'.$by.'/'.$sort);
	
	echo '<a href="'.$return.'">'.$labelName.$icon.'</a>';
}
?>

<script type="text/javascript">

$(document).ready(function(){

	$('#gc_check_all').click(function(){
		if(this.checked)
		{
			$('.gc_check').attr('checked', 'checked');
		}
		else
		{
			 $(".gc_check").removeAttr("checked"); 
		}
	});
});

function submit_form()
{
	if($(".gc_check:checked").length > 0)
	{
		return confirm('Are you sure you want to delete? ');
	}
	else
	{
		alert('No review has been selected');
		return false;
	}
}

function updateStatus(review_id) {    
            $.ajax({
                type: 'POST',
                cache : false, 
                url: '/admin/products/updateReviewStatus/',
                data:  { review_id : review_id } , 
                success: function(data) { 
                    if( data == 'false') {
                        alert('Please try after some time');
                    } else {
                        $('#' +review_id).html( data );                   
                    }
                } 
            }); 
}

</script>
<style type="text/css">
	.pagination {
		margin:0px;
		margin-top:-3px;
	}
</style>

<div class="row">
	<div class="span12" style="border-bottom:1px solid #f5f5f5;">
		<div class="row">
			<div class="span4">
				<?php echo $this->pagination->create_links();?>	&nbsp;
			</div>
		</div>
	</div>
</div>
<div class="btn-group pull-right">
</div>

<?php echo form_open($this->config->item('admin_folder').'/products/bulk_delete_review/'.$productId, array('id'=>'delete_form', 'onsubmit'=>'return submit_form();', 'class="form-inline"')); ?>
	<table class="table table-striped">
		<thead>
			<tr>
				<th><input type="checkbox" id="gc_check_all" /> <button type="submit" class="btn btn-small btn-danger"><i class="icon-trash icon-white"></i></button></th>
				<th><?php echo sort_url('User', 'firstname', $order_by, $sort_order, $this->config->item('admin_folder'), $productId); ?></th>
				<th>Review</th>
				<th><?php echo sort_url('Date', 'date', $order_by, $sort_order, $this->config->item('admin_folder'), $productId); ?></th>
				<th><?php echo sort_url('Action', 'is_approved', $order_by, $sort_order, $this->config->item('admin_folder'), $productId); ?></th>
				
			</tr>
		</thead>
		<tbody>
		<?php echo (count($reviews) < 1) ?'<tr><td style="text-align:center;" colspan="7">No reviews has been added for this product</td></tr>':''?>
	<?php foreach ($reviews as $review):?>
			<tr>
				<td><input name="review[]" type="checkbox" value="<?php echo $review->review_id; ?>" class="gc_check"/></td>
				<td><?php echo $review->fullname; ?></td>
				<td><?php echo $review->review_content; ?></td>
				<td><?php echo date('M d, Y', strtotime($review->date)); ?></td>
				<td><a id="<?php echo $review->review_id; ?>" onclick="updateStatus('<?php echo $review->review_id; ?>');" href="javascript:void(0);"><?php echo ($review->is_approved == 0) ? '<i class="icon-remove"></i>' : '<i class="icon-ok"></i>'; ?></a></td>
				
			
			</tr>
	<?php endforeach; ?>
		</tbody>
	</table>
</form>