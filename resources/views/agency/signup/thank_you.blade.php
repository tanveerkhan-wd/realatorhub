@extends('agency.layout.app_with_login_without_verification')
@section('title','Login')
@section('content')
    <section class="grey_bg auth_section">
        <div class="container-fluid path_link_container">
            <div class="path_link">
                <a href="#" >Personal Information > </a><a href="#">Verify Email > </a><a href="#" class="current_page">Select Subscription</a>
            </div>
        </div>
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
                                    <h1 class="auth_title">Thank You</h1>
                                </div>
                                <div class="thankyou_box text-center">
                                    <img src="{{url('public/assets/images/thank_you.png') }}">
                                    <p>Your Registration is completed Successfully.</p>
                                    <a href="{{route('signup.agency.dashboard')}}" class="theme-btn btn-color btn-text btn-size auth_btn">Get started</a>
                                </div>
                                
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
@push('custom-styles')
<style>
    .alert {
        position: relative;
        margin: 3px 0;
        width: 100%;
    }
</style>
@endpush

@push('custom-scripts')
<script src="https://www.jqueryscript.net/demo/jQuery-International-Telephone-Input-With-Flags-Dial-Codes/build/js/intlTelInput.js"></script>
<script type="text/javascript">

</script>

@endpush