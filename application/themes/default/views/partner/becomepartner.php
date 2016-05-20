<script src="/application/themes/default/assets/js/partner/manage.js" type="text/javascript"></script>            

<div class="span4" style="float: none; margin: 0 auto; width: 100%;">

    <p>
    <h2><?php echo lang('account_information'); ?></h2>
    <p>
    <div class="my-account-box">
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
                            <li class="<?php if ($link['menu_name'] == 'become partner') { ?> active <?php } ?>">
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

            <div class="tab-content" style="height: 540px; overflow: hidden; padding-top: 10px;">
                <div style="clear: both">
                    <div class="span11" id='venture_list' style="height: 540px;">
                        <?php if (count($vendors) > 0): ?>
                            <table style="width: 60%; margin-top: 10px;" class="table table-bordered table-striped clsMargin">
                                <tr>
                                    <th colspan="2">Vendor details</th>
                                </tr>
                                <?php foreach ($vendors As $rowVen) { ?>
                                    <tr>
                                        <td>
                                            <div class="row-fluid">
                                                <div class="span12">
                                                    <div>name: <?php echo $rowVen->firstname . " " . $rowVen->lastname; ?></div>
                                                    <div>company: <?php echo $rowVen->company; ?></div>
                                                </div>
                                            </div>
                                        </td>
                                        <td style="border-left:0">
                                            <?php if (!$rowVen->map_id) { ?>
                                                <input type="button" class="btn btn-info becomePartner" rel="<?php echo $rowVen->customer_id; ?>" value="Become a partner" />
                                                <?php
                                            } else {
                                                if ($rowVen->map_approved) {
                                                    ?>
                                                    <span style="padding: 4px" class="alert alert-success">Already partner</span>
                                                <?php } else {
                                                    ?>
                                                    <span style="padding: 4px" class="alert alert-info">Request is under consideration</span>
                                                    <?php
                                                }
                                            }
                                            ?>
                                        </td>
                                    </tr>
                                <?php } ?>
                            </table>
                        <?php endif; ?>
                    </div>

                    <div id="venture-form-container" class="hide">

                    </div>

                    <div id="venture-map-container" style="height: 600px; width: 1100px; clear: both;">

                    </div>
                </div>

        </fieldset>
    </div>
</div>
