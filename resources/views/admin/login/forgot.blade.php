@extends('admin.layout.app_without_login')
@section('title','Forgot Password')
@section('content')
<!-- 
View File for Reset Password
@package    Laravel
@subpackage View
@since      1.0
 -->
  <!-- /.login-logo -->
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
    <p class="login-box-msg">Sign in to start your session</p>

    <form action="{{ url('admin/forgotPasswordPost') }}" method="post" id="loginForm" name="loginForm">
      {{ csrf_field() }}
      <div class="form-group has-feedback">
        <input type="email" class="form-control" placeholder="Email" name="email" id="email">
        <span class="glyphicon glyphicon-envelope form-control-feedback"></span>
        <div id="email_validate"></div>
      </div>

      <div class="row">
        <div class="col-xs-8">
          
        </div>
        <!-- /.col -->
        <div class="col-xs-4">
          <button type="submit" class="btn btn-primary">Send Email</button>
        </div>
        <!-- /.col -->
      </div>
    </form>
    <!-- /.social-auth-links -->
    <div class="text-center" style="margin-top: 20px;">
        <a href="{{ url('admin/login') }}" class="blue_link">Login</a>
    </div>
    
  <!-- </div> -->
  <!-- /.login-box-body -->
@endsection
@push('custom-scripts')
  <script type="text/javascript" src="{{ url('public/js/admin/login/forgot.js') }}"></script>
@endpush