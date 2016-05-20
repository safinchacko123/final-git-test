<?php
$f_id = array('id' => 'f_id', 'style' => 'display:none;', 'name' => 'id', 'value' => set_value('id', $id));
$f_company = array('id' => 'f_company', 'class' => 'span12', 'name' => 'company', 'value' => set_value('company', $company));
$f_address1 = array('id' => 'f_address1', 'class' => 'span12', 'name' => 'address1', 'value' => set_value('address1', $address1));
$f_address2 = array('id' => 'f_address2', 'class' => 'span12', 'name' => 'address2', 'value' => set_value('address2', $address2));
$f_first = array('id' => 'f_firstname', 'class' => 'span12', 'name' => 'firstname', 'value' => set_value('firstname', $firstname));
$f_last = array('id' => 'f_lastname', 'class' => 'span12', 'name' => 'lastname', 'value' => set_value('lastname', $lastname));
$f_email = array('id' => 'f_email', 'class' => 'span12', 'name' => 'email', 'value' => set_value('email', $email));
$f_phone = array('id' => 'f_phone', 'class' => 'span12', 'name' => 'phone', 'value' => set_value('phone', $phone));
$f_city = array('id' => 'f_city', 'class' => 'span12', 'name' => 'city', 'value' => set_value('city', $city));
$f_zip = array('id' => 'f_zip', 'maxlength' => '50', 'class' => 'span12', 'name' => 'zip', 'value' => set_value('zip', $zip));
$f_lat = array('id' => 'f_lat', 'maxlength' => '50', 'name' => 'f_lat', 'style' => 'display:none');
$f_long = array('id' => 'f_long', 'maxlength' => '50', 'name' => 'f_long', 'style' => 'display:none');

echo form_input($f_id);
?>
<div class="clsMargin" id="my-modal" style="width:50%">
    <div class="modal-header">
        <h3><?php echo lang('address_form'); ?></h3>
    </div>
    <div class="modal-body">
        <div class="alert alert-danger hide" id="form-error">
            <a class="close" data-dismiss="alert">×</a>
        </div>
        <div class="row-fluid hide">
            <div class="span6">
                <label><?php echo lang('address_firstname'); ?></label>
                <?php echo form_input($f_first); ?>
            </div>
            <div class="span6">
                <label><?php echo lang('address_lastname'); ?></label>
                <?php echo form_input($f_last); ?>
            </div>
        </div>
        <div class="row-fluid">
            <div class="span6">
                <label style="float: left">Name: </label>
                <?php echo $firstname . " " . $lastname; ?>
            </div>
        </div>
        <div class="row-fluid">
            <div class="span6">
                <label><?php echo lang('address_email'); ?></label>
                <?php echo form_input($f_email); ?>
            </div>
            <div class="span6">
                <label><?php echo lang('address_phone'); ?></label>
                <?php echo form_input($f_phone); ?>
            </div>
        </div>
        <div class="row-fluid">
            <div class="span12">
                <label><?php echo lang('address_company'); ?></label>
                <?php echo form_input($f_company); ?>
            </div>
        </div>
        <div class="row-fluid">
            <div class="span12">
                <label><?php echo lang('address'); ?></label>
                <?php
                echo form_input($f_address1);
                //echo form_input($f_address2);
                ?>
            </div>
        </div>
        <div class="row-fluid">
            <div class="span12">
                <label><?php echo lang('address_country'); ?></label>
                <?php echo form_dropdown('country_id', $countries_menu, set_value('country_id', $country_id), 'id="f_country_id" class="span12"'); ?>
            </div>
        </div>
        <div class="row-fluid">
            <div class="span4">
                <label><?php echo lang('address_city'); ?></label>
                <?php echo form_input($f_city); ?>
            </div>
            <div class="span6">
                <label><?php echo lang('address_state'); ?></label>
                <?php echo form_dropdown('zone_id', $zones_menu, set_value('zone_id', $zone_id), 'id="f_zone_id" class="span12"'); ?>
            </div>
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
    </div>
    <div class="modal-footer">
        <a href="javascript:void(0)" id="btnStep2" class="btn btn-primary" type="button">Next</a>
    </div>
</div>

<script>
    $(document).ready(function() {
        $('#f_country_id').change(function() {
            $.post('<?php echo site_url('locations/get_zone_menu'); ?>', {id: $('#f_country_id').val()}, function(data) {
                $('#f_zone_id').html(data);
            });
        });
    });
</script>