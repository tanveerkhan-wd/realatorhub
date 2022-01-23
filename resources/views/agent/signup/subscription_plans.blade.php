@extends('agency.layout.app_with_login_without_verification')
@section('title','Login')
@section('content')
<div class="fix_header">
<section class="section grey_bg">
    <div class="container-fluid path_link_container">
        <div class="path_link">
            <a href="#" >Personal Information > </a><a href="#" class="">Verify Email > </a><a href="#" class="current_page">Select Subscription</a>
        </div>
    </div>
    <div class="container">
        
        @if(!empty($subscriptionPlans))
            <div class="row equal_height">
            @foreach($subscriptionPlans->toArray()  as $plan)
                        <div class="col-md-6 col-lg-4 equal_height_container ">
                            <div class="subscription_box text-center subscription_box_space">

                                <div class="subscription_box_header">
                                    <h2 class="subscription_title">{{$plan['plan_name']}}</h2>
                                    <h3>$ {{$plan['monthly_price']}} <span>/ Month</span></h3>
                                    <button type="button" class="theme-btn btn-color btn-text btn-size blue_border_btn  select_plan"
                                            data-plan-id="{{$plan['id']}}"
                                            data-select-plan="0"
                                            id="select_plan"> Select this Plan
                                    </button>
                                </div>

                                <div class="subscription_box_body">
                                    <p>{{$plan['description']}}</p>
{{--                                    <p>{{$plan['subscriptions_count']}} Active Agents</p>--}}
                                    <p>Agents Limit</p>
                                    <h4>{{$plan['no_of_agent']}}</h4>
                                    <p>Additional Agents Allowed</p>
                                    <h4>Up to {{$plan['additional_agent']}}</h4>
                                    <p>Price Per Additional Agent</p>
                                    <h4>$ {{$plan['additional_agent_per_rate']}}</h4>
                                </div>
                            </div>
                        </div>
            @endforeach
            </div>

        @endif
    </div>


    <div class="text-center">
        <!-- <P CLASS="subscriptionPlans_bottom_text">"Try any plan free for 30 Days, If you don't want to continue the you can cancel at any time.</P> -->
        <P CLASS="subscriptionPlans_bottom_text">Start your free trial with any plan for 30 days.  You can cancel at anytime, no commitments.</P>
        <button type="button" class="theme-btn btn-color btn-text btn-size auth_btn" id="start_trial">Start Free Trial
        </button>
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

<script type="text/javascript" src="{{ url('public/js/agency/signup/subscription_plans.js') }}"></script>
@endpush