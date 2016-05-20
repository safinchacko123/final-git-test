<script type="text/javascript" src="<?php echo site_url('/assets/js/default/register.js');?>"></script>
<?php
$company = array('id' => 'bill_company', 'class' => 'span6', 'name' => 'company', 'value' => set_value('company'));
$first = array('id' => 'bill_firstname', 'class' => 'span3', 'name' => 'firstname', 'value' => set_value('firstname'));
$last = array('id' => 'bill_lastname', 'class' => 'span3', 'name' => 'lastname', 'value' => set_value('lastname'));
$email = array('id' => 'bill_email', 'class' => 'span3', 'name' => 'email', 'value' => set_value('email'));
$phone = array('id' => 'bill_phone', 'maxlength'=>'10', 'class' => 'span3', 'name' => 'phone', 'value' => set_value('phone'));
?>

<style type="text/css">
    .checkboxline {
        display: inine!important;
    }
    .tooltip_link {
        text-decoration: none!important;
    }
    @media only screen and (min-width: 750px) {
        .check-restaurent {
            margin-left: 0!important;
        }
    }
    @media only screen and (max-width: 700px) {
        .business_currency {
            margin-top: 10px;
        }
        .check-restaurent {
            margin-left: 0;
        }
    }
</style>
<script type="text/javascript">

</script>
<div class="row" style="margin-top:50px;">
    <div class="span6 offset3">
        <div class="page-header">
            <h1 class="clsHeading"><?php echo lang('form_register') . " as"; ?></h1>
        </div>
        <?php echo form_open_multipart('secure/register'); ?>
        <input type="hidden" name="submitted" value="submitted" />
        <input type="hidden" id="registerType" name="registerType" value="<?php echo $registerAs; ?>"/>
        <input type="hidden" name="redirect" value="<?php echo $redirect; ?>" />

        <fieldset>
            <div class="tabbable">
                <ul data-tabs="tabs" class="nav nav-tabs">
                    <!-- <li class="active">
                        <a id="lnkCust" href="" data-toggle="tab" class="clsTab">Customer</a>
                    </li> -->
                    <li class="active">
                        <a id="lnkVendor" href="" data-toggle="tab" class="clsTab">Vendor</a>
                    </li>
                    <li>
                        <a id="lnkPartner" href="" data-toggle="tab" class="clsTab">Partner</a>
                    </li>
                </ul>
            </div>

            <div class="tab-content">

                <div id="tabCust" class="tab-pane active">

                    <div class="row company">
                        <div class="span6">
                            <label for="company"><?php echo lang('account_company'); ?><span class="error">*</span></label>
                            <?php echo form_input($company); ?>
                        </div>
                    </div>
                    <div class="row">	
                        <div class="span3">
                            <label for="account_firstname"><?php echo lang('account_firstname'); ?> <span class="error">*</span></label>
                            <?php echo form_input($first); ?>
                        </div>

                        <div class="span3">
                            <label for="account_lastname"><?php echo lang('account_lastname'); ?> <span class="error">*</span></label>
                            <?php echo form_input($last); ?>
                        </div>
                    </div>

                    <div class="row">
                        <div class="span3">
                            <label for="account_email"><?php echo lang('account_email'); ?> <span class="error">*</span></label>
                            <?php echo form_input($email); ?>
                        </div>

                        <div class="span3">
                            <label for="account_phone"><?php echo lang('account_phone'); ?> <span class="error">*</span></label>
                            <?php echo form_input($phone); ?>
                        </div>
                    </div>

                    <!--                    <div class="row">
                                            <div class="span7">
                                                <label class="checkbox">
                                                    <input type="checkbox" name="email_subscribe" value="1" <?php echo set_radio('email_subscribe', '1', TRUE); ?>/> <?php echo lang('account_newsletter_subscribe'); ?>
                                                </label>
                                            </div>
                                        </div>-->

                    <div class="row">	
                        <div class="span3">
                            <label for="account_password"><?php echo lang('account_password'); ?> <span class="error">*</span></label>
                            <input type="password" name="password" value="" class="span3" autocomplete="off" />
                        </div>

                        <div class="span3">
                            <label for="account_confirm"><?php echo lang('account_confirm'); ?> <span class="error">*</span></label>
                            <input type="password" name="confirm" value="" class="span3" autocomplete="off" />
                        </div>
                    </div>

                    <div class="row pNo">	
                            <div class="span3">
                            <label for="addressline1"><?php echo lang('address_line1'); ?> <span class="error">*</span></label>
                            <input type="text" name="addressline1" value="<?php echo set_value('addressline1'); ?>" class="span3" autocomplete="off" />
                        </div>

                        <div class="span3">
                            <label for="addressline2"><?php echo lang('address_line2'); ?></label>
                            <input type="text" name="addressline2" value="<?php echo set_value('addressline2'); ?>" class="span3" autocomplete="off" />
                        </div>

                    </div>

                    <div class="row pNo">	
                        <div class="span3">
                            <label for="city"><?php echo lang('city'); ?> <span class="error">*</span></label>
                            <input type="text" name="city" value="<?php echo set_value('city'); ?>" class="span3" autocomplete="off" />
                        </div>

                        <div class="span3">
                            <label for="state"><?php echo lang('state'); ?> <span class="error">*</span></label>
                            <input type="text" name="state" value="<?php echo set_value('state'); ?>" class="span3" autocomplete="off" />
                        </div>

                    </div>

                    <div class="row pNo">	

                        <div class="span3">
                            <label for="country"><?php echo lang('country'); ?> <span class="error">*</span></label>
                            <select class="span3" name="vendor_country">
                                <option value="">- Select Country -</option>
                                <?php if($countries) { ?>
                                    <?php foreach($countries as $country) { ?>
                                        <option <?php if($country->id==set_value('vendor_country')) { echo "selected=selected"; }?> value="<?php echo $country->id; ?>"><?php echo $country->name; ?></option>
                                    <?php } ?>
                                <?php } ?>
                            </select>
                        </div>

                        <div class="span3">
                            <label for="zipcode"><?php echo lang('zipcode'); ?> <span class="error">*</span></label>
                            <input type="text" name="vendor_zipcode" value="<?php echo set_value('vendor_zipcode'); ?>" class="span3" autocomplete="off" />
                        </div>
                    </div>

                    <div class="row pNo">	
                        <div class="span3">
                            <label for="business_category"><?php echo lang('business_category'); ?> (<?php echo lang('note_businesscategory'); ?>) <span class="error">*</span> 
                            </label>
                            <?php if($businesscategories) { ?>
                                <?php foreach($businesscategories as $cat) { ?>
                                    <label class="checkbox inline"><input name="categories_id[]" <?php if (in_array($cat->id, $businesscategoryids)) { echo "checked=checked"; }?> type="checkbox" value="<?php echo $cat->id; ?>" title="Please choose your category carefully" class="check"> <?php echo $cat->name; ?></label>
                                <?php } ?>
                            <?php } ?>                            
                        </div>

                        <?php /*<div class="span3">
                            <label for="business_currency" class="business_currency"><?php echo lang('business_currency'); ?> <span class="error">*</span></label>
                            <select class="span3" name="business_currency">
                                <option value="">- Select Currency -</option>
                                <?php if($businesscurriencies) { ?>
                                    <?php foreach($businesscurriencies as $curr) { ?>
                                        <option <?php if($curr->id==set_value('business_currency')) { echo "selected=selected"; }?> value="<?php echo $curr->id; ?>"><?php echo $curr->currency_name; ?> - <?php echo $curr->currency_symbol; ?></option>
                                    <?php } ?>
                                <?php } ?>
                            </select>
                        </div> */?>
                        <div class="span3">
                            <label for="partner_no">Partner No</label>
                            <input type="text" name="partner_no" value="" class="span3" autocomplete="off" />
                        </div>
                    </div>

                    <!-- Address Field for Partners -->
                    <div class="row docs">
                        <div class="span3">
                            <label for="partneraddressline1"><?php echo lang('address_line1'); ?> <span class="error">*</span></label>
                            <input type="text" name="partneraddressline1" value="<?php echo set_value('partneraddressline1'); ?>" class="span3" autocomplete="off" />
                    </div>

                        <div class="span3">
                            <label for="partneraddressline2"><?php echo lang('address_line2'); ?></label>
                            <input type="text" name="partneraddressline2" value="<?php echo set_value('partneraddressline2'); ?>" class="span3" autocomplete="off" />
                        </div>

                    </div>

                    <div class="row docs">	
                        <div class="span3">
                            <label for="partnercity"><?php echo lang('city'); ?> <span class="error">*</span></label>
                            <input type="text" name="partnercity" value="<?php echo set_value('partnercity'); ?>" class="span3" autocomplete="off" />
                        </div>

                        <div class="span3">
                            <label for="partnerstate"><?php echo lang('state'); ?> <span class="error">*</span></label>
                            <input type="text" name="partnerstate" value="<?php echo set_value('partnerstate'); ?>" class="span3" autocomplete="off" />
                        </div>

                    </div>

                    <div class="row docs">

                        <div class="span3">
                            <label for="country"><?php echo lang('country'); ?> <span class="error">*</span></label>
                            <select class="span3" name="partnercountry">
                                <option value="">- Select Country -</option>
                                <?php if($countries) { ?>
                                    <?php foreach($countries as $country) { ?>
                                        <option <?php if($country->id==set_value('partnercountry')) { echo "selected=selected"; }?> value="<?php echo $country->id; ?>"><?php echo $country->name; ?></option>
                                    <?php } ?>
                                <?php } ?>
                            </select>
                        </div>

                        <div class="span3">
                            <label for="zipcode"><?php echo lang('zipcode'); ?> <span class="error">*</span></label>
                            <input type="text" name="partnerzipcode" value="<?php echo set_value('partnerzipcode'); ?>" class="span3" autocomplete="off" />
                        </div>
                    </div>
                    <!-- Address Field for Partners -->

                    <fieldset class="docs">
                        <legend>Document upload</legend>
                        <div class="row docs">	
                            <div>
                                <div class="span3">
                                    <label for="passport">Passport <span class="error">*</span></label>
                                    <input type="file" name="passport" value="" class="span3" autocomplete="off" />
                                </div>

                                <div class="span3">
                                    <label for="id_proof">Id proof <span class="error">*</span></label>
                                    <input type="file" name="id_proof" value="" class="span3" autocomplete="off"/>
                                </div>
                            </div>
                        </div>
                    </fieldset>

                     <div class="row">
                        <div class="span6">
                            <input type="checkbox" <?php if(set_value('chkTerms')){ echo "checked=checked"; } ?> id="chkTerms" name="chkTerms" value="1" />
                            I have read and accept the <a id="reg-tc" href="javascript:void(0);" target="_blank">Terms & Conditions</a> and <a href="<?php echo site_url('privacy-policy'); ?>" target="_blank">Privacy Policy</a> of Marketplace.
                        </div>
                    </div>

                    <input type="submit" value="<?php echo lang('form_register'); ?>" class="btn btn-primary" />

                </div>

            </div>
    </div>
</div>
</fieldset>
</form>

<div style="text-align:center;">
    <a href="<?php echo site_url('secure/login'); ?>"><?php echo lang('go_to_login'); ?></a>
</div>
</div>
</div>




<?php
