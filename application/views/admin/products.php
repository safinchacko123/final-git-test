<?php

function trimstr($string, $length = 25, $method = 'WORDS', $pattern = '...') {  //this is dirty :(
    if (!is_numeric($length)) {
        $length = 25;
    }

    if (strlen($string) <= $length) {
        return rtrim($string);
    }

    $truncate = substr($string, 0, $length);

    if ($method != 'WORDS') {

        return rtrim($truncate) . $pattern;
    }

    if ($truncate[$length - 1] == ' ') {
        return rtrim($truncate) . $pattern;
    }
    // we got ' ' right where we want it

    $pos = strrpos($truncate, ' ');
    // lets find nearest right ' ' in the truncated string

    if (!$pos) {
        return $pattern;
    }
    // no ' ' (one word) or it resides at the very begining 
    // of the string so the whole string goes to the toilet

    return rtrim(substr($truncate, 0, $pos)) . $pattern;
    // profit
}

//set "code" for searches
if (!$code) {
    $code = '';
} else {
    $code = '/' . $code;
}

function sort_url($lang, $by, $sort, $sorder, $code, $admin_folder) {
    if ($sort == $by) {
        if ($sorder == 'asc') {
            $sort = 'desc';
            $icon = ' <i class="icon-chevron-up"></i>';
        } else {
            $sort = 'asc';
            $icon = ' <i class="icon-chevron-down"></i>';
        }
    } else {
        $sort = 'asc';
        $icon = '';
    }


    $return = site_url($admin_folder . '/products/index/' . $by . '/' . $sort . '/' . $code);

    echo '<a href="' . $return . '">' . $lang . $icon . '</a>';
}

if (!empty($term)):
    $term = json_decode($term);
    if (!empty($term->term) || !empty($term->category_id)):
        ?>
        <div class="alert alert-info">
            <?php echo sprintf(lang('search_returned'), intval($total)); ?>
        </div>
    <?php endif; ?>
<?php endif; ?>

<script type="text/javascript">

    function areyousure()
    {
        return confirm('<?php echo lang('confirm_delete_product'); ?>');
    }


    function updateStatus(product_id) {
        $.ajax({
            type: 'POST',
            cache: false,
            url: '<?php echo base_url(); ?>admin/products/updateRating/',
            data: {product_id: product_id},
            success: function (data) {
                if (data == 'false') {
                    alert('Please try after some time');
                } else {
                    $('#' + product_id).html(data);
                }
            }
        });
    }


    function areyousure()
    {
        return confirm('<?php echo lang('confirm_delete_product'); ?>');
    }

</script>
<style type="text/css">
    .pagination {
        margin:0px;
        margin-top:-3px;
    }
    .fitwidth {
        white-space: nowrap;width: 1%;
    }
</style>
<?php
$module = '';
if ($user_role != 'Venture') {
    $module = $this->config->item('admin_folder');
}
?>
<div class="row">
    <div class="span12" style="border-bottom:1px solid #f5f5f5;">
        <div class="row">
            <div class="span4">
                <?php echo $this->pagination->create_links(); ?>	&nbsp;
            </div>
            <div class="span8">
                <?php echo form_open($module . '/products', 'class="form-inline" style="float:right"'); ?>
                <fieldset>
                    <?php
                    if ($user_role != 'Venture') {
                        $vendorDropdown = '<select id="vendorDropdown" name="vendorDropdown">';
                        $vendorDropdown .= '<option value="0">Select Vendor</option>';

                        foreach ($vendorList as $vendor) {
                            if (isset($term->vendorDropdown) && $vendor->id == $term->vendorDropdown) {
                                $sel = 'selected="selected"';
                            } else {
                                $sel = '';
                            }


                            $vendorDropdown .= '<option value="' . $vendor->id . '" ' . $sel . '>' . $vendor->company . '</option>';
                        }
                        $vendorDropdown .= '</select>';
                        echo $vendorDropdown;
                    }
                    ?>

                    <?php

                    function list_categories($id, $categories, $sub = '') {

                        foreach ($categories[$id] as $cat):
                            ?>
                            <option class="span2" value="<?php echo $cat->id; ?>"><?php echo $sub . $cat->name; ?></option>
                            <?php
                            if (isset($categories[$cat->id]) && sizeof($categories[$cat->id]) > 0) {
                                $sub2 = str_replace('&rarr;&nbsp;', '&nbsp;', $sub);
                                $sub2 .= '&nbsp;&nbsp;&nbsp;&rarr;&nbsp;';
                                list_categories($cat->id, $categories, $sub2);
                            }
                        endforeach;
                    }

                    if (!empty($categories)) {
                        echo '<select name="category_id">';
                        echo '<option value="">' . lang('filter_by_category') . '</option>';
                        list_categories(0, $categories);
                        echo '</select>';
                    }
                    ?>

                    <input type="text" class="span2" name="term" placeholder="<?php echo lang('search_term'); ?>" /> 
                    <button class="btn" name="submit" value="search"><?php echo lang('search') ?></button>
                    <a class="btn" href="<?php echo site_url($this->config->item('admin_folder') . '/products/index'); ?>">Reset</a>
                </fieldset>
                </form>
            </div>
        </div>
    </div>
</div>
<div class="btn-group pull-right">
</div>


<?php echo form_open($this->config->item('admin_folder') . '/products/bulk_save', array('id' => 'bulk_form')); ?>
<table class="table <!--table-striped-->" width="100%;">
    <thead>
        <tr>
            <th>Image</th>
            <th><?php echo sort_url('Name', 'Name', $order_by, $sort_order, $code, $module); ?></th>
            <th><?php echo sort_url('Price', 'Price', $order_by, $sort_order, $code, $module); ?></th>
            <th><?php echo sort_url('Vendor', 'company', $order_by, $sort_order, $code, $module); ?></th>
           <!-- <th><?php echo sort_url('quantity', 'quantity', $order_by, $sort_order, $code, $module); ?></th> -->
            <th><?php echo sort_url('Enabled', 'Enabled', $order_by, $sort_order, $code, $module); ?></th>
            <th>Review</th>
            <th><?php echo sort_url('Rating', 'show_rating', $order_by, $sort_order, $code, $module); ?></th>
            <th><?php echo sort_url('Created On', 'created_on', $order_by, $sort_order, $code, $module); ?></th>            
            <th><?php echo sort_url(lang('modified_on'), 'modified_on', $order_by, $sort_order, $code, $module); ?></th>
            <th class="fitwidth">
                <!--<span class="btn-group pull-right">-->
                        <!-- <button class="btn" href="#"><i class="icon-ok"></i> <?php echo lang('bulk_save'); ?></button> -->
                <!--<span class="btn-group pull-right">-->
                <a class="btn pull-right " style="font-weight:normal;" href="<?php echo site_url($module . '/products/form'); ?>"><i class="icon-plus-sign"></i> <?php echo lang('add_new_product'); ?></a>
                <!--</span>-->
                <!--input-block-level form-control-->
                <!--</span>-->
            </th>
        </tr>
    </thead>

    <tbody>
        <?php echo (count($products) < 1) ? '<tr><td style="text-align:center;" colspan="9">' . lang('no_products') . '</td></tr>' : '' ?>
        <?php foreach ($products as $product): ?>
            <tr>

                <td rowspan="2">
                    <?php
                    $arrImages = json_decode($product->images);
                    if ($arrImages) {
                        foreach ($arrImages As $rowImg) {
                            ?>
                            <div class="thumbnail"><img src="<?php echo base_url(); ?>uploads/images/small/<?php echo $rowImg->filename; ?>" alt="" /></div>
                        <?php
                        }
                    }
                    ?>
                </td>

                <td><?php echo $product->name; ?></td>

                <td style=""><?php echo $product->currency_symbol . $product->price; ?></td>
                <td><?php echo $product->company; ?></td>
                <!-- <td><?php echo $product->quantity; ?></td> -->
                <td><?php echo ($product->enabled == 1) ? 'Active' : 'Inactive'; ?>
                </td>
                <td>
                    <a class="btn" href="<?php echo site_url($module . '/products/review/' . $product->id); ?>">View Reviews</a>
                </td>
                <td><a id="<?php echo $product->id; ?>" onclick="updateStatus('<?php echo $product->id; ?>');" href="javascript:void(0);"><?php echo ($product->show_rating == 0) ? '<i class="icon-remove"></i>' : '<i class="icon-ok"></i>'; ?></a></td>
                <?php $time = $product->created_on; ?>
                <!--<td><?php //echo date('Y/m/d H:i', $time);                      ?></td>-->
                <td><?php echo date('d/m/Y', strtotime($time)); ?></td>
                <td><?php $modified_time = $product->modified_on; ?>
                    <?php echo date('d/m/Y', strtotime($modified_time)); ?>
                </td>
                <td class="fitwidth">
                    <span class="pull-right">
                        <a class="btn" href="<?php echo site_url($module . '/products/form/' . $product->id); ?>"><i class="icon-pencil"></i>  <?php echo lang('edit'); ?></a>
                        <!-- <a class="btn" href="<?php //echo  site_url($this->config->item('admin_folder').'/products/form/'.$product->id.'/1');       ?>"><i class="icon-share-alt"></i> <?php //echo lang('copy');       ?></a> -->
                        <a class="btn btn-danger" href="<?php echo site_url($module . '/products/delete/' . $product->id); ?>" onclick="return areyousure();"><i class="icon-trash icon-white"></i> <?php echo lang('delete'); ?></a>
                    </span>
                </td>
            </tr>
            <tr>
                <td colspan="8" style="border: none;">

                    <?php
                    $str = $product->description;
                    $t = preg_replace('/<[^<|>]+?>/', '', htmlspecialchars_decode($str));
                    $t = htmlentities($t, ENT_QUOTES, "UTF-8");
                    echo trimstr($t, 300);
                    ?>

                </td>
            </tr>

        <?php endforeach; ?>
    </tbody>
</table>

</form>