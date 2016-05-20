<script type="text/javascript">
    function areyousure()
    {
        return confirm('<?php echo "Are you sure you want to delete this partner ?"; ?>');
    }

    function updateStatus(partner_id) {
        $.ajax({
            type: 'POST',
            cache: false,
            url: '<?php echo base_url(); ?>admin/partners/updatePartnerStatus/',
            data: {partner_id: partner_id},
            success: function (data) {
                if (data == 'false') {
                    alert('Please try after some time');
                } else {
                    $('#' + partner_id).html(data);
                }
            }
        });
    }

</script>
<div class="btn-group pull-right">
    <a class="btn" href="<?php echo site_url($this->config->item('admin_folder') . '/partners/export_xml'); ?>"><i class="icon-download"></i>Export Partners</a></div>
<table class="table table-striped partners">
    <thead>
        <tr>

            <?php
            if ($by == 'ASC') {
                $by = 'DESC';
            } else {
                $by = 'ASC';
            }
            ?>

<th><a href="<?php echo site_url($this->config->item('admin_folder') . '/partners/index/firstname/'); ?>/<?php echo ($field == 'firstname') ? $by : ''; ?>"><?php echo lang('firstname'); ?>
                    <?php
                    if ($field == 'firstname') {
                        echo ($by == 'ASC') ? '<i class="icon-chevron-down"></i>' : '<i class="icon-chevron-up"></i>';
                    }
                    ?></a>
            </th>

            <th><a href="<?php echo site_url($this->config->item('admin_folder') . '/partners/index/lastname/'); ?>/<?php echo ($field == 'lastname') ? $by : ''; ?>"><?php echo lang('lastname'); ?>
                    <?php
                    if ($field == 'lastname') {
                        echo ($by == 'ASC') ? '<i class="icon-chevron-down"></i>' : '<i class="icon-chevron-up"></i>';
                    }
                    ?> </a>
            </th>

            <th><a href="<?php echo site_url($this->config->item('admin_folder') . '/partners/index/email/'); ?>/<?php echo ($field == 'email') ? $by : ''; ?>"><?php echo lang('email'); ?>
                    <?php
                    if ($field == 'email') {
                        echo ($by == 'ASC') ? '<i class="icon-chevron-down"></i>' : '<i class="icon-chevron-up"></i>';
                    }
                    ?></a>
            </th>
            <th>
                <a href="<?php echo site_url($this->config->item('admin_folder').'/partners/index/partner_id/');?>/<?php echo ($field == 'partner_id')?$by:'';?>"><?php echo lang('partner_vendors');?>  
                <?php if($field == 'partner_id'){ echo ($by == 'ASC')?'<i class="icon-chevron-down"></i>':'<i class="icon-chevron-up"></i>';} ?>
                </a>
            </th>
            <th>
                <a href="<?php echo site_url($this->config->item('admin_folder').'/partners/index/active/');?>/<?php echo ($field == 'active')?$by:'';?>"><?php echo lang('active');?>    
                <?php if($field == 'active'){ echo ($by == 'ASC')?'<i class="icon-chevron-down"></i>':'<i class="icon-chevron-up"></i>';} ?>
                </a>
            </th>
            <th>
                <a href="<?php echo site_url($this->config->item('admin_folder').'/partners/index/created_on/');?>/<?php echo ($field == 'created_on')?$by:'';?>"><?php echo lang('created_on');?>  
                <?php if($field == 'created_on'){ echo ($by == 'ASC')?'<i class="icon-chevron-down"></i>':'<i class="icon-chevron-up"></i>';} ?>
                </a>
            </th>
        </tr>
    </thead>

    <tbody>
        <?php
        $page_links = $this->pagination->create_links();

        if ($page_links != ''):
            ?>
            <tr><td colspan="5" style="text-align:center"><?php echo $page_links; ?></td></tr>
        <?php endif; ?>
        <?php echo (count($partners) < 1) ? '<tr><td style="text-align:center;" colspan="5">There are no ventures</td></tr>' : '' ?>
        <?php foreach ($partners as $customer): ?>
            <tr>
                <td class="gc_cell_left"><?php echo $customer->firstname; ?></td>
                <td><?php echo $customer->lastname; ?></td>
                <td><a href="mailto:<?php echo $customer->email; ?>"><?php echo $customer->email; ?></a></td>
                <td><a href="#" class="partneredvendors" data-pid="<?php echo $customer->id; ?>"><?php echo $customer->vendor_count; ?></a></td>
                <td><a id="<?php echo $customer->id; ?>" onclick="updateStatus('<?php echo $customer->id; ?>');" href="javascript:void(0);"><?php echo ($customer->active == 0) ? '<i class="icon-remove"></i>' : '<i class="icon-ok"></i>'; ?></a></td>
                <td>
                    <?php echo date('d/m/Y', strtotime($customer->created_on)); ?>
                </td>

            </tr>
            <?php
        endforeach;
        if ($page_links != ''):
            ?>
            <tr><td colspan="5" style="text-align:center"><?php echo $page_links; ?></td></tr>
        <?php endif; ?>
    </tbody>
</table>

<div class="alert alert-info marginAuto">Become a partner requests</div>
<table class="table table-striped">
    <thead>
        <tr>
            <th>Partner Name</th>
            <th>Documents</th>
            <th>Vendor Name</th>
            <th>Status</th>
            <th>Requested on</th>
            <th>Share Percentage</th>
            <th>Action</th>
        </tr>
    </thead>
    <tbody>
        <?php
        if (count($becomepartner) > 0) {
            foreach ($becomepartner As $rowReq) {
                ?>
                <tr>
                    <td>
                        <?php echo $rowReq->partner_firstname . " " . $rowReq->partner_lastname; ?>
                    </td>
                    <td>
                        <?php foreach ($rowReq->partner_doc As $rowDoc) { ?>
                            <a href="<?php echo base_url($rowDoc->path . "/" . $rowDoc->document_type); ?>" target="_blank"><?php echo $rowDoc->document_type; ?></a>
                        <?php } ?>
                    </td>
                    <td><?php echo $rowReq->vendor_id->firstname . " " . $rowReq->vendor_id->lastname; ?></td>
                    <td>
                        <?php
                        if ($rowReq->approved) {
                            echo '<div class="span1 alert alert-success">Approved<div>';
                        } else {
                            echo '<div class="span1 alert alert-notice">Pending<div>';
                        }
                        ?>
                    </td>
                    <td><?php echo $rowReq->requested_on; ?></td>
                    <td><input class="span1" type="text" value="<?php echo $rowReq->share_percentage; ?>"/></td>
                    <td>
                        <?php if (!$rowReq->approved) { ?>
                            <input type="button" value="Approve" class="btn btn-primary">
                        <?php } ?>
                    </td>
                </tr>
                <?php
            }
        }
        ?>
    </tbody>
</table>
<div id="partnered-vendors"></div>