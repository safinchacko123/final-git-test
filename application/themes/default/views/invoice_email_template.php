<html>
    <head>
        <title></title>        
    </head>
    <body>
        <p>
            Hi <?php echo $venturename; ?>,<br /><br />
            Following is your monthly invoice of orders from <?php echo date('m/d/y', strtotime($previous_date)); ?> to <?php echo date('m/d/y', strtotime($current_date)); ?> and you can pay using these options : <a href="#">Paypal</a>/<a href="#">Paytm</a>.
        </p><br />
        <table style="font-family: verdana,arial,sans-serif;font-size:11px;color:#333333;border-width: 1px;border-color: #666666;border-collapse: collapse;" class="gridtable">
            <tr>
                <th style="border-width: 1px;padding: 8px; border-style: solid;border-color: #666666;background-color: #dedede;">
                    Order
                </th>
                <th style="border-width: 1px;padding: 8px; border-style: solid;border-color: #666666;background-color: #dedede;">
                    Ordered On
                </th>
                <th style="border-width: 1px;padding: 8px; border-style: solid;border-color: #666666;background-color: #dedede;">
                    Status
                </th>
                <th style="border-width: 1px;padding: 8px; border-style: solid;border-color: #666666;background-color: #dedede;">
                    Total
                </th>
            </tr>
            <?php $total=0; ?>
            <?php foreach($orders as $order): ?>
            <tr>
                <td style="border-width: 1px;padding: 8px;border-style: solid;border-color: #666666;background-color: #ffffff;">
                    <?php echo $order['order_number']; ?>
                </td>
                <td style="border-width: 1px;padding: 8px;border-style: solid;border-color: #666666;background-color: #ffffff;">
                    <?php echo date('m/d/y h:i a', strtotime($order['ordered_on'])); ?>
                </td>
                <td style="border-width: 1px;padding: 8px;border-style: solid;border-color: #666666;background-color: #ffffff;">
                    <?php //echo $order['status']; ?>
                    <?php echo lang($order['status']);?>
                </td>
                <td style="border-width: 1px;padding: 8px;border-style: solid;border-color: #666666;background-color: #ffffff;">
                    <?php echo format_currency($order['total']); ?>
                </td>
            </tr>
            <?php $total = $total + $order['total']; ?>
            <?php endforeach; ?>
            <tr>
                <td style="border-width: 1px;padding: 8px;border-style: solid;border-color: #666666;background-color: #ffffff;">
                    <strong><?php echo lang('total');?></strong>
                </td>
                <td colspan="3" style="border-width: 1px;padding: 8px;border-style: solid;border-color: #666666;background-color: #ffffff;text-align: right;">
                    <?php 
                        //echo format_currency($total);
                        echo number_format($total, 2, '.', '');
                    ?>
                </td>                
            </tr>
            <tr>
                <td style="border-width: 1px;padding: 8px;border-style: solid;border-color: #666666;background-color: #ffffff;">
                    <strong>Venture's Share Total</strong>
                </td>
                <td colspan="3" style="border-width: 1px;padding: 8px;border-style: solid;border-color: #666666;background-color: #ffffff;text-align: right;">
                    <?php 
                        $venture_share = $total*95/100;
                        //echo format_currency($total);
                        echo number_format($venture_share, 2, '.', '');
                    ?>
                </td>                
            </tr>
            <tr>
                <td style="border-width: 1px;padding: 8px;border-style: solid;border-color: #666666;background-color: #ffffff;">
                    <strong>Dropneed's Share Total</strong>
                </td>
                <td colspan="3" style="border-width: 1px;padding: 8px;border-style: solid;border-color: #666666;background-color: #ffffff;text-align: right;">
                    <?php 
                        $dropneed_share = $total*5/100;
                        //echo format_currency($total);
                        echo number_format($dropneed_share, 2, '.', '');
                    ?>
                </td>                
            </tr>            
        </table><br />
        <p>
            Thank you,<br /><br />            
            Team Dropneed
        </p>
    </body>
</html>