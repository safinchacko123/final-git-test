<style type="text/css">
    .tab-content{
        overflow: scroll !important;
    }
</style>
<?php if (validation_errors()): ?>
    <div class="alert alert-danger">
        <a class="close" data-dismiss="alert">×</a>
        <?php echo validation_errors(); ?>
    </div>
<?php endif; ?>
<script type="text/javascript" src="<?php echo base_url('/application/themes/default/assets/js/jquery-ui.min.js'); ?>"></script>
<script src="https://maps.googleapis.com/maps/api/js?v=3.exp&sensor=false"></script>
<script src="<?php echo base_url('/application/themes/default/assets/js/jquery.validate.js'); ?>" type="text/javascript"></script>
<script src="<?php echo base_url('/application/themes/default/assets/js/vendor/gmap.js'); ?>" type="text/javascript"></script>            

<div class="span4" style="float: none; margin: 0 auto; width: 100%;">

    <p>
    <h2><?php echo lang('account_information'); ?></h2>
    <p>
    <div class="my-account-box">
        <fieldset>

            <div class="tabbable">
                <ul data-tabs="tabs" class="nav nav-tabs">
                    <li>
                        <a id="lnkInfo" href="<?php echo site_url('secure/my_account'); ?>" class="clsTab">Profile</a>
                    </li>
                    <li>
                        <a id="lnkPass" href="<?php echo site_url('secure/my_account?cp=1'); ?>" class="clsTab">Change password</a>
                    </li>
                    <li class="active">
                        <a id="lnkVen" href="<?php echo site_url('secure/manage_ventures'); ?>" class="clsTab">Ventures</a>
                    </li>
                </ul>
            </div>

            <div class="tab-content" style="height: 540px; overflow: hidden; padding-top: 10px;">
                <div class="width_60percent float-left marginBottom10">
                    <input type="button" class="btn btn-info show_list" rel="0" value="list"/>
                    <input type="button" class="btn btn-info add_venture" rel="0" value="Add venture"/>
                </div>
                <div style="clear: both">
                    <div class="" id='venture_list' style="height: 540px;">
                        <?php if (count($ventures) > 0): ?>
                            <table class="table table-bordered table-striped clsMargin float-left width_100percent marginTop10">
                                <tr>
                                    <td colspan="2">
                                        <h4>Manager details</h4>
                                    </td>
                                    <td colspan="2">
                                        <h4>Address</h4>
                                    </td>
                                </tr>
                                <?php
                                $c = 1;
                                foreach ($ventures as $v):
                                    ?>
                                    <tr id="venture_<?php echo $v->id; ?>">
                                        <td>                                            
                                            <div>Name: <?php echo $v->firstname . " " . $v->lastname; ?></div>
                                            <div>Email: <?php echo $v->email; ?></div>
                                        </td>
                                        <td style="border-left:0">
                                            <div class="row-fluid">
                                                <div class="span12">
                                                    <div class="pull-right">
        <!--                                                        <i class="icon-pencil edit_venture" rel="<?php echo $v->id; ?>" title="Edit venture"></i>
                                                        <i class="icon-pencil delete_venture" rel="<?php echo $v->id; ?>" title="Delete venture"></i>-->
                                                        <input type="button" class="btn edit_venture paddingRight10" rel="<?php echo $v->id; ?>" value="<?php echo lang('form_edit'); ?>" />
                                                        <input type="button" class="btn btn-danger delete_venture" rel="<?php echo $v->id; ?>" value="<?php echo lang('form_delete'); ?>" />
                                                    </div>
                                                </div>
                                            </div>
                                        </td>
                                        <?php if ($v->va_id) { ?>
                                            <td>
                                                <div class="row-fluid">
                                                    <div class="span12">
                                                        <div>address: <?php echo $v->va_address; ?></div>
                                                        <div>city: <?php echo $v->va_city; ?></div>
                                                        <div>country: <?php echo $v->va_country; ?></div>
                                                        <div>zip: <?php echo $v->va_zip; ?></div>
                                                    </div>
                                                </div>
                                            </td>
                                            <td style="border-left:0">
                                                <div class="pull-right">
                                                    <input type="button" class="btn edit_address" rel="<?php echo $v->va_id; ?>" value="<?php echo lang('form_edit'); ?>" />
                                                    <input type="button" class="btn btn-danger delete_address" rel="<?php echo $v->va_id; ?>" value="<?php echo lang('form_delete'); ?>" />
                                                </div>
                                            </td>
                                        <?php } else { ?>
                                            <td colspan="2">
                                                <div class="pull-right">
                                                    <input type="button" class="btn add_address" rel="<?php echo $v->id; ?>" value="Add" />
                                                </div>
                                            </td>
                                        <?php } ?>
                                    </tr>
                                <?php endforeach; ?>
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