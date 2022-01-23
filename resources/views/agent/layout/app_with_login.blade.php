<?php $userDetails=checkAgencyLogin();
$adminDetails=getSettings();
?>
<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ @csrf_token() }}">
    <title>Realtorhubs | @yield('title')</title>
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
    <link rel="stylesheet" type="text/css" href="{{ url('public/assets/css/bootstrap.min.css')}}">
    <link rel="stylesheet" type="text/css" href="{{ url('public/assets/css/fontawesome.css')}}">
    <link rel="stylesheet" type="text/css" href="{{ url('public/assets/css/animate.css')}}">
    <link rel="stylesheet" type="text/css" href="{{ url('public/assets/css/owl.carousel.min.css')}}">
    <link rel="stylesheet" type="text/css" href="{{ url('public/assets/css/owl.theme.default.min.css')}}">
    <link rel="stylesheet" type="text/css" href="{{ url('public/assets/css/dashboard.css')}}">
    <link rel="stylesheet" type="text/css" href="{{ url('public/assets/css/custom.css')}}">
    <link rel="stylesheet" type="text/css" href="{{ url('public/assets/css/style.css')}}">
    <link href="{{ url('public/admin/') }}/plugins/datatables/dataTables.bootstrap.css" rel="stylesheet">
    <link href="{{ url('public/admin/') }}/plugins/select2/select2.min.css" rel="stylesheet">
    <link rel="stylesheet" href="{{ url('public/css/sweetalert2.min.css') }}">
    <link rel="stylesheet" href="{{ url('public/css/toastr.min.css') }}">
    <script type="text/javascript">
        var current_room_id='';
    </script>
    @stack('custom-styles')

    @include('general_settings.style')
</head>

<body class="dashboard_page">
    <div class="loader-outer-container" id="pageLoadingDiv" style=" display: none; ">
        <div class="laoder-inner-container">
            <div class="loader">
                <i class="fas fa-circle-notch animation rotateleft"></i>
                <p class="loader-text" id="loadingText">Loading...</p>
            </div>
        </div>
    </div>
    <div class="inner_container">
        <div class="dashboard">
            <div class="overlay" onclick="closeOverlay()"></div>

@include('agent.common.header')
<!-- Left side column. contains the logo and sidebar -->
@include('agent.common.sidebar')

<!-- Content Wrapper. Contains page content -->
    <div class="side_content">
        @yield('content')
    </div>
    </div>
    <!-- /.content-wrapper -->

        </div>
    </div>

<script type="text/javascript" src="{{ url('public/assets/js/all.js')}}"></script>
    <script type="text/javascript" src="{{ url('public/assets/js/jquery-3.4.1.min.js')}}"></script>
    <script type="text/javascript" src="{{ url('public/assets/js/wow.min.js')}}"></script>
    <script type="text/javascript" src="{{ url('public/assets/js/owl.carousel.js')}}"></script>
    <script type="text/javascript" src="{{ url('public/assets/js/popper.min.js')}}"></script>
    <script type="text/javascript" src="{{ url('public/assets/js/bootstrap.min.js')}}"></script>
    <script type="text/javascript" src="{{ url('public/assets/js/custom.js')}}"></script>  
    <script src="{{ url('public/js/sweetalert2.min.js') }}"></script>
    <script src="{{ url('public/js/jquery.validate.js') }}"></script>
    <script src="{{ url('public/js/promise.min.js') }}"></script>
    <script src="{{ url('public/js/additional-methods.js') }}"></script>
    <script src="{{ url('public/js/toastr.min.js') }}"></script>
    <script src="{{ url('public/js/custom-develop.js') }}"></script>
    <script type="text/javascript" src="{{ url('public/admin/') }}/plugins/datatables/jquery.dataTables.min.js"></script>
<script type="text/javascript" src="{{ url('public/admin/') }}/plugins/datatables/dataTables.bootstrap.min.js"></script>
<script type="text/javascript" src="{{ url('public/admin/') }}/plugins/datatables/jquery.dataTables.columnFilter.js"></script>
<script type="text/javascript" src="{{ url('public/admin/') }}/plugins/select2/select2.full.min.js"></script>
    <script type="text/javascript" src="{{ url('public/admin/') }}/plugins/moment/moment.min.js"></script>
    <script type="text/javascript" src="https://cdnjs.cloudflare.com/ajax/libs/moment-timezone/0.5.31/moment-timezone-with-data.js"></script>
    <script type="text/javascript" src="{{ url('public/admin/') }}/plugins/daterangepicker/daterangepicker.min.js"></script>
    <link rel="stylesheet" type="text/css" href="https://cdn.jsdelivr.net/npm/daterangepicker/daterangepicker.css" />


    <script type="text/javascript">
        var base_url = '<?php echo url("/public/") ?>';
        var base_url_route = '<?php echo url("/") ?>';
        var timezone = '<?= getCurrentUserTimeZone(Auth::user()->id) ?>';
        var system_timezone = '<?= config('app.timezone'); ?>';
        var moment= moment.tz.setDefault(system_timezone);
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
