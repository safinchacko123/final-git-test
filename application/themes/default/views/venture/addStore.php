<?php if (validation_errors()): ?>
    <div class="alert alert-danger">
        <a class="close" data-dismiss="alert">×</a>
        <?php echo validation_errors(); ?>
    </div>
<?php endif; ?>
<script type="text/javascript" src="<?php echo base_url('/application/themes/default/assets/js/jquery-ui.min.js');?>"></script>
<script src="https://maps.googleapis.com/maps/api/js?v=3.exp&sensor=false"></script>
<script src="<?php echo base_url('/application/themes/default/assets/js/jquery.validate.js');?>" type="text/javascript"></script>
<script src="<?php echo base_url('/application/themes/default/assets/js/vendor/gmap.js');?>" type="text/javascript"></script>            

<div class="span4" style="float: none; margin: 0 auto; width: 100%;">

    <p>
    <h2><?php echo lang('account_information'); ?></h2>
    <p>
    <div class="my-account-box">
        <?php echo form_open('secure/my_account'); ?>
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
                <div class="span3" style="width: 97%; text-align: center;">
                    <input type="button" class="btn btn-info show_list" rel="0" value="list"/>
                    <input type="button" class="btn btn-info edit_venture" rel="0" value="Add venture"/>
                </div>
                <div>
                    <div class="span7" id='venture_list' style="height: 540px;">
                        <?php if (count($ventures) > 0): ?>
                            <table style="width: 60%; float: left;" class="table table-bordered table-striped">
                                <?php
                                $c = 1;
                                foreach ($ventures as $v):
                                    ?>
                                    <tr id="venture_<?php echo $v->id; ?>">
                                        <td>
                                            <h4>Manager details</h4>
                                            <div>Name: <?php echo $v->firstname . " " . $v->lastname; ?></div>
                                            <div>Email: <?php echo $v->email; ?></div>
                                        </td>
                                        <td>
                                            <div class="row-fluid">
                                                <div class="span12">
                                                    <div class="btn-group pull-right">
                                                        <input type="button" class="btn edit_venture" rel="<?php echo $v->id; ?>" value="<?php echo lang('form_edit'); ?>" />
                                                        <input type="button" class="btn btn-danger delete_venture" rel="<?php echo $v->id; ?>" value="<?php echo lang('form_delete'); ?>" />
                                                        <input type="button" style="clear:both" class="btn btn-info add_store" rel="<?php echo $v->id; ?>" value="Add store"/>
                                                    </div>
                                                </div>
                                            </div>
                                        </td>
                                    </tr>
                                <?php endforeach; ?>
                            </table>
                        <?php endif; ?>
                    </div>

                    <div id="venture-form-container" class="hide">
                    </div>

                    <div style="text-align: center;">
                        <div id="venture-map-container" style="height: 400px; width: 1100px; clear: both;">

                        </div>
                        <a href="javascript:void(0)" id="backStep2" class="btn btn-info" type="button">back</a>
                        <a href="javascript:void(0)" id="btnStep3" class="btn btn-primary" type="button">save</a>
                    </div>

                    <div id="venture-store-container" class="hide">
                    </div>

                    <div style="text-align: center;">
                        <div id="venture-storeMap-container" style="height: 400px; width: 1100px; clear: both;">

                        </div>
                        <a href="javascript:void(0)" id="backStep2" class="btn btn-info" type="button">back</a>
                        <a href="javascript:void(0)" id="btnStep3" class="btn btn-primary" type="button">save</a>
                    </div>

                </div>

        </fieldset>
    </div>
</div>

<!--<div id="venture-form-container" class="hide">

</div>-->