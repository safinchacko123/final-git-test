<?php
$venture_id = array('id' => 'venture_id', 'style' => 'display:none;', 'name' => 'venture_id', 'value' => set_value('id', $ventureDtl->id));
$f_address1 = array('id' => 'f_address1', 'class' => 'span12', 'name' => 'address1');
$f_phone = array('id' => 'f_phone', 'class' => 'span12', 'name' => 'phone');
$f_city = array('id' => 'f_city', 'class' => 'span12', 'name' => 'city');
$f_zip = array('id' => 'f_zip', 'maxlength' => '10', 'class' => 'span12', 'name' => 'zip');
$f_lat = array('id' => 'f_lat', 'maxlength' => '50', 'name' => 'f_lat', 'style' => 'display:none');
$f_long = array('id' => 'f_long', 'maxlength' => '50', 'name' => 'f_long', 'style' => 'display:none');
$f_coverage = array('id' => 'f_coverage', 'maxlength' => '50', 'name' => 'f_coverage', 'value' => '5');
echo form_input($venture_id);
?>
<div id="my-modal" style="width: 50%; margin: 0 auto;">
    <div class="modal-body">
        <div class="alert alert-danger hide" id="form-error">
            <a class="close" data-dismiss="alert">×</a>
        </div>

        <div class="alert alert-success">
            <div class="row-fluid">
                <div class="span12">
                    <h4>Store details</h4>
                </div>
            </div>
            <div class="row-fluid">
                <div class="span6">
                    <label><?php echo lang('address_phone'); ?></label>
                    <?php echo form_input($f_phone); ?>
                </div>
            </div>
        </div>

        <div class="alert alert-info">
            <div class="row-fluid">
                <div class="span12">
                    <h4>Address details</h4>
                </div>
            </div>

            <div class="row-fluid">
                <div class="span12">
                    <label><?php echo lang('address'); ?></label>
                    <?php
                    echo form_input($f_address1);
                    ?>
                </div>
            </div>

            <div class="row-fluid">
                <div class="span12">
                    <label><?php echo lang('address_country'); ?></label>
                    <?php //echo form_dropdown('country_id', $countries_menu, 'id="f_country_id" class="span12"'); ?>
                </div>
            </div>
            <div class="row-fluid">
                <div class="span4">
                    <label><?php echo lang('address_city'); ?></label>
                    <?php echo form_input($f_city); ?>
                </div>
<!--                <div class="span6">
                    <label><?php echo lang('address_state'); ?></label>
                    <?php //echo form_dropdown('zone_id', $zones_menu, 'id="f_zone_id" class="span12"'); ?>
                </div>-->
                <div class="span2">
                    <label><?php echo lang('address_zip'); ?></label>
                    <?php echo form_input($f_zip); ?>
                </div>
                <div>
                    <?php
                    echo form_input($f_lat);
                    echo form_input($f_long);
                    ?>
                </div>
            </div>
            <div class="row-fluid">
                <div class="span12">                    
                    <label>Coverage area (in Km)</label>
                    <?php echo form_input($f_coverage); ?>
                </div>
            </div>
        </div>
    </div>
    <div class="modal-footer">
        <a href="#" class="btn" data-dismiss="modal"><?php echo lang('close'); ?></a>
        <a href="javascript:void(0)" id="btnStep2" class="btn btn-primary" type="button">Next</a>
        <!--        <a href="#" class="btn btn-primary" type="button" onclick="save_venture();
                        return false;"><?php echo lang('form_submit'); ?></a>-->
    </div>
</div>