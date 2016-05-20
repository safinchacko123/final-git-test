<script type="text/javascript" src="/assets/js/default/login.js"></script>
<div class="row" style="margin-top:50px;">
    <div class="span6 offset3 liteBgGrey box_shadow border_radius">

        <div class="page-header">
            <h1 class="clsHeading"><?php echo lang('login'); ?> as</h1>
        </div>

        <div class="tabbable">
            <ul data-tabs="tabs" class="nav nav-tabs">
                <li class="active">
                    <a id="lnkCust" href="#tabCust" data-toggle="tab" class="clsTab">Customer</a>
                </li>
                <li>
                    <a id="lnkVendor" href="#tabVendor" data-toggle="tab" class="clsTab">Vendor / Venture</a>
                </li>
                <li>
                    <a id="lnkPartner" href="#tabPartner" data-toggle="tab" class="clsTab">Partner</a>
                </li>
            </ul>
        </div>
        <?php echo form_open('secure/login', 'class="form-horizontal"'); ?>
        <fieldset>

            <div class="tab-content">

                <div id="tabCust" class="tab-pane active">

                    <div class="control-group">
                        <label class="control-label" for="email"><?php echo lang('email'); ?></label>
                        <div class="controls">
                            <input type="text" name="email" class="span3" value="<?php echo $email; ?>"/>
                        </div>
                    </div>

                    <div class="control-group">
                        <label class="control-label" for="password"><?php echo lang('password'); ?></label>
                        <div class="controls">
                            <input type="password" name="password" class="span3" autocomplete="off" value="<?php echo $password; ?>"/>
                        </div>
                    </div>

                    <div class="control-group">
                        <label class="control-label"></label>
                        <div class="controls">
                            <label class="checkbox">
                                <input name="remember" value="true" type="checkbox" />
                                <?php echo lang('keep_me_logged_in'); ?>
                            </label>
                        </div>
                    </div>
                    <div class="control-group">
                        <label class="control-label" for="password"></label>
                        <div class="controls">
                            <input type="submit" value="<?php echo lang('form_login'); ?>" name="submit" class="btn btn-primary"/>
                        </div>
                    </div>
                </div>
                
            </div>
        </fieldset>

        <input type="hidden" value="<?php echo $redirect; ?>" name="redirect"/>
        <input type="hidden" value="submitted" name="submitted"/>
        <input type="hidden" id="loginType" name="loginType" value="<?php echo $loginAs;?>"/>

        </form>

        <div style="text-align:center;">
            <a href="<?php echo site_url('secure/forgot_password'); ?>"><?php echo lang('forgot_password') ?></a> | <a href="<?php echo site_url('secure/register'); ?>"><?php echo lang('register'); ?></a>            
        </div>
    </div>
</div>