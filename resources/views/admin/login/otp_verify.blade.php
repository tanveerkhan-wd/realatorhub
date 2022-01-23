@extends('admin.layout.app_without_login')
@section('title','Verify OTP')
@section('content')
<!-- 
View File for OTP Verify
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


    <form action="{{ url('admin/otpverifyPost') }}" method="post" id="loginForm" name="loginForm">
      {{ csrf_field() }}
      <input type="hidden" name="user_id" id="user_id" value="{{ \Illuminate\Support\Facades\Session::get('user_id') }}">
      <div class="form-group has-feedback">
        <input type="text" class="form-control" placeholder="OTP" name="otp" id="otp" required="">
        <span class="glyphicon glyphicon-envelope form-control-feedback"></span>
        <div id="otp_validate"></div>
      </div>

      <div class="row">
        <div class="col-xs-8">
          
        </div>
        <!-- /.col -->
        <div class="col-xs-4">
          <button type="submit" class="btn btn-primary btn-block btn-flat">Confirm</button>
        </div>
        <!-- /.col -->
      </div>
    </form>
    <!-- /.social-auth-links -->
    <div class="text-center" style="margin-top: 20px;">
        <a href="{{ url('admin/login') }}" class="blue_link">Login</a>
    </div>
    <!-- <a href="{{ url('admin/login') }}">Login</a><br> -->
  <!-- </div> -->
@endsection
@push('custom-scripts')
  <script type="text/javascript" src="{{ url('public/js/admin/login/otp.js') }}"></script>
@endpush

@push('custom-css')
  <style type="text/css">
    .alert.alert-success{
      top: 2pc;
    }
  </style>
@endpush