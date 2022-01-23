@extends('frontend.layout.app_without_login')
@section('title','Verify OTP')
@section('content')
<!-- 
View File for OTP Verify
@package    Laravel
@subpackage View
@since      1.0
 -->
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
                                <h1 class="auth_title">Email Verification</h1>
                            </div>
                            <form action="{{ url('/'.$slug.'/otpverifyPost') }}" method="post" id="loginForm" name="loginForm">
                                {{ csrf_field() }}
                                <input type="hidden" name="user_id" id="user_id" value="{{ Session::get('user_id') }}">
                                <label>OTP</label>
                                <div class="form-group has-feedback mb-0">
                                    <input type="text" class="form-control" placeholder="OTP" name="otp" id="otp" required="">
                                    <div id="otp_validate"></div>
                                </div>
                                <div class="text-center auth_link">
                                    <a href="javascript:void(0)" class="resend-otp" data-email="1"  data-modal="1">Resend OTP</a>
                                </div>
                                <div class="text-center two_btns">
                                    <a href="{{ url('/'.$slug.'/forgotPass') }}" class="theme-btn btn-color btn-text btn-size auth_btn">Back</a>
                                    <button type="submit" class="theme-btn btn-color btn-text btn-size auth_btn">Submit</button>
                                </div>
                                <div class="bottom-link text-center">
                                    <p>Already have an account?<a href="{{ url('/'.$slug.'/login') }}">
                                     Login</a></p>
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
  <script type="text/javascript" src="{{ url('public/js/admin/login/otp.js') }}"></script>
  <script type="text/javascript" src="{{ url('public/js/frontend/signup/email_verification.js') }}"></script>
@endpush

@push('custom-css')
  <style type="text/css">
    .alert.alert-success{
      top: 2pc;
    }
  </style>
@endpush