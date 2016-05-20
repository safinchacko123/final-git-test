<?php header ("Content-Type:text/xml"); 
echo '<?xml version="1.0" ?>';?>
<?php
    $m	= Array(
            lang('january')
            ,lang('february')
            ,lang('march')
            ,lang('april')
            ,lang('may')
            ,lang('june')
            ,lang('july')
            ,lang('august')
            ,lang('september')
            ,lang('october')
            ,lang('november')
            ,lang('december')
    );
    //$month->currency.' '.
?>
<export>
<?php foreach($orders as $month) : ?>
<report>
<date><?php echo $m[intval($month->month)-1].' '.$month->year; ?></date>
<products>₹<?php echo $month->product_totals; ?></products>
<shipping>₹<?php echo format_currency($month->shipping); ?></shipping>
<tax>₹<?php echo format_currency($month->tax); ?></tax>
<total>₹<?php echo format_currency($month->total); ?></total>
</report>
<?php endforeach; ?>
</export>