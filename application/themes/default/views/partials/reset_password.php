<?php
$password = array('id' => 'password', 'class' => 'span2', 'name' => 'password', 'value' => '');
$confirm = array('id' => 'confirm', 'class' => 'span2', 'name' => 'confirm', 'value' => '');
?>
<div>
    <form method="post">
        <div class="span6 offset3 liteBgGrey box_shadow border_radius">
            <div id="tabPass" class="tab-pane">
                <div class="row" style="margin-top: 20px;">
                    <div class="span2">
                        <label for="account_password">New <?php echo lang('account_password'); ?></label>
                        <?php echo form_password($password); ?>
                    </div>
                </div>
                <div class="row">
                    <div class="span2">
                        <label for="account_confirm"><?php echo lang('account_confirm'); ?></label>
                        <?php echo form_password($confirm); ?>
                    </div>
                </div>
            </div>
            <input type="submit" name="submit" value="<?php echo lang('form_submit'); ?>" class="btn btn-primary" />
        </div>
    </form>
</div>