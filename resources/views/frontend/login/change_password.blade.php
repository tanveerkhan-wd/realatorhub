@extends('frontend.layout.app_without_login')
@section('title','Change Password')
@section('content')
<!-- 
View File for Reset Password
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

    <div class="container">
        <div class="row">
            <div class="col-lg-1"></div>
            <div class="col-lg-10">
                <div class="white_box auth_box">
                    <div class="row">
                        <div class="col-lg-3"></div>
                        <div class="col-lg-6">
                            <div class="text-center">
                                <h1 class="auth_title">Create New Password</h1>
                            </div>
                            <form action="{{ url('/'.$slug.'/changePasswordPost') }}" method="post" id="loginForm" name="loginForm">
                                {{ csrf_field() }}
                                <input type="hidden" name="user_id" id="user_id" value="{{ Session::get('user_id') }}">
                                <div class="form-group has-feedback">
                                    <label>New Password</label>
                                    <input type="password" class="form-control" placeholder="New Password" name="new_password" id="new_password" required="">
                                    <div id="new_password_validate"></div>
                                </div>

                                <div class="form-group has-feedback mb-0">
                                    <label>Confirm Password</label>
                                    <input type="password" class="form-control" placeholder="Confirm Password" name="confirm_password" id="confirm_password" required="">
                                    <div id="confirm_password_validate"></div>
                                </div>
                                <div class="text-center">
                                    <button type="submit" class="theme-btn btn-color btn-text btn-size auth_btn">Submit</button>
                                </div>
                                <div class="bottom-link text-center">
                                    <p>Alredy have account?<a href="{{ url('/'.$slug.'/login') }}">Login</a></p>
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
  <script type="text/javascript" src="{{ url('public/js/admin/login/change_password.js') }}"></script>
@endpush