<?php
$f_id = array('id' => 'f_id', 'style' => 'display:none', 'name' => 'id', 'value' => set_value('id', $adrDtls->venture_id));
$f_addrress_id = array('id' => 'f_address_id', 'style' => 'display:none', 'name' => 'address_id', 'value' => set_value('id', $adrDtls->id));
$f_address = array('id' => 'f_address', 'class' => 'span12', 'name' => 'address', 'value' => set_value('id', $adrDtls->address));
$f_city = array('id' => 'f_city', 'class' => 'span12', 'name' => 'city', 'value' => set_value('id', $adrDtls->city));
$f_zip = array('id' => 'f_zip', 'maxlength' => '50', 'class' => 'span12', 'name' => 'zip', 'value' => set_value('id', $adrDtls->zip));
$f_coverage = array('id' => 'f_coverage', 'maxlength' => '50', 'class' => 'span12', 'name' => 'coverage', 'value' => set_value('id', $adrDtls->coverage_area));
$f_lat = array('id' => 'f_lat', 'maxlength' => '50', 'name' => 'f_lat', 'style' => 'display:none', 'value' => set_value('id', $adrDtls->latitude));
$f_long = array('id' => 'f_long', 'maxlength' => '50', 'name' => 'f_long', 'style' => 'display:none', 'value' => set_value('id', $adrDtls->longitude));

echo form_input($f_id);
echo form_input($f_addrress_id);
?>
<div class="clsMargin addressSection thumbnail" id="my-modal">
    <form id="frmEVA">
        <div class="modal-header">
            <h3><?php echo lang('address_form'); ?></h3>
        </div>
        <div class="modal-body">
            <div class="alert alert-danger hide" id="form-error">
                <a class="close" data-dismiss="alert">×</a>
            </div>
            <div class="row-fluid">
                <div class="span12">
                    <label><?php echo lang('address'); ?></label>
                    <?php
                    echo form_input($f_address);
                    ?>
                </div>
            </div>
            <div class="row-fluid">
                <div class="span12">
                    <label><?php echo lang('address_country'); ?></label>
                    <?php echo form_dropdown('country_id', $countries_menu, set_value('country_id', $adrDtls->country_id), 'id="f_country_id" class="span12"'); ?>
                </div>
            </div>
            <div class="row-fluid">
                <div class="span4">
                    <label><?php echo lang('address_city'); ?></label>
                    <?php echo form_input($f_city); ?>
                </div>
<!--                <div class="span6">
                    <label><?php echo lang('address_state'); ?></label>
                    <?php //echo form_dropdown('zone_id', '', set_value('zone_id', ''), 'id="f_zone_id" class="span12"'); ?>
                </div>-->
                <div class="span2">
                    <label><?php echo lang('address_zip'); ?></label>
                    <?php echo form_input($f_zip); ?>
                </div>
                <div class="span12">
                    <label>Coverage area</label>
                    <?php echo form_input($f_coverage); ?>
                </div>
                <div>
                    <?php
                    echo form_input($f_lat);
                    echo form_input($f_long);
                    ?>
                </div>
            </div>
        </div>
        <div class="modal-footer">
            <a href="javascript:void(0)" id="markIt" class="btn btn-primary" type="button">Mark on graph</a>
            <a href="javascript:void(0)" id="saveVAdr" class="btn btn-primary" type="button">Save</a>
        </div>
    </form>
</div>