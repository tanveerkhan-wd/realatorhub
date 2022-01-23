<?php 
$adminDetails=getSettings();
//echo "<pre>"; print_r($adminDetails); exit;
?>
<!DOCTYPE html>
<html>
<head>
    <!-- <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge"> -->
    {{--<title>Realtorhubs | @yield('title')</title>--}}
    <?php if(!empty($adminDetails['seo_title'])){
        $seo_title=$adminDetails['seo_title'];
    }else{
        $seo_title='';
    }if(!empty($adminDetails['seo_description'])){
        $seo_description=$adminDetails['seo_description'];
    }else{
        $seo_description='';
    }

    ?>
    <title>{{ $seo_title }}</title>
    <!-- Tell the browser to be responsive to screen width -->
    <?php
    if(!empty($adminDetails) && !empty($adminDetails['favicon_icon'])){
        $favicon = $adminDetails['favicon_icon'];
    }
    else{
        $favicon = '';
    }

    ?>

    <meta name="og:title" content="{{ $seo_title }}">
    <meta name="og:description" content="{{ $seo_description }}">
    @if(!empty($favicon))
    <link rel="icon" href="{{ url('public/uploads/common_settings').'/'.$favicon }}" type="image/ico" sizes="16x16">
    @endif
    <meta content="width=device-width, initial-scale=1, maximum-scale=1, user-scalable=no" name="viewport">
    <meta name="csrf-token" content="{{ @csrf_token() }}">
    <!-- <link rel="stylesheet" href="{{ url('public/admin/bower_components/bootstrap/dist/css/bootstrap.min.css') }}">
    <link rel="stylesheet" href="{{ url('public/admin/bower_components/font-awesome/css/font-awesome.min.css') }}">
    <link rel="stylesheet" href="{{ url('public/admin/bower_components/Ionicons/css/ionicons.min.css') }}">
    <link rel="stylesheet" href="{{ url('public/admin/dist/css/AdminLTE.min.css') }}">
    <link rel="stylesheet" href="{{ url('public/admin/plugins/iCheck/square/blue.css') }}">
    <link rel="stylesheet" href="https://fonts.googleapis.com/css?family=Source+Sans+Pro:300,400,600,700,300italic,400italic,600italic">
    <link rel="stylesheet" href="{{ url('public/admin/dist/css/custom.css') }}">
    <link rel="stylesheet" href="{{ url('public/admin/dist/css/withoutlogin.css') }}"> -->
    
    <link rel="stylesheet" type="text/css" href="{{ url('public/assets/css/bootstrap.min.css') }}">
    <link rel="stylesheet" type="text/css" href="{{ url('public/assets/css/fontawesome.css') }}">
    <link rel="stylesheet" type="text/css" href="{{ url('public/assets/css/animate.css') }}">
    <link rel="stylesheet" type="text/css" href="{{ url('public/assets/css/owl.carousel.min.css') }}">
    <link rel="stylesheet" type="text/css" href="{{ url('public/assets/css/owl.theme.default.min.css') }}">
    <!-- <link rel="stylesheet" type="text/css" href="{{ url('public/assets/css/dashboard.css') }}"> -->
    <link rel="stylesheet" type="text/css" href="{{ url('public/assets/css/custom.css') }}">
    <link rel="stylesheet" type="text/css" href="{{ url('public/assets/css/style.css') }}">
    <link rel="stylesheet" href="{{ url('public/css/toastr.min.css') }}">
    <link href="{{ url('public/admin/') }}/plugins/select2/select2.min.css" rel="stylesheet">
    <script type="text/javascript">
        var current_room_id='';
    </script>
    <?php
//    $getSettingData = getMenu();
//    $favicon = $getSettingData['home_favicon'];
    ?>
    {{--<link rel="icon" href="{{ url('public/front_end/assets/images/').'/'.$favicon }}" type="image/ico" sizes="16x16">--}}
    <style type="text/css">
      /*  .alert a{
            text-decoration: none;
        }
        .alert{
            margin: 25px;
        }
        .alert.alert-danger {
            top: 2pc !important;
        }
        .alert.alert-success {
            top: 2pc !important;
        }*/
    </style>
    @include('general_settings.style')
    @stack('custom-styles')
</head>
<body>
<?php
//$getSettingData = getMenu();
?>
<div class="loader-outer-container" id="pageLoadingDiv" style=" display: none; ">
    <div class="laoder-inner-container">
        <div class="loader">
            <i class="fas fa-circle-notch animation rotateleft"></i>
            <p class="loader-text" id="loadingText">Loading...</p>
        </div>
    </div>
</div>
<div class="inner-container sticky_footer sticky_login_footer">
       
<!--<div class="main_header without_login_header">
    <div class="top_header">
        <div class="container-fluid">
            <div class="anounce_bar">
                <p>Digital Signage results in 47.7% effectiveness for brand awareness</p>
                <a href="#" class="btn-color btn-text btn-size"><i class="fas fa-envelope"></i>Subscribe & Save</a>
            </div>
        </div>
    </div>
    <div class="top_menu">
        <div class="middel_header header-bg header-text header-hover-text">
            <div class="container-fluid">
                <div class="middel_header_inner">
                    <a href="#x" id="slide" class="navbar-toggle">
                        <i class="fas fa-bars mobile_menu_icon bar_icon"></i>
                         <i class="fas fa-times mobile_menu_icon close_icon"></i> 
                    </a>
                    <a  class="navbar-toggle mobile-menu-style dash-navbar-toggle" id="slideclose"><i class="fas fa-times"></i></a>
                    <div class="header_logo">
                        <img src="https://cdn.shopify.com/s/files/1/0348/6243/2388/files/Digiteau_Logo_White_Bold_180x.png?v=1583265518">
                    </div>
                    <div class="header_searchbar web_header_searchbar">
                        <input type="text" name="" placeholder="Search">
                        <button class="btn-color btn-text"><i class="fas fa-search"></i></button>
                    </div>
                    <div class="header_cart">
                        <a href="#">
                            <div class="cart_icon"><i class="fas fa-shopping-cart"></i>
                                 <span class="cart_counter btn-color btn-text">3</span> 
                            </div>
                            <p>Cart</p>
                        </a>
                    </div>
                    <div class="header_searchbar mobile_header_searchbar">
                        <input type="text" name="" placeholder="Search">
                        <button class="btn-color btn-text"><i class="fas fa-search"></i></button>
                    </div>
                </div>
            </div>

             mobile menu 
            <div class="sideoff-off">
                 <div  class="navbar-header"><a  class="navbar-toggle mobile-menu-style dash-navbar-toggle" id="slideclose"><i class="fas fa-times"></i></a></div> 
                <ul class="res-navbar-nav ml-auto res-dash-nav">
                    <li class="nav-item ">
                        <a class="nav-link" href="#">Home</a>
                    </li>
                    <li class="nav-item ">
                        <a class="nav-link" href="#">COVID-19 SUPPORT</a>
                    </li>
                    <li class="nav-item ">
                        <a class="nav-link" href="#">Our Solutions</a>
                    </li>
                    <li class="nav-item ">
                        <a class="nav-link" href="#">Markets</a>
                    </li>
                    <li class="nav-item ">
                        <a class="nav-link" href="#">Software Solutions</a>
                    </li>
                    <li class="nav-item ">
                        <a class="nav-link" href="#">Purchase</a>
                    </li>
                    <li class="nav-item ">
                        <a class="nav-link" href="#">About</a>
                    </li>
                    <li class="nav-item ">
                        <a class="nav-link" href="#">Contact</a>
                    </li>
                    <li class="nav-item ">
                        <a class="nav-link" href="#">1 (866) 545-3149</a>
                    </li>
                </ul>
            </div>
        </div>
        <nav class="navbar navbar-expand-lg  menu-bg menu-text menu-hover-text" id="main_nvigation">
            <div class="container-fluid">
                <div class="collapse navbar-collapse" id="navbarResponsive">
                    <ul class="navbar-nav">
                        <li class="nav-item ">
                            <a class="nav-link" href="#">Home</a>
                        </li>
                        <li class="nav-item ">
                            <a class="nav-link" href="#">COVID-19 SUPPORT</a>
                        </li>
                        <li class="nav-item ">
                            <a class="nav-link" href="#">Our Solutions</a>
                        </li>
                        <li class="nav-item ">
                            <a class="nav-link" href="#">Markets</a>
                        </li>
                        <li class="nav-item ">
                            <a class="nav-link" href="#">Software Solutions</a>
                        </li>
                        <li class="nav-item ">
                            <a class="nav-link" href="#">Purchase</a>
                        </li>
                        <li class="nav-item ">
                            <a class="nav-link" href="#">About</a>
                        </li>
                        <li class="nav-item ">
                            <a class="nav-link" href="#">Contact</a>
                        </li>
                        <li class="nav-item ">
                            <a class="nav-link" href="#">1 (866) 545-3149</a>
                        </li>
                    </ul>
                </div>
            </div>
        </nav>

        
    </div>
     <section>
        
    </section> 
</div>-->
<!-- <div class="main_header without_login_header">
    <div class="top_menu">
        <div class="middel_header header-bg header-text header-hover-text"><div>
        @include('frontend.home.common.header')
    </div>
</div> -->

    <div class="login_header">
<?php $agencydata=get_agency_data(); ?>
       
<div class="main_header  frontend_main_header property_main_header">
    <div class="overlay" onclick="closeOverlay()"></div>
    <nav class="navbar navbar-expand-lg top_menu property_nav fixed-top header-bg header-text header-hover-text" id="main_nvigation">
        <div class="container-fluid">
            <div class="logo_img">
                <a href="{{route('user.home')}}">
                    @if(!empty($adminDetails['website_logo']))
                        <img src="{{  url('public/uploads/common_settings').'/'.$adminDetails['website_logo'] }}" id="main_wb_logo" alt="logo">
                     @else
                        <img src="{{ url('public/admin/dist/img/realtorhubs.png') }}" id="main_wb_logo" alt="logo">
                    @endif
                </a>
            </div>
            <a href="javascript:void(0);" id="slide" class="navbar-toggle"><i class="fas fa-bars mobile_menu_icon"></i></a>
            <div class="collapse navbar-collapse" id="navbarResponsive">
                <ul class="navbar-nav ml-auto menu-center" id="menu-center">
                    <li class="nav-item">
                        <a class="nav-link  hvr-underline-from-left @if(Request::url() == url('/'))active @endif" href="{{route('user.home')}}">Home</a> 
                    </li>
                    <li class="nav-item">
                        <a class="nav-link  hvr-underline-from-left @if(Request::url() == url('/about-us'))active @endif" href="{{route('user.about.us')}}">About Us</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link  hvr-underline-from-left @if(Request::url() == url('/pricing'))active @endif" href="{{route('user.home')}}#pricing">Pricing</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link  hvr-underline-from-left @if(Request::url() == url('/blogs'))active @endif" href="{{route('user.blog.home.list')}}">Blog</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link  hvr-underline-from-left @if(Request::url() == url('/contact-us'))active @endif" href="{{route('user.contact.us')}}">Contact Us</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link login_link @if(Request::url() == url('/agency/login'))active @endif" href="{{route('agency.login.page')}}" >Login</a>
                    </li>
                    <li class="nav-item ">
                        <a class="nav-link sign_link @if(Request::url() == url('/agency/signup'))active @endif" href="{{route('agency.signup')}}">Signup</a>
                    </li>
                </ul>
            </div>
        </div>
    </nav>
    <section>
        <div class="sideoff-off">
            <div class="navbar-header"><a class="navbar-toggle mobile-menu-style" id="slideclose"><i class="fas fa-times"></i></a></div>
            <ul class="ml-auto responsive_menu">
                <li class="nav-item">
                    <a class="nav-link  hvr-underline-from-left @if(Request::url() == url('/'))active @endif" href="{{route('user.home')}}">Home</a> 
                </li>
                <li class="nav-item">
                    <a class="nav-link  hvr-underline-from-left @if(Request::url() == url('/about-us'))active @endif" href="{{route('user.about.us')}}">About Us</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link  hvr-underline-from-left @if(Request::url() == url('/pricing'))active @endif" href="{{route('user.home')}}#pricing">Pricing</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link  hvr-underline-from-left @if(Request::url() == url('/blogs'))active @endif" href="{{route('user.blog.home.list')}}">Blog</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link  hvr-underline-from-left @if(Request::url() == url('/contact-us'))active @endif" href="{{route('user.contact.us')}}">Contact Us</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link login_link @if(Request::url() == url('/agency/login'))active @endif" href="{{route('agency.login.page')}}" >Login</a>
                    <a class="nav-link sign_link @if(Request::url() == url('/agency/signup'))active @endif" href="{{route('agency.signup')}}">Signup</a>
                </li>
                        
            </ul>
        </div>
    </section>
</div>
</div>
@yield('content')
 <?php $general_settings = getGeneralSettings(); 
?>
<footer>
    <div class="top_footer text-center footer-bg footer-text footer-hover-text">
        <div class="footer_link footer_social_link">
            <ul>
                @if(isset($general_settings['home_facebook_url_link'])&&!empty($general_settings['home_facebook_url_link']))
                <li>
                    <a href="{{$general_settings['home_facebook_url_link']}}" class="fb_icon"><i class="fab fa-facebook-f"></i></a>
                </li>
                @endif
                
                @if(isset($general_settings['home_twitter_url_link'])&&!empty($general_settings['home_twitter_url_link']))
                <li>
                    <a href="{{$general_settings['home_twitter_url_link']}}" class="twitter_icon"><i class="fab fa-twitter"></i></a>
                </li>
                @endif
                
                @if(isset($general_settings['home_instagram_url_link'])&&!empty($general_settings['home_instagram_url_link']))
                <li>
                    <a href="{{$general_settings['home_instagram_url_link']}}" class="insta_icon"><i class="fab fa-instagram"></i></a>
                </li>
                @endif
                
                @if(isset($general_settings['home_youtube_url_link'])&&!empty($general_settings['home_youtube_url_link']))
                <li>
                    <a href="{{$general_settings['home_youtube_url_link']}}" class="youtume_icon"><i class="fab fa-youtube"></i></a>
                </li>
                @endif
                
                @if(isset($general_settings['home_linkedin_url_link'])&&!empty($general_settings['home_linkedin_url_link']))
                <li>
                    <a href="{{$general_settings['home_linkedin_url_link']}}" class="linkedin_icon"><i class="fab fa-linkedin-in"></i></a>
                </li>
                @endif
            </ul>
        </div>
        <div class="footer_link">
            <ul>
                <li>
                    <a href="{{route('user.terms.condition')}}" class="@if(Request::url() == url('/terms-condition'))active @endif">Terms &amp; Conditions</a>
                </li>
                <li>
                    <a href="{{route('user.privacy.policy')}}" class="@if(Request::url() == url('/privacy-policy'))active @endif">Privacy Policy</a>
                </li>
                <li>
                    <a href="{{route('user.faqs')}}" class="@if(Request::url() == url('/faqs'))active @endif">FAQs</a>
                </li>
                <li>
                    <a href="{{route('user.contact.us')}}" class="@if(Request::url() == url('/contact-us'))active @endif">Contact Us</a>
                </li>
            </ul>
        </div>
    </div>
    <div class="bottom_footer">
        <div class="container-fluid">
            <div class="row">
                <div class="col-md-8 text-md-left text-center">
                    <p>© <?= date('Y') ?> Digiteau. All Rights Reserved </p>
                </div>
                <div class="col-md-4 text-md-right text-center">
                    <p>Division of <a href="https://digiteau.com/">Digiteau</a></p>
                </div>
            </div>
        </div>
    </div>
</footer>
<!-- /.login-box -->
<!--<footer>
    <div class="top_footer text-center footer-bg footer-text footer-hover-text">
        <div class="footer_link footer_social_link">
            <ul>
                <li>
                    <a href="#" class="fb_icon"><i class="fab fa-facebook-f"></i></a>
                </li>
                <li>
                    <a href="#" class="twitter_icon"><i class="fab fa-twitter"></i></a>
                </li>
                <li>
                    <a href="#" class="insta_icon"><i class="fab fa-instagram"></i></a>
                </li>
                <li>
                    <a href="#" class="youtume_icon"><i class="fab fa-youtube"></i></a>
                </li>
                <li>
                    <a href="#" class="linkedin_icon"><i class="fab fa-linkedin-in"></i></a>
                </li>
            </ul>
        </div>
        <div class="footer_link">
            <ul>
                <li>
                    <a href="#">Search</a>
                </li>
                <li>
                    <a href="#">Catalog</a>
                </li>
                <li>
                    <a href="#">Privacy Policy</a>
                </li>
                <li>
                    <a href="#">Terms & Conditions</a>
                </li>
            </ul>
        </div>
    </div>
    <div class="bottom_footer">
        <div class="container-fluid">
            <div class="row">
                <div class="col-md-8 text-md-left text-center">
                    <p>© 2020 Digiteau. All Rights Reserved </p>
                </div>
                <div class="col-md-4 text-md-right text-center">
                    <p>Designed by <a href="#">Technource</a></p>
                </div>
            </div>
        </div>
    </div>
</footer>-->



<!-- <script src="{{ url('public/admin/bower_components/jquery/dist/jquery.min.js') }}"></script> -->
<script type="text/javascript" src="{{ url('public/assets/js/jquery-3.4.1.min.js') }}"></script>
<script type="text/javascript" src="{{ url('public/assets/js/all.js') }}"></script>
<script type="text/javascript" src="{{ url('public/assets/js/wow.min.js') }}"></script>
<script type="text/javascript" src="{{ url('public/assets/js/owl.carousel.js') }}"></script>
<script type="text/javascript" src="{{ url('public/assets/js/popper.min.js') }}"></script>
<script type="text/javascript" src="{{ url('public/assets/js/bootstrap.min.js') }}"></script>
<script type="text/javascript" src="{{ url('public/assets/js/custom.js') }}"></script>  
<script src="{{ url('public/js/jquery.validate.js') }}"></script>
<script src="{{ url('public/js/promise.min.js') }}"></script>
<script src="{{ url('public/js/additional-methods.js') }}"></script>
<script src="{{ url('public/js/toastr.min.js') }}"></script>
<script src="{{ url('public/js/custom-develop') }}"></script>
<script type="text/javascript" src="{{ url('public/admin/') }}/plugins/select2/select2.full.min.js"></script>

<script type="text/javascript">
    var base_url = '<?php echo url("/public/") ?>';
    var base_url_route = '<?php echo url("/") ?>';

    wow = new WOW({
        animateClass: 'animated',
        offset:       100,
        callback:     function(box) {
          console.log("WOW: animating <" + box.tagName.toLowerCase() + ">")
        }
    });
    wow.init();
</script>   

@stack('custom-scripts')
</body>
</html>