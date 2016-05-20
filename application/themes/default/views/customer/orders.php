

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
                            <li class="<?php if ($link['menu_name'] == 'Orders') { ?> active <?php } ?>">
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
                <div class="row">
                    <div class="span7" style="border-bottom:1px solid #f5f5f5;">
                        <div class="row">
                            <div class="span4">
                                <?php echo $this->pagination->create_links(); ?>&nbsp;
                            </div>
                            <div class="span8">
                                <?php echo form_open($this->config->item('admin_folder') . '/orders/index', 'class="form-inline" style="float:right"'); ?>
                            </div>
                        </div>
                    </div>
                </div>

                <table class="table table-striped">
                    <thead>
                        <tr>
                            <th>Order id</th>
                            <th>Vendor</th>
                            <th>Ship to</th>
                            <th>Ordered on</th>
                            <th>Status</th>
                            <th>Total</th>
                            <th></th>
                        </tr>
                    </thead>

                    <tbody>
                        <?php echo (count($orders) < 1) ? '<tr><td style="text-align:center;" colspan="8">' . lang('no_orders') . '</td></tr>' : '' ?>
                        <?php foreach ($orders as $order): ?>
                            <tr>

                                <td><a href="<?php echo base_url('orders/index?order_id=' . $order->id); ?>"><?php echo $order->order_number; ?></a></td>
                                <td style="white-space:nowrap"><?php echo $order->company; ?></td>
                                <td style="white-space:nowrap"><?php echo $order->ship_lastname . ', ' . $order->ship_firstname; ?></td>
                                <td style="white-space:nowrap"><?php echo date('m/d/y h:i a', strtotime($order->ordered_on)); ?></td>
                                <td style="span2">
                                    <?php echo $order->status; ?>

                                </td>
                                <td><div class="MainTableNotes">$<?php echo format_currency($order->total); ?></div></td>
                                <td>
<!--                                    <a class="btn btn-small" style="float:right;"href="<?php echo site_url($this->config->item('admin_folder') . '/orders/order/' . $order->id); ?>"><i class="icon-search"></i> <?php echo lang('form_view') ?></a>-->
                                </td>
                            </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
                
            </div>
    </div>
</div>
<script type="text/javascript">
    $(document).ready(function() {
        $('#gc_check_all').click(function() {
            if (this.checked)
            {
                $('.gc_check').attr('checked', 'checked');
            }
            else
            {
                $(".gc_check").removeAttr("checked");
            }
        });

        // set the datepickers individually to specify the alt fields
        $('#start_top').datepicker({dateFormat: 'mm-dd-yy', altField: '#start_top_alt', altFormat: 'yy-mm-dd'});
        $('#start_bottom').datepicker({dateFormat: 'mm-dd-yy', altField: '#start_bottom_alt', altFormat: 'yy-mm-dd'});
        $('#end_top').datepicker({dateFormat: 'mm-dd-yy', altField: '#end_top_alt', altFormat: 'yy-mm-dd'});
        $('#end_bottom').datepicker({dateFormat: 'mm-dd-yy', altField: '#end_bottom_alt', altFormat: 'yy-mm-dd'});
    });

    function do_search(val)
    {
        $('#search_term').val($('#' + val).val());
        $('#start_date').val($('#start_' + val + '_alt').val());
        $('#end_date').val($('#end_' + val + '_alt').val());
        $('#search_form').submit();
    }

    function do_export(val)
    {
        $('#export_search_term').val($('#' + val).val());
        $('#export_start_date').val($('#start_' + val + '_alt').val());
        $('#export_end_date').val($('#end_' + val + '_alt').val());
        $('#export_form').submit();
    }

    function submit_form()
    {
        if ($(".gc_check:checked").length > 0)
        {
            return confirm('<?php echo lang('confirm_delete_order') ?>');
        }
        else
        {
            alert('<?php echo lang('error_no_orders_selected') ?>');
            return false;
        }
    }

    function save_status(id)
    {
        show_animation();
        $.post("<?php echo site_url($this->config->item('admin_folder') . '/orders/edit_status'); ?>", {id: id, status: $('#status_form_' + id).val()}, function(data) {
            setTimeout('hide_animation()', 500);
        });
    }

    function show_animation()
    {
        $('#saving_container').css('display', 'block');
        $('#saving').css('opacity', '.8');
    }

    function hide_animation()
    {
        $('#saving_container').fadeOut();
    }
</script>