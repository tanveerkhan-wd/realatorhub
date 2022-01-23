<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="csrf-token" content="{{ @csrf_token() }}">

    <title>Realtorhubs | @yield('title')</title>
    <!-- Tell the browser to be responsive to screen width -->
    <meta content="width=device-width, initial-scale=1, maximum-scale=1, user-scalable=no" name="viewport">
    <!-- Bootstrap 3.3.7 -->
    <link rel="stylesheet" href="{{ url('public/admin/bower_components/bootstrap/dist/css/bootstrap.min.css') }}">
    <!-- Font Awesome -->
    <link rel="stylesheet" href="{{ url('public/admin/bower_components/font-awesome/css/font-awesome.min.css') }}">
    <!-- Ionicons -->
    <link rel="stylesheet" href="{{ url('public/admin/bower_components/Ionicons/css/ionicons.min.css') }}">
    <!-- jvectormap -->
    <link rel="stylesheet" href="{{ url('public/admin/bower_components/jvectormap/jquery-jvectormap.css') }}">
    <link rel="stylesheet" type="text/css" href="{{ url('public/admin/bower_components/bootstrap-datepicker/dist/css/bootstrap-datepicker.css') }}">
    <!-- Theme style -->
    <link rel="stylesheet" href="{{ url('public/admin/dist/css/AdminLTE.css') }}">
    <!-- AdminLTE Skins. Choose a skin from the css/skins
         folder instead of downloading all of them to reduce the load. -->
    <link rel="stylesheet" href="{{ url('public/admin/dist/css/skins/_all-skins.css') }}">

    <link rel="stylesheet" href="{{ url('public/css/sweetalert2.min.css') }}">
    <link rel="stylesheet" href="{{ url('public/css/toastr.min.css') }}">

    <link rel="stylesheet" href="{{ url('public/admin/dist/css/custom.css') }}">

    <?php
    $user_id = \Illuminate\Support\Facades\Auth::user()->id;
    $getSettingData = getSettings($user_id);
    if(!empty($getSettingData) && !empty($getSettingData['admin_logo'])){
        $website_logo = $getSettingData['admin_logo'];
    }
    else{
        $website_logo = '';
    }
    if(!empty($getSettingData) && !empty($getSettingData['favicon_icon'])){
        $favicon = $getSettingData['favicon_icon'];
    }
    else{
        $favicon = '';
    }

    ?>
    @if(!empty($favicon))
    <link rel="icon" href="{{ url('public/uploads/common_settings').'/'.$favicon }}" type="image/ico" sizes="16x16">
    @endif

    <link href="{{ url('public/admin/') }}/plugins/datatables/dataTables.bootstrap.css" rel="stylesheet">
    <link href="{{ url('public/admin/') }}/plugins/select2/select2.min.css" rel="stylesheet">

    <!-- HTML5 Shim and Respond.js IE8 support of HTML5 elements and media queries -->
    <!-- WARNING: Respond.js doesn't work if you view the page via file:// -->
    <!--[if lt IE 9]>
    <script src="https://oss.maxcdn.com/html5shiv/3.7.3/html5shiv.min.js"></script>
    <script src="https://oss.maxcdn.com/respond/1.4.2/respond.min.js"></script>
    <![endif]-->

    <!-- Google Font -->
    <link rel="stylesheet"
          href="https://fonts.googleapis.com/css?family=Source+Sans+Pro:300,400,600,700,300italic,400italic,600italic">
    {{--<link rel="stylesheet" type="text/css" href={{ url('public/admin/') }}/plugins/daterangepicker/daterangepicker.css" />--}}
    <style type="text/css">
        .alert a{
            text-decoration: none;
        }
        .alert{
            margin: 25px;
        }
    </style>
    @include('general_settings.style')

    @stack('custom-styles')
</head>
<body class="hold-transition sidebar-mini">
<!-- Use this Layout when user Login -->
<div class="loader-outer-container" id="pageLoadingDiv" style=" display: none; ">
    <div class="laoder-inner-container">
        <div class="loader">
            <i class="fa fa-circle-o-notch fa-spin fa-fw"></i>
            <p class="loader-text" id="loadingText">Loading...</p>
        </div>
    </div>
</div>

<div class="wrapper">

@include('admin.common.header')
<!-- Left side column. contains the logo and sidebar -->
@include('admin.common.sidebar')

<!-- Content Wrapper. Contains page content -->
    <div class="content-wrapper">
        @yield('content')
    </div>
    <!-- /.content-wrapper -->

</div>
<!-- ./wrapper -->

<!-- jQuery 3 -->
<script src="{{ url('public/admin/bower_components/jquery/dist/jquery.min.js') }}"></script>
<!-- Bootstrap 3.3.7 -->
<script src="{{ url('public/admin/bower_components/bootstrap/dist/js/bootstrap.min.js') }}"></script>
<!-- FastClick -->
<script src="{{ url('public/admin/bower_components/fastclick/lib/fastclick.js') }}"></script>
<!-- AdminLTE App -->
<script src="{{ url('public/admin/dist/js/adminlte.min.js') }}"></script>
<!-- Sparkline -->
<script src="{{ url('public/admin/bower_components/jquery-sparkline/dist/jquery.sparkline.min.js') }}"></script>
<!-- jvectormap  -->
<script src="{{ url('public/admin/plugins/jvectormap/jquery-jvectormap-1.2.2.min.js') }}"></script>
<script src="{{ url('public/admin/plugins/jvectormap/jquery-jvectormap-world-mill-en.js') }}"></script>
<!-- SlimScroll -->
<script src="{{ url('public/admin/bower_components/jquery-slimscroll/jquery.slimscroll.min.js') }}"></script>
<!-- ChartJS -->
<script src="{{ url('public/admin/bower_components/chart.js/Chart.js') }}"></script>
<script type="text/javascript" src="{{ url('public/admin/') }}/plugins/moment/moment.min.js"></script>
<script type="text/javascript" src="https://cdnjs.cloudflare.com/ajax/libs/moment-timezone/0.5.31/moment-timezone-with-data.js"></script>

<!-- AdminLTE for demo purposes -->
<script src="{{ url('public/admin/dist/js/demo.js') }}"></script>
<script src="{{ url('public/js/jquery.validate.js') }}"></script>
<script src="{{ url('public/js/sweetalert2.min.js') }}"></script>
<script src="{{ url('public/js/toastr.min.js') }}"></script>
<script src="{{ url('public/js/promise.min.js') }}"></script>
<script src="{{ url('public/js/additional-methods.js') }}"></script>

<script type="text/javascript" src="{{ url('public/admin/') }}/plugins/datatables/jquery.dataTables.min.js"></script>
<script type="text/javascript" src="{{ url('public/admin/') }}/plugins/datatables/dataTables.bootstrap.min.js"></script>
<script type="text/javascript" src="{{ url('public/admin/') }}/plugins/datatables/jquery.dataTables.columnFilter.js"></script>
<script type="text/javascript" src="{{ url('public/admin/') }}/plugins/select2/select2.full.min.js"></script>
<script type="text/javascript" src="{{ url('public/admin/') }}/plugins/daterangepicker/daterangepicker.min.js"></script>
<script type="text/javascript" src="{{ url('public/js/') }}/admin/custom-develop.js"></script>
<link rel="stylesheet" type="text/css" href="https://cdn.jsdelivr.net/npm/daterangepicker/daterangepicker.css" />
<script
        src="https://code.jquery.com/ui/1.11.4/jquery-ui.min.js"
        integrity="sha256-xNjb53/rY+WmG+4L6tTl9m6PpqknWZvRt0rO1SRnJzw="
        crossorigin="anonymous"></script>
<script type="text/javascript" src="{{ url('public/admin/bower_components/bootstrap-datepicker/dist/js/bootstrap-datepicker.js') }}"></script>


<script type="text/javascript">
    var base_url = '{{ url('/') }}';
    var timezone = '<?= getCurrentUserTimeZone(Auth::user()->id) ?>';
    var system_timezone = '<?= config('app.timezone'); ?>';
    var moment= moment.tz.setDefault(system_timezone);
    $(document).ready(function () {
        $('.checkActive').find('.active').closest('.treeview').addClass('active');
        setTimeout(function(){
            $('.alert.alert-success').fadeOut( "slow", function() {});
            $('.alert.alert-danger').fadeOut( "slow", function() {});
        },2500)
    });
    $('.select2').select2();
    // $('meta[name="viewport"]').prop('content', 'width=1440');
    $.fn.digits = function(){
        return this.each(function(){
            $(this).text( $(this).text().replace(/(\d)(?=(\d\d\d)+(?!\d))/g, "$1,") );
        })
    }
    setTimeout(function(){
        $('td.amountVal').digits();
    },1000);

    function digitAdd(){
        setTimeout(function(){
            $('td.amountVal').digits();
        },1000);
    }



</script>

@stack('custom-scripts')
</body>
</html>
