<!DOCTYPE html>
<html xmlns="http://www.w3.org/1999/xhtml">
    <head>
        <meta http-equiv="Content-Type" content="text/html; charset=UTF-8" />
        <meta name="viewport" content="width=device-width, initial-scale=1.0">

            <title><?php
                echo (!empty($seo_title)) ? $seo_title . ' - ' : '';
                echo $this->config->item('company_name');
                ?></title>


            <?php if (isset($meta)): ?>
                <?php echo $meta; ?>
            <?php else: ?>
                <meta name="Keywords" content="Shopping Cart, eCommerce, Code Igniter">
                    <meta name="Description" content="Go Cart is an open source shopping cart built on the Code Igniter framework">
                    <?php endif; ?>

                    <?php echo theme_css('bootstrap.min.css', true); ?>
                    <?php echo theme_css('bootstrap-responsive.min.css', true); ?>
                    <?php echo theme_css('styles.css', true); ?>
                    <?php echo theme_css('skin/grid.css', true); ?>
                    <?php echo theme_css('skin/style.css', true); ?>
                    <?php echo theme_css('skin/animate.css', true); ?>
                    <?php echo theme_css('skin/owl.carousel.css', true); ?>
                    <?php echo theme_css('skin/subscribe.css', true); ?>
                    <?php echo theme_css('skin/contact-form.css', true); ?>

                    <?php echo theme_js('jquery.js', true); ?>
                    <?php echo theme_js('bootstrap.min.js', true); ?>
                    <?php echo theme_js('squard.js', true); ?>
                    <?php echo theme_js('equal_heights.js', true); ?>

                    <?php echo theme_js('jquery-migrate-1.2.1.js', true); ?>
                    <?php echo theme_js('script.js', true); ?>
                    <?php echo theme_js('TMForm.js', true); ?>
                    <?php echo theme_js('owl.carousel.js', true); ?>
                    <?php echo theme_js('wow.js', true); ?>


                    <?php
//with this I can put header data in the header instead of in the body.
                    if (isset($additional_header_info)) {
                        echo $additional_header_info;
                    }
                    ?>
                    <script>
                        $(document).ready(function() {
                            if ($('html').hasClass('desktop')) {
                                new WOW().init();
                            }
                        });
                    </script>

                    </head>

                    <body>
                        <header id="header">
                            <div id="stuck_container">
                                <div class="full-width-container">
                                    <div class="container">
                                        <div class="row">
                                            <div class="grid_12">
                                                <h1><a href="index.html">FoodGrocery<i>Pharma</i></a></h1>
                                                <nav>
                                                    <div class="main_menu">
                                                        <ul class="sf-menu">
                                                            <li class="current"><a href="#">Home</a>
                                                                <ul>
                                                                    <li><a href="#">About</a></li>
                                                                    <li><a href="#">News</a>
                                                                        <ul>
                                                                            <li><a href="#">Lastest News</a></li>
                                                                            <li><a href="#">Featured News</a></li>
                                                                            <li><a href="#">Archive</a></li>
                                                                        </ul>
                                                                    </li>
                                                                    <li><a href="#">Services</a></li>
                                                                    <li><a href="#">Blog</a></li>
                                                                    <li><a href="#">Contacts</a></li>
                                                                </ul>
                                                            </li>
                                                            <li><a href="#">About</a></li>
                                                            <li><a href="#">News</a></li>
                                                            <li><a href="#">Services</a></li>
                                                            <li><a href="#">Blog</a></li>
                                                            <li><a href="#">Contacts</a></li>
                                                        </ul>
                                                    </div>
                                                </nav>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            
                            
                        </header>

                        <div class="container">
                            <?php if (!empty($base_url) && is_array($base_url)): ?>
                                <div class="row">
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

                            <?php if (!empty($error)): ?>
                                <div class="alert alert-error">
                                    <a class="close" data-dismiss="alert">×</a>
                                    <?php echo $error; ?>
                                </div>
                            <?php endif; ?>


<?php
/*
End header.php file
*/