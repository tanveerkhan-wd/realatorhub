@extends('admin.layout.app_without_login')
@section('title','Change Password')
@section('content')
<!-- 
View File for Reset Password
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


    <form action="{{ url('admin/changePasswordPost') }}" method="post" id="loginForm" name="loginForm">
      {{ csrf_field() }}
      <input type="hidden" name="user_id" id="user_id" value="{{ Session::get('user_id') }}">
      <div class="form-group has-feedback">
        <input type="password" class="form-control" placeholder="New Password" name="new_password" id="new_password" required="">
        <span class="glyphicon glyphicon-lock form-control-feedback"></span>
        <div id="new_password_validate"></div>
      </div>

      <div class="form-group has-feedback">
        <input type="password" class="form-control" placeholder="Confirm Password" name="confirm_password" id="confirm_password" required="">
        <span class="glyphicon glyphicon-lock form-control-feedback"></span>
        <div id="confirm_password_validate"></div>
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
  <script type="text/javascript" src="{{ url('public/js/admin/login/change_password.js') }}"></script>
@endpush