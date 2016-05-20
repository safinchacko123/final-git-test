<div class="span7 pull-right">
    <div class="row" style="padding-top:10px;">
        <div class="span4">
            <h2>Associated Partners</h2>
        </div>
        <div class="span3" style="text-align:right;">
            <input type="button" class="btn edit_partners" rel="0" value="Add partners"/>            
        </div>
    </div>
    <div class="row">
        <div class="span7" id='address_list'>
            <?php if (count($partners) > 0): ?>
                <table class="table table-bordered table-striped">
                    <?php
                    $c = 1;
                    foreach ($partners as $p):
                        ?>
                        <tr id="partner_<?php echo $p->id; ?>">
                            <td>
                                <?php
                                echo $p->firstname . " " . $p->lastname . '<br/>';
                                echo $p->company;
                                ?>
                            </td>
                            <td>
                                <div class="row-fluid">
                                    <div class="span12">
                                        <div class="btn-group pull-right">
                                            <input type="button" class="btn btn-danger delete_partner" rel="<?php echo $p->id; ?>" value="<?php echo lang('form_delete'); ?>" />
                                        </div>
                                    </div>
                                </div>
                            </td>
                        </tr>
                    <?php endforeach; ?>
                </table>
            <?php endif; ?>
        </div>
    </div>

</div>