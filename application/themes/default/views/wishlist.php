<style type="text/css">
    .pagination {
        margin:0px;
        margin-top:-3px;
    }
</style>

<div class="row">
    <div class="span12" style="border-bottom:1px solid #f5f5f5;">
        <div class="row">
            <div class="span4">
                <?php //echo $this->pagination->create_links();?>	&nbsp;
            </div>
        </div>
    </div>
</div>
<div class="btn-group pull-right">
</div>

<table class="table table-striped clsMargin-min">
    <thead>
        <tr>
        <!--	<th><input type="checkbox" id="gc_check_all" /> <button type="submit" class="btn btn-small btn-danger"><i class="icon-trash icon-white"></i></button></th> -->
            <th>Image</th>
            <th>Product</th>
            <th>Action</th>

        </tr>
    </thead>
    <tbody>
        <?php echo (count($wishlist) < 1) ? '<tr><td style="text-align:center;" colspan="7">No product has been added to wishlist.</td></tr>' : '' ?>
        <?php foreach ($wishlist as $product): ?>
            <tr>
                    <!-- <td><input name="review[]" type="checkbox" value="<?php echo $review->review_id; ?>" class="gc_check"/></td> -->
                <td><?php
                    $photo = theme_img('no_picture.png', lang('no_image_available'));

                    $product->images = array_values(json_decode($product->images, true));

                    if (!empty($product->images[0])) {
                        $primary = $product->images[0];
                        foreach ($product->images as $photo) {
                            if (isset($photo->primary)) {
                                $primary = $photo;
                            }
                        }

                        $photo = '<img class="responsiveImage" src="' . base_url('uploads/images/medium/' . $primary['filename']) . '" alt="' . $product->seo_title . '" style="width:24%"/>';
                    }
                    echo $photo;
                    ?></td>
                <td><?php echo $product->name; ?></td>
                <td> <div class="control-group">
                        <div id="<?php echo "wishlist_" . $product->wishlist_id; ?>" class="controls">
                            <a href="removeFromWishlist/<?php echo $product->wishlist_id; ?>" class="btn btn-primary btn-middle">Remove from Favorites</a>

                        </div>
                    </div></td>


            </tr>
<?php endforeach; ?>
    </tbody>
</table>
