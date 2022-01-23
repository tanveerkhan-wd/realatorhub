<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <title>Realtorhubs | @yield('title')</title>
    <!-- Tell the browser to be responsive to screen width -->
    <meta content="width=device-width, initial-scale=1, maximum-scale=1, user-scalable=no" name="viewport">
    <!-- Bootstrap 3.3.7 -->
    <link rel="stylesheet" href="{{ url('public/admin/bower_components/bootstrap/dist/css/bootstrap.min.css') }}">
    <!-- Font Awesome -->
    <link rel="stylesheet" href="{{ url('public/admin/bower_components/font-awesome/css/font-awesome.min.css') }}">
    <!-- Ionicons -->
    <link rel="stylesheet" href="{{ url('public/admin/bower_components/Ionicons/css/ionicons.min.css') }}">
    <!-- Theme style -->
    <link rel="stylesheet" href="{{ url('public/admin/dist/css/AdminLTE.min.css') }}">
    <!-- iCheck -->
    <link rel="stylesheet" href="{{ url('public/admin/plugins/iCheck/square/blue.css') }}">
    <!-- Google Font -->
    <link rel="stylesheet" href="https://fonts.googleapis.com/css?family=Source+Sans+Pro:300,400,600,700,300italic,400italic,600italic">

    <link rel="stylesheet" href="{{ url('public/admin/dist/css/custom.css') }}">
    <link rel="stylesheet" href="{{ url('public/admin/dist/css/withoutlogin.css') }}">
    <?php
    $user_id =1;
    $getSettingData = getSettings($user_id);
    //echo "<pre>"; print_r($getSettingData); exit;
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
    <style type="text/css">
        .alert a{
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
        }
    </style>
    @include('general_settings.style')

    @stack('custom-styles')
</head>
<body class="hold-transition login-page">
<!-- Use this Layout when user not Login -->
<?php
//$getSettingData = getMenu();
?>
<div class="login-box">
    <div class="login-box-body">
        <div class="login-logo">
            @if(!empty($website_logo))
                <img src="{{  url('public/uploads/common_settings/'.$website_logo) }}" class="logo_img">
            @else
                <img src="{{ url('public/admin/dist/img/realtorhubs.png') }}" class="logo_img">
            @endif
        </div>
        @yield('content')
    </div>
<!-- /.login-box -->
</div>

<!-- jQuery 3 -->
<script src="{{ url('public/admin/bower_components/jquery/dist/jquery.min.js') }}"></script>
<!-- Bootstrap 3.3.7 -->
<script src="{{ url('public/admin/bower_components/bootstrap/dist/js/bootstrap.min.js') }}"></script>
<!-- iCheck -->
<script src="{{ url('public/admin/plugins/iCheck/icheck.min.js') }}"></script>

<script
        src="https://code.jquery.com/ui/1.11.4/jquery-ui.min.js"
        integrity="sha256-xNjb53/rY+WmG+4L6tTl9m6PpqknWZvRt0rO1SRnJzw="
        crossorigin="anonymous"></script>

<script>
    $(function () {
        $('input').iCheck({
            checkboxClass: 'icheckbox_square-blue',
            radioClass: 'iradio_square-blue',
            increaseArea: '20%' /* optional */
        });

        setTimeout(function(){
            $('.alert.alert-success').fadeOut( "slow", function() {});
            $('.alert.alert-danger').fadeOut( "slow", function() {});
        },2500);
    });
    // $('meta[name="viewport"]').prop('content', 'width=1440');
</script>

<script src="{{ url('public/js/jquery.validate.js') }}"></script>
<script src="{{ url('public/js/promise.min.js') }}"></script>
<script src="{{ url('public/js/additional-methods.js') }}"></script>


@stack('custom-scripts')
</body>
</html>