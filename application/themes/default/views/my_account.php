<?php if (validation_errors()): ?>
    <div class="alert alert-danger">
        <a class="close" data-dismiss="alert">×</a>
        <?php echo validation_errors(); ?>
    </div>
<?php endif; ?>
<script type="text/javascript" src="<?php echo base_url('/application/themes/default/assets/js/jquery-ui.min.js');?>"></script>
<script type="text/javascript" src="https://maps.googleapis.com/maps/api/js?key=AIzaSyCu2f5nkkLPiX4gg-fId8vas2STZn4oudA&sensor=false&libraries=geometry"></script>
<script src="<?php echo base_url('/application/themes/default/assets/js/jquery.validate.js');?>" type="text/javascript"></script>
<script>
    $(document).ready(function () {
        $('.delete_address').click(function () {
            if ($('.delete_address').length > 1)
            {
                if (confirm('<?php echo lang('delete_address_confirmation'); ?>'))
                {
                    $.post("<?php echo site_url('secure/delete_address'); ?>", {id: $(this).attr('rel')},
                    function (data) {
                        $('#address_' + data).remove();
                        $('#address_list .my_account_address').removeClass('address_bg');
                        $('#address_list .my_account_address:even').addClass('address_bg');
                    });
                }
            }
            else
            {
                alert('<?php echo lang('error_must_have_address'); ?>');
            }
        });

        $('.edit_address').click(function () {
            $.post('<?php echo site_url('secure/address_form'); ?>/' + $(this).attr('rel'),
                    function (data) {
                        $('#address-form-container').html(data).modal('show');
                    }
            );
        });

        /**
         * Code added for handling new partner associate with Vendor
         */
        $('.edit_partners').click(function () {
            $.post('<?php echo site_url('secure/partner_form'); ?>/' + $(this).attr('rel'),
                    function (data) {
                        $('#partner-form-container').html(data).modal('show');
                    }
            );
        });

        /**
         * Code to delete partner request handling
         * @param {type} address_id
         * @param {type} type
         * @returns {undefined}
         */
        $('.delete_partner').click(function () {
            if ($('.delete_partner').length > 1)
            {
                if (confirm('Do you realy want to delete partner?'))
                {
                    $.post("<?php echo site_url('secure/delete_partner'); ?>", {id: $(this).attr('rel')},
                    function (data) {
                        $('#partner_' + data).remove();
//                        $('#address_list .my_account_address').removeClass('address_bg');
//                        $('#address_list .my_account_address:even').addClass('address_bg');
                    });
                }
            }
            else
            {
                alert('Error must have partner');
            }
        });

    });


    function set_default(address_id, type)
    {
        var fdata = {
            id: address_id,
            type: type
        }
        $.ajax({
            url: site_url + '/secure/set_default_address',
            type: 'POST',
            data: fdata,
            dataType: 'html',
            async: false,
            error: function () {
                maketoast('danger', 'Error', 'Server error please try after some time');
            },
            success: function (resp) {
                alert(144)
                maketoast('success', 'Success', 'Changes done successfully');
            }
        });

    }


</script>


<?php
$company = array('id' => 'company', 'class' => 'span4', 'name' => 'company', 'value' => set_value('company', $customer['company']));
$first = array('id' => 'firstname', 'class' => 'span2', 'name' => 'firstname', 'value' => set_value('firstname', $customer['firstname']));
$last = array('id' => 'lastname', 'class' => 'span2', 'name' => 'lastname', 'value' => set_value('lastname', $customer['lastname']));
$email = array('id' => 'email', 'class' => 'span2', 'name' => 'email', 'value' => set_value('email', $customer['email']));
$phone = array('id' => 'phone', 'class' => 'span2', 'name' => 'phone', 'value' => set_value('phone', $customer['phone']));

$oldPassword = array('id' => 'oldPassword', 'class' => 'span2', 'name' => 'oldPassword', 'value' => '');
$password = array('id' => 'password', 'class' => 'span2', 'name' => 'password', 'value' => '');
$confirm = array('id' => 'confirm', 'class' => 'span2', 'name' => 'confirm', 'value' => '');
?>
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
                            <li class="<?php if ($link['menu_name'] == 'profile') { ?> active <?php } ?>">
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

            <div class="tab-content">

                <div id="tabInfo" class="tab-pane active clsMargin">

                    <?php
                    if ($this->customer['access'] == 'Partner') {
                        $ci = &get_instance();
                        $ci->load->model('Partner_model');
                        $record = $ci->Partner_model->getUniqueId($this->customer['id']);
                        if ($record) {
                            ?>
                            <div class="alert alert-info">
                                <h4 for="account_phone">Partner no: <?php echo $record->unique_id; ?></h4>                                    
                            </div>
                            <?php
                        }
                    } else {
                        ?>
                        <div class="alert alert-success">
                            <h5>Access type: <?php echo $role['name']; ?></h5>
                        </div>
                    <?php }
						if($this->customer['access'] != 'Customer') {
						?>
                    <div class="row">
                        <div class="span4">
                            <label for="company"><?php echo lang('account_company'); ?></label>
                            <?php echo form_input($company); ?>
                        </div>
                    </div>
					<?php } ?>

                    <div class="row">	
                        <div class="span2">
                            <label for="account_firstname"><?php echo lang('account_firstname'); ?></label>
                            <?php echo form_input($first); ?>
                        </div>

                        <div class="span2">
                            <label for="account_lastname"><?php echo lang('account_lastname'); ?></label>
                            <?php echo form_input($last); ?>
                        </div>
                    </div>

                    <div class="row">
                        <div class="span2">
                            <label for="account_email"><?php echo lang('account_email'); ?></label>
                            <?php echo  $customer['email']; ?>
                        </div>

                        <div class="span2">
                            <label for="account_phone"><?php echo lang('account_phone'); ?></label>
                            <?php echo form_input($phone); ?>
                        </div> 
                    </div>

                    <input type="submit" value="<?php echo lang('form_submit'); ?>" class="btn btn-primary" />
                </div>
                <!--            
                <div class="row">
                    <div class="span7">
                        <label class="checkbox">
                            <input type="checkbox" name="email_subscribe" value="1" <?php if ((bool) $customer['email_subscribe']) { ?> checked="checked" <?php } ?>/> <?php echo lang('account_newsletter_subscribe'); ?>
                        </label>
                    </div>
                </div>
                <div class="row">
                    <div class="span4">
                        <div style="margin:30px 0px 10px; text-align:center;">
                            <strong><?php echo lang('account_password_instructions'); ?></strong>
                        </div>
                    </div>
                </div>
                -->
                <div id="tabPass" class="tab-pane clsMargin">
                    <div class="row">	
                        <div class="">
                            <label for="old_password">Old password</label>
                            <?php echo form_password($oldPassword); ?>
                        </div>
                        <div class="">
                            <label for="account_password">New <?php echo lang('account_password'); ?></label>
                            <?php echo form_password($password); ?>
                        </div>
                        <div class="">
                            <label for="account_confirm"><?php echo lang('account_confirm'); ?></label>
                            <?php echo form_password($confirm); ?>
                        </div>
                    </div>
                    <input type="submit" value="<?php echo lang('form_submit'); ?>" class="btn btn-primary" />
                </div>

            </div>
        </fieldset>
        </form>
    </div>
</div>
<?php 