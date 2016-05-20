<?php
$f_id = array('id' => 'f_id', 'style' => 'display:none;', 'name' => 'id');
$f_company = array('id' => 'f_company', 'class' => 'span12 required', 'name' => 'company');
$f_first = array('id' => 'f_firstname', 'class' => 'span12 required', 'name' => 'firstname');
$f_last = array('id' => 'f_lastname', 'class' => 'span12 required', 'name' => 'lastname');
$f_email = array('id' => 'f_email', 'class' => 'span12 required', 'name' => 'email');
$f_phone = array('id' => 'f_phone', 'class' => 'span12 required', 'name' => 'phone');
$f_line1 = array('id' => 'f_line1', 'class' => 'span12 required', 'name' => 'ventureaddress_line1');
$f_line2 = array('id' => 'f_line2', 'class' => 'span12', 'name' => 'ventureaddress_line2');
$f_city = array('id' => 'f_city', 'class' => 'span12 required', 'name' => 'venturecity');
$f_state = array('id' => 'f_state', 'class' => 'span12 required', 'name' => 'venturestate');
//$f_country = array('id' => 'f_country', 'class' => 'span12 required', 'name' => 'venturecountry');
$f_zipcode = array('id' => 'f_zipcode', 'class' => 'span12 required', 'name' => 'venturezipcode');
$f_company = array('id' => 'f_company', 'class' => 'span12 required', 'name' => 'address_company');
$f_license = array('id' => 'f_license', 'class' => 'span12 required', 'name' => 'license_no');
echo form_input($f_id);
?>
<div id="my-modal" style="width: 50%; margin: 0 auto;">
    <div class="modal-body">
        <form id="frmAddVen" enctype="multipart/form-data">
            <div class="alert alert-danger hide" id="form-error">
                <a class="close" data-dismiss="alert">×</a>
            </div>

            <div class="alert alert-success">
                <div class="row-fluid">
                    <div class="span12">
                        <h4>Manager details <small>[Password will be email to provided email address]</small></h4>
                    </div>
                </div>

                <div class="row-fluid">
                    <div class="span6">
                        <label><?php echo lang('address_firstname'); ?><span class="error">*</span></label>
                        <?php echo form_input($f_first); ?>
                    </div>
                    <div class="span6">
                        <label><?php echo lang('address_lastname'); ?><span class="error">*</span></label>
                        <?php echo form_input($f_last); ?>
                    </div>
                </div>
                <div class="row-fluid">
                    <div class="span6">
                        <label><?php echo lang('address_email'); ?><span class="error">*</span></label>
                        <div class="form-control-static">
                            <?php echo form_input($f_email); ?>
                        </div>
                    </div>
                    <div class="span6">
                        <label><?php echo lang('address_phone'); ?><span class="error">*</span></label>
                        <?php echo form_input($f_phone); ?>
                    </div>
                </div>        
                <div class="row-fluid">
                    <div class="span6">
                        <label><?php echo lang('address_line1'); ?><span class="error">*</span></label>
                        <?php echo form_input($f_line1); ?>
                    </div>
                    <div class="span6">
                        <label><?php echo lang('address_line2'); ?></label>
                        <?php echo form_input($f_line2); ?>
                    </div>
                </div>       
                <div class="row-fluid">
                    <div class="span6">
                        <label><?php echo lang('city'); ?><span class="error">*</span></label>
                        <?php echo form_input($f_city); ?>
                    </div>
                    <div class="span6">
                        <label><?php echo lang('state'); ?><span class="error">*</span></label>
                        <?php echo form_input($f_state); ?>
                    </div>
                </div>        
                <div class="row-fluid">
                    <div class="span6">
                        <label><?php echo lang('country'); ?><span class="error">*</span></label>
                        <?php //echo form_input($f_country); ?>
                        <select class="span12 required" id="f_country" name="venturecountry">
                            <option value="3">- Select Country -</option>
                            <?php if($countries) { ?>
                                <?php foreach($countries as $country) { ?>
                                    <option value="<?php echo $country->id; ?>"><?php echo $country->name; ?></option>
                                <?php } ?>
                            <?php } ?>
                        </select>
                    </div>
                    <div class="span6">
                        <label><?php echo lang('zipcode'); ?><span class="error">*</span></label>
                        <?php echo form_input($f_zipcode); ?>
                    </div>
                </div>       
                <div class="row-fluid">
                    <div class="span6">
                        <label><?php echo lang('address_company'); ?><span class="error">*</span></label>
                        <?php echo form_input($f_company); ?>
                    </div>
                    <div class="span6">
                        <label><?php echo lang('license_no'); ?><span class="error">*</span></label>
                        <?php echo form_input($f_license); ?>
                </div>        
                </div>

                <div class="row-fluid">
                    <div class="span6">
                        <label><?php echo lang('minimum_delivery_amount'); ?><span class="error">*</span></label>
                        <input type="text" id="f_minimum_delivery_amount" class="span12 " name="minimum_delivery_amount">
                    </div>
                    <div class="span6">
                        <label><?php echo lang('delivery_fee'); ?></label>
                        <input type="text" id="f_delivery_fee" class="span12" name="delivery_fee">
                    </div>
                    </div>

                <div class="row-fluid">                    
                    <div class="span6">
                        <label><?php echo lang('avg_delivery_time'); ?><span class="error">*</span></label>
                        <input type="text" id="f_avg_delivery_time" class="span12 " name="avg_delivery_time">
                </div>
                    <div class="span6">
                        <label><?php echo lang('payment_method'); ?><span class="error">*</span></label>
                        <select class="span12 " id="f_payment_method" name="payment_method">
                            <option value="">-Select Method-</option>
                            <option value="cash_on_delivery">Cash on delivery</option>
                            <option value="card_on_delivery">Credit/Debit Card on delivery</option>
                            <option value="paypal">Paypal</option>
                            <option value="paytm">Paytm</option>
                            <option value="card_online">Credit/Debit Card online</option>
                        </select>
                    </div>
                </div>

                <?php /*<div class="row-fluid">
                    <div class="span12 pad-bottom">
                        <label><?php echo lang('cuisine'); ?></label>                            
                        <?php
                        if ($cuisines) {
                            $i = 0;
                            $div = array();
                            ?>
                            <?php
                            foreach ($cuisines as $cuisine) {
                                $div[$i % 3] .= '<label class="checkbox clearfix"><input id="f_cuisine_id" name="cuisine_id[]" type="checkbox" value="' . $cuisine->cuisine_id . '" class="check">' . $cuisine->cuisine_name . '</label>';
                                $i++;
                            }
                            ?>
                        <?php } ?>
                        <div class="row-fluid">
                            <div class="span4">
                                <?php echo $div[0]; ?>
                    </div>
                            <div class="span4">
                                <?php echo $div[1]; ?>
                            </div>
                            <div class="span4">
                                <?php echo $div[2]; ?>
                            </div>
                        </div>
                    </div>
                </div> */ ?>

                <div id="addmoreafter" class="row-fluid">
                    <div class="span6">
                        <label><?php echo lang('working_days'); ?><span class="error">*</span></label>
                        <select class="span12 example" id="example-getting-started" name="days[]" multiple>
                            <option value="0">Sunday</option>
                            <option value="1">Monday</option>
                            <option value="2">Tuesday</option>
                            <option value="3">Wednesday</option>
                            <option value="4">Thursday</option>
                            <option value="5">Friday</option>
                            <option value="6">Saturday</option>
                        </select>
                    </div>
                    <div class="span3">
                        <label><?php echo lang('start_time'); ?><span class="error">*</span></label>
                        <input type="text" id="f_starttime" class="span12" name="start_time[]">
                    </div>
                    <div class="span3">
                        <label><?php echo lang('end_time'); ?><span class="error">*</span></label>
                        <input type="text" id="f_endtime" class="span12" name="end_time[]">
                </div>    
                </div>

                <div class="row-fluid">
                    <div class="span12">
                        <a id="addMore" href="#"><?php echo lang('add_more'); ?></a>&nbsp;&nbsp;
                        <a id="removeMore" href="#"><?php echo lang('remove'); ?></a>
                    </div>
                </div> 

                <div class="row-fluid">
                    <div class="span6">
                        <label><?php echo lang('license_details'); ?><span class="error">*</span></label>
                        <?php //echo form_input($f_company); ?>
                        <input type="file" class="required" name="licensedoc" size="20" />
                    </div>
                    <div class="span6">
                        <label><?php echo lang('venture_logo'); ?><span class="error">*</span></label>
                        <input type="file" class="required" id="f_venture_logo" name="venture_logo">
                        <p class="error" id="err_venture_logo"></p>
                </div>        
                </div>        
                <div class="row-fluid">
                    <div class="span12 clsMarCen">
                        <a href="javascript:void(0)" id="backStep2" class="btn btn-info" type="button">back</a>
                        <a href="javascript:void(0)" id="btnStep3" class="btn btn-primary" type="button">save</a>
                        <img id="loading" src="<?php echo base_url('assets/img/ajax-loader.gif');?>" alt="loading" />
                    </div>
                </div>  
            </div>
        </form>
    </div>

</div>

<script type="text/javascript">
    
    $(function () {
        var extDiv = $('#addmoreafter');
        var i = $('#removemoreafter').size() + 1;
        $('#removeMore').hide();
        
        $('#addMore').on('click', function () {
            $('#removeMore').show();
            $('<div id="removemoreafter" class="row-fluid"><div class="span6"><label><?php echo lang('additional_working_day'); ?></label><select class="span12 example" style="width:100%;" id=example-getting-started' + i + ' name="days[]" multiple><option value="0">Sunday</option><option value="1">Monday</option><option value="2">Tuesday</option><option value="3">Wednesday</option><option value="4">Thursday</option><option value="5">Friday</option><option value="6">Saturday</option></select></div><div class="span3"><label><?php echo lang('start_time'); ?></label><input type="text" id=f_starttime' + i + ' class="span12" name="start_time[]"></div><div class="span3"><label><?php echo lang('end_time'); ?></label><input type="text" id=f_endtime' + i + ' class="span12" name="end_time[]"></div></div>').appendTo(extDiv);
            $('.example').multiselect();
            $('#f_starttime' + i).timepicker({'scrollDefault': '9:30am','timeFormat': 'h:i A'});
            $('#f_endtime' + i).timepicker({'scrollDefault': '5:30pm','timeFormat': 'h:i A'});
            i++;
            return false;
        });
        
        $('#removeMore').on('click', function () {
            if (i < 2) {
                $('#addMore').show();
                $('#removeMore').hide();
                return false;
            } else {
                $('#addMore,#removeMore').show();
                $("#removemoreafter").remove();
            }
            i--;
            if (i < 2) {
                $('#removeMore').hide();
            }
});
    });

    $('#example-getting-started').multiselect();
    $('#f_starttime').timepicker({'scrollDefault': '9:30am','timeFormat': 'h:i A'});
    $('#f_endtime').timepicker({'scrollDefault': '5:30pm','timeFormat': 'h:i A'});

</script>

