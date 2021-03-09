@extends('layouts.main')

@section('title', 'COLORIT')

@section('content')

    <div id="canvas" class="">
        <div id="box_wrapper" class="">
            <!-- template sections -->

            <div class="transparent_wrapper wrapper_v1">
                <section class="page_toplogo toplogo1 cs toggler_right columns_margin_0">
                    <div class="container-fluid">
                        <div class="row">
                            <div class="col-xs-12">
                                <a href="/" rel="home" class="logo logo_image_only">
                                    <img src="img/colorit.png" alt="">
                                </a>
                                <!-- menu toggler -->
                                <span class="toggle_menu"><span></span></span>

                                <!-- header toggler -->
                                <span class="toggle_header visible-lg"><span></span></span>
                            </div>
                        </div>
                    </div>
                </section>

                <div class="header_v1_wrapper">
                    <header class="page_header header_white header_v1 toggler_xxs_right">
                        <div class="container-fluid">
                            <div class="row">
                                <div class="col-sm-12 display-flex v-center">
                                    <div class="header_left_logo">
                                        <a href="/" rel="home" class="logo logo_image_only">
                                            <img src="/img/2colorit.png" alt="">
                                        </a>
                                    </div>

                                    <div class="header_mainmenu mainmenu_wrapper text-center">
                                        <nav class="mainmenu_wrapper primary-navigation">
                                            <ul id="menu-main-menu" class="sf-menu nav-menu nav">
                                                <li id="menu-item-18"
                                                    class="menu-item menu-item-type-post_type menu-item-object-page menu-item-home current-menu-item page_item page-item-9 current_page_item menu-item-has-children menu-item-18">
                                                    <a href="#"><span>Home</span></a></li>
                                                <li id="menu-item-1300"
                                                    class="menu-item menu-item-type-post_type menu-item-object-page menu-item-1300">
                                                    <a href="#about"><span>About</span></a></li>
                                                <li id="menu-item-90"
                                                    class="menu-item menu-item-type-post_type menu-item-object-page menu-item-90">
                                                    <a href="#our-services"><span>Our Services</span></a></li>
                                                <li id="menu-item-1187"
                                                    class="menu-item menu-item-type-post_type menu-item-object-page menu-item-1187">
                                                    <a href="#designs"><span>Designs</span></a></li>
                                                <li id="menu-item-1309"
                                                    class="menu-item menu-item-type-post_type menu-item-object-page menu-item-1309">
                                                    <a href="#faq"><span>FAQ</span></a></li>
                                                <li id="menu-item-1331"
                                                    class="menu-item menu-item-type-post_type menu-item-object-page menu-item-1331">
                                                    <a href="#contact"><span>Contact</span></a></li>
                                            </ul>
                                        </nav>
                                    </div>

                                    <div class="header_right_buttons text-right">
                                        <!-- header toggler -->
                                        <span class="toggle_header_close"><span></span></span>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </header>

                    <section
                            class="page_contacts cs main_color7 section_padding_top_110 section_padding_bottom_115 columns_margin_bottom_40 visible-lg">
                        <div class="container">
                            <div class="row">
                                <div class="col-xs-12 col-sm-4">
                                    <div class="teaser text-center">

                                        <div class="teaser_icon size_huge highlight3">

                                            <img width="120" height="121" src="/img/clr-phone-call-3.png" class=""
                                                 alt=""
                                                 srcset="/img/clr-phone-call-3.png 120w, /img/clr-phone-call-3-100x100.png 100w"
                                                 sizes="(max-width: 120px) 100vw, 120px"/>
                                        </div>

                                        <p><span class="small-text highlight3">call us 24/7</span><br/><span
                                                    class="big black">0-800-234-5678</span></p>
                                    </div>
                                </div>
                                <div class="col-xs-12 col-sm-4">
                                    <div class="teaser text-center">

                                        <div class="teaser_icon size_huge highlight8">

                                            <img width="120" height="85" src="/img/clr-mail-5.png" class="" alt=""/>
                                        </div>

                                        <p><span class="small-text highlight8">write us</span><br/><span
                                                    class="big black darklinks"><a href="mailto:#">cway@support.com</a></span>
                                        </p>
                                    </div>
                                </div>
                                <div class="col-xs-12 col-sm-4">
                                    <div class="teaser text-center">

                                        <div class="teaser_icon size_huge highlight9">

                                            <img width="120" height="120" src="/img/clr-clock-5.png" class="" alt=""
                                                 srcset="/img/clr-clock-5.png 120w, /img/clr-clock-5-100x100.png 100w"
                                                 sizes="(max-width: 120px) 100vw, 120px"/>
                                        </div>

                                        <p><span class="small-text highlight9">7 days a week</span><br/><span
                                                    class="big black">9:00am - 8:00PM</span></p>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </section>

                    <section class="page_social cs section_padding_top_110 section_padding_bottom_110 visible-lg">
                        <div class="container">
                            <div class="row">
                                <div class="col-xs-12 text-center">
                                    <span class="social-icons ">
                                        <a href="#" class="social-icon socicon-facebook color-bg-icon rounded-icon" target="_blank">
                                        </a>
                                        <a href="#"
                                           class="social-icon socicon-twitter color-bg-icon rounded-icon"
                                           target="_blank">
                                        </a>
                                        <a href="#"
                                           class="social-icon socicon-googleplus color-bg-icon rounded-icon"
                                           target="_blank">
                                        </a>
                                    </span>
                                </div>
                            </div>
                        </div>
                    </section>
                </div>
            </div>
            <section class="intro_section hello page_mainslider cs all-scr-cover image-dependant ">
                <div class="flexslider " data-nav="true" data-dots="false">
                    <ul class="slides">
                        <li class="text-center">

                            <div class="slide-image-wrap ">
                                <img width="1920" height="1000" src="img/slide01.jpg" class="attachment-full size-full"
                                     alt=""
                                     srcset="img/slide01.jpg 1920w, img/slide01-300x156.jpg 300w, http:img/slide01-768x400.jpg 768w, img/slide01-1024x533.jpg 1024w, img/slide01-600x313.jpg 600w"
                                     sizes="(max-width: 1920px) 100vw, 1920px"/>
                            </div>

                            <div class="container">
                                <div class="row">
                                    <div class="col-sm-12">
                                        <div class="slide_description_wrapper">
                                            <div class="slide_description">

                                                <div class="intro-layer to_animate" data-animation="fadeInUp">
                                                    <h1 class="black  ">
                                                        Graphic Design <br>&amp; Printing Services </h1>
                                                </div>
                                                <div class="intro-layer to_animate" data-animation="fadeInUp">
                                                    <div class="slide_buttons inline-content v-spacing">

                                                        <a href="index.php/file-upload" target="_self"
                                                           class="theme_button bg_button color2 min_width_button left ">
                                                            <i class="fa fa-upload rightpadding_10"></i>
                                                            Upload Files
                                                        </a>
                                                        <a href="#contact" target="_self"
                                                           class="theme_button bg_button color1 min_width_button left ">
                                                            <i class="fa fa-pencil rightpadding_10"></i>
                                                            Request Quote
                                                        </a>
                                                    </div>
                                                </div>
                                            </div>
                                            <!-- eof .slide_description -->
                                        </div>
                                        <!-- eof .slide_description_wrapper -->
                                    </div>
                                    <!-- eof .col-* -->
                                </div>
                                <!-- eof .row -->
                            </div>
                            <!-- eof .container -->
                        </li>
                    </ul>
                </div>
                <!-- eof flexslider -->
            </section>
            <!-- eof intro_section -->
            <div class="fw-page-builder-content">
                <section
                        class="fw-main-row  cs main_color2 half_cs section_padding_top_65 section_padding_bottom_65 columns_padding_80"
                        id="subscribe">

                    <div class="container">
                        <div class="row flex-wrap v-center">

                            <div class="col-xs-12 col-sm-6 text-sm-right text-center">


                                <div class="text-block shortcode">
                                    <div class="small-text greylinks"><a href="mailto:#">example@example.com</a></div>
                                    <p class="hero black">0-800-234-5678</p>
                                </div>
                            </div>


                            <div class="col-xs-12 col-sm-6 text-sm-left text-center">


                                <div class="text-block shortcode">
                                    <div>
                                        <form id="mc4wp-form-1" class="mc4wp-form mc4wp-form-33" method="post"
                                              data-id="33" data-name="">
                                            <div class="mc4wp-form-fields">
                                                <div class="form-group-wrap">
                                                    <div class="form-group margin_0">
                                                        <input type="email" name="EMAIL" class="form-control"
                                                               placeholder="Newsletter Subscribe" required/>
                                                        <button type="submit" class="theme_button bg_button color7">
                                                            Submit
                                                        </button>
                                                    </div>
                                                </div>
                                                </div>
                                                <label style="display: none !important;">Leave this field empty if
                                                    you're human:
                                                    <input type="text" name="_mc4wp_honeypot" value=""
                                                           tabindex="-1"
                                                           autocomplete="off"/>
                                                </label>
                                                <input type="hidden" name="_mc4wp_timestamp"
                                                        value="1609603402"/>
                                                <input type="hidden" name="_mc4wp_form_id"
                                                       value="33"/>
                                                <input type="hidden"
                                                       name="_mc4wp_form_element_id"
                                                       value="mc4wp-form-1"/>
                                                <div class="mc4wp-response"></div>
                                        </form>
                                        <!-- / MailChimp for WordPress Plugin -->
                                    </div>
                                </div>
                            </div>


                        </div>
                    </div>
                </section>
                <section class="fw-main-row  ls section_padding_top_145 section_padding_bottom_0" id="about">

                    <div class="container">
                        <div class="row flex-wrap v-center">

                            <div class="col-xs-12 col-md-7">


                                <img src="img/about.png" alt="img/about.png" class="cover-image"/></div>


                            <div class="col-xs-12 col-md-5">


                                <p class=" highlight   above_heading ">
                                    Welcome </p>


                                <h2 class="    section_header ">
                                    Colorway is the leader in different format printing </h2>

                                <div class="text-block shortcode">
                                    <p>With over 30 years of experience, we specialize in variety of high quality
                                        services.</p>
                                </div>

                                <div class="fw-divider-space  hidden-sm hidden-xs" style="padding-top: 10px;"></div>

                            </div>


                        </div>
                    </div>
                </section>
                <section class="fw-main-row  ls section_padding_top_0 section_padding_bottom_0" id="partners">

                    <div class="container">
                        <div class="row flex-wrap v-center">

                            <div class="col-xs-12 col-md-7 col-md-push-5">


                                <img src="img/partners.png" alt="img/partners.png" class="cover-image"/></div>


                            <div class="col-xs-12 col-md-5 col-md-pull-7">


                                <p class=" highlight2   above_heading ">
                                    Partners </p>


                                <h2 class="    section_header ">
                                    We have extended partnership and collaboration </h2>


                                <div class="fw-divider-space " style="padding-top: 15px;"></div>


                                <div class="isotope_container images-grid isotope row  columns_margin_bottom_20 bordered masonry-layout">
                                    <div class="isotope-item col-lg-4 col-md-4 col-sm-4 col-xs-4">
                                        <a href="#" class="partner-link">
                                            <img src="img/partner9.png"
                                                 alt="">
                                        </a>
                                    </div>
                                    <div class="isotope-item col-lg-4 col-md-4 col-sm-4 col-xs-4">
                                        <a href="#" class="partner-link">
                                            <img src="img/partner8.png"
                                                 alt="">
                                        </a>
                                    </div>
                                    <div class="isotope-item col-lg-4 col-md-4 col-sm-4 col-xs-4">
                                        <a href="#" class="partner-link">
                                            <img src="img/partner7.png"
                                                 alt="">
                                        </a>
                                    </div>
                                    <div class="isotope-item col-lg-4 col-md-4 col-sm-4 col-xs-4">
                                        <a href="#" class="partner-link">
                                            <img src="img/partner6.png"
                                                 alt="">
                                        </a>
                                    </div>
                                    <div class="isotope-item col-lg-4 col-md-4 col-sm-4 col-xs-4">
                                        <a href="#" class="partner-link">
                                            <img src="img/partner5.png"
                                                 alt="">
                                        </a>
                                    </div>
                                    <div class="isotope-item col-lg-4 col-md-4 col-sm-4 col-xs-4">
                                        <a href="#" class="partner-link">
                                            <img src="img/partner4.png"
                                                 alt="">
                                        </a>
                                    </div>
                                    <div class="isotope-item col-lg-4 col-md-4 col-sm-4 col-xs-4">
                                        <a href="#" class="partner-link">
                                            <img src="img/partner3.png"
                                                 alt="">
                                        </a>
                                    </div>
                                    <div class="isotope-item col-lg-4 col-md-4 col-sm-4 col-xs-4">
                                        <a href="#" class="partner-link">
                                            <img src="img/partner2.png"
                                                 alt="">
                                        </a>
                                    </div>
                                    <div class="isotope-item col-lg-4 col-md-4 col-sm-4 col-xs-4">
                                        <a href="#" class="partner-link">
                                            <img src="img/partner1.png"
                                                 alt="">
                                        </a>
                                    </div>
                                </div>
                                <!-- eof .isotope_container -->
                            </div>


                        </div>
                    </div>
                </section>
                <section class="fw-main-row  ls section_padding_top_75 section_padding_bottom_120" id="services">

                    <div class="container">
                        <div class="row flex-wrap v-center">

                            <div class="col-xs-12 col-md-7">


                                <img src="/img/services.jpg" alt="/img/services.jpg" class="cover-image"/></div>


                            <div class="col-xs-12 col-md-5">
                                <p class=" highlight3 above_heading">
                                    Services
                                </p>

                                <h2 class="section_header">
                                    We specialize in a big variety of services
                                </h2>

                                <div class="fw-divider-space " style="padding-top: 10px;"></div>

                                <ul class="shortcode-services list1 no-bullets greylinks underlined-links color3">
                                    <li>
                                        <div class="media teaser media-icon-teaser">
                                            <div class="media-left media-middle">
                                                <i class="clr- clr-service-copying teaser_icon size_normal highlight3"></i>
                                            </div>
                                            <div class="media-body media-middle">
                                                <a href="#copying-services">Copying Services</a>
                                            </div>
                                        </div>
                                    </li>
                                    <li>
                                        <div class="media teaser media-icon-teaser">
                                            <div class="media-left media-middle">
                                                <i class="clr- clr-service-design teaser_icon size_normal highlight3"></i>
                                            </div>
                                            <div class="media-body media-middle">
                                                <a href="#design-services">Design Services</a>
                                            </div>
                                        </div>
                                    </li>
                                    <li>
                                        <div class="media teaser media-icon-teaser">
                                            <div class="media-left media-middle">
                                                <i class="clr- clr-service-scaning teaser_icon size_normal highlight3"></i>
                                            </div>
                                            <div class="media-body media-middle">
                                                <a href="#digital-scanning">Digital Scanning</a>
                                            </div>
                                        </div>
                                    </li>
                                    <li>
                                        <div class="media teaser media-icon-teaser">
                                            <div class="media-left media-middle">
                                                <i class="clr- clr-service-printing teaser_icon size_normal highlight3"></i>
                                            </div>
                                            <div class="media-body media-middle">
                                                <a href="#printing-services">Printing Services</a>
                                            </div>
                                        </div>
                                    </li>
                                </ul>
                                <!-- eof .isotope_container -->
                            </div>


                        </div>
                    </div>
                </section>
                <section
                        class="fw-main-row  cs main_color5 section_padding_top_150 section_padding_bottom_150 parallax overlay_color"
                        style="background-image:url(/img/progress.jpg);" id="featured-video">
                    <div class="container">
                        <div class="row">
                            <div class="col-xs-12 text-center">
                                <a href="https://www.youtube.com/watch?v=2Kvl0vpV6lM" target="_self"
                                   class="theme_button bg_button color3 round_button play_button"
                                   data-gal="prettyPhoto[gal-video-5ff0994a159f8]">
                                    <i class="fa fa-play" aria-hidden="true"></i>
                                </a>
                                <div class="fw-divider-space  hidden-md hidden-sm hidden-xs"
                                     style="padding-top: 30px;"></div>
                                <div class="fw-divider-space " style="padding-top: 30px;"></div>
                                <p class=" highlight2 above_heading">
                                    Video Presentation
                                </p>

                                <h2 class="black section_header ">
                                    Our Working Process
                                </h2>
                            </div>
                        </div>
                    </div>
                </section>
                <section class="fw-main-row  ls section_padding_top_150 section_padding_bottom_150" id="products">
                    <div class="container">
                        <div class="row">
                            <div class="col-xs-12 text-center">
                                <p class="highlight above_heading">
                                    Products
                                </p>
                                <h2 class="section_header">
                                    We will help you look<br> professional
                                </h2>
                            </div>
                            <div class="col-xs-12 col-sm-6 col-md-4">
                                <div class="media-item vertical-item content-padding big-padding with_bottom_border cs main_color2 item_color_2 text-center">
                                    <div class="item-media">
                                        <img width="370" height="370" src="/img/shop_banner_1.jpg"
                                             class="attachment-colorlab-square-width size-colorlab-square-width" alt=""
                                             srcset="/img/shop_banner_1.jpg 370w, /img/shop_banner_1-150x150.jpg 150w, /img/shop_banner_1-300x300.jpg 300w, /img/shop_banner_1-100x100.jpg 100w"
                                             sizes="(max-width: 370px) 100vw, 370px"/>
                                        <a class="abs-link" href="/shop"></a>
                                    </div>

                                    <div class="item-content ">
                                        <h3 class="entry-title">
                                            <a href="/shop">
                                                Business Cards
                                            </a>
                                        </h3>
                                        <p>Starting at <strong>$9.99</strong></p>
                                    </div>
                                </div><!-- eof .vertical-item -->
                            </div>
                            <div class="col-xs-12 col-sm-6 col-md-4">
                                <div class="media-item vertical-item content-padding big-padding with_bottom_border cs main_color2 item_color_1 text-center">
                                    <div class="item-media">
                                        <img width="370" height="370" src="/img/shop_banner_2.jpg"
                                             class="attachment-colorlab-square-width size-colorlab-square-width" alt=""
                                             srcset="/img/shop_banner_2.jpg 370w, /img/shop_banner_2-150x150.jpg 150w, /img/shop_banner_2-300x300.jpg 300w, /img/shop_banner_2-100x100.jpg 100w"
                                             sizes="(max-width: 370px) 100vw, 370px"/>
                                        <a class="abs-link" href="/shop"></a>
                                    </div>

                                    <div class="item-content ">
                                        <h3 class="entry-title">
                                            <a href="/shop">
                                                T-Shirts
                                            </a>
                                        </h3>
                                        <p>Starting at <strong>$29.99</strong></p>
                                    </div>
                                </div><!-- eof .vertical-item -->
                            </div>
                            <div class="col-xs-12 col-sm-6 col-md-4">
                                <div class="media-item vertical-item content-padding big-padding with_bottom_border cs main_color2 item_color_3 text-center">
                                    <div class="item-media">
                                        <img width="370" height="370" src="/img/shop_banner_3.jpg"
                                             class="attachment-colorlab-square-width size-colorlab-square-width" alt=""
                                             srcset="/img/shop_banner_3.jpg 370w, /img/shop_banner_3-150x150.jpg 150w, /img/shop_banner_3-300x300.jpg 300w, /img/shop_banner_3-100x100.jpg 100w"
                                             sizes="(max-width: 370px) 100vw, 370px"/>
                                        <a class="abs-link" href="/shop"></a>
                                    </div>

                                    <div class="item-content ">
                                        <h3 class="entry-title">
                                            <a href="/shop">
                                                Banners </a>
                                        </h3>
                                        <p>Starting at <strong>$99.99</strong></p></div>
                                </div><!-- eof .vertical-item -->
                            </div>
                            <div class="col-xs-12 col-sm-6 col-md-4">
                                <div class="media-item vertical-item content-padding big-padding with_bottom_border cs main_color2 item_color_2 text-center">
                                    <div class="item-media">
                                        <img width="370" height="370" src="/img/shop_banner_1.jpg"
                                             class="attachment-colorlab-square-width size-colorlab-square-width" alt=""
                                             srcset="/img/shop_banner_1.jpg 370w, /img/shop_banner_1-150x150.jpg 150w, /img/shop_banner_1-300x300.jpg 300w, /img/shop_banner_1-100x100.jpg 100w"
                                             sizes="(max-width: 370px) 100vw, 370px"/>
                                        <a class="abs-link" href="/shop"></a>
                                    </div>

                                    <div class="item-content ">
                                        <h3 class="entry-title">
                                            <a href="/shop">
                                                Brochures
                                            </a>
                                        </h3>
                                        <p>Starting at <strong>$6.99</strong></p></div>
                                </div><!-- eof .vertical-item -->
                            </div>
                            <div class="col-xs-12 col-sm-6 col-md-4">
                                <div class="media-item vertical-item content-padding big-padding with_bottom_border cs main_color2 item_color_1 text-center">
                                    <div class="item-media">
                                        <img width="370" height="370" src="/img/shop_banner_2.jpg"
                                             class="attachment-colorlab-square-width size-colorlab-square-width" alt=""
                                             srcset="/img/shop_banner_2.jpg 370w, /img/shop_banner_2-150x150.jpg 150w, /img/shop_banner_2-300x300.jpg 300w, /img/shop_banner_2-100x100.jpg 100w"
                                             sizes="(max-width: 370px) 100vw, 370px"/>
                                        <a class="abs-link" href="/shop"></a>
                                    </div>

                                    <div class="item-content ">
                                        <h3 class="entry-title">
                                            <a href="/shop">
                                                Calendars
                                            </a>
                                        </h3>
                                        <p>Starting at <strong>$19.99</strong></p>
                                    </div>
                                </div><!-- eof .vertical-item -->
                            </div>
                            <div class="col-xs-12 col-sm-6 col-md-4">
                                <div class="media-item vertical-item content-padding big-padding with_bottom_border cs main_color2 item_color_3 text-center">
                                    <div class="item-media">
                                        <img width="370" height="370" src="/img/shop_banner_3.jpg"
                                             class="attachment-colorlab-square-width size-colorlab-square-width" alt=""
                                             srcset="/img/shop_banner_3.jpg 370w, /img/shop_banner_3-150x150.jpg 150w, /img/shop_banner_3-300x300.jpg 300w, /img/shop_banner_3-100x100.jpg 100w"
                                             sizes="(max-width: 370px) 100vw, 370px"/>
                                        <a class="abs-link" href="/shop"></a>
                                    </div>

                                    <div class="item-content ">
                                        <h3 class="entry-title">
                                            <a href="/shop">
                                                Copy services
                                            </a>
                                        </h3>
                                        <p>Starting at <strong>$4.99</strong></p></div>
                                </div><!-- eof .vertical-item -->
                            </div>
                        </div>
                    </div>
                </section>

                <section
                        class="fw-main-row  ls section_padding_top_0 section_padding_bottom_0 columns_padding_0 fluid_padding_0"
                        id="gallery">

                    <div class="container-fluid">
                        <div class="row">

                            <div class="col-xs-12">


                                <div id="widget_portfolio_carousel_5ff0994a197d7" class="owl-carousel gallery-carousel "
                                     data-nav="1" data-dots="" data-loop="1" data-center="1" data-autoplay="1"
                                     data-autopaly-timeout="5000" data-margin="0" data-responsive-xxs="1"
                                     data-responsive-xs="2"
                                     data-responsive-sm="2" data-responsive-md="3" data-responsive-lg="3">
                                    <div class="owl-carousel-item item-layout-item-title banners ">
                                        <div class="vertical-item text-center gallery-title-item">
                                            <div class="item-media">
                                                <img width="1170" height="780" src="/img/01.jpg"
                                                     class="attachment-colorlab-full-width size-colorlab-full-width wp-post-image"
                                                     alt=""
                                                     srcset="img/01.jpg 1170w, img/01-300x200.jpg 300w, img/01-768x512.jpg 768w, img/01-1024x683.jpg 1024w, img/01-780x520.jpg 780w, img/01-600x400.jpg 600w"
                                                     sizes="(max-width: 1170px) 100vw, 1170px"/>
                                                <div class="media-links">
                                                    <div class="links-wrap">
                                                        <a class="p-view prettyPhoto "
                                                           data-gal="prettyPhoto[gal-5ff0994a197d7]"
                                                           href="img/01.jpg"></a>
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="item-content topmargin_0">
                                                <h3 class="entry-title small">
                                                    <a href="/">
                                                        Aenean eget justo </a>
                                                </h3>
                                                <div class="categories-links small-text highlightlinks">
                                                    <a href="/" rel="tag">Banners</a>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="owl-carousel-item item-layout-item-title business-cards ">
                                        <div class="vertical-item text-center gallery-title-item">
                                            <div class="item-media">
                                                <img width="1170" height="780" src="/img/02.jpg"
                                                     class="attachment-colorlab-full-width size-colorlab-full-width wp-post-image"
                                                     alt=""
                                                     srcset="img/02.jpg 1170w, img/02-300x200.jpg 300w, img/02-768x512.jpg 768w, img/02-1024x683.jpg 1024w, img/02-780x520.jpg 780w, img/02-600x400.jpg 600w"
                                                     sizes="(max-width: 1170px) 100vw, 1170px"/>
                                                <div class="media-links">
                                                    <div class="links-wrap">
                                                        <a class="p-view prettyPhoto "
                                                           data-gal="prettyPhoto[gal-5ff0994a197d7]"
                                                           href="/img/02.jpg"></a>
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="item-content topmargin_0">
                                                <h3 class="entry-title small">
                                                    <a href="/">
                                                        Phasellus euismod varius
                                                    </a>
                                                </h3>
                                                <div class="categories-links small-text highlightlinks">
                                                    <a href="/" rel="tag">Business Cards</a></div>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="owl-carousel-item item-layout-item-title finishing ">
                                        <div class="vertical-item text-center gallery-title-item">
                                            <div class="item-media">
                                                <img width="1170" height="780" src="/img/03.jpg"
                                                     class="attachment-colorlab-full-width size-colorlab-full-width wp-post-image"
                                                     alt=""
                                                     srcset="img/03.jpg 1170w, img/03-300x200.jpg 300w, img/03-768x512.jpg 768w, img/03-1024x683.jpg 1024w, img/03-780x520.jpg 780w, img/03-600x400.jpg 600w"
                                                     sizes="(max-width: 1170px) 100vw, 1170px"/>
                                                <div class="media-links">
                                                    <div class="links-wrap">
                                                        <a class="p-view prettyPhoto "
                                                           data-gal="prettyPhoto[gal-5ff0994a197d7]"
                                                           href="img/03.jpg"></a>
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="item-content topmargin_0">
                                                <h3 class="entry-title small">
                                                    <a href="/">
                                                        Maecenas sapien urna </a>
                                                </h3>
                                                <div class="categories-links small-text highlightlinks">
                                                    <a href="/" rel="tag">Finishing</a>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="owl-carousel-item item-layout-item-title flyers ">
                                        <div class="vertical-item text-center gallery-title-item">
                                            <div class="item-media">
                                                <img width="1170" height="780" src="img/04.jpg"
                                                     class="attachment-colorlab-full-width size-colorlab-full-width wp-post-image"
                                                     alt=""
                                                     srcset="img/04.jpg 1170w, img/04-300x200.jpg 300w, img/04-768x512.jpg 768w, img/04-1024x683.jpg 1024w, img/04-780x520.jpg 780w, img/04-600x400.jpg 600w"
                                                     sizes="(max-width: 1170px) 100vw, 1170px"/>
                                                <div class="media-links">
                                                    <div class="links-wrap">
                                                        <a class="p-view prettyPhoto "
                                                           data-gal="prettyPhoto[gal-5ff0994a197d7]"
                                                           href="/img/04.jpg"></a>
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="item-content topmargin_0">
                                                <h3 class="entry-title small">
                                                    <a href="/">
                                                        Etiam congue elit sed </a>
                                                </h3>
                                                <div class="categories-links small-text highlightlinks">
                                                    <a href="/" rel="tag">Flyers</a></div>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="owl-carousel-item item-layout-item-title t-shirts ">
                                        <div class="vertical-item text-center gallery-title-item">
                                            <div class="item-media">
                                                <img width="1170" height="780" src="/img/05.jpg"
                                                     class="attachment-colorlab-full-width size-colorlab-full-width wp-post-image"
                                                     alt=""
                                                     srcset="img/05.jpg 1170w, img/05-300x200.jpg 300w, img/05-768x512.jpg 768w, img/05-1024x683.jpg 1024w, img/05-780x520.jpg 780w, img/05-600x400.jpg 600w"
                                                     sizes="(max-width: 1170px) 100vw, 1170px"/>
                                                <div class="media-links">
                                                    <div class="links-wrap">
                                                        <a class="p-view prettyPhoto "
                                                           data-gal="prettyPhoto[gal-5ff0994a197d7]"
                                                           href="/img/05.jpg"></a>
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="item-content topmargin_0">
                                                <h3 class="entry-title small">
                                                    <a href="/">
                                                        Nullam nec felis consectetur
                                                    </a>
                                                </h3>
                                                <div class="categories-links small-text highlightlinks">
                                                    <a href="/" rel="tag">T-Shirts</a>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="owl-carousel-item item-layout-item-title banners ">
                                        <div class="vertical-item text-center gallery-title-item">
                                            <div class="item-media">
                                                <img width="1170" height="780" src="/img/06.jpg"
                                                     class="attachment-colorlab-full-width size-colorlab-full-width wp-post-image"
                                                     alt=""
                                                     srcset="img/06.jpg 1170w, img/06-300x200.jpg 300w, img/06-768x512.jpg 768w, img/06-1024x683.jpg 1024w, img/06-780x520.jpg 780w, img/06-600x400.jpg 600w"
                                                     sizes="(max-width: 1170px) 100vw, 1170px"/>
                                                <div class="media-links">
                                                    <div class="links-wrap">
                                                        <a class="p-view prettyPhoto"
                                                           data-gal="prettyPhoto[gal-5ff0994a197d7]"
                                                           href="/img/06.jpg"></a>
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="item-content topmargin_0">
                                                <h3 class="entry-title small">
                                                    <a href="/">
                                                        In egestas elit felis </a>
                                                </h3>
                                                <div class="categories-links small-text highlightlinks">
                                                    <a href="/" rel="tag">Banners</a>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>


                        </div>
                    </div>
                </section>
                <section class="fw-main-row  ls section_padding_top_150 section_padding_bottom_140" id="testimonials">
                    <div class="container">
                        <div class="row">
                            <div class="col-xs-12 text-center">
                                <div class="owl-carousel testimonials-owl-carousel" data-responsive-lg="1"
                                     data-responsive-md="1" data-responsive-sm="1" data-nav="" data-dots="1"
                                     data-autoplay="" data-autoplay-timeout="">
                                    <blockquote>
                                        <div class="avatar">
                                            <img src="/img/face-01.jpg" alt="/img/face-01.jpg"/></div>

                                        <footer>
                                            <cite>Mary J. McQuiston</cite>
                                            <span class="highlight small-text">manager</span>
                                        </footer>
                                        <p>Colorway did an incredible job in creating our new marketing &amp; sales
                                            pieces. Their prices are very competitive, and their quality is excellent.
                                            Thank you for the excellent service on the brochure reprints and
                                            the new display posters.</p>
                                    </blockquote>
                                    <blockquote>
                                        <div class="avatar">
                                            <img src="/img/face-02.jpg" alt="/img/face-02.jpg"/></div>

                                        <footer>
                                            <cite>Ada B. Powell</cite>
                                            <span class="highlight small-text">manager</span>
                                        </footer>
                                        <p>Thank you for the excellent service on the brochure reprints and the new
                                            display posters. I really appreciate your fine quality, speed and low price.
                                            Colorway has consistently demonstrated the ability to provide
                                            quality work.</p>
                                    </blockquote>
                                    <blockquote>
                                        <div class="avatar">
                                            <img src="img/face-03.jpg" alt="img/face-03.jpg"/></div>

                                        <footer>
                                            <cite>Stephen I. Lebouef</cite>
                                            <span class="highlight small-text">Businessman</span>
                                        </footer>
                                        <p>Colorway has consistently demonstrated the ability to provide quality work at
                                            a competitive price. We can always rely on them to have our printing
                                            completed on time and for acceptable price.</p>
                                    </blockquote>
                                </div>
                            </div>
                        </div>
                    </div>
                </section>
                <section class="fw-main-row  ls section_padding_top_125 section_padding_bottom_140 columns_padding_30"
                         id="faq">
                    <div class="container">
                        <div class="row">
                            <div class="col-xs-12 col-md-6">
                                <h2 class=" highlight thin  section_header big margin_0 poiret">
                                    FAQs </h2>

                                <div class="fw-divider-space  hidden-sm hidden-xs"
                                     style="margin-top: -10px;"
                                ></div>

                                <h3 class=" highlight2 thin text-uppercase section_header poiret margin_0">
                                    Frequently Asked Questions </h3>
                                <div class="fw-divider-space  hidden-md hidden-sm hidden-xs"
                                     style="padding-top: 50px;"
                                ></div>

                                <div class="fw-divider-space  hidden-lg"
                                     style="padding-top: 30px;"
                                ></div>
                                <div class="bootstrap-accordion">
                                    <div class="panel-group collapse-unstyled"
                                         id="accordion-4660f6592ec3697d296a9946a3eb85bb">
                                        <div class="panel">
                                            <h4>
                                                <a class=""
                                                   href="#collapse-4660f6592ec3697d296a9946a3eb85bb-0"
                                                   data-toggle="collapse"
                                                   data-parent="#accordion-4660f6592ec3697d296a9946a3eb85bb">
                                                    What is printing services? </a>
                                            </h4>
                                            <div id="collapse-4660f6592ec3697d296a9946a3eb85bb-0"
                                                 class="panel-collapse collapse in">
                                                <div class="panel-content">
                                                    <p>Colorway offers a wide variety of printing and finishing
                                                        services, including electronic file access (e.g., e-mails, CDs,
                                                        USB drives)</p></div>
                                            </div>
                                        </div>
                                        <div class="panel">
                                            <h4>
                                                <a class="collapsed"
                                                   href="#collapse-4660f6592ec3697d296a9946a3eb85bb-1"
                                                   data-toggle="collapse"
                                                   data-parent="#accordion-4660f6592ec3697d296a9946a3eb85bb">
                                                    What is digital printing? </a>
                                            </h4>
                                            <div id="collapse-4660f6592ec3697d296a9946a3eb85bb-1"
                                                 class="panel-collapse collapse ">
                                                <div class="panel-content">
                                                    <p>Turducken porchetta pancetta capicola, tenderloin kevin shank
                                                        pork loin tail tongue picanha ball tip pastrami sausage. Shank
                                                        shoulder tri-tip cupim ham hock pork frankfurter.</p></div>
                                            </div>
                                        </div>
                                        <div class="panel">
                                            <h4>
                                                <a class="collapsed"
                                                   href="#collapse-4660f6592ec3697d296a9946a3eb85bb-2"
                                                   data-toggle="collapse"
                                                   data-parent="#accordion-4660f6592ec3697d296a9946a3eb85bb">
                                                    How can I get my print job once it is finished? </a>
                                            </h4>
                                            <div id="collapse-4660f6592ec3697d296a9946a3eb85bb-2"
                                                 class="panel-collapse collapse ">
                                                <div class="panel-content">
                                                    <p>Turducken porchetta pancetta capicola, tenderloin kevin shank
                                                        pork loin tail tongue picanha ball tip pastrami sausage. Shank
                                                        shoulder tri-tip cupim ham hock pork frankfurter.</p></div>
                                            </div>
                                        </div>
                                        <div class="panel">
                                            <h4>
                                                <a class="collapsed"
                                                   href="#collapse-4660f6592ec3697d296a9946a3eb85bb-3"
                                                   data-toggle="collapse"
                                                   data-parent="#accordion-4660f6592ec3697d296a9946a3eb85bb">
                                                    Can I get a price quote for my print job? </a>
                                            </h4>
                                            <div id="collapse-4660f6592ec3697d296a9946a3eb85bb-3"
                                                 class="panel-collapse collapse ">
                                                <div class="panel-content">
                                                    <p>Turducken porchetta pancetta capicola, tenderloin kevin shank
                                                        pork loin tail tongue picanha ball tip pastrami sausage. Shank
                                                        shoulder tri-tip cupim ham hock pork frankfurter.</p></div>
                                            </div>
                                        </div>
                                        <div class="panel">
                                            <h4>
                                                <a class="collapsed"
                                                   href="#collapse-4660f6592ec3697d296a9946a3eb85bb-4"
                                                   data-toggle="collapse"
                                                   data-parent="#accordion-4660f6592ec3697d296a9946a3eb85bb">
                                                    Can you design or help me design my print job? </a>
                                            </h4>
                                            <div id="collapse-4660f6592ec3697d296a9946a3eb85bb-4"
                                                 class="panel-collapse collapse ">
                                                <div class="panel-content">
                                                    <p>Turducken porchetta pancetta capicola, tenderloin kevin shank
                                                        pork loin tail tongue picanha ball tip pastrami sausage. Shank
                                                        shoulder tri-tip cupim ham hock pork frankfurter.</p></div>
                                            </div>
                                        </div>
                                        <div class="panel">
                                            <h4>
                                                <a class="collapsed"
                                                   href="#collapse-4660f6592ec3697d296a9946a3eb85bb-5"
                                                   data-toggle="collapse"
                                                   data-parent="#accordion-4660f6592ec3697d296a9946a3eb85bb">
                                                    Can you scan my hardcopy originals into electronic form? </a>
                                            </h4>
                                            <div id="collapse-4660f6592ec3697d296a9946a3eb85bb-5"
                                                 class="panel-collapse collapse ">
                                                <div class="panel-content">
                                                    <p>Turducken porchetta pancetta capicola, tenderloin kevin shank
                                                        pork loin tail tongue picanha ball tip pastrami sausage. Shank
                                                        shoulder tri-tip cupim ham hock pork frankfurter.</p></div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>


                            <div class="col-xs-12 col-md-6">


                                <h2 class=" highlight7 thin text-uppercase section_header big margin_0 poiret">
                                    Services </h2>


                                <div class="fw-divider-space  hidden-sm hidden-xs"
                                     style="margin-top: -10px;"
                                ></div>

                                <h3 class=" highlight2 thin text-uppercase section_header poiret margin_0">
                                    That We can do </h3>


                                <div class="fw-divider-space  hidden-md hidden-sm hidden-xs"
                                     style="padding-top: 45px;"
                                ></div>

                                <div class="fw-divider-space  hidden-lg"
                                     style="padding-top: 25px;"
                                ></div>
                                <div class="text-block shortcode">
                                    <p>Transform your project into a finished piece. We can print and design just about
                                        anything from signs and banners to brochures, promotional products and forms,
                                        with options of variable data printing.</p></div>

                                <div class="fw-divider-space  hidden-md hidden-sm hidden-xs"
                                     style="padding-top: 25px;"
                                ></div>
                                <p class="progress-bar-title grey">Business Cards</p>
                                <div class="progress">
                                    <div class="progress-bar progress-bar-color7" role="progressbar"
                                         data-transitiongoal="85" aria-valuenow="85"
                                         aria-valuemin="0" aria-valuemax="100">
                                        <span class="grey">85%</span>
                                    </div>
                                </div>
                                <p class="progress-bar-title grey">T-Shirts</p>
                                <div class="progress">
                                    <div class="progress-bar " role="progressbar"
                                         data-transitiongoal="90" aria-valuenow="90"
                                         aria-valuemin="0" aria-valuemax="100">
                                        <span class="grey">90%</span>
                                    </div>
                                </div>
                                <p class="progress-bar-title grey">Banners</p>
                                <div class="progress">
                                    <div class="progress-bar progress-bar-color3" role="progressbar"
                                         data-transitiongoal="67" aria-valuenow="67"
                                         aria-valuemin="0" aria-valuemax="100">
                                        <span class="grey">67%</span>
                                    </div>
                                </div>
                                <p class="progress-bar-title grey">Design</p>
                                <div class="progress">
                                    <div class="progress-bar progress-bar-color8" role="progressbar"
                                         data-transitiongoal="85" aria-valuenow="85"
                                         aria-valuemin="0" aria-valuemax="100">
                                        <span class="grey">85%</span>
                                    </div>
                                </div>
                            </div>


                        </div>
                    </div>
                </section>
                <section class="fw-main-row  cs section_padding_top_150 section_padding_bottom_145 parallax"
                         style="background-image:url(img/contact.jpg);" id="contact">

                    <div class="container">
                        <div class="row">

                            <div class="col-xs-12 col-md-7 col-lg-6">


                                <p class=" black   above_heading ">
                                    Request a quote </p>


                                <h2 class="    section_header ">
                                    We usually answer within one hour </h2>

                                <div class="form-wrapper    columns_margin_bottom_20">
                                    <form data-fw-form-id="fw_form" data-fw-ext-forms-type="contact-forms">
                                        <div class="wrap-forms">
                                            <div class="row"></div>
                                            <div class="row">
                                                <div class="col-xs-12 col-sm-6 form-builder-item">
                                                    <div class="form-group has-placeholder">
                                                        <label for="id-1">Name <sup>*</sup> </label>
                                                        <input class="form-control" type="text" name="text_8c9a28d"
                                                               placeholder="Name" value="" id="id-1"
                                                               required="required">
                                                    </div>
                                                </div>
                                                <div class="col-xs-12 col-sm-6 form-builder-item">
                                                    <div class="form-group has-placeholder">
                                                        <label for="id-2">Email <sup>*</sup> </label>
                                                        <input class="form-control" type="text" name="email_f30cef8"
                                                               placeholder="Email" value="" id="id-2"
                                                               required="required">
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="row">
                                                <div class="col-xs-12 form-builder-item">
                                                    <div class="form-group has-placeholder">
                                                        <label for="id-3">What you&#039;re interested in? </label>
                                                        <input class="form-control" type="text" name="text_cb06958"
                                                               placeholder="What you&#039;re interested in?" value=""
                                                               id="id-3">
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="row">
                                                <div class="col-xs-12 form-builder-item">
                                                    <div class="form-group has-placeholder">
                                                        <label for="id-4">Message <sup>*</sup> </label>
                                                        <textarea class="form-control" name="textarea_85f41db"
                                                                  placeholder="Message" id="id-4"
                                                                  required="required"></textarea>
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="row"></div>
                                        </div>
                                        <div class="wrap-forms form-submit">
                                            <div class="row">
                                                <div class="col-sm-12 bottommargin_0">
                                                    <button class="theme_button color1 min_width_button" type="submit">
                                                        Submit quote
                                                    </button>
                                                </div>
                                            </div>
                                        </div>
                                    </form>
                                </div>
                            </div>
                        </div>
                    </div>
                </section>
            </div>

            <footer class="page_footer cs main_color7 section_padding_top_110 section_padding_bottom_115 columns_margin_bottom_40">
                <div class="container">
                    <div class="row">
                        <div class="col-xs-12 col-sm-4">
                            <div class="widget-theme-wrapper widget_no_background ">
                                <div id="mwt_teaser-2" class="widget widget_teaser">
                                    <div class="teaser text-center  ">
                                        <div class="teaser_icon size_huge highlight3 image_icon">
                                            <img width="120" height="121" src="/img/clr-phone-call-3.png"
                                                 class="attachment-thumbnail size-thumbnail" alt=""
                                                 srcset="/img/clr-phone-call-3.png 120w, /img/clr-phone-call-3-100x100.png 100w"
                                                 sizes="(max-width: 120px) 100vw, 120px"/></div>

                                        <p><span class="small-text highlight3">call us 24/7</span><br/><span
                                                    class="big black">0-800-234-5678</span></p>


                                    </div>

                                </div>
                            </div>
                        </div>
                        <div class="col-xs-12 col-sm-4">
                            <div class="widget-theme-wrapper widget_no_background ">
                                <div id="mwt_teaser-3" class="widget widget_teaser">
                                    <div class="teaser text-center  ">
                                        <div class="teaser_icon size_huge highlight8 image_icon">
                                            <img width="120" height="85" src="/img/clr-mail-5.png"
                                                 class="attachment-thumbnail size-thumbnail" alt=""/></div>

                                        <p><span class="small-text highlight8">write us</span><br/><span
                                                    class="big black darklinks"><a href="mailto:clab@support.com">clab@support.com</a></span>
                                        </p>


                                    </div>

                                </div>
                            </div>
                        </div>
                        <div class="col-xs-12 col-sm-4">
                            <div class="widget-theme-wrapper widget_no_background ">
                                <div id="mwt_teaser-4" class="widget widget_teaser">
                                    <div class="teaser text-center  ">
                                        <div class="teaser_icon size_huge highlight9 image_icon">
                                            <img width="120" height="120" src="/img/clr-clock-5.png"
                                                 class="attachment-thumbnail size-thumbnail" alt=""
                                                 srcset="/img/clr-clock-5.png 120w, /img/clr-clock-5-100x100.png 100w"
                                                 sizes="(max-width: 120px) 100vw, 120px"/></div>

                                        <p><span class="small-text highlight9">7 days a week</span><br/><span
                                                    class="big black">9:00am - 8:00PM</span></p>


                                    </div>

                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </footer>
            <!-- .page_footer -->


            <section class="page_copyright cs section_padding_top_110 section_padding_bottom_100">
                <div class="container">
                    <div class="row">
                        <div class="col-xs-12 text-center">
                            <p class="page_social bottommargin_20">
                                        <span class="social-icons ">
                            <a href=""
                               class="social-icon socicon-facebook light-bg-icon color-icon rounded-icon"
                               target="_blank">
                                </a>
                                        <a href=""
                                           class="social-icon socicon-twitter light-bg-icon color-icon rounded-icon"
                                           target="_blank">
                                </a>
                                        <a href=""
                                           class="social-icon socicon-googleplus light-bg-icon color-icon rounded-icon"
                                           target="_blank">
                                </a>
                    </span>
                            </p>
                            <p><span class="small-text black">© Copyright 2021. All Rights Reserved</span></p>
                        </div>
                    </div>
                </div>
            </section>

        </div>
        <!-- eof #box_wrapper -->
    </div>

@endsection
