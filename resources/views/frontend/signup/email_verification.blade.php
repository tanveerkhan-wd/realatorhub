@extends('frontend.layout.app_without_login')
@section('title','Login')
@section('content')
<div class="fix_header">
    <section class="section grey_bg">

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
        @if (\Session::has('error'))
            <div class="alert alert-danger">
                <a href="#" class="close" data-dismiss="alert" aria-label="close">&times;</a>
                <ul>
                    <li>{!! \Session::get('error') !!}</li>
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
                                

                                <form method="POST" action="{{url('/'.$slug.'/signup-email-verification')}}" enctype="multipart/form-data" id="verificationForm" name="verificationForm">
                                    @csrf
                                    <div class="form-group mb-0">
                                        <label for="email_otp">OTP </label>
                                        <input type="hidden" name="user_id" id="user_id" value="{{$user->id}}">
                                        <input type="text" class="form-control email_otp"  name="email_otp" id="email_otp" aria-describedby="textHelp" placeholder="Enter OTP" >
                                        <div name="email_otp_validate" id="email_otp_validate"></div>
                                    </div>
                                    <div class="text-center auth_link">
                                        <a href="javascript:void(0)" class="resend-otp" data-email="1"  data-modal="1">Resend OTP</a>
                                    </div>
                                    <div class="text-center">
                                        <button type="submit" class="theme-btn btn-color btn-text btn-size auth_btn">Submit</button>
                                    </div>
                                    <div class="bottom-link text-center">
                                        <p>Already have an account?<a href="{{ url('/'.$slug.'/login') }}"> Login</a></p>
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
</div>
@endsection
@push('custom-styles')
<style>
    .alert {
        position: relative;
        margin: 3px 0;
        width: 100%;
    }

    .blue_border_btn {
        opacity:0.5;
    }
    .blue_btn{
        opacity:1;
    }



</style>
@endpush

@push('custom-scripts')
<script src="https://www.jqueryscript.net/demo/jQuery-International-Telephone-Input-With-Flags-Dial-Codes/build/js/intlTelInput.js"></script>
<script type="text/javascript">

</script>

<script type="text/javascript" src="{{ url('public/js/frontend/signup/email_verification.js') }}"></script>
@endpush