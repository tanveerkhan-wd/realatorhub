@extends('frontend.layout.app_without_login')
@section('title','Login')
@section('content')
<section class="grey_bg auth_section">
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
    <div class="container">
        <div class="row">
            <div class="col-lg-1"></div>
            <div class="col-lg-10">
                <div class="white_box auth_box">
                    <div class="row">
                        <div class="col-lg-3"></div>
                        <div class="col-lg-6">
                            <div class="text-center">
                                <h1 class="auth_title">Login</h1>
                            </div>
                            <form action="{{ url('/'.$slug.'/loginPost') }}" method="post" id="loginForm" name="loginForm">
                                {{ csrf_field() }}
                                <div class="form-group">
                                    <label>Email ID</label>
                                    <input type="email" class="form-control" placeholder="Enter Email ID" name="email" id="email">
                                    <!-- <span class="glyphicon glyphicon-envelope form-control-feedback"></span> -->
                                    <div id="email_validate"></div>
                                </div>
                                <div class="form-group mb-0">
                                    <label>Password</label>
                                    <input type="password" class="form-control" name="password" id="password" placeholder="Enter password">
                                    <!-- <span class="glyphicon glyphicon-lock form-control-feedback"></span> -->
                                    <div id="password_validate"></div>
                                </div>
                                <div class="text-center auth_link">
                                    <a href="{{ url('/'.$slug.'/forgotPass') }}">Forgot Password?</a>
                                </div>
                                <div class="text-center">
                                    <button type="submit" class="theme-btn btn-color btn-text btn-size auth_btn">Login</button>
                                </div>
                                <div class="bottom-link text-center">
                                    <p>Don’t have account?<a href="{{ url('/'.$slug.'/signup') }}"> Sign Up</a></p>
                                </div> 
                            </form>
                        </div>
                        <div class="col-lg-3"></div>
                    </div>
                </div>
            </div>
            <div class="col-lg-1"></div>
        </div>
    </div>
</section>
@endsection
@push('custom-scripts')
<script type="text/javascript" src="{{ url('public/js/admin/login/login.js') }}"></script>
@endpush