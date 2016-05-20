
<form action="<?php echo base_url('orders/cancelorder'); ?>" method="POST">
    <div class="span4" style="float: none; margin: 0 auto; width: 100%;">

        <div class="my-account-box">

            <table class="table table-striped">
                <thead>
                    <tr>
                        <th>Image</th>
                        <th>Item name</th>
                        <th>quantity</th>
                        <th>sale price</th>
                    </tr>
                </thead>

                <tbody>

                    <?php foreach ($orders->contents as $content): ?>
                        <tr>
                            <td>

                                <?php
                                $imageName = '';
                                $images = json_decode($content['images']);
                                if (count($images)) {
                                    foreach ($images as $objImg) {
                                        if ($imageName == '') {
                                            $imageName = $objImg->filename;
                                        }
                                    }
                                }
                                ?>
                                <div class="product-image">
                                    <img class="span1" style="width:100px;" src="<?php echo base_url('uploads/images/medium/' . $imageName); ?>"/>
                                </div>
                            </td>
                            <td><?php echo $content['name']; ?></td>
                            <td><?php echo $content['quantity']; ?></td>
                            <td style="white-space:nowrap">$<?php echo $content['subtotal']; ?></td>
                        </tr>
                    <?php endforeach; ?>
                        <tr>
                            <td colspan="3" style="text-align: right;">
                                <b>Sub-Total</b>
                            </td>
                            <td>
                                $<?php echo $orders->subtotal; ?>
                            </td>
                        </tr>
                </tbody>
            </table>
        </div>
    </div>
    <?php
    if ($orders->status != 'Cancelled') {
        ?>
        <input type="hidden" name="order_id" value="<?php echo $order_id; ?>"/>
        <input class="btn btn-danger" style="float: right" type="submit" value="Cancle order"/>
    <?php }else{ ?>
        <div class="alert alert-danger" style="text-align: center;"><b>Order Cancelled </b></div>
    <?php } ?>
</form>