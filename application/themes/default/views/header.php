<?php
$this->customer = $this->mp_cart->customer();
/*
  if ($this->customer['role_id']) {
  ?>
  <div class="alert alert-error">
  <a class="close" data-dismiss="alert">×</a>
  Loged in as <?php echo $this->customer['role']['role_name'];?>
  </div>
  <?php } */
?>

<?php
$CI = & get_instance();
$customer = $CI->session->userdata('cart_contents');
$custData = $customer['customer'];
?>
<!DOCTYPE html>
<html xmlns="http://www.w3.org/1999/xhtml">
    <head>
        <meta http-equiv="Content-Type" content="text/html; charset=UTF-8" />
        <meta name="viewport" content="width=device-width, initial-scale=1.0">

            <title>
                <?php
                echo (!empty($seo_title)) ? $seo_title . ' - ' : '';
                echo $this->config->item('company_name');
                ?>
            </title>

            <input type="hidden" id="site_url" value="<?php echo rtrim(base_url(), "/"); ?>"/>
            <?php if (isset($meta)): ?>
                <?php echo $meta; ?>
            <?php else: ?>
                <meta name="Keywords" content="Shopping Cart, eCommerce, Market Place">
                    <meta name="Description" content="Market Place your shopping store">
                    <?php endif; ?>

                    <?php // echo theme_css('bootstrap.min.css', true); ?>
                    <?php // echo theme_css('bootstrap-responsive.min.css', true); ?>

                    <?php echo theme_css('customboot/bootstrap3.3.5.css', true); ?>
                    <?php echo theme_css('customboot/bootstrap.theme.css', true); ?>
                    <?php //echo theme_css('bootstrap-3.3.5/css/bootstrap.min.css', true); ?>
                    <?php //echo theme_css('bootstrap-3.3.5/css/bootstrap-theme.min.css', true); ?>
                    <?php // echo theme_css('bootstrap-responsive.min.css', true); ?>
                    <link type="text/css" href="<?php echo base_url('assets/css/jquery-ui.css'); ?>" rel="stylesheet" />
                    <link type="text/css" href="<?php echo base_url('assets/css/logged-in.css'); ?>" rel="stylesheet" />
                    <?php echo theme_css('styles.css', true); ?>

                    <?php echo theme_js('jquery.js', true); ?>
                    <?php //echo theme_js('bootstrap-3.3.5/js/bootstrap.min.js', true); ?>
                    <?php echo theme_js('bootstrap.min.js', true); ?>
                    <script type="text/javascript" src="<?php echo base_url('assets/js/jquery-ui.js'); ?>"></script>  
                    <?php echo theme_js('plugin/jquery.toaster.js', true); ?>
                    <?php echo theme_js('common.js', true); ?>
                    <script type="text/javascript" src="<?php echo base_url('assets/tinymce/tinymce.min.js'); ?>"></script>
                    <script type="text/javascript" src="<?php echo base_url('assets/js/logged-in.js'); ?>"></script>

                    <?php if (isset($venture_css_js) && $venture_css_js == 1) { ?>
                        <?php echo theme_css('bootstrap-multiselect.css', true); ?>
                        <?php echo theme_js('bootstrap-multiselect.js', true); ?>
                        <?php echo theme_css('jquery.timepicker.css', true); ?>
                        <?php echo theme_js('jquery.timepicker.js', true); ?>
                    <?php } ?>

                    <?php
//with this I can put header data in the header instead of in the body.
                    if (isset($additional_header_info)) {
                        echo $additional_header_info;
                    }
                    ?>
                    </head>

                    <body>
                        <div class="navbar navbar-fixed-top">
                            <?php
                            $extraClass = '';
                            if ($custData['access'] == 'Vendor') {
                                $extraClass = "navbar-inner-vendor";
                            } else if ($custData['access'] == 'Venture') {
                                $extraClass = "navbar-inner-venture";
                            }
                            ?>
                            <div class="navbar-inner <?= $extraClass; ?>">
                                <div class="container">

                                    <!-- .btn-navbar is used as the toggle for collapsed navbar content -->
                                    <a class="btn btn-navbar" data-toggle="collapse" data-target=".nav-collapse">
                                        <span class="icon-bar"></span>
                                        <span class="icon-bar"></span>
                                        <span class="icon-bar"></span>
                                    </a>

                                    <a class="brand clsTooltip" href="<?php echo site_url(); ?>"><?php echo $this->config->item('company_name'); ?></a>

                                    <div class="nav-collapse">
                                        <ul class="nav">
                                            <?php
                                            if (isset($this->pages[0])) {
                                                foreach ($this->pages[0] as $menu_page):
                                                    ?>
                                                    <!--                                                    <li>
                                                    <?php if (empty($menu_page->content)): ?>
                                                                                                                                                                                                                    <a href="<?php echo $menu_page->url; ?>" <?php
                                                        if ($menu_page->new_window == 1) {
                                                            echo 'target="_blank"';
                                                        }
                                                        ?>><?php echo $menu_page->menu_title; ?></a>
                                                    <?php else: ?>
                                                                                                                                                                                                                    <a href="<?php echo site_url($menu_page->slug); ?>"><?php echo $menu_page->menu_title; ?></a>
                                                    <?php endif; ?>
                                                                                                        </li>-->

                                                    <?php
                                                endforeach;
                                            }
                                            ?>

                                            <li>
                                                <?php
                                                if ($this->customer) {
                                                    ?>
                                                    <a href="<?php echo site_url('secure/my_account'); ?>">Home</a>										
                                                    <?php
                                                }
                                                ?>
                                            </li>

                                            <?php if ($this->customer['role_id'] == 2) { ?>
                                                <li><a href="<?php echo base_url('index.php/secure/favorites'); ?>">My Favorites</a></li>
                                            <?php } ?>


                                            <?php
                                            if ($this->customer) {
                                                if ($this->customer['role_id'] == 0) {
                                                    $roleName = 'CustomerLinks';
                                                } else {
                                                    $roleName = $this->customer['role']['role_name'] . 'Links';
                                                }
                                                foreach ($this->config->item($roleName) as $link) {
                                                    if (in_array('navbar', $link['location'])) {
                                                        ?>
                                                        <li>
                                                            <!--rel="tooltip" data-content="hello guys" data-html="true" data-original-title="Timeline" style="clear: both"-->
                                                            <a href="<?php echo site_url($link['lnk']); ?>" class="<?php echo $link['class'] ?>"><?php echo $link['menu_name']; ?></a>
                                                        </li>
                                                        <?php
                                                    }
                                                }
                                            }
                                            ?>

                                            <?php if ($this->customer && $this->customer['role_id'] == 0) { ?>
                                                <li class="dropdown">
                                                    <a href="#" class="dropdown-toggle" data-toggle="dropdown">Categories <b class="caret"></b></a>
                                                    <ul class="dropdown-menu">
                                                        <?php
                                                        if (isset($this->categories[0])):
                                                            ?>
                                                            <?php foreach ($this->categories[0] as $cat_menu): ?>
                                                                <li <?php echo $cat_menu->active ? 'class="active"' : false; ?>>
                                                                    <a href="<?php echo site_url($cat_menu->slug); ?>"><?php echo $cat_menu->name; ?></a>
                                                                </li>
                                                                <li class="divider"></li>
                                                                <?php
                                                            endforeach;
                                                        endif;
                                                        ?>
                                                    </ul>
                                                </li>
                                            <?php } ?>

                                            <?php
                                            if (isset($this->categories[0])):
                                            /*
                                              ?>
                                              <li class="dropdown"><a href="#" class="dropdown-toggle" data-toggle="dropdown"><?php echo lang('catalog'); ?> <b class="caret"></b></a>
                                              <ul class="dropdown-menu">
                                              <?php foreach ($this->categories[0] as $cat_menu): ?>
                                              <li <?php echo $cat_menu->active ? 'class="active"' : false; ?>><a href="<?php echo site_url($cat_menu->slug); ?>"><?php echo $cat_menu->name; ?></a></li>
                                              <?php endforeach; ?>
                                              </ul>
                                              </li>
                                              <?php
                                             */
                                            endif;
                                            ?>

                                        </ul>

                                        <ul class="nav pull-right">

                                            <?php if ($this->Customer_model->is_logged_in(false, false)): ?>
                                                <li class="dropdown">
                                                    <a href="#" class="dropdown-toggle" data-toggle="dropdown">Account <b class="caret"></b></a>
                                                    <ul class="dropdown-menu">
                                                        <li><a href="<?php echo site_url('secure/my_account'); ?>">My Account</a></li>
                                                        <li class="divider"></li>
                                                        <li><a href="<?php echo site_url('secure/logout'); ?>">Logout</a></li>
                                                    </ul>
                                                </li>
                                            <?php else: ?>
                                                <li>
                                                    <a href="<?php echo site_url('secure/login'); ?>" class="dropdown-toggle" data-toggle="dropdown"><?php echo lang('login'); ?></a>
                                                </li>
                                            <?php
                                            endif;
                                            if ($this->customer['role_id'] == 0) {
                                                ?>
                                                <li>
                                                    <a href="<?php echo site_url('cart/view_cart'); ?>">
                                                        <?php
                                                        if ($this->mp_cart->total_items() == 0) {
                                                            echo lang('empty_cart');
                                                        } else {
                                                            if ($this->mp_cart->total_items() > 1) {
                                                                echo sprintf(lang('multiple_items'), $this->mp_cart->total_items());
                                                            } else {
                                                                echo sprintf(lang('single_item'), $this->mp_cart->total_items());
                                                            }
                                                        }
                                                        ?>
                                                    </a>
                                                </li>
                                            <?php } ?>
                                        </ul>

                                        <?php //echo form_open('cart/search', 'class="navbar-search pull-right"');     ?>
                                        <!--<input type="text" name="term" class="search-query span2" placeholder="<?php echo lang('search'); ?>"/>-->
                                        </form>
                                    </div>

                                </div>
                            </div>
                        </div>
                        <div class="container" style="padding-top: 10px;">
                            <?php if (!empty($base_url) && is_array($base_url)): ?>
                                <div class="row" style="display: none;">
                                    <div class="span12">
                                        <ul class="breadcrumb">
                                            <?php
                                            $url_path = '';
                                            $count = 1;
                                            foreach ($base_url as $bc):
                                                $url_path .= '/' . $bc;
                                                if ($count == count($base_url)):
                                                    ?>
                                                    <li class="active"><?php echo $bc; ?></li>
                                                <?php else: ?>
                                                    <li><a href="<?php echo site_url($url_path); ?>"><?php echo $bc; ?></a></li> <span class="divider">/</span>
                                                <?php
                                                endif;
                                                $count++;
                                            endforeach;
                                            ?>
                                        </ul>
                                    </div>
                                </div>
                            <?php endif; ?>

                            <?php if ($this->session->flashdata('message')): ?>
                                <div class="alert alert-info">
                                    <a class="close" data-dismiss="alert">×</a>
                                    <?php echo $this->session->flashdata('message'); ?>
                                </div>
                            <?php endif; ?>

                            <?php if ($this->session->flashdata('error')): ?>
                                <div class="alert alert-error">
                                    <a class="close" data-dismiss="alert">×</a>
                                    <?php echo $this->session->flashdata('error'); ?>
                                </div>
                            <?php endif; ?>

                            <?php 
                            $error = validation_errors();
                            if (!empty($error)): ?>
                                <div class="alert alert-error">
                                    <a class="close" data-dismiss="alert">×</a>
                                    <?php echo $error; ?>
                                </div>
                                <?php
                            endif;

                            if (isset($customer['customer']['bill_address'])) {
                                $billAddress = $customer['customer']['bill_address'];
                                echo "<div class='clsBillAdd hide' >";
                                if ($billAddress) {
                                    echo $billAddress['address1'] . " " . $billAddress['city'] . " " . $billAddress['zip'];
                                }
                                echo "</div>";
                            }
                                /*
                                  End header.php file
                                 */