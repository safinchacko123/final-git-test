<?php
//set "code" for searches
if (!$code) {
    $code = '';
} else {
    $code = '/' . $code;
}

function sort_url($lang, $by, $sort, $sorder, $code, $admin_folder) {
    if ($sort == $by) {
        if ($sorder == 'asc') {
            $sort = 'desc';
            $icon = ' <i class="icon-chevron-up"></i>';
        } else {
            $sort = 'asc';
            $icon = ' <i class="icon-chevron-down"></i>';
        }
    } else {
        $sort = 'asc';
        $icon = '';
    }


    $return = site_url($admin_folder . '/orders/index/' . $by . '/' . $sort . '/' . $code);

    echo '<a href="' . $return . '">' . lang($lang) . $icon . '</a>';
}

if ($term):
    ?>

    <div class="alert alert-info">
    <?php echo sprintf(lang('search_returned'), intval($total)); ?>
    </div>
<?php endif; ?>

<style type="text/css">
    .pagination {
        margin:0px;
        margin-top:-3px;
    }
    
	.top-revenue-label.orders {  float: left;  width: 100%;padding: 0 0 10px;}
	.top-revenue-label.orders > h1 {  display: inline-block;  width: auto;}
	.top-revenue-label.orders > ul {  clear: both;  float: right;  margin: 7px 0 0;  padding: 0;  width: auto;}
	.top-revenue-label.orders li {  border-right: 1px solid;  display: inline-block;  font-weight: bold;  list-style-type: none;  margin: 0;  padding-right: 5px;}
	.top-revenue-label.orders li:last-child {  border: medium none;}    
	#releasedPaymentPopup-loader {    margin: 0 0 0 16px;display:none}
</style>
<div class="row">
<?php 
$adminDetail = $this->session->userdata('admin');
$currentClass =  $this->router->fetch_class(); 
$currentMethod =  $this->router->fetch_method();
?>
	<div class="top-revenue-label orders">
		
		<?php if($currentClass=='orders' && $currentMethod=='index') {  ?>
		<ul >
<?php 	if($adminDetail['access'] == 'Admin')
		{  ?>
			<li>Total Sale : <?php if(!empty($revenueDetail->sum_sub_total)) {  echo  number_format($revenueDetail->sum_sub_total,2,".",","); } else { echo "00.00"; } ?></li>
			<li>Total Revenue : <?php if(!empty($revenueDetail->admin_ren)) {  echo  number_format($revenueDetail->admin_ren,2,".",","); } else { echo "00.00"; } ?></li>
			<li>Partners Share : <?php if(!empty($revenueDetail->partner_ren)) {  echo  number_format($revenueDetail->partner_ren,2,".",","); } else { echo "00.00"; } ?></li>
			<li>Dropneed Share : <?php if(!empty($revenueDetail->admin_ren)) {  echo  number_format($revenueDetail->admin_ren-$revenueDetail->partner_ren,2,".",","); } else { echo "00.00"; } ?></li>
<?php   }  ?>			
<?php 	if($adminDetail['access'] == 'Partner')
		{
			
			if(!empty($revenueDetail->partner_ren)) {  
				$pending_revenue = $revenueDetail->partner_ren-$partner_released_payment;
			} else { $pending_revenue = 0; }
			  ?>
			<li>Total Revenue  : <?php if(!empty($revenueDetail->partner_ren)) {  echo  number_format($revenueDetail->partner_ren,2,".",","); } else { echo "00.00"; } ?></li>
			<li>Widraw Revenue  : <?php if(!empty($revenueDetail->partner_ren)) {  echo  number_format($partner_released_payment,2,".",","); } else { echo "00.00"; } ?></li>
			<li>Pending Revenue  : <?php echo number_format($pending_revenue,2,".",","); ?></li>
			<?php if($pending_revenue >= 1000 ) { ?><a href="javascript:void(0)" data-partnerId="<?php echo $adminDetail['id']; ?>" id="btn-orderReleasePayment">Release payment now</a><?php } ?>
<?php   }  ?>
<?php 	if($adminDetail['access'] == 'Venture')
		{  ?>
			<li>Total Sale : <?php if(!empty($revenueDetail->sum_sub_total)) {  echo  number_format($revenueDetail->sum_sub_total,2,".",","); } else { echo "00.00"; } ?></li>
			<li>Dropneed Share : <?php if(!empty($revenueDetail->admin_ren)) {  echo  number_format($revenueDetail->admin_ren,2,".",","); } else { echo "00.00"; } ?></li>
			<li>My Sale : <?php if(!empty($revenueDetail->sum_sub_total)) {  echo  number_format($revenueDetail->sum_sub_total-$revenueDetail->admin_ren,2,".",","); } else { echo "00.00"; } ?></li>
			
<?php   }  ?>			
		</ul>	
		<?php } ?>
	</div>	
	
	<div class="span12" style="border-bottom:1px solid #f5f5f5;">
		<div class="row">
			<div class="span4">
				<?php echo $this->pagination->create_links();?>&nbsp;
			</div>
			<div class="span8">
				<?php echo form_open($this->config->item('admin_folder').'/orders/index', 'class="form-inline" style="float:right"');?>
					<fieldset>
                                        <?php if($this->customer['access']!='Venture') { ?>
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
                                        <?php } ?>
						<input id="start_top"  value="" class="span2" type="text" placeholder="Start Date"/>
						<input id="start_top_alt" type="hidden" name="start_date" />
						<input id="end_top" value="" class="span2" type="text"  placeholder="End Date"/>
						<input id="end_top_alt" type="hidden" name="end_date" />
				
						<input id="top" type="text" class="span2" name="term" placeholder="<?php echo lang('term')?>" /> 

						<button class="btn" name="submit" value="search"><?php echo lang('search')?></button>
						<button class="btn" name="submit" value="export"><?php echo lang('xml_export')?></button>
					</fieldset>
				</form>
			</div>
		</div>
	</div>
</div>

<?php echo form_open($this->config->item('admin_folder') . '/orders/bulk_delete', array('id' => 'delete_form', 'onsubmit' => 'return submit_form();', 'class="form-inline"')); ?>

<table class="table">
    <thead>
		<tr>
			
			<th><?php echo sort_url('order', 'order_number', $sort_by, $sort_order, $code, $this->config->item('admin_folder')); ?></th>
			<th>Vendor</th>
			<th>Venture</th>

            <?php if ($this->customer['access'] != 'Partner') { ?>
			<th><?php echo sort_url('ship_to', 'ship_lastname', $sort_by, $sort_order, $code, $this->config->item('admin_folder')); ?></th>
            <?php } ?>

            <th><?php echo sort_url('ordered_on', 'ordered_on', $sort_by, $sort_order, $code, $this->config->item('admin_folder')); ?></th>
            <th><?php echo sort_url('shipped_on', 'shipped_on', $sort_by, $sort_order, $code, $this->config->item('admin_folder')); ?></th>
            <th><?php echo sort_url('status', 'status', $sort_by, $sort_order, $code, $this->config->item('admin_folder')); ?></th>
            <th><?php echo sort_url('total', 'total', $sort_by, $sort_order, $code, $this->config->item('admin_folder')); ?></th>
            <?php if ($this->customer['access'] == '' || $this->customer['access'] == 'Venture') { ?>
                <th><?php echo lang('venture_percentage'); ?></th>
            <?php } ?>
            <?php if ($this->customer['access'] == '') { ?>
                <th><?php echo lang('admin_percentage'); ?></th>
            <?php } ?>
            <?php if ($this->customer['access'] == '' || $this->customer['access'] == 'Partner') { ?>
                <th><?php echo lang('partner_percentage'); ?></th>
            <?php } ?>
            <th style="width: 72px;"><?php
                if ($this->customer['access'] != 'Partner') {
                    echo lang('action');
                }
                ?></th>
	    </tr>
	</thead>

    <tbody>
        <?php echo (count($orders) < 1) ? '<tr><td style="text-align:center;" colspan="8">' . lang('no_orders') . '</td></tr>' : '' ?>
<?php foreach ($orders as $order): ?>  
    <?php ($order->status == "order_disputed") ? $disputed_class = "text-warning" : $disputed_class = ""; ?>
            <tr class="<?php echo $disputed_class; ?>">        
		
		<td><?php echo $order->order_number; ?></td>
		<td style="white-space:nowrap"><?php echo $order->vendorName; ?></td>
		<td style="white-space:nowrap"><?php echo $order->company; ?></td>
                <?php if ($this->customer['access'] != 'Partner') { ?>
                    <td style="white-space:nowrap"><?php echo $order->ship_lastname . ', ' . $order->ship_firstname; ?></td>
                    <?php } ?>
		<td style="white-space:nowrap"><?php echo date('m/d/y h:i a', strtotime($order->ordered_on)); ?></td>
                <td style="white-space:nowrap"><?php
                    if ($order->shipped_on != '0000-00-00 00:00:00') {
                        echo date('m/d/y h:i a', strtotime($order->shipped_on));
                    } else {
                        echo 'NA';
                    }
                    ?></td>

		<td style="span2">
			
                <?php echo lang($order->status); ?>
		</td>
		<td><div class="MainTableNotes"><?php echo format_currency($order->total); ?></div></td>
                <?php
                $venture_share = $order->subtotal * 95 / 100;
                $admin_share = $order->subtotal * 5 / 100;
                $partner_share = $admin_share * intval($order->share_percentage) / 100;
                $final_admin_share = $admin_share - $partner_share;
                ?>
                        <?php if ($this->customer['access'] == '' || $this->customer['access'] == 'Venture') { ?>
		<td>
                        <div class="MainTableNotes">
                    <?php echo number_format($venture_share, 2, '.', ''); ?>
                        </div>
		</td>
    <?php } ?>
                        <?php if ($this->customer['access'] == '') { ?>
                    <td>
                        <div class="MainTableNotes">
                    <?php echo number_format($final_admin_share, 2, '.', ''); ?>
                        </div>
                    </td>
    <?php } ?>
                        <?php if ($this->customer['access'] == '' || $this->customer['access'] == 'Partner') { ?>
                    <td>
                        <div class="MainTableNotes">
                    <?php echo number_format($partner_share, 2, '.', ''); ?>
                        </div>
                    </td>
                    <?php } ?>
                <td>   
                    <?php if ($this->customer['access'] == '' || $this->customer['access'] == 'Venture') { ?>
                        <a class="btn btn-small <?php if ($order->status != 'order_delivered') { ?>disabled<?php } ?>" style="float:right;" <?php if ($order->status == 'order_delivered') { ?>href="<?php echo site_url('/invoice/' . $order->id.'/1'); ?>"<?php } ?>><i class="icon-download-alt"></i></a>
    <?php } if ($this->customer['access'] != 'Partner') { ?>
                        <a class="btn btn-small" style="float:left;" href="<?php echo site_url($this->config->item('admin_folder') . '/orders/order/' . $order->id); ?>"><i class="icon-search"></i> <?php echo lang('form_view') ?></a> <?php } ?>
                </td>
	</tr>
    <?php endforeach; ?>
    </tbody>
</table>

</form>
<script type="text/javascript">
    $(document).ready(function() {
        $('#gc_check_all').click(function() {
            if (this.checked)
            {
                $('.gc_check').attr('checked', 'checked');
            }
            else
            {
                $(".gc_check").removeAttr("checked");
            }
        });

        // set the datepickers individually to specify the alt fields
        $('#start_top').datepicker({dateFormat: 'mm-dd-yy', altField: '#start_top_alt', altFormat: 'yy-mm-dd'});
        $('#start_bottom').datepicker({dateFormat: 'mm-dd-yy', altField: '#start_bottom_alt', altFormat: 'yy-mm-dd'});
        $('#end_top').datepicker({dateFormat: 'mm-dd-yy', altField: '#end_top_alt', altFormat: 'yy-mm-dd'});
        $('#end_bottom').datepicker({dateFormat: 'mm-dd-yy', altField: '#end_bottom_alt', altFormat: 'yy-mm-dd'});
    });

    function do_search(val)
    {
        $('#search_term').val($('#' + val).val());
        $('#start_date').val($('#start_' + val + '_alt').val());
        $('#end_date').val($('#end_' + val + '_alt').val());
        $('#search_form').submit();
    }

    function do_export(val)
    {
        $('#export_search_term').val($('#' + val).val());
        $('#export_start_date').val($('#start_' + val + '_alt').val());
        $('#export_end_date').val($('#end_' + val + '_alt').val());
        $('#export_form').submit();
    }

    function submit_form()
    {
        if ($(".gc_check:checked").length > 0)
        {
            return confirm('<?php echo lang('confirm_delete_order') ?>');
        }
        else
        {
            alert('<?php echo lang('error_no_orders_selected') ?>');
            return false;
        }
    }

    function save_status(id)
    {
        show_animation();
        $.post("<?php echo site_url($this->config->item('admin_folder') . '/orders/edit_status'); ?>", {id: id, status: $('#status_form_' + id).val()}, function(data) {
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
    <img id="saving_animation" src="<?php echo base_url('assets/img/storing_animation.gif'); ?>" alt="saving" style="z-index:100001; margin-left:-32px; margin-top:-32px; position:fixed; left:50%; top:50%"/>
    <div id="saving_text" style="text-align:center; width:100%; position:fixed; left:0px; top:50%; margin-top:40px; color:#fff; z-index:100001"><?php echo lang('saving'); ?></div>
</div>
<input type="hidden" name="" id="order-project_url" data-value="<?php echo base_url(); ?>" />
<div id="releasedPaymentPopup-popup"></div>

<div id="releasedPayment-dialog-form" title="Release Payment">
  <p class="validateTips"></p>
 
  <form >
    <fieldset>
      <label for="name">Amount to withdraw</label>
      <input type="text" name="release_amount" id="release_amount" value="" onkeypress='return event.charCode >= 48 && event.charCode <= 57' required class="text ui-widget-content ui-corner-all">
      <br>
      <label for="name">Enter Bank detail</label>
      <textarea name="bank_detail" id="bank_detail" required ></textarea>
      <!-- Allow form submission with keyboard without duplicating the dialog button -->
      <input type="button" id="btn-releasePayment-request" value="Submit" tabindex="-1" >
      <img src="<?php echo site_url('assets/img/form-loader.gif'); ?>" id="releasedPaymentPopup-loader"  />
    </fieldset>
  </form>
</div>
