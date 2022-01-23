@extends('agency.layout.app_with_login_without_verification')
@section('title','Login')
@section('content')
    <section class="grey_bg auth_section">
        <div class="container-fluid path_link_container">
            <div class="path_link">
                <a href="#" >Personal Information > </a><a href="#">Verify Email > </a><a href="#" class="current_page">Select Subscription</a>
            </div>
        </div>
        <div class="container">
            <div class="row">
                <div class="col-lg-1"></div>
                <div class="col-lg-10">
                    <div class="white_box auth_box">
                        <div class="row">
                            <div class="col-lg-3"></div>
                            <div class="col-lg-6">
                                <div class="text-center">
                                    <h1 class="auth_title">Payment Detail</h1>
                                </div>
                                <form id='payment-form' class="payment_form">
                                    <div class="form-group">
                                        <!-- <label class=''>Name on Card</label> -->
                                        <input name="cardame" id="card_name" class='form-control' size='4' type='text' placeholder="Card Holder Name">
                                        <span class="card_name_error"></span>
                                        <!-- Used to display form errors -->
                                        <div id="card-errors"></div>
                                    </div>
                                    <input type="hidden" id="plan_id" name="plan_id" value="{{$plan_id}}" >
                                    {{--<input type="hidden" id="card_name" name="card_name" value="{{Auth::User()->full_name}}" >--}}
                                    
                                    <div class="form-group">
                                        <div id="card-element"></div>
                                        <!-- Used to display form errors. -->
                                        <div id="card-errors" role="alert"></div>
                                    </div>
                                    
                                    <div class="text-center">
                                        <button type="button" class="theme-btn btn-color btn-text btn-size auth_btn" id="submit_payment">Submit Payment</button>
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
@push('custom-styles')
<style>
    .alert {
        position: relative;
        margin: 3px 0;
        width: 100%;
    }
</style>
<style>
    .StripeElement {
        box-sizing: border-box;
        height: 40px;
        padding: 10px 12px;
        border-radius: 5px;
        background-color: white;
        /* box-shadow: 0 1px 3px 0 #e6ebf1; */
        -webkit-transition: box-shadow 150ms ease;
        transition: box-shadow 150ms ease;
        border: 1px solid #d9d8d8;
    }
    .StripeElement--focus {
        box-shadow: none;
        border: 1px solid #4e43fc;
    }

    .StripeElement--invalid {
        border-color: #fa755a;
    }

    .StripeElement--webkit-autofill {
        background-color: #fefde5 !important;
    }
</style>
@endpush

@push('custom-scripts')
<script src="https://www.jqueryscript.net/demo/jQuery-International-Telephone-Input-With-Flags-Dial-Codes/build/js/intlTelInput.js"></script>
<script type="text/javascript">

</script>
<script src="https://js.stripe.com/v3/"></script>

<script type="text/javascript" src="{{ url('public/js/agency/signup/direct-subscription.js') }}"></script>

<script type="text/javascript" src="{{ url('public/js/agency/signup/subscription_plans.js') }}"></script>
@endpush