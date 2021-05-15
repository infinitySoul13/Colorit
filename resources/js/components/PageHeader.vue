<template>
    <div class="transparent_wrapper">
<!--        <div class="site-mobile-menu site-navbar-target">-->
<!--            <div class="site-mobile-menu-header">-->
<!--                <div class="site-mobile-menu-close mt-3">-->
<!--                    <span class="icon-close2 js-menu-toggle"></span>-->
<!--                </div>-->
<!--            </div>-->
<!--            <div class="site-mobile-menu-body"></div>-->
<!--        </div>-->
<!--        <header class="page_header header_v2 header_white site-navbar js-sticky-header site-navbar-target" role="banner">-->
<!--            <div class="container-fluid">-->
<!--                <div class="d-flex align-items-center">-->
<!--                    <div class="mr-auto ">-->
<!--&lt;!&ndash;                        <a href="#" id="sideMenuBtn" class="filter-icon mr-5"><i class="icon-filter site-menu-icon h3" aria-hidden="true"></i></a>&ndash;&gt;-->
<!--                        <slot></slot>-->
<!--                    </div>-->
<!--                    <div class="site-logo mr-auto">-->
<!--                        <a href="/" class="logo logo_image_only">-->
<!--                            <img src="/img/2colorit.png" alt="">-->
<!--                            <img src="/img/colorit.png" alt="">-->
<!--                        </a>-->
<!--                    </div>-->

<!--                    <div class="mx-auto text-center w-75">-->
<!--                        <nav class="site-navigation position-relative text-center mx-auto" role="navigation" id="mainNav">-->
<!--                            <ul class="sf-menu site-menu main-menu js-clone-nav mx-auto m-0 p-0 mx-auto">-->
<!--                                <li class="nav-link menu-item menu-item-type-post_type menu-item-object-page menu-item-home menu-item-18">-->
<!--                                    <a href="/">-->
<!--                                        <span>Home</span>-->
<!--                                    </a>-->
<!--                                </li>-->
<!--                                <li class="nav-link  menu-item menu-item-type-post_type menu-item-object-page menu-item-1248">-->
<!--                                    <a href="/cart">-->
<!--                                        <span>Cart<cart-count-index></cart-count-index></span>-->
<!--                                    </a>-->
<!--                                </li>-->
<!--                                <li class="nav-link menu-item menu-item-type-post_type menu-item-object-page current-menu-item current_page_item menu-item-1248">-->
<!--                                    <a href="/shop">-->
<!--                                        <span>Shop</span>-->
<!--                                    </a>-->
<!--                                </li>-->
<!--                            </ul>-->
<!--                        </nav>-->
<!--                    </div>-->
<!--                    <div class="ml-auto ">-->
<!--                        <a href="#" class=" toggle_menu site-menu-toggle js-menu-toggle float-right"><span class="icon-menu site-menu-icon h3"></span></a>-->
<!--                    </div>-->
<!--                    <div class="header_right_buttons text-right hidden-xxs">-->
<!--                        <span class="social-icons">-->
<!--                            <a href="#" class="social-icon socicon-facebook color-bg-icon rounded-icon" target="_blank"></a>-->
<!--                            <a href="#" class="social-icon socicon-twitter color-bg-icon rounded-icon" target="_blank"></a>-->
<!--                            <a href="#" class="social-icon socicon-googleplus color-bg-icon rounded-icon" target="_blank"></a>-->
<!--                        </span>-->
<!--                    </div>-->
<!--                </div>-->
<!--            </div>-->

<!--        </header>-->

        <header class="page_header header_v2 header_white toggler_xxs_right d-none d-lg-block ">
            <div class="container-fluid">
                <div class="row">
                    <div class="col-sm-12 display-flex v-center">
                        <div class="header_left_logo">
                            <a href="/" rel="home" class="logo logo_image_only">
                                <img src="/img/2colorit.png" alt="">
                                <img src="/img/colorit.png" alt="">
                            </a>
                        </div>

                        <div class="header_mainmenu text-center">
                            <nav class="mainmenu_wrapper primary-navigation">
                                <ul id="menu-main-menu" class="sf-menu nav-menu nav d-block">
                                    <li id="menu-item-18" class="menu-item menu-item-type-post_type menu-item-object-page menu-item-home menu-item-18">
                                        <a href="/">
                                            <span>Home</span>
                                        </a>
                                    </li>
<!--                                    current-menu-item current_page_item -->
                                    <li id="menu-item-128" class="menu-item menu-item-type-post_type menu-item-object-page menu-item-1248">
                                        <a href="/cart">
                                            <span>Cart<cart-count-index></cart-count-index></span>
                                        </a>
                                    </li>
                                    <li id="menu-item-1248" class="menu-item menu-item-type-post_type menu-item-object-page  menu-item-1248">
                                        <a href="/shop">
                                            <span>Shop</span>
                                        </a>
                                    </li>
                                </ul>
                            </nav>
                            <!-- header toggler -->
                            <span class="toggle_menu"><span></span></span>
                        </div>

                        <div class="header_right_buttons text-right hidden-xxs">
                            <span class="social-icons">
                                <a href="#" class="social-icon socicon-facebook color-bg-icon rounded-icon" target="_blank"></a>
                                <a href="#" class="social-icon socicon-twitter color-bg-icon rounded-icon" target="_blank"></a>
                                <a href="#" class="social-icon socicon-googleplus color-bg-icon rounded-icon" target="_blank"></a>
                            </span>
                        </div>
                    </div>
                </div>
            </div>
        </header>
        <header class="page_header header_v2 toggler_xxs_right d-lg-none">
            <div class="container-fluid">
                <div class="row w-100 m-auto align-items-center justify-content-between">
                    <slot></slot>
                    <div class="header_left_logo">
                        <a href="/" rel="home" class="logo logo_image_only">
                            <img src="/img/2colorit.png" alt="">
                            <img src="/img/colorit.png" alt="">
                        </a>
                    </div>
                    <div class="header_menu ml-auto">
                        <span class="toggle_mobile_menu ml-auto" v-b-toggle.sidebar-right><i class="fa fa-bars" style="color:white; font-size: 30px"></i></span>
                        <b-sidebar id="sidebar-right" title="" right shadow>
                            <ul class="">
                                <li class="menu-item menu-item-type-post_type menu-item-object-page menu-item-home menu-item-18">
                                    <a href="/">
                                        <span>Home</span>
                                    </a>
                                </li>
                                <li class="menu-item menu-item-type-post_type menu-item-object-page menu-item-1248">
                                    <a href="/cart">
                                        <span style="position:relative">Cart<cart-count-index></cart-count-index></span>
                                    </a>
                                </li>
                                <li class="menu-item menu-item-type-post_type menu-item-object-page menu-item-1248">
                                    <a href="/shop">
                                        <span>Shop</span>
                                    </a>
                                </li>
                            </ul>
                        </b-sidebar>
                    </div>
                </div>
            </div>
        </header>
    </div>
</template>

<script>
    export default {
        name: "PageHeader"
    }
</script>

<style>

</style>