<script type="text/javascript">
$(document).ready(function () {
    $('#Send').click(function () {
        // name validation
        var nameVal = $("#name").val();
        var codeVal = $("#code").val();
        if ( nameVal == '' ) {
            $("#name_error").html('');
            $("#name").after('<label class="error" id="name_error"><?php echo lang( 'contact_name_error' );?></label>');
            return false
        } else if( codeVal == '' ) {
            $("#code_error").html('');
            $("#code").after('<label class="error" id="code_error"><?php echo lang( 'contact_captcha_field_error' );?></label>');
            return false
        } else {
            $("#name_error").html('');
            $("#code_error").html('');
        }
        /// email validation
        var emailReg = /^([\w-\.]+@([\w-]+\.)+[\w-]{2,4})?$/;
        var emailaddressVal = $("#email").val();
        if (emailaddressVal == '') {
            $("#email_error").html('');
            $("#email").after('<label class="error" id="email_error"><?php echo lang( 'contact_email_error' );?></label>');
            return false
        } else if (!emailReg.test(emailaddressVal)) {
            $("#email_error").html('');
            $("#email").after('<label class="error" id="email_error"><?php echo lang( 'contact_valid_email_error' );?></label>');
            return false

        } else {
            $("#email_error").html('');
        }
        
        $('#Send').val('<?php echo html_entity_decode(lang('loading'));?>');
        
        $.post("<?php echo base_url();?>index/verify_contact_form/" , $("#MYFORM").serialize() , function (response) {
            if ( response == 1 )
            {
                $("#after_submit").html('');
                $("#Send").after('<label class="success" id="after_submit"><?php echo lang('contact_submit_success');?></label>');
                change_captcha();
                clear_form();
            } else {
                $("#after_submit").html('');
                $("#Send").after('<label class="error" id="after_submit"><?php echo lang('contact_captcha_error');?></label>');
            }
            $('#Send').val('<?php echo lang('form_submit');?>');
        });        
        return false;
    });

    // refresh captcha
    $('a#refresh').click(function () {

        change_captcha();
    });

    function change_captcha() {
        document.getElementById('captcha').src = "<?php echo base_url();?>index/get_captcha/?rnd=" + Math.random();
    }

    function clear_form() {
        $("#name").val('');
        $("#email").val('');
        $("#message").val('');
        $("#code").val('');
    }
});
</script>
<style>
.error { color:red; }
</style>
<div class="row">                            
    <div class="span4 marginAuto box_shadow border_radius liteBgGrey marginTop_30" style="float:none; margin-top:60px;">
        <div class="my-account-box">
        <form action="#" name="MYFORM" id="MYFORM">
            <fieldset>
                <h2 class="marginLeft">Contact</h2>								
               
                <div class="row">	
                    <div class="span2">
                        <label for="account_firstname"><?php echo lang('account_firstname');?></label>
                        <?php echo form_input( array( 'name' => 'name' , 'id' => 'name') );?>
                    </div>
                </div>
                
                <div class="row">
                    <div class="span2">
                        <label for="account_email"><?php echo lang('account_email');?></label>
                        <?php echo form_input( array( 'name' => 'email' , 'id' => 'email') );?>
                    </div>									
                </div>
                
                <div class="row">
                    <div class="span2">
                        <label for="account_email"><?php echo lang('content');?></label>
                        <?php echo form_textarea( array( 'name' => 'message' , 'id' => 'message' , 'size' => '50' , 'style' => 'width:350px;height:100px;' ) );?>
                    </div>									
                </div>
                
                <div class="row">                    
                    <div class="span2">
                        <br />
                        <label for="account_email"><?php echo lang('verify_captcha');?></label>
                        <img src="<?php echo base_url();?>index/get_captcha" alt="" id="captcha" />                        
                        <?php echo form_input( array( 'name' => 'code' , 'id' => 'code' ) );?>
                        <a href="javascript://" id="refresh">Refresh</a>
                    </div>									
                </div>
                
                <div class="pad10">
                    <input type="submit" id="Send" value="<?php echo lang('form_submit');?>" class="btn btn-primary"  />
                </div>
                
            </fieldset>
        </form>
        </div>
    </div>	
</div>