<style>
body{
  margin      : 0;
  padding     : 1.5em;
  font-family : sans-serif;
  line-height : 1.5;
}

p{
  margin  : 0 0 1.5em;
  padding : 0;
}

a{
  color           : #9c3;
  text-decoration : none;
}

.starRating:not(old){
  display        : inline-block;
  width          : 7.5em;
  height         : 1.5em;
  overflow       : hidden;
  vertical-align : bottom;
}

.starRating:not(old) > input{
  margin-right : -100%;
  opacity      : 0;
}

.starRating:not(old) > label{
  display         : block;
  float           : right;
  position        : relative;
  background      : url('<?php echo base_url("application/themes/default/assets/img"); ?>/star_empty.png');
  background-size : contain;
}

.starRating:not(old) > label:before{
  content         : '';
  display         : block;
  width           : 1.5em;
  height          : 1.5em;
  background      : url('<?php echo base_url("application/themes/default/assets/img"); ?>/star_full.png');
  background-size : contain;
  opacity         : 0;
  transition      : opacity 0.2s linear;
}

.starRating:not(old) > label:hover:before,
.starRating:not(old) > label:hover ~ label:before,
.starRating:not(:hover) > :checked ~ label:before{
  opacity : 1;
}
    </style>
<script src="<?php echo base_url("application/themes/default/assets/js/"); ?>/jquery-latest.js"></script>
<script>

// This is the first thing we add ------------------------------------------
    $(document).ready(function () {

        $(".ratingClass").click(function () {
            var ratingId = this.id;
            var rating = $("#" + ratingId).val();

            //alert(this.id);
            //updateRating(rating);
            $.ajax({
                type: 'POST',
                cache: false,
                url: "<?php echo base_url('cart/updateRating'); ?>", 
                data: {product_id: '<?php echo $product->id; ?>', customer_id: '<?php echo $customer_id; ?>', rating: rating},
                success: function (data)
                {
                    if (data == 2)
                    {
                        alert('Thank you for rating the product');
                    }
                    else if (data == 1)
                    {
                        alert('You have already rated this product');
                    }
                    else {
                        alert('Please try after some time');
                    }
                }
            });



        });
    });

    function updateRating(rating) {
        $.ajax({
            type: 'POST',
            cache: false,
            url: "<?php echo base_url('cart/updateWishlist'); ?>",
            data: {product_id: '<?php echo $product->id; ?>', customer_id: '<?php echo $customer_id; ?>', wishlist_id: wishlist_id},
            success: function (data) {
                if (data == 'false') {
                    alert('Please try after some time');
                } else {
                    $("#wishlist_<?php echo $product->id; ?>").html(data);
                }
            }
        });
    }


    function updateStatus(wishlist_id) {
        $.ajax({
            type: 'POST',
            cache: false,
            url: "<?php echo base_url('cart/updateWishlist'); ?>",
            data: {product_id: '<?php echo $product->id; ?>', customer_id: '<?php echo $customer_id; ?>', wishlist_id: wishlist_id},
            success: function (data) {
                if (data == 'false') {
                    alert('Please try after some time');
                } else {
                    $("#wishlist_<?php echo $product->id; ?>").html(data);
                }
            }
        });
    }


</script>

<div class="row">
    <div class="span4">

        <div class="row">
            <div class="span4" id="primary-img">
                <?php
                $photo = theme_img('no_picture.png', lang('no_image_available'));
                $product->images = array_values($product->images);

                if (!empty($product->images[0])) {
                    $primary = $product->images[0];
                    foreach ($product->images as $photo) {
                        if (isset($photo->primary)) {
                            $primary = $photo;
                        }
                    }

                    $photo = '<img class="responsiveImage" src="' . base_url('uploads/images/medium/' . $primary->filename) . '" alt="' . $product->seo_title . '"/>';
                }
                echo $photo
                ?>
            </div>
        </div>
                <?php if (!empty($primary->caption)): ?>
            <div class="row">
                <div class="span4" id="product_caption">
            <?php echo $primary->caption; ?>
                </div>
            </div>
                <?php endif; ?>
<?php if (count($product->images) > 1): ?>
            <div class="row">
                <div class="span4 product-images">
            <?php foreach ($product->images as $image): ?>
                        <img class="span1" onclick="$(this).squard('390', $('#primary-img'));" src="<?php echo base_url('uploads/images/medium/' . $image->filename); ?>"/>
                    <?php endforeach; ?>
                </div>
            </div>
                <?php endif; ?>
    </div>
    <div class="span8 pull-right">

        <div class="row">
            <div class="span8">
                <div class="page-header">
                    <h2 style="font-weight:normal">
<?php echo $product->name; ?>
<?php if ($this->session->userdata('admin')): ?>
                            <a class="btn" title="<?php echo lang('edit_product'); ?>" href="<?php echo site_url($this->config->item('admin_folder') . '/products/form/' . $product->id); ?>"><i class="icon-pencil"></i></a>
                        <?php endif; ?>
                        <span class="pull-right">
                        <?php if ($product->saleprice > 0): ?>
                                <small><?php echo lang('on_sale'); ?></small>
                                <span class="product_price">$<?php echo format_currency($product->saleprice); ?></span>
                            <?php else: ?>
                                <small><?php echo lang('product_price'); ?></small>
                                <span class="product_price">$<?php echo format_currency($product->price); ?></span>
                            <?php endif; ?>
                        </span>
                    </h2>
                </div>
            </div>
        </div>

        <div class="row">
            <div class="span8">
<?php echo $product->excerpt; ?>
            </div>
        </div>

        <div class="row" style="margin-top:15px; margin-bottom:15px;">
            <div class="span4 sku-pricing">
<?php if (!empty($product->sku)): ?><div><?php echo lang('sku'); ?>: <?php echo $product->sku; ?></div><?php endif; ?>&nbsp;
            </div>
                <?php if ((bool) $product->track_stock && $product->quantity < 1 && config_item('inventory_enabled')): ?>
                <div class="span4 out-of-stock">
                    <div><?php echo lang('out_of_stock'); ?></div>
                </div>
<?php endif; ?>
        </div>

        <div class="row">
            <div class="span8">
                <div class="product-cart-form">
<?php echo form_open('cart/add_to_cart', 'class="form-horizontal"'); ?>
                    <input type="hidden" name="cartkey" value="<?php echo $this->session->flashdata('cartkey'); ?>" />
                    <input type="hidden" name="id" value="<?php echo $product->id ?>"/>
                    <fieldset>
<?php if (count($options) > 0): ?>
    <?php
    foreach ($options as $option):
        $required = '';
        if ($option->required) {
            $required = ' <p class="help-block">Required</p>';
        }
        ?>
                                <div class="control-group">
                                    <label class="control-label"><?php echo $option->name; ?></label>
                                <?php
                                /*
                                  this is where we generate the options and either use default values, or previously posted variables
                                  that we either returned for errors, or in some other releases of Go Cart the user may be editing
                                  and entry in their cart.
                                 */

                                //if we're dealing with a textfield or text area, grab the option value and store it in value
                                if ($option->type == 'checklist') {
                                    $value = array();
                                    if ($posted_options && isset($posted_options[$option->id])) {
                                        $value = $posted_options[$option->id];
                                    }
                                } else {
                                    if (isset($option->values[0])) {
                                        $value = $option->values[0]->value;
                                        if ($posted_options && isset($posted_options[$option->id])) {
                                            $value = $posted_options[$option->id];
                                        }
                                    } else {
                                        $value = false;
                                    }
                                }

                                if ($option->type == 'textfield'):
                                    ?>
                                        <div class="controls">
                                            <input type="text" name="option[<?php echo $option->id; ?>]" value="<?php echo $value; ?>" class="span4"/>
                                        <?php echo $required; ?>
                                        </div>
                                    <?php elseif ($option->type == 'textarea'): ?>
                                        <div class="controls">
                                            <textarea class="span4" name="option[<?php echo $option->id; ?>]"><?php echo $value; ?></textarea>
                                        <?php echo $required; ?>
                                        </div>
                                    <?php elseif ($option->type == 'droplist'): ?>
                                        <div class="controls">
                                            <select name="option[<?php echo $option->id; ?>]">
                                                <option value=""><?php echo lang('choose_option'); ?></option>

                                        <?php
                                        foreach ($option->values as $values):
                                            $selected = '';
                                            if ($value == $values->id) {
                                                $selected = ' selected="selected"';
                                            }
                                            ?>

                                                    <option<?php echo $selected; ?> value="<?php echo $values->id; ?>">
                                                    <?php echo($values->price != 0) ? ' (+' . format_currency($values->price) . ') ' : '';
                                                    echo $values->name; ?>
                                                    </option>

                                                <?php endforeach; ?>
                                            </select>
                                                <?php echo $required; ?>
                                        </div>
                                                <?php elseif ($option->type == 'radiolist'): ?>
                                        <div class="controls">
            <?php
            foreach ($option->values as $values):

                $checked = '';
                if ($value == $values->id) {
                    $checked = ' checked="checked"';
                }
                ?>
                                                <label class="radio">
                                                    <input<?php echo $checked; ?> type="radio" name="option[<?php echo $option->id; ?>]" value="<?php echo $values->id; ?>"/>
                                                <?php echo($values->price != 0) ? '(+' . format_currency($values->price) . ') ' : '';
                                                echo $values->name; ?>
                                                </label>
                                            <?php endforeach; ?>
                                            <?php echo $required; ?>
                                        </div>
                                            <?php elseif ($option->type == 'checklist'): ?>
                                        <div class="controls">
                                            <?php
                                            foreach ($option->values as $values):

                                                $checked = '';
                                                if (in_array($values->id, $value)) {
                                                    $checked = ' checked="checked"';
                                                }
                                                ?>
                                                <label class="checkbox">
                                                    <input<?php echo $checked; ?> type="checkbox" name="option[<?php echo $option->id; ?>][]" value="<?php echo $values->id; ?>"/>
                                                <?php echo($values->price != 0) ? '(' . format_currency($values->price) . ') ' : '';
                                                echo $values->name; ?>
                                                </label>

                                                <?php endforeach; ?>
                                        </div>
            <?php echo $required; ?>
                                        <?php endif; ?>
                                </div>
                                <?php endforeach; ?>
                            <?php endif; ?>

                        <div class="control-group">
                            <label class="control-label"><?php echo lang('quantity') ?></label>
                            <div class="controls">
<?php if (!config_item('inventory_enabled') || config_item('allow_os_purchase') || !(bool) $product->track_stock || $product->quantity > 0) : ?>
    <?php if (!$product->fixed_quantity) : ?>
                                        <input class="span2" type="text" name="quantity" value=""/>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
                                    <?php endif; ?>
                                    <button class="btn btn-primary btn-large" type="submit" value="submit"><i class="icon-shopping-cart icon-white"></i> <?php echo lang('form_add_to_cart'); ?></button>
                                <?php endif; ?>
                            </div>
                        </div>

                    </fieldset>
                    </form>
                </div>

            </div>
        </div>

        <div class="row" style="margin-top:15px;">
            <div class="span8">
<?php echo $product->description; ?>
            </div>
        </div>

    </div>
    <div class="control-group">
        <div id="<?php echo "wishlist_" . $product->id; ?>" class="controls">
<?php if ($wishlistStatus != 0) { ?>
                <a href="javascript:void(0);" class="btn btn-primary btn-large" onclick="updateStatus('<?php echo $wishlistStatus; ?>');">Remove from Favorites</a>
<?php } else { ?>
                <a href="javascript:void(0);" class="btn btn-primary btn-large" onclick="updateStatus(0);">Add to Favorites</a>
            <?php } ?>

        </div>
    </div>
            <?php
            /*
              Rating section
             */
            if ($product->show_rating == 1) {
                ?>
        <p>
            Rating:
            <span class="starRating">
        <?php
        $strRating = '';
        for ($i = 5; $i > 0; $i--) {
            $strRating .= '<input id="rating' . $i . '" type="radio" name="rating" class="ratingClass" value="' . $i . '" ';
            if ($rating == $i) {
                $strRating .= ' checked="checked" ';
            }
            $strRating .= ' >';

            $strRating .= '<label for="rating' . $i . '">' . $i . '</label>';
        }
        echo $strRating;
        ?>

            </span>
                <?php echo "  " . $customerCount . " Customers has rated this product"; ?>
        </p>
        <p>Total Rating : <b><?php echo $rating; ?></b></p>

        <?php } ?>

    <hr style="background-color:#000000;border-width:0;color:#000000;height:1px;line-height:0;float:left;width:100%;"/>
    <div>
        <form id="frmAddReview" name="frmAddReview" method="post" action="">
            <h3>Add a review</h3>
            <textarea id="txtreview" name="txtreview" rows="3" cols="150"  style="width: 900px; height: 45px;"></textarea>
            <input class="btn btn-primary btn-large" type="submit" id="btnSubmit" name="btnSubmit" value="Submit">

        </form>
        <h3>Reviews</h3>
        <br/>
        <hr style="background-color:#000000;border-width:0;color:#000000;height:1px;line-height:0;float:left;width:100%;"/>

        <table class="table table-striped">
            <tbody>
<?php echo (count($reviewList) < 1) ? '<tr><td style="text-align:center;" colspan="7">No reviews has been added for this product</td></tr>' : '' ?>
<?php foreach ($reviewList as $review): ?>
                    <tr>

                        <td><?php echo $review->fullname . '<br>' . date('M d, Y', strtotime($review->date)); ?></td>
                        <td><?php echo $review->review_content; ?></td>


                    </tr>
<?php endforeach; ?>
            </tbody>
        </table>



    </div>
<?php if (!empty($product->related_products)): ?>
        <div class="related_products">
            <div class="row">
                <div class="span4">
                    <h3 style="margin-top:20px;"><?php echo lang('related_products_title'); ?></h3>
                    <ul class="thumbnails"> 
    <?php foreach ($product->related_products as $relate): ?>
                            <li class="span2 product">
        <?php
        $photo = theme_img('no_picture.png', lang('no_image_available'));



        $relate->images = array_values((array) json_decode($relate->images));

        if (!empty($relate->images[0])) {
            $primary = $relate->images[0];
            foreach ($relate->images as $photo) {
                if (isset($photo->primary)) {
                    $primary = $photo;
                }
            }

            $photo = '<img src="' . base_url('uploads/images/thumbnails/' . $primary->filename) . '" alt="' . $relate->seo_title . '"/>';
        }
        ?>
                                <a class="thumbnail" href="<?php echo site_url($relate->slug); ?>">
                                <?php echo $photo; ?>
                                </a>
                                <h5 style="margin-top:5px;"><a href="<?php echo site_url($relate->slug); ?>"><?php echo $relate->name; ?></a>
                                <?php if ($this->session->userdata('admin')): ?>
                                        <a class="btn" title="<?php echo lang('edit_product'); ?>" href="<?php echo site_url($this->config->item('admin_folder') . '/products/form/' . $relate->id); ?>"><i class="icon-pencil"></i></a>
                                <?php endif; ?>
                                </h5>

                                <div>
        <?php if ($relate->saleprice > 0): ?>
                                        <span class="price-slash"><?php echo lang('product_reg'); ?> <?php echo format_currency($relate->price); ?></span>
                                        <span class="price-sale"><?php echo lang('product_sale'); ?> <?php echo format_currency($relate->saleprice); ?></span>
                                    <?php else: ?>
                                        <span class="price-reg"><?php echo lang('product_price'); ?> <?php echo format_currency($relate->price); ?></span>
        <?php endif; ?>
                                </div>
                                    <?php if ((bool) $relate->track_stock && $relate->quantity < 1 && config_item('inventory_enabled')) { ?>
                                    <div class="stock_msg"><?php echo lang('out_of_stock'); ?></div>
        <?php } ?>
                            </li>
                                <?php endforeach; ?>
                    </ul>
                </div>
            </div>
        </div>
                        <?php endif; ?>
</div>
<script>
    $(function () {
        $('.category_container').each(function () {
            $(this).children().equalHeights();
        });
    });
</script>