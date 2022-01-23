@extends('agency.layout.app_without_login')
@section('title','Forgot Password')
@section('content')
<!-- 
View File for Reset Password
@package    Laravel
@subpackage View
@since      1.0
 -->
<section class="grey_bg auth_section">
  <!-- /.login-logo -->
  
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
    <div class="container">
        <div class="row">
            <div class="col-lg-1"></div>
            <div class="col-lg-10">
                <div class="white_box auth_box">
                    <div class="row">
                        <div class="col-lg-3"></div>
                        <div class="col-lg-6">
                            <div class="text-center">
                                <h1 class="auth_title">Forgot Password</h1>
                            </div>
                            <form action="{{ url('agency/forgotPasswordPost') }}" method="post" id="loginForm" name="loginForm">
                                {{ csrf_field() }}
                                <div class="form-group has-feedback">
                                    <label>Email ID</label>
                                    <input type="email" class="form-control" placeholder="Email" name="email" id="email">
                                    <div id="email_validate"></div>
                                </div>
                                <div class="text-center">
                                    <button type="submit" class="theme-btn btn-color btn-text btn-size auth_btn">Submit</button>
                                </div>
                                <div class="bottom-link text-center">
                                    <p>Alredy have an account?<a href="{{ url('agency/login') }}"> Login</a></p>
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
  <script type="text/javascript" src="{{ url('public/js/admin/login/forgot.js') }}"></script>
@endpush