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
                            <li class="<?php if ($link['menu_name'] == 'address') { ?> active <?php } ?>">
                                <?php if ($link['tab']) { ?>
                                                                        <a id="lnkPass" href="<?php echo site_url('secure/my_account?cp=1'); ?>" class="clsTab"><?php echo ucfirst($link['menu_name']); ?></a>
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

            <div class="tab-content" style="padding-top: 10px;">
                <div style="clear: both">
                    <div class="width_100percent" id='venture_list' style="">
                        <?php if (count($address) > 0): ?>
                            <table style="width: 100%; margin-top: 10px;" class="table table-bordered table-striped clsMargin">
                                <td>
                                    <div class="row-fluid">
                                        <div class="span12">
                                            <div><b>Address:</b> <?php echo $address->address; ?></div>
                                            <div><b>City:</b> <?php echo $address->city; ?></div>
                                            <div><b>Country:</b> <?php echo $address->country; ?></div>
                                            <div><b>Zip:</b> <?php echo $address->zip; ?></div>
                                        </div>
                                    </div>
                                </td>
                                <td style="border-left:0;text-align: right;">
                                    <input type="button" class="btn edit_address" rel="<?php echo $address->id; ?>" value="<?php echo lang('form_edit'); ?>" />
                                    <input type="button" class="btn btn-danger delete_address" rel="<?php echo $address->id; ?>" value="<?php echo lang('form_delete'); ?>" />
                                </td>
                                </tr>
                            </table>
                        <?php endif; ?>
                    </div>

                    <div id="venture-form-container" class="hide">

                    </div>

                    <div id="venture-map-container" style="height: 600px; width: 1100px; clear: both;">

                    </div>
                </div>

        </fieldset>
    </div>
</div>

<!--<div id="venture-form-container" class="hide">

</div>-->