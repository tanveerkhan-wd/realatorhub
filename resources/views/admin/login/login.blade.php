@extends('admin.layout.app_without_login')
@section('title','Login')
@section('content')
    <!--
View File for Login Page
@package    Laravel
@subpackage View
@since      1.0
 -->
    <!-- <div class="login-box-body"> -->
        @if ($errors->any())
            <div class="alert alert-danger">
                <a href="#" class="close" data-dismiss="alert" aria-label="close">&times;</a>
                <ul>
                    @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        @if (\Session::has('success'))
            <div class="alert alert-success">
                <a href="#" class="close" data-dismiss="alert" aria-label="close">&times;</a>
                <ul>
                    <li>{!! \Session::get('success') !!}</li>
                </ul>
            </div>
        @endif

        <p class="login-box-msg">Admin Panel</p>

        <form action="{{ route('admin.login') }}" method="post" id="loginForm" name="loginForm">
            {{ csrf_field() }}
            <div class="form-group has-feedback">
                <input type="email" class="form-control" placeholder="Email" name="email" id="email">
                <span class="glyphicon glyphicon-envelope form-control-feedback"></span>
                <div id="email_validate"></div>
            </div>
            <div class="form-group has-feedback">
                <input type="password" class="form-control" name="password" id="password" placeholder="Password">
                <span class="glyphicon glyphicon-lock form-control-feedback"></span>
                <div id="password_validate"></div>
            </div>
            <div class="row">
                <div class="col-xs-8">
                    <div class="checkbox icheck">
                        <label>
                            <input type="checkbox" name="remember_me" id="remember_me" value="1"> Remember Me
                        </label>
                    </div>
                </div>
                <!-- /.col -->
                <div class="col-xs-4">
                    <button type="submit" class="btn btn-primary btn-block btn-flat">Sign In</button>
                </div>
                <!-- /.col -->
            </div>
        </form>
        <!-- /.social-auth-links -->
        <div class="text-center" style="margin-top: 20px;">
            <a href="{{ url('admin/forgotPass') }}" class="blue_link">Forgot Password?</a>
        </div>
        <br>
    <!-- </div> -->
@endsection
@push('custom-scripts')
<script type="text/javascript" src="{{ url('public/js/admin/login/login.js') }}"></script>
@endpush