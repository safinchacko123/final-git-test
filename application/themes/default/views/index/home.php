<link rel="stylesheet" href="<?php echo base_url('application/themes/default/assets/css/app.css?ver=34'); ?>">
<div data-ui-view="" class="ng-scope"><style class="ng-scope">
        .about_navbar {
            background-color: transparent;
            border-bottom: 1px solid rgba(255, 255, 255, 0.19);
        }
    </style>

    <div class="about_imgContainer ng-scope">
        <h1 class="aboutUs_title">We Make Shopping Easy</h1>
    </div>
    <div class="about_companyDetailWrapper about_tilt ng-scope" id="about_wrapper">
	<?php if(isset($_SESSION['userdata']['cart_contents']['customer']) && count($_SESSION['userdata']['cart_contents']['customer']) <= 1) { ?>
        <div style="text-align: center">
            <a href="<?php echo base_url('secure/login');?>" class="btn btn-success" >Start shopping now</a> 
            <a href="<?php echo base_url('secure/register?as=lnkVendor');?>" class="btn btn-info" >Register as vendor</a> 
            <a href="<?php echo base_url('secure/register?as=lnkPartner');?>" class="btn btn-info" >Become a partner</a> 
        </div>
		<?php } ?>
        <div class="container">
            <div class="row about_companyDetailRow">
                <div class="col-sm-offset-2 col-sm-8">
                    <h2 class="text-center about_storyHd">Our Story</h2>

                    <p class="about_companyDetailPara text-center">
                        Market Place is the way advanced way to buy groceries. As a 21st century we are making shopping
                        online.
                        Focussed on accurate and on time deliveries, We simplify product display w.r.t. your location,
                        Which makes shopping easy and fast. So anyone can quickly shop what they want using Market Place.
                    </p>
                </div>
            </div>
            <div class="row about_objectiveRow">
                <div class="col-xs-4">
                    <div class="about_objectiveWrapper">
                        <!--<img src="images/delight-heart.png" class="center-block img-responsive about_objectiveImg" alt="Delight heart">-->

                        <h3 class="text-center about_objectiveHd">Delight as a metric</h3>

                        <p class="text-center about_objectiveContent">
                            Delighting our customers is not another objective, it is the objective, so much so we track
                            it as our main business metric.
                        </p>
                    </div>

                </div>
                <div class="col-xs-4">
                    <div class="about_objectiveWrapper">
                        <!--<img src="images/instant.png" class="center-block img-responsive about_objectiveImg" alt="">-->

                        <h3 class="text-center about_objectiveHd">Instant Gratification</h3>

                        <p class="text-center about_objectiveContent">
                            Instant delivery is not a promise, its at the very core of our philosophy. Our executives
                            take extreme care to ensure what you ordered gets to you in under 2 hours.
                        </p>
                    </div>

                </div>
                <div class="col-xs-4">
                    <div class="about_objectiveWrapper">
                        <!--<img src="images/smile.png" class="center-block img-responsive about_objectiveImg">-->

                        <h3 class="text-center about_objectiveHd">Happy People</h3>

                        <p class="text-center about_objectiveContent">
                            Happy and committed people do great things. We strive to keep our people happy
                            and aligned with our goal of delighting our customers and are always looking for people
                            to join our team.
                        </p>
                    </div>

                </div>
                <br/>
                <br/>
            </div>
        </div>
    </div>

    <script class="ng-scope">
        $(document).ready(function() {

            var scroll_start = 0;
            var startchange = $('#about_wrapper');
            var offset = startchange.offset();

            if (startchange.length) {
                $(document).scroll(function() {
                    scroll_start = $(this).scrollTop();
                    if (scroll_start > offset.top) {
                        $(".about_navbar").css({
                            'background-color': '#363636',
                            'transition': '0.2s',
                            'border-bottom': 'none'
                        });
                    } else {
                        $('.about_navbar').css({
                            'background-color': 'transparent',
                            'border-bottom': '1px solid rgba(255, 255, 255, 0.19)'
                        });
                    }
                });
            }

        });
        $(function() {
            var $elems = $('.animate_block');
            var winheight = $(window).height();
            var fullheight = $(document).height();

            $(window).scroll(function() {
                animate_elems();
            });

            function animate_elems() {
                wintop = $(window).scrollTop(); // calculate distance from top of window

                // loop through each item to check when it animates
                $elems.each(function() {
                    $elm = $(this);

                    if ($elm.hasClass('animated')) {
                        return true;
                    } // if already animated skip to the next item

                    topcoords = $elm.offset().top; // element's distance from top of page in pixels

                    if (wintop > (topcoords - (winheight * .75))) {
                        // animate when top of the window is 3/4 above the element
                        $elm.addClass('animated');
                    }
                });
            } // end animate_elems()
        });
    </script>
</div>