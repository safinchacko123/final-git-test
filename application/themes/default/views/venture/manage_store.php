<?php if (validation_errors()): ?>
    <div class="alert alert-danger">
        <a class="close" data-dismiss="alert">×</a>
        <?php echo validation_errors(); ?>
    </div>
<?php endif; ?>
<script type="text/javascript" src="<?php echo base_url('/application/themes/default/assets/js/jquery-ui.min.js');?>"></script>
<script src="https://maps.googleapis.com/maps/api/js?v=3.exp&sensor=false"></script>
<script src="<?php echo base_url('/application/themes/default/assets/js/jquery.validate.js');?>" type="text/javascript"></script>
<script src="<?php echo base_url('/application/themes/default/assets/js/venture/gmap.js');?>" type="text/javascript"></script>

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
                            <li class="<?php if ($link['menu_name'] == 'Stores') { ?> active <?php } ?>">
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
                <div class="row" style="text-align: center; margin-bottom: 10px;">
                    <input type="button" class="btn btn-info show_list" rel="0" value="list"/>
                    <input type="button" class="btn btn-info edit_store" rel="0" value="add store"/>
                </div>
                <div>
                    <div class="span7" id='address_list'>
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
                                                    <div class="btn-group pull-right">
                                                        <input type="button" class="btn edit_store" rel="<?php echo $a['id']; ?>" value="<?php echo lang('form_edit'); ?>" />
                                                        <input type="button" class="btn btn-danger delete_address" rel="<?php echo $a['id']; ?>" value="<?php echo lang('form_delete'); ?>" />
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