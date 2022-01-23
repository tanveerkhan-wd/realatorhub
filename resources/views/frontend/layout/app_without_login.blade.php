<?php 
$adminDetails=getSettings();
$agencyDeatils=get_agency_data();
$slug=Session::get('slug');
$agency_id=Session::get('agency_id');
//echo "<pre>"; print_r($adminDetails); exit;
?>
<!DOCTYPE html>
<html>
<head>
    <!-- <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge"> -->
   <!-- <title>Realtorhubs | @yield('title')</title> -->
    <!-- Tell the browser to be responsive to screen width -->
    <?php
    if(!empty($adminDetails) && !empty($adminDetails['favicon_icon'])){
        $favicon = $adminDetails['favicon_icon'];
    }
    else{
        $favicon = '';
    }

    ?>
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
    <link rel="stylesheet" type="text/css" href="{{ url('public/assets/css/dashboard.css') }}">
    <link rel="stylesheet" type="text/css" href="{{ url('public/assets/css/custom.css') }}">
    <link rel="stylesheet" type="text/css" href="{{ url('public/assets/css/style.css') }}">
    <link rel="stylesheet" href="{{ url('public/css/toastr.min.css') }}">
    <link href="{{ url('public/admin/') }}/plugins/select2/select2.min.css" rel="stylesheet">
    <link rel="stylesheet" href="{{ url('public/css/sweetalert2.min.css') }}">
    <link rel="stylesheet" href="{{ url('public/css/toastr.min.css') }}">
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
<div class="inner-container sticky_footer sticky_frontend_footer">
@include('frontend.common.header')
@yield('content')
@include('frontend.common.footer')

<!-- /.login-box -->
</div>

<!-- jQuery 3 -->
<!-- <script src="{{ url('public/admin/bower_components/jquery/dist/jquery.min.js') }}"></script> -->
<!-- Bootstrap 3.3.7 -->
<!-- <script src="{{ url('public/admin/bower_components/bootstrap/dist/js/bootstrap.min.js') }}"></script> -->
<!-- iCheck -->

<script type="text/javascript" src="{{ url('public/assets/js/jquery-3.4.1.min.js') }}"></script>
<script type="text/javascript" src="{{ url('public/assets/js/all.js') }}"></script>
<script type="text/javascript" src="{{ url('public/assets/js/wow.min.js') }}"></script>
<script type="text/javascript" src="{{ url('public/assets/js/owl.carousel.js') }}"></script>
<script type="text/javascript" src="{{ url('public/assets/js/popper.min.js') }}"></script>
<script type="text/javascript" src="{{ url('public/assets/js/bootstrap.min.js') }}"></script>
<script type="text/javascript" src="{{ url('public/assets/js/custom.js') }}"></script>  
<script src="{{ url('public/js/sweetalert2.min.js') }}"></script>
<script src="{{ url('public/js/jquery.validate.js') }}"></script>
<script src="{{ url('public/js/promise.min.js') }}"></script>
<script src="{{ url('public/js/additional-methods.js') }}"></script>
<script src="{{ url('public/js/toastr.min.js') }}"></script>
<script src="{{ url('public/js/custom-develop.js') }}"></script>
<script type="text/javascript" src="{{ url('public/admin/') }}/plugins/select2/select2.full.min.js"></script>

<script
        src="https://code.jquery.com/ui/1.11.4/jquery-ui.min.js"
        integrity="sha256-xNjb53/rY+WmG+4L6tTl9m6PpqknWZvRt0rO1SRnJzw="
        crossorigin="anonymous"></script>

<script>
    $(function () {
        /*$('input').iCheck({
            checkboxClass: 'icheckbox_square-blue',
            radioClass: 'iradio_square-blue',
            increaseArea: '20%' 
        });*/

        setTimeout(function(){
            $('.alert.alert-success').fadeOut( "slow", function() {});
            $('.alert.alert-danger').fadeOut( "slow", function() {});
        },2500);
    });
</script>  
<script src="https://developers.google.com/maps/documentation/javascript/examples/markerclusterer/markerclusterer.js"></script>
<!-- Replace the value of the key parameter with your own API key. -->
<script type="text/javascript" src="https://maps.googleapis.com/maps/api/js?key=<?= $adminDetails['google_map_api_key']?>&libraries=places&sensor=false"></script>
<script type="text/javascript">
    wow = new WOW({
        animateClass: 'animated',
        offset:       100,
        callback:     function(box) {
          console.log("WOW: animating <" + box.tagName.toLowerCase() + ">")
        }
    });
    wow.init();
    var base_url = '<?php echo url("/public/") ?>';
    var base_url_route = '<?php echo url("/") ?>';
</script>   

@stack('custom-scripts')
</body>
</html>