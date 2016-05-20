<?php if (validation_errors()): ?>
    <div class="alert alert-danger">
        <a class="close" data-dismiss="alert">×</a>
        <?php echo validation_errors(); ?>
    </div>
<?php endif; ?>
<script type="text/javascript" src="<?php echo base_url('application/themes/default/assets/js/jquery-ui.min.js');?>"></script>
<script src="https://maps.googleapis.com/maps/api/js?v=3.exp&sensor=false"></script>
<script src="<?php echo base_url('application/themes/default/assets/js/jquery.validate.js');?>" type="text/javascript"></script>
<script src="<?php echo base_url('application/themes/default/assets/js/customer/gmap.js');?>" type="text/javascript"></script>

<div class="span4" style="float: none; margin: 0 auto; width: 100%;">

    <p>
    <h2><?php echo lang('account_information'); ?></h2>
    <p>
    <div class="my-account-box">
        <?php echo form_open('secure/my_account'); ?>
        <fieldset>

            <div class="tabbable">
                <ul data-tabs="tabs" class="nav nav-tabs">
                    <?php
                    if ($this->customer) {
                        if ($this->customer['role_id'] == 0) {
                            $roleName = 'CustomerLinks';
                        } else {
                            $roleName = $this->customer['role']['role_name'] . 'Links';
                        }
                        foreach ($this->config->item($roleName) as $link) {
                            ?>
                            <li class="<?php if ($link['menu_name'] == 'Address') { ?> active <?php } ?>">
                                <?php if ($link['tab']) { ?>
                                    <a id="lnkInfo" href="#<?php echo $link['tab']; ?>" data-toggle="tab" class="clsTab"><?php echo ucfirst($link['menu_name']); ?></a>
                                <?php } else { ?>
                                    <a id="lnkInfo" href="<?php echo site_url($link['lnk']); ?>" class="clsTab"><?php echo ucfirst($link['menu_name']); ?></a>
                                <?php } ?>
                            </li>
                            <?php
                        }
                    }
                    ?>
                </ul>
            </div>

            <div class="tab-content addSec">
                <div class="width_100percent float-right marginBottom10">
                    <input type="button" class="btn btn-info show_list" rel="0" value="list"/>
                    <input type="button" class="btn btn-info edit_address" rel="0" value="<?php echo lang('add_address'); ?>"/>
                </div>
                <div>
                    <div class="width_100percent float-left" id='address_list'>
                        <?php if (count($addresses) > 0): ?>
                            <table class="table table-bordered">
                                <?php
                                $c = 1;
                                foreach ($addresses as $a):
                                    ?>
                                    <tr id="address_<?php echo $a['id']; ?>" class=" <?php
                                    if ($customer['default_billing_address'] == $a['id']) {
                                        echo "alert alert-success";
                                    }
                                    ?>">
                                        <td style="width: 60%">
                                            <?php
                                            $b = $a['field_data'];
                                            echo format_address($b, true);
                                            ?>
                                        </td>
                                        <td style="width: 40%">
                                            <div class="row-fluid">
                                                <div class="span12">
                                                    <div class="pull-right">
                                                        <input type="button" class="btn edit_address" rel="<?php echo $a['id']; ?>" value="<?php echo lang('form_edit'); ?>" />
                                                        <input type="button" class="btn btn-danger delete_address" rel="<?php echo $a['id']; ?>" value="<?php echo lang('form_delete'); ?>" />
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="row-fluid">
                                                <div class="span12">
                                                    <div class="pull-right" style="padding-top:10px;">
                                                        <input type="radio" name="bill_chk" onclick="set_default(<?php echo $a['id'] ?>, 'bill')" <?php if ($customer['default_billing_address'] == $a['id']) echo 'checked="checked"' ?> /> <?php echo lang('default_billing'); ?>
                                                        &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<input type="radio" name="ship_chk" onclick="set_default(<?php echo $a['id'] ?>, 'ship')" <?php if ($customer['default_shipping_address'] == $a['id']) echo 'checked="checked"' ?>/> <?php echo lang('default_shipping'); ?>
                                                    </div>
                                                </div>
                                            </div>
                                        </td>
                                    </tr>
                                <?php endforeach; ?>
                            </table>
                        <?php endif; ?>
                    </div>

                    <div id="address-form-container" class="hide">
                    </div>

                    <div style="text-align: center;">
                        <div id="address-map-container" style="height: 400px; width: 1100px; clear: both;">

                        </div>
                        <a href="javascript:void(0)" id="backStep2" class="btn btn-info" type="button">back</a>
                        <a href="javascript:void(0)" id="btnStep3" class="btn btn-primary" type="button">save</a>
                    </div>
                </div>
            </div>
        </fieldset>

    </div>
</div>

<!--<div id="address-form-container" class="hide">
</div>-->