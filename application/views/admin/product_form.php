<div id="error-div"><?php echo form_error('images', "<div class='alert alert-error'><a class='close' data-dismiss='alert'>×</a><div id='error-msg'>", "</div></div>"); ?></div>
<style type="text/css">
    .tab-content{
        min-height: 300px;
    }
    .clsRelevent .product-image{
        width: 20%;
        margin-right: 5px;
    }
    .classBlue{
        background-color: #1821DC;
    }
</style>

<?php $GLOBALS['option_value_count'] = 0; ?>
<style type="text/css">
    .sortable { list-style-type: none; margin: 0; padding: 0; width: 100%; }
    .sortable li { margin: 0 3px 3px 3px; padding: 0.4em; padding-left: 1.5em; height: 18px; }
    .sortable li>span { position: absolute; margin-left: -1.3em; margin-top:.4em; }
</style>

<script type="text/javascript">
//<![CDATA[

    $(document).ready(function () {
        $(".sortable").sortable();
        $(".sortable > span").disableSelection();
        //if the image already exists (phpcheck) enable the selector

<?php if ($id) : ?>
            //options related
            var ct = $('#option_list').children().size();
            // set initial count
            option_count = <?php echo count($product_options); ?>;
<?php endif; ?>

        photos_sortable();
    });

    function add_product_image(data)
    {
        p = data.split('.');

        var photo = '<?php add_image("'+p[0]+'", "'+p[0]+'.'+p[1]+'", '', '', '', base_url('uploads/images/thumbnails')); ?>';
        $('#gc_photos').append(photo);
        $('#gc_photos').sortable('destroy');
        photos_sortable();
    }

    function remove_image(img)
    {
        if (confirm('<?php echo lang('confirm_remove_image'); ?>'))
        {
            var id = img.attr('rel')
            $('#gc_photo_' + id).remove();
        }
    }

    function photos_sortable()
    {
        $('#gc_photos').sortable({
            handle: '.gc_thumbnail',
            items: '.gc_photo',
            axis: 'y',
            scroll: true
        });
    }

    function remove_option(id)
    {
        if (confirm('<?php echo lang('confirm_remove_option'); ?>'))
        {
            $('#option-' + id).remove();
        }
    }

//]]>
</script>

<script type="text/javascript" src="/application/themes/default/assets/js/product/index.js"></script>
<?php
$module = '';
if ($user_role != 'Venture') {
    $module = $this->config->item('admin_folder');
}
$attributes = array('id' => 'product-add');
?>
<?php echo form_open_multipart($module . '/products/form/' . $id, $attributes); ?>
<div class="row">
    <div class="span8">
        <div class="tabbable">
            <ul class="nav nav-tabs">
                <li class="active"><a href="#product_info" data-toggle="tab"><?php echo lang('details'); ?></a></li>
                <?php
                //if there aren't any files uploaded don't offer the client the tab
                if (count($file_list) > 0):
                    ?>
                    <li style="display: none"><a href="#product_downloads" data-toggle="tab"><?php echo lang('digital_content'); ?></a></li>
                <?php endif; ?>
                <li><a href="#product_categories" data-toggle="tab"><?php echo lang('categories'); ?></a></li>
                <!--<li><a href="#product_options" data-toggle="tab"><?php echo lang('options'); ?></a></li>-->
                <li><a id="productPhoto" href="#product_photos" data-toggle="tab"><?php echo lang('images'); ?></a></li>
                <li><a href="#product_related" data-toggle="tab"><?php echo lang('related_products'); ?></a></li>
            </ul>
        </div>
        <div class="tab-content">
            <div class="tab-pane active" id="product_info">
                <div class="row">
                    <div class="span8">
                        <?php
                        $data = array('placeholder' => lang('name'), 'name' => 'name', 'id' => 'productName', 'value' => set_value('name', $name), 'class' => 'input-xxlarge');
                        echo form_input($data);
                        ?>
                    </div>
                </div>

                <div class="row">
                    <div class="span8">

                        <?php
                        $data = array('name' => 'description', 'class' => 'redactor', 'value' => set_value('description', $description));
                        echo form_textarea($data);
                        ?>

                    </div>
                </div>

                <!--                <div class="row">
                                    <div class="span8">
                                        <label><?php echo lang('excerpt'); ?></label>
                <?php
                $data = array('name' => 'excerpt', 'value' => set_value('excerpt', $excerpt), 'class' => 'span8', 'rows' => 5);
                echo form_textarea($data);
                ?>
                                    </div>
                                </div>-->

                <!--                <div class="row">
                                    <div class="span8">
                                        <fieldset>
                                            <legend><?php echo lang('inventory'); ?></legend>
                                            <div class="row" style="padding-top:10px;">
                                                <div class="span3">
                                                    <label for="track_stock"><?php echo lang('track_stock'); ?> </label>
                <?php
                $options = array('1' => lang('yes')
                    , '0' => lang('no')
                );
                echo form_dropdown('track_stock', $options, set_value('track_stock', $track_stock), 'class="span3"');
                ?>
                                                </div>
                                                <div class="span3">
                                                    <label for="fixed_quantity"><?php echo lang('fixed_quantity'); ?> </label>
                <?php
                $options = array('0' => lang('no')
                    , '1' => lang('yes')
                );
                echo form_dropdown('fixed_quantity', $options, set_value('fixed_quantity', $fixed_quantity), 'class="span3"');
                ?>
                                                </div>
                                                <div class="span2">
                                                    <label for="quantity"><?php //echo lang('quantity'); ?> </label>
                <?php
                //$data = array('name' => 'quantity', 'value' => set_value('quantity', $quantity), 'class' => 'span2');
                //echo form_input($data);
                ?>
                                                </div>
                                            </div>
                                        </fieldset>
                                    </div>
                                </div>-->

                <!--                <div class="row">
                                    <div class="span8">
                                        <fieldset>
                                            <legend><?php echo lang('header_information'); ?></legend>
                                            <div class="row" style="padding-top:10px;">
                                                <div class="span8">
                
                                                    <label for="slug"><?php echo lang('slug'); ?> </label>
                <?php
                $data = array('name' => 'slug', 'value' => set_value('slug', $slug), 'class' => 'span8');
                echo form_input($data);
                ?>
                
                                                    <label for="seo_title"><?php echo lang('seo_title'); ?> </label>
                <?php
                $data = array('name' => 'seo_title', 'value' => set_value('seo_title', $seo_title), 'class' => 'span8');
                echo form_input($data);
                ?>
                
                                                    <label for="meta"><?php echo lang('meta'); ?> <i><?php echo lang('meta_example'); ?></i></label> 
                <?php
                $data = array('name' => 'meta', 'value' => set_value('meta', html_entity_decode($meta)), 'class' => 'span8');
                echo form_textarea($data);
                ?>
                                                </div>
                                            </div>
                                        </fieldset>
                                    </div>
                                </div>-->

            </div>

            <div class="tab-pane" id="product_downloads">
                <div class="alert alert-info">
                    <?php echo lang('digital_products_desc'); ?>
                </div>
                <fieldset>
                    <table class="table table-striped">
                        <thead>
                            <tr>
                                <th><?php echo lang('filename'); ?></th>
                                <th><?php echo lang('title'); ?></th>
                                <th style="width:70px;"><?php echo lang('size'); ?></th>
                                <th style="width:16px;"></th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php echo (count($file_list) < 1) ? '<tr><td style="text-align:center;" colspan="6">' . lang('no_files') . '</td></tr>' : '' ?>
                            <?php foreach ($file_list as $file): ?>
                                <tr>
                                    <td><?php echo $file->filename ?></td>
                                    <td><?php echo $file->title ?></td>
                                    <td><?php echo $file->size ?></td>
                                    <td><?php echo form_checkbox('downloads[]', $file->id, in_array($file->id, $product_files)); ?></td>
                                </tr>
                            <?php endforeach; ?>
                        </tbody>
                    </table>
                </fieldset>
            </div>

            <div class="tab-pane" id="product_categories">
                <div class="row">
                    <div class="span8">
                        <?php if (isset($categories[0])): ?>
                            <label><strong><?php echo lang('select_a_category'); ?></strong></label>
                            <table class="table table-striped">
                                <thead>
                                    <tr>
                                        <th colspan="2"><?php echo lang('name') ?></th>
                                    </tr>
                                </thead>
                                <?php

                                function list_categories($parent_id, $cats, $sub = '', $product_categories) {

                                    foreach ($cats[$parent_id] as $cat):
                                        ?>
                                        <tr>
                                            <td><?php echo $sub . $cat->name; ?></td>
                                            <td>
                                                <input type="checkbox" name="categories[]" value="<?php echo $cat->id; ?>" <?php echo(in_array($cat->id, $product_categories)) ? 'checked="checked"' : ''; ?>/>
                                            </td>
                                        </tr>
                                        <?php
                                        if (isset($cats[$cat->id]) && sizeof($cats[$cat->id]) > 0) {
                                            $sub2 = str_replace('&rarr;&nbsp;', '&nbsp;', $sub);
                                            $sub2 .= '&nbsp;&nbsp;&nbsp;&rarr;&nbsp;';
                                            list_categories($cat->id, $cats, $sub2, $product_categories);
                                        }
                                    endforeach;
                                }

                                list_categories(0, $categories, '', $product_categories);
                                ?>
                            </table>
                        <?php else: ?>
                            <div class="alert"><?php echo lang('no_available_categories'); ?></div>
                        <?php endif; ?>
                    </div>
                </div>
            </div>

            <div class="tab-pane" id="product_options">
                <div class="row">
                    <div class="span8">
                        <div class="pull-right" style="padding:0px 0px 10px 0px;">
                            <select id="option_options" style="margin:0px;">
                                <option value=""><?php echo lang('select_option_type') ?></option>
                                <option value="checklist"><?php echo lang('checklist'); ?></option>
                                <option value="radiolist"><?php echo lang('radiolist'); ?></option>
                                <option value="droplist"><?php echo lang('droplist'); ?></option>
                                <option value="textfield"><?php echo lang('textfield'); ?></option>
                                <option value="textarea"><?php echo lang('textarea'); ?></option>
                            </select>
                            <input id="add_option" class="btn" type="button" value="<?php echo lang('add_option'); ?>" style="margin:0px;"/>
                        </div>
                    </div>
                </div>

                <script type="text/javascript">

    $("#add_option").click(function () {
        if ($('#option_options').val() != '')
        {
            add_option($('#option_options').val());
            $('#option_options').val('');
        }
    });

    function add_option(type)
    {
        //increase option_count by 1
        option_count++;

<?php
$value = array(array('name' => '', 'value' => '', 'weight' => '', 'price' => '', 'limit' => ''));
$js_textfield = (object) array('name' => '', 'type' => 'textfield', 'required' => false, 'values' => $value);
$js_textarea = (object) array('name' => '', 'type' => 'textarea', 'required' => false, 'values' => $value);
$js_radiolist = (object) array('name' => '', 'type' => 'radiolist', 'required' => false, 'values' => $value);
$js_checklist = (object) array('name' => '', 'type' => 'checklist', 'required' => false, 'values' => $value);
$js_droplist = (object) array('name' => '', 'type' => 'droplist', 'required' => false, 'values' => $value);
?>
        if (type == 'textfield')
        {
            $('#options_container').append('<?php add_option($js_textfield, "'+option_count+'"); ?>');
        }
        else if (type == 'textarea')
        {
            $('#options_container').append('<?php add_option($js_textarea, "'+option_count+'"); ?>');
        }
        else if (type == 'radiolist')
        {
            $('#options_container').append('<?php add_option($js_radiolist, "'+option_count+'"); ?>');
        }
        else if (type == 'checklist')
        {
            $('#options_container').append('<?php add_option($js_checklist, "'+option_count+'"); ?>');
        }
        else if (type == 'droplist')
        {
            $('#options_container').append('<?php add_option($js_droplist, "'+option_count+'"); ?>');
        }
    }

    function add_option_value(option)
    {

        option_value_count++;
<?php
$js_po = (object) array('type' => 'radiolist');
$value = (object) array('name' => '', 'value' => '', 'weight' => '', 'price' => '');
?>
        $('#option-items-' + option).append('<?php add_option_value($js_po, "'+option+'", "'+option_value_count+'", $value); ?>');
    }

    $(document).ready(function () {
        $('body').on('click', '.option_title', function () {
            $($(this).attr('href')).slideToggle();
            return false;
        });

        $('body').on('click', '.delete-option-value', function () {
            if (confirm('<?php echo lang('confirm_remove_value'); ?>'))
            {
                $(this).closest('.option-values-form').remove();
            }
        });



        $('#options_container').sortable({
            axis: "y",
            items: 'tr',
            handle: '.handle',
            forceHelperSize: true,
            forcePlaceholderSize: true
        });

        $('.option-items').sortable({
            axis: "y",
            handle: '.handle',
            forceHelperSize: true,
            forcePlaceholderSize: true
        });
    });
                </script>
                <style type="text/css">
                    .option-form {
                        display:none;
                        margin-top:10px;
                    }
                    .option-values-form
                    {
                        background-color:#fff;
                        padding:6px 3px 6px 6px;
                        -webkit-border-radius: 3px;
                        -moz-border-radius: 3px;
                        border-radius: 3px;
                        margin-bottom:5px;
                        border:1px solid #ddd;
                    }

                    .option-values-form input {
                        margin:0px;
                    }
                    .option-values-form a {
                        margin-top:3px;
                    }
                </style>
                <div class="row">
                    <div class="span8">
                        <table class="table table-striped"  id="options_container">
                            <?php
                            $counter = 0;
                            if (!empty($product_options)) {
                                foreach ($product_options as $po) {
                                    $po = (object) $po;
                                    if (empty($po->required)) {
                                        $po->required = false;
                                    }

                                    add_option($po, $counter);
                                    $counter++;
                                }
                            }
                            ?>

                        </table>
                    </div>
                </div>
            </div>

            <div class="tab-pane" id="product_related">
                <div class="row">
                    <div class="span8">
                        <label><strong><?php echo lang('select_a_product'); ?></strong></label>
                    </div>
                </div>
                <div class="row">
                    <div class="span2" style="text-align:center">
                        <div class="row">
                            <div class="span2">
                                <input class="span2" type="text" id="product_search" />
                                <script type="text/javascript">
                                    $('#product_search').keyup(function () {
                                        $('#product_list').html('');
                                        run_product_query();
                                    });

                                    function run_product_query()
                                    {
                                        $.post("<?php echo site_url($this->config->item('admin_folder') . '/products/product_autocomplete/'); ?>", {name: $('#product_search').val(), limit: 10},
                                        function (data) {

                                            $('#product_list').html('');

                                            $.each(data, function (index, value) {

                                                if ($('#related_product_' + index).length == 0)
                                                {
                                                    $('#product_list').append('<option id="product_item_' + index + '" value="' + index + '">' + value + '</option>');
                                                }
                                            });

                                        }, 'json');
                                    }
                                </script>
                            </div>
                        </div>
                        <div class="row">
                            <div class="span2">
                                <select class="span2" id="product_list" size="5" style="margin:0px;"></select>
                            </div>
                        </div>
                        <div class="row">
                            <div class="span2" style="margin-top:8px;">
                                <a href="#" onclick="add_related_product();
                                        return false;" class="btn" title="Add Related Product"><?php echo lang('add_related_product'); ?></a>
                            </div>
                        </div>
                    </div>
                    <div class="span6">
                        <table class="table table-striped" style="margin-top:10px;">
                            <tbody id="product_items_container">
                                <?php
                                foreach ($related_products as $rel) {
                                    echo related_items($rel->id, $rel->name);
                                }
                                ?>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>

            <div class="tab-pane" id="product_photos">
                <div class="row">
                    <iframe id="iframe_uploader" src="<?php echo site_url($this->config->item('admin_folder') . '/products/product_image_form'); ?>" class="span8" style="height:75px; border:0px;"></iframe>
                </div>
                <div class="row">
                    <div class="span8">

                        <div id="gc_photos">

                            <?php
                            foreach ($images as $photo_id => $photo_obj) {
                                if (!empty($photo_obj)) {
                                    $photo = (array) $photo_obj;
                                    add_image($photo_id, $photo['filename'], $photo['alt'], $photo['caption'], $photo['is_nutritional'], isset($photo['primary']));
                                }
                            }
                            ?>
                        </div>
                    </div>
                </div>

                <div class="row clsRelevent">
                    <div class="span8">
                        <div><b>Relevant images</b></div>
                        <div id="gc_photos">

                        </div>
                    </div>
                </div>

            </div>
        </div>
        <br />
        <div class="row">
            <div class="span8">
                <h4><?php echo lang('addons'); ?></h4>
                <br />
                <div id="createbuttondiv">
                    <?php
                    $addons = set_value('createmainaddons', $addons); //populate below fields
                    foreach ($addons as $key => $value) {
                        $checked = (set_value('createmainaddons[' . $key . '][required]', $addons[$key]['required']) == "required") ? 'checked="checked"' : "";
                        echo '<div class="form-group madSubAddonNew2 form-horizontal addonmarginbottom"><span name="mainremove_' . $key . '" id="mainremove_' . $key . '" class="col-sm-offset-3 col-sm-9"><span class="" id="mainaddremove_' . $key . '"><input type="text" placeholder="' . lang('name') . '" value="' . set_value('createmainaddons[' . $key . '][mainaddonsname]', $addons[$key]['mainaddonsname']) . '" class="form-control input-sm textbox inline textaddonbottom" id="mainaddons_' . $key . '" name="createmainaddons[' . $key . '][mainaddonsname]"> <input type="number" class="form-control input-sm textbox inline textaddonbottom" placeholder="' . lang('max_options_selectable') . '" value="' . set_value('createmainaddons[' . $key . '][mainaddoncnt]', $addons[$key]['mainaddoncnt']) . '" id="mainaddoncnt" name="createmainaddons[' . $key . '][mainaddoncnt]"><label class="checkbox inline margin-l"><input type="checkbox" name="createmainaddons[' . $key . '][required]" value="required" class="form-control input-sm textaddonbottom" ' . $checked . '">' . lang('addon_required') . '</label><span style="margin-left: 15px;" class="col-sm-2"><a class="btn btnmargin" onclick="removemainaddon(' . $key . ');"><i class="glyphicon glyphicon-remove">X</i></a></span><span id="sublist_' . $key . '"><a class="btn btnmargin" onclick="createAddSubaddonsList(' . $key . ');"><i class="glyphicon glyphicon-plus-sign marRight"></i>' . lang('add_options') . '</a></span><div class="addtoCartInner" id="createsubbuttondiv_' . $key . '">';

                        foreach ($value['subaddons'] as $sub_key => $sub_value) {
                            if (set_value('createmainaddons[' . $key . '][subaddons][' . $sub_key . '][subaddonsradio]', $addons[$key]['subaddons'][$sub_key]['subaddonsradio']) == "Paid") {
                                $paid_checked = 'checked="checked"';
                                $display = '';
                            } else {
                                $paid_checked = '';
                                $display = 'style="display:none;"';
                            }

                            $free_checked = (set_value('createmainaddons[' . $key . '][subaddons][' . $sub_key . '][subaddonsradio]', $addons[$key]['subaddons'][$sub_key]['subaddonsradio']) == "Free") ? 'checked="checked"' : "";
                            echo '<div class="madSubAddonNew1 form-horizontal addonmargin" id="removesubmore_' . $sub_key . '"><span class="radio inline"><input type="text" placeholder="' . lang('name') . '" value="' . set_value('createmainaddons[' . $key . '][subaddons][' . $sub_key . '][subaddonsname]', $addons[$key]['subaddons'][$sub_key]['subaddonsname']) . '" class="form-control textbox inline textaddonbottom" id="mainaddonsmore_' . $sub_key . '" name="createmainaddons[' . $key . '][subaddons][' . $sub_key . '][subaddonsname]"></span><label class="radio inline" for="' . $key . '"><input type="radio" onclick="createaddonsfreeoption(' . $sub_key . ');" checked="checked" value="Free" name="createmainaddons[' . $key . '][subaddons][' . $sub_key . '][subaddonsradio]" class="inputPrice" id="' . $key . '" ' . $free_checked . '>' . lang('free') . '</label><label class="radio inline" for="2"><input type="radio" onclick="createaddonspaidoption(' . $sub_key . ');" value="Paid" name="createmainaddons[' . $key . '][subaddons][' . $sub_key . '][subaddonsradio]" class="inputPrice" id="2" ' . $paid_checked . '>' . lang('paid') . '</label><span class="col-sm-4 pad' . $sub_key . '" ' . $display . ' id="showcreateaddonsprice1_' . $sub_key . '"><input type="number" style="margin-left: 15px;" placeholder="' . lang('price') . '" value="' . set_value('createmainaddons[' . $key . '][subaddons][' . $sub_key . '][subaddonsprice]', $addons[$key]['subaddons'][$sub_key]['subaddonsprice']) . '" name="createmainaddons[' . $key . '][subaddons][' . $sub_key . '][subaddonsprice]" step="0.01" class="form-control textbox inline"></span><a class="btn btnmargin" onclick="removeSubmore(' . $sub_key . ');"><i class="glyphicon glyphicon-remove">X</i></a></div>';
                        }
                        echo '</div></span> </span> </div>';
                    }
                    ?>

                </div>
                <a id="createaddons" onclick="addCreateMoreAddons();" class="btn" id="madAddons_firstajax"><i class="fa fa-file marRight"></i><?php echo lang('create_addons'); ?></a>
            </div>
        </div>
        <br />
        <div class="row">
            <div class="span8">
                <div>
                    <h4><?php echo lang('cuisine'); ?></h4>       
                    <br />
                    <?php
                    if (empty($id)) {
                        if ($cuisines) {
                            $i = 0;
                            $div = array();
                            ?>
                            <?php
                            foreach ($cuisines as $cuisine) {

                                $div[$i % 3] .= '<label class="checkbox clearfix"><input';
                                if (in_array($cuisine->cuisine_id, $cuisinedatas)) {
                                    $div[$i % 3] .= " checked=checked ";
                                }
                                $div[$i % 3] .= ' id="f_cuisine_id" name="cuisine_id[]" type="checkbox" value="' . $cuisine->cuisine_id . '" class="check">' . $cuisine->cuisine_name . '</label>';
                                $i++;
                            }
                            ?>
                            <?php
                        }
                    } else {
                        if ($cuisines) {
                            $i = 0;
                            $div = array();
                            foreach ($cuisines as $cuisine) {
                                $div[$i % 3] .= '<label class="checkbox clearfix"><input';
                                if (in_array($cuisine->cuisine_id, $productcuisines) && empty($cuisinedatas)) {
                                    $div[$i % 3] .= " checked=checked ";
                                } elseif (in_array($cuisine->cuisine_id, $cuisinedatas)) {
                                    $div[$i % 3] .= " checked=checked ";
                                }
                                $div[$i % 3] .= ' id="f_cuisine_id" name="cuisine_id[]" type="checkbox" value="' . $cuisine->cuisine_id . '" class="check">' . $cuisine->cuisine_name . '</label>';
                                $i++;
                            }
                        }
                    }
                    ?>
                    <div class="row-fluid">
                        <div class="span4">
                            <?php echo $div[0]; ?>
                        </div>
                        <div class="span4">
                            <?php echo $div[1]; ?>
                        </div>
                        <div class="span4">
                            <?php echo $div[2]; ?>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <br />
        <div class="row">
            <div class="span8">
                <h4><?php echo lang('ingredients'); ?></h4>
                <br>
                <div class="form-group">
                    <?php
                        $data = array('name' => 'ingredients', 'id' => 'ingredients', 'value' => $ingredients);
                        echo form_textarea($data);
                    ?>
    </div>
            </div>
        </div>
        <br />
        <div class="row">
            <div class="span8">
                <h4><?php echo lang('directions'); ?></h4>
                <br>
                <div class="form-group">
                    <?php
                        $data = array('name' => 'directions', 'id' => 'directions', 'value' => $directions);
                        echo form_textarea($data);
                    ?>
                </div>
            </div>
        </div>
        <br />        
                    
        <div class="row">
            <div class="span8">
                <h4><?php echo lang('nutritional_facts'); ?></h4>
                <br>
                <div class="form-group">
                    <?php if($nutrition_image) { ?>
                        <img src="<?php echo base_url('uploads/images/thumbnails/' . $nutrition_image); ?>" style="padding:5px; border:1px solid #ddd"/>
                    <?php } ?>
                    <input type="file" id="nutritional_facts" name="nutritional_facts">
                    <?php if($nutrition_image) { ?>
                    <br /> 
                    <p><?php echo lang('nutritional_note'); ?></p>   
                    <?php } ?>
                </div>
            </div>
        </div>
        <br />
        <div class="row">
            <div class="span8">
                <h5><?php echo lang('nutritional_alt_tag'); ?></h5>
                <br>
                <div class="form-group">
                    <?php
                        $data = array('name' => 'alt_tag', 'value' =>$alt_tag, 'class' => 'input-xxlarge');
                        echo form_input($data);
                    ?>
                </div>
            </div>
        </div>
        <br />
        <div class="row">
            <div class="span8">
                <h5><?php echo lang('nutritional_caption'); ?></h5>
                <br>
                <div class="form-group">                    
                    <?php
                        $data = array('name' => 'caption', 'rows'=>'3', 'class' => 'input-xxlarge', 'value' =>$caption);
                        echo form_textarea($data);
                    ?>
                </div>
            </div>
        </div>        
    </div>
    <div class="span4">
        <?php
        $options = array('1' => lang('enabled')
            , '0' => lang('disabled')
        );
        echo form_dropdown('enabled', $options, set_value('enabled', $enabled), 'class="span4"');
        ?>

        <?php /*
          $options = array('1' => lang('shippable')
          , '0' => lang('not_shippable')
          );
          echo form_dropdown('shippable', $options, set_value('shippable', $shippable), 'class="span4"');
          ?>

          <?php
          $options = array('1' => lang('taxable')
          , '0' => lang('not_taxable')
          );
          echo form_dropdown('taxable', $options, set_value('taxable', $taxable), 'class="span4"');
         * 
         */
        ?>

        <?php /*
          <label for="sku"><?php echo lang('sku'); ?></label>
          <?php
          $data = array('name' => 'sku', 'value' => set_value('sku', $sku), 'class' => 'span4');
          echo form_input($data);
          ?>
         */ ?>
        <div class="pad-margin">
          <?php
            $data = array('name' => 'subunit', 'value' => set_value('subunit', $subunit), 'class' => 'textbox inline width-35', 'placeholder' => lang('sub_unit'));

            echo form_input($data);/*<input type="number" name="subunit" value="<?php echo set_value('subunit', $subunit); ?>" class="textbox inline width-35" placeholder="Subunits">*/

            $data = array('name' => 'weight', 'value' => set_value('weight', $weight), 'class' => 'textbox inline width-35', 'placeholder' => lang('weight_volume'));

            echo form_input($data);

            //echo "<span style='margin-left: 5px;'></span>";

            $options = array(
                '' => 'Unit',
                'cbm' => 'cubic meter (cbm)',
                'floz' => 'fluid ounce (fl. oz)',
                'gal' => 'gallon (gal)',
                'gm' => 'gram (gm)',
                'kg' => 'kilogram (kg)',
                'ml' => 'milliliter (ml)',
                'l' => 'liter (l)',
                'oz' => 'ounce (oz)',
                'lb' => 'pound (lb)'
            );

            $selected = ($this->input->post('unit')) ? $this->input->post('unit') : '';
            echo form_dropdown('unit', $options, set_value('unit', $weight_volume_unit), 'class="textbox inline width-19"');
            ?>
        </div>

        <div>
            <label class="inline-disp"><?php echo lang('price'); ?>: </label>
            <label for="price_fixed" class="radio inline no-padding-top">
                <?php
                if ($price_type_check == 'fixed' || $price_type == 'fixed') {
                    $checked = 'fixed';
                } else {
                    $checked = 'size';
                }
                $data = array(
                    'name' => 'price_option',
                    'id' => 'price_fixed',
                    'value' => 'fixed',
                    'data-id' => 'price_fixed_options',
                    'checked' => set_value('price_option', $checked) === 'fixed'
                );

                echo form_radio($data);
                echo lang('Fixed');
                ?>
            </label>
            <label for="price_size" class="radio inline no-padding-top">
                <?php
                if ($price_type_check == 'size' || $price_type == 'size') {
                    $checked = 'size';
                } else {
                    $checked = 'fixed';
                }
                $data = array(
                    'name' => 'price_option',
                    'id' => 'price_size',
                    'value' => 'size',
                    'data-id' => 'price_size_options',
                    'checked' => set_value('price_option', $checked) === 'size'
                );

                echo form_radio($data);
                echo lang('Size');
                ?>
            </label>
            <!--<label for="fixed" class="radio inline no-padding-top">
                <input type="radio" onclick="return fixedOption();" value="fixed" id="fixed" name="price_option">
                Fixed
            </label>-->
            <div id="price_options_container">
                <div id="price_fixed_options">
                    <?php
                    $data = array('name' => 'price', 'value' => set_value('price', $price), 'class' => 'span4');
                    echo form_input($data);
                    ?>        
                    <label for="saleprice"><?php echo lang('saleprice'); ?></label>
                    <?php
                    $data = array('name' => 'saleprice', 'value' => set_value('saleprice', $saleprice), 'class' => 'span4');
                    echo form_input($data);
                    ?>
                    <label for="fixed_stock"><?php echo lang('max_items_allowed'); ?></label>
                    <?php
                    $data = array('name' => 'quantity', 'value' => set_value('quantity', $quantity), 'class' => 'span4');
                    echo form_input($data);
                    ?>
                </div>
                <div id="price_size_options" style="display: none;">
                    <div class="sizes_container">
                        <?php
                        $size = set_value('size', $size); //populate below fields
                        for ($i = 0; $i < count($size['name']); $i++) {
                            $size_price = isset($size['price'][$i]) ? $size['price'][$i] : "";
                            $size_sale_price = isset($size['sale_price'][$i]) ? $size['sale_price'][$i] : "";
                            $size_stock = isset($size['stock'][$i]) ? $size['stock'][$i] : "";
                            echo '<div class="size_container margin-10"><input type="text" class="textbox inline width-45 textbox-s" name="size[name][]" placeholder="' . lang('Size_name') . '" value="' . $size['name'][$i] . '"><input type="text" class="textbox inline width-45 textbox-s" name="size[stock][]" placeholder="' . lang('max_items_allowed') . '" value="' . $size_stock . '" style=""><br /><input type="text" class="textbox inline width-20 textbox-s" name="size[price][]" placeholder="' . lang('price') . '" value="' . $size_price . '"><input type="text" class="textbox inline width-20 textbox-s" name="size[sale_price][]" placeholder="' . lang('saleprice') . '" value="' . $size_sale_price . '"><a class="btn textbox inline remove-sizes remove-margin"><i class="icon-minus-sign"> </i> ' . lang('remove') . '</a></div>'; //<a style="display: inline-block; margin: 9px 0px 0px 11px;" class="btn remove-size"><i class="icon-minus-sign"> </i></a>
                        }
                        ?>
                    </div>
                    <br/>
                    <a id="add-sizes" class="btn "><i class="icon-plus-sign"> </i> Add</a>

                </div>
            </div>
        </div>
    </div>
</div>

<div class="form-actions">
    <button type="submit" class="btn btn-primary"><?php echo lang('save'); ?></button>
</div>
<!--</form>-->

<?php echo form_close(); ?>

<?php

function add_image($photo_id, $filename, $alt, $caption, $is_nutritional, $primary = false) {

    ob_start();
    ?>
    <div class="row gc_photo" id="gc_photo_<?php echo $photo_id; ?>" style="background-color:#fff; border-bottom:1px solid #ddd; padding-bottom:20px; margin-bottom:20px;">
        <div class="span2">
            <input type="hidden" name="images[<?php echo $photo_id; ?>][filename]" value="<?php echo $filename; ?>"/>
            <input type="hidden" name="images[<?php echo $photo_id; ?>][is_nutritional]" value="<?php echo $is_nutritional; ?>"/>
            <img class="gc_thumbnail" src="<?php echo base_url('uploads/images/thumbnails/' . $filename); ?>" style="padding:5px; border:1px solid #ddd"/>
        </div>
        <div class="span6">
            <div class="row">
                <div class="span2">
                    <input name="images[<?php echo $photo_id; ?>][alt]" value="<?php echo $alt; ?>" class="span2" placeholder="<?php echo lang('alt_tag'); ?>"/>
                </div>
                <div class="span2">
                    <input type="radio" name="primary_image" value="<?php echo $photo_id; ?>" <?php if ($primary) echo 'checked="checked"'; ?>/> <?php echo lang('primary'); ?>
                </div>
                <div class="span2">
                    <a onclick="return remove_image($(this));" rel="<?php echo $photo_id; ?>" class="btn btn-danger" style="float:right; font-size:9px;"><i class="icon-trash icon-white"></i> <?php echo lang('remove'); ?></a>
                </div>
            </div>
            <div class="row">
                <div class="span6">
                    <label><?php echo lang('caption'); ?></label>
                    <textarea name="images[<?php echo $photo_id; ?>][caption]" class="span6" rows="3"><?php echo $caption; ?></textarea>
                </div>
            </div>
        </div>
    </div>

    <?php
    $stuff = ob_get_contents();

    ob_end_clean();

    echo replace_newline($stuff);
}

function add_option($po, $count) {
    ob_start();
    ?>
    <tr id="option-<?php echo $count; ?>">
        <td>
            <a class="handle btn btn-mini"><i class="icon-align-justify"></i></a>
            <strong><a class="option_title" href="#option-form-<?php echo $count; ?>"><?php echo $po->type; ?> <?php echo (!empty($po->name)) ? ' : ' . $po->name : ''; ?></a></strong>
            <button type="button" class="btn btn-mini btn-danger pull-right" onclick="remove_option(<?php echo $count ?>);"><i class="icon-trash icon-white"></i></button>
            <input type="hidden" name="option[<?php echo $count; ?>][type]" value="<?php echo $po->type; ?>" />
            <div class="option-form" id="option-form-<?php echo $count; ?>">
                <div class="row-fluid">

                    <div class="span10">
                        <input type="text" class="span10" placeholder="<?php echo lang('option_name'); ?>" name="option[<?php echo $count; ?>][name]" value="<?php echo $po->name; ?>"/>
                    </div>

                    <div class="span2" style="text-align:right;">
                        <input class="checkbox" type="checkbox" name="option[<?php echo $count; ?>][required]" value="1" <?php echo ($po->required) ? 'checked="checked"' : ''; ?>/> <?php echo lang('required'); ?>
                    </div>
                </div>
                <?php if ($po->type != 'textarea' && $po->type != 'textfield'): ?>
                    <div class="row-fluid">
                        <div class="span12">
                            <a class="btn" onclick="add_option_value(<?php echo $count; ?>);"><?php echo lang('add_item'); ?></a>
                        </div>
                    </div>
                <?php endif; ?>
                <div style="margin-top:10px;">

                    <div class="row-fluid">
                        <?php if ($po->type != 'textarea' && $po->type != 'textfield'): ?>
                            <div class="span1">&nbsp;</div>
                        <?php endif; ?>
                        <div class="span3"><strong>&nbsp;&nbsp;<?php echo lang('name'); ?></strong></div>
                        <div class="span2"><strong>&nbsp;<?php echo lang('value'); ?></strong></div>
                        <div class="span2"><strong>&nbsp;<?php echo lang('weight'); ?></strong></div>
                        <div class="span2"><strong>&nbsp;<?php echo lang('price'); ?></strong></div>
                        <div class="span2"><strong>&nbsp;<?php echo ($po->type == 'textfield') ? lang('limit') : ''; ?></strong></div>
                    </div>
                    <div class="option-items" id="option-items-<?php echo $count; ?>">
                        <?php if ($po->values): ?>
                            <?php
                            foreach ($po->values as $value) {
                                $value = (object) $value;
                                add_option_value($po, $count, $GLOBALS['option_value_count'], $value);
                                $GLOBALS['option_value_count'] ++;
                            }
                            ?>
                        <?php endif; ?>
                    </div>
                </div>
            </div>
        </td>
    </tr>

    <?php
    $stuff = ob_get_contents();

    ob_end_clean();

    echo replace_newline($stuff);
}

function add_option_value($po, $count, $valcount, $value) {
    ob_start();
    ?>
    <div class="option-values-form">
        <div class="row-fluid">
            <?php if ($po->type != 'textarea' && $po->type != 'textfield'): ?><div class="span1"><a class="handle btn btn-mini" style="float:left;"><i class="icon-align-justify"></i></a></div><?php endif; ?>
            <div class="span3"><input type="text" class="span12" name="option[<?php echo $count; ?>][values][<?php echo $valcount ?>][name]" value="<?php echo $value->name ?>" /></div>
            <div class="span2"><input type="text" class="span12" name="option[<?php echo $count; ?>][values][<?php echo $valcount ?>][value]" value="<?php echo $value->value ?>" /></div>
            <div class="span2"><input type="text" class="span12" name="option[<?php echo $count; ?>][values][<?php echo $valcount ?>][weight]" value="<?php echo $value->weight ?>" /></div>
            <div class="span2"><input type="text" class="span12" name="option[<?php echo $count; ?>][values][<?php echo $valcount ?>][price]" value="<?php echo $value->price ?>" /></div>
            <div class="span2">
                <?php if ($po->type == 'textfield'): ?><input class="span12" type="text" name="option[<?php echo $count; ?>][values][<?php echo $valcount ?>][limit]" value="<?php echo $value->limit ?>" />
                <?php elseif ($po->type != 'textarea' && $po->type != 'textfield'): ?>
                    <a class="delete-option-value btn btn-danger btn-mini pull-right"><i class="icon-trash icon-white"></i></a>
                    <?php endif; ?>
            </div>
        </div>
    </div>
    <?php
    $stuff = ob_get_contents();

    ob_end_clean();

    echo replace_newline($stuff);
}

//this makes it easy to use the same code for initial generation of the form as well as javascript additions
function replace_newline($string) {
    return trim((string) str_replace(array("\r", "\r\n", "\n", "\t"), ' ', $string));
}
?>
<script type="text/javascript">
    //<![CDATA[
    var option_count = <?php echo $counter ?>;
    var option_value_count = <?php echo $GLOBALS['option_value_count']; ?>

    function add_related_product()
    {
        //if the related product is not already a related product, add it
        if ($('#related_product_' + $('#product_list').val()).length == 0 && $('#product_list').val() != null)
        {
<?php $new_item = str_replace(array("\n", "\t", "\r"), '', related_items("'+$('#product_list').val()+'", "'+$('#product_item_'+$('#product_list').val()).html()+'")); ?>
            var related_product = '<?php echo $new_item; ?>';
            $('#product_items_container').append(related_product);
            run_product_query();
        }
        else
        {
            if ($('#product_list').val() == null)
            {
                alert('<?php echo lang('alert_select_product'); ?>');
            }
            else
            {
                alert('<?php echo lang('alert_product_related'); ?>');
            }
        }
    }

    function remove_related_product(id)
    {
        if (confirm('<?php echo lang('confirm_remove_related'); ?>'))
        {
            $('#related_product_' + id).remove();
            run_product_query();
        }
    }

    function photos_sortable()
    {
        $('#gc_photos').sortable({
            handle: '.gc_thumbnail',
            items: '.gc_photo',
            axis: 'y',
            scroll: true
        });
    }
    //]]>
</script>
<script type="text/javascript">
    var special_row = <?php echo ++$key; ?>;
    function addCreateMoreAddons() {
        $('#createbuttondiv').append('<div class="form-group madSubAddonNew2 form-horizontal addonmarginbottom" >' +
                '<span class="col-sm-offset-3 col-sm-9" id="mainremove_' + special_row + '" name="mainremove_' + special_row + '">' +
                '<span id="mainaddremove_' + special_row + '" class="">' +
                '<input type="text" name="createmainaddons[' + special_row + '][mainaddonsname]" id="mainaddons_' + special_row + '" class="form-control input-sm textbox inline textaddonbottom" value="" placeholder="<?php echo lang('name'); ?>" /> ' +
                '<input type="number" name="createmainaddons[' + special_row + '][mainaddoncnt]" id="mainaddoncnt" value="" placeholder="<?php echo lang('max_options_selectable'); ?>" class="form-control input-sm textbox inline textaddonbottom"/>' +
                '<label class="checkbox inline margin-l"><input type="checkbox" class="form-control input-sm textaddonbottom" value="required" name="createmainaddons[' + special_row + '][required]"><?php echo lang('addon_required'); ?></label>' +
                '<span class="col-sm-2" style="margin-left: 15px;"><a onclick="removemainaddon(' + special_row + ');"  class="btn btnmargin"><i class="glyphicon glyphicon-remove">X</i></a></span>' +
                '<span id="sublist_' + special_row + '" ><a onclick="createAddSubaddonsList(' + special_row + ');" class="btn btnmargin"><i class="glyphicon glyphicon-plus-sign marRight"></i><?php echo lang('add_options'); ?></a></span>' +
                '<div id="createsubbuttondiv_' + special_row + '" class="addtoCartInner"></div></span>' +
                ' </span>' +
                ' </span>' +
                '</div>');
        special_row++;
    }

    function removemainaddon(aid) {
        $("#mainremove_" + aid).parent("div").remove();
        return false;
    }

    var special_row1 = <?php echo ++$sub_key; ?>;
    function createAddSubaddonsList(mainaddid) {

        var pizza_size_ht = '';

        $('#createsubbuttondiv_' + mainaddid).append('<div id="removesubmore_' + special_row1 + '" class="madSubAddonNew1 form-horizontal addonmargin">' +
                '<span class="radio inline"><input type="text" name="createmainaddons[' + mainaddid + '][subaddons][' + special_row1 + '][subaddonsname]" id="mainaddonsmore_' + special_row1 + '" class="form-control textbox inline textaddonbottom" value="" placeholder="Name"/></span>' +
                '<label for="1" class="radio inline"><input id="1" class="inputPrice" type="radio" name="createmainaddons[' + mainaddid + '][subaddons][' + special_row1 + '][subaddonsradio]" value="Free" checked="checked" onclick="createaddonsfreeoption(' + special_row1 + ');" /><?php echo lang('free'); ?></label>' +
                '<label for="2" class="radio inline"><input id="2" class="inputPrice" type="radio" name="createmainaddons[' + mainaddid + '][subaddons][' + special_row1 + '][subaddonsradio]" value="Paid" onclick="createaddonspaidoption(' + special_row1 + ');" /><?php echo lang('paid'); ?></label>' +
                '<span id="showcreateaddonsprice1_' + special_row1 + '" style="display:none;" class="col-sm-4 pad0"><input class="form-control textbox inline" type="number" step="0.01" name="createmainaddons[' + mainaddid + '][subaddons][' + special_row1 + '][subaddonsprice]" value="" placeholder="Price" style="margin-left: 15px;"/>' +
                '</span>' +
                '<a onclick="removeSubmore(' + special_row1 + ');" class="btn btnmargin"><i class="glyphicon glyphicon-remove">X</i></a>' +
                '</div>');
        special_row1++;
    }

    function createaddonsfreeoption(cid) {

        $("#showcreateaddonsprice1_" + cid).hide();
    }
    function createaddonspaidoption(cid) {

        $("#showcreateaddonsprice1_" + cid).show();
    }

    function removeSubmore(said) {
        /*$('#removesubmore_'+said).html('');*/
        $('#removesubmore_' + said).remove();
    }
</script>
<?php

function related_items($id, $name) {
    return '
			<tr id="related_product_' . $id . '">
				<td>
					<input type="hidden" name="related_products[]" value="' . $id . '"/>
					' . $name . '</td>
				<td>
					<a class="btn btn-danger pull-right btn-mini" href="#" onclick="remove_related_product(' . $id . '); return false;"><i class="icon-trash icon-white"></i> ' . lang('remove') . '</a>
				</td>
			</tr>
		';
}
