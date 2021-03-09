<!DOCTYPE html>
<html lang="en">

<head>
    <!-- all meta -->
    <meta charset="UTF-8">
    <meta http-equiv="x-ua-compatible" content="ie=edge">
    <meta name="description" content="">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <meta name="csrf-token" content="{{ csrf_token() }}">
    <!-- Page Title -->
    <title>COLORIT</title>
    <!-- Favicon Icon -->
    <link rel="shortcut icon" href="{{asset('assets/images/red2.png')}}" type="image/png">
    <!-- All css here -->
    <link href="https://fonts.googleapis.com/css?family=Russo+One&display=swap&subset=cyrillic" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css?family=Open+Sans:300,400,600,800&display=swap" rel="stylesheet">
    <link href="https://fonts.googleapis.com/icon?family=Material+Icons" rel="stylesheet">
    <!-- Fonts -->
    <link href="https://fonts.googleapis.com/css2?family=Nunito:wght@400;600;700&display=swap" rel="stylesheet">
    <!-- Styles -->
    <link rel='stylesheet' id='colorlab-icon-fonts-css' href="{{ asset('css/fonts.css?ver=2.0.0')}}" type='text/css' media='all' />
    <link rel='stylesheet' id='colorlab-font-css' href='http://fonts.googleapis.com/css?family=Poppins%3A200%2C300%2C300i%2C500%2C800%7CPoiret+One%3A400&#038;subset=latin&#038;ver=2.0.0' type='text/css' media='all' />
    <link rel='stylesheet' id='colorlab-woo-css' href="{{ asset('css/woo.css?ver=2.0.0')}}" type='text/css' media='all' />
    <link rel='stylesheet' id='colorlab-css-style-css' href="{{ asset('style.css?ver=5.0.11')}}" type='text/css' media='all' />
    <link rel='stylesheet' id='bootstrap-css' href="{{ asset('css/bootstrap.min.css?ver=2.0.0')}}" type='text/css' media='all' />
    <link rel='stylesheet' id='colorlab-animations-css' href="{{ asset('css/animations.css?ver=2.0.0')}}" type='text/css' media='all' />
    <link rel='stylesheet' id='colorlab-main-css' href="{{ asset('css/main.css?ver=2.0.0')}}" type='text/css' media='all' />
    <link rel='stylesheet' href="{{ asset('css/app.css?ver=2.0.0')}}" type='text/css' media='all' />
</head>

<body class="home page-template page-template-page-templates page-template-full-width page-template-page-templatesfull-width-php page page-id-9 woocommerce-no-js masthead-fixed full-width footer-widgets grid">
    <!-- page preloader -->
    <div class="preloader">
        <div class="preloader_image pulse"></div>
    </div>

    <div id="app">
        <notifications group="info" :position="'top left'" :max="5"/>
        @yield('content')
    </div>

    <script type='text/javascript' src="{{ asset('js/jquery/jquery.js?ver=1.12.4')}}"></script>
    <script type='text/javascript' src="{{ asset('js/jquery/jquery-migrate.min.js?ver=1.4.1')}}"></script>
    <script type='text/javascript' src="{{ asset('js/vendor/modernizr-2.6.2.min.js?ver=2.6.2')}}"></script>
    <script type='text/javascript' src="{{ asset('js/vendor/bootstrap.min.js?ver=2.0.0')}}"></script>
    <script type='text/javascript' src="{{ asset('js/vendor/jquery.appear.js?ver=2.0.0')}}"></script>
    <script type='text/javascript' src="{{ asset('js/vendor/jquery.hoverIntent.js?ver=2.0.0')}}"></script>
    <script type='text/javascript' src="{{ asset('js/vendor/superfish.js?ver=2.0.0')}}"></script>
    <script type='text/javascript' src="{{ asset('js/vendor/jquery.easing.1.3.js?ver=2.0.0')}}"></script>
    <script type='text/javascript' src="{{ asset('js/vendor/jquery.ui.totop.js?ver=2.0.0')}}"></script>
    <script type='text/javascript' src="{{ asset('js/vendor/jquery.localScroll.min.js?ver=2.0.0')}}"></script>
    <script type='text/javascript' src="{{ asset('js/vendor/jquery.scrollTo.min.js?ver=2.0.0')}}"></script>
    <script type='text/javascript' src="{{ asset('js/vendor/jquery.scrollbar.min.js?ver=2.0.0')}}"></script>
    <script type='text/javascript' src="{{ asset('js/vendor/jquery.parallax-1.1.3.js?ver=2.0.0')}}"></script>
    <script type='text/javascript' src="{{ asset('js/vendor/jquery.easypiechart.min.js?ver=2.0.0')}}"></script>
    <script type='text/javascript' src="{{ asset('js/vendor/bootstrap-progressbar.min.js?ver=2.0.0')}}"></script>
    <script type='text/javascript' src="{{ asset('js/vendor/jquery.countTo.js?ver=2.0.0')}}"></script>
    <script type='text/javascript' src="{{ asset('js/vendor/jquery.prettyPhoto.js?ver=2.0.0')}}"></script>
    <script type='text/javascript' src="{{ asset('js/vendor/jquery.countdown.min.js?ver=2.0.0')}}"></script>
    <script type='text/javascript' src="{{ asset('js/vendor/isotope.pkgd.min.js?ver=2.0.0')}}"></script>
    <script type='text/javascript' src="{{ asset('js/vendor/owl.carousel.min.js?ver=2.0.0')}}"></script>
    <script type='text/javascript' src="{{ asset('js/vendor/jquery.flexslider-min.js?ver=2.0.0')}}"></script>
    <script type='text/javascript' src="{{ asset('js/plugins.js?ver=2.0.0')}}"></script>
    <script type='text/javascript' src="{{ asset('js/woo.js?ver=2.0.0')}}"></script>
    <script type='text/javascript' src="{{ asset('js/selectize.min.js?ver=2.0.0')}}"></script>
    <script type='text/javascript' src="{{ asset('js/main.js?ver=2.0.0')}}"></script>
    <script src="{{asset('js/app.js')}}"></script>

</body>

</html>
