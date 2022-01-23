@extends('agency.layout.app_with_login')
@section('title','Dashboard')
@section('content')

    <div class="dash_tab">
        <div class="container-fluid">
            <div class="row">
                <div class="col-12 text-center">
                    <ul class="nav nav-tabs center_tabs theme_tab">
                        <li class="nav-item">
                            <a class="nav-link active" href="{{route('agency.subscription')}}">My Subscription Plans</a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link" href="{{route('agency.subscription.transaction')}}">Transaction History</a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link" href="{{route('agency.payment.setting')}}">Payment Settings</a>
                        </li>
                    </ul>
                </div>
            </div>
            @if(!empty($subscriptionPlans))
                <div class="row equal_height">

                    @foreach($subscriptionPlans->toArray()  as $plan)
                        
                    <div class="col-lg-4 col-md-6 equal_height_container">
                        <div class="subscription_box text-center subscription_box_space">

                            <div class="subscription_box_header">
                                @if(!empty($activeSubscription) && $activeSubscription->plan_id == $plan['id'] && $free_trial )
                                    
                                    <img src="{{ url('public/assets/images/ic_freetrial.png') }}" class="free_trial_icon">
                                    <!-- <h2 class="subscription_title">On Free Trial</h2> -->
                                @endif
                                <h2 class="subscription_title">{{$plan['plan_name']}}</h2>
                                <h3>$ {{$plan['monthly_price']}} <span>/ Month</span></h3>

                                @if(!empty($activeSubscription) && ($activeSubscription->plan_id != $plan['id'] ))
                                    <?php
                                        $activePlanAgents = ($activeSubscription->plan->no_of_agent) +($activeSubscription->plan->additional_agent);
                                        $planAgents = ($plan['no_of_agent']) +($plan['additional_agent']);
                                    ?>
                                    @if($activePlanAgents < $planAgents)
                                        <button type="button" class="blue_border_btn medium_btn upgrade_plan {{($enableUpgradeDowngrade == 0)?'theme-btn btn-color btn-text btn-size disabled_btn':''}}"
                                                data-active-plan-id ="{{$activeSubscription->id}}"
                                                data-plan-id="{{$plan['id']}}"
                                                data-select-plan="0"
                                                id="select_plan"
                                       > Upgrade plan
                                        </button>
                                    @endif
                                        @if($activePlanAgents > $planAgents)
                                            <button type="button" class="theme-btn btn-color btn-text btn-size blue_border_btn medium_btn downgrade_plan {{($enableUpgradeDowngrade == 0)?'theme-btn btn-color btn-text btn-size disabled_btn':''}}"
                                                    data-active-plan-id ="{{$activeSubscription->id}}"
                                                    data-total-additional-agent = "{{$activeSubscription->additional_agents_counts}}"
                                                    data-plan-additional-agent = "{{$plan['additional_agent']}}"
                                                    data-plan-id="{{$plan['id']}}"
                                                    data-select-plan="0"
                                                    id="select_plan"> Downgrade Plan
                                            </button>
                                        @endif
                                        @if($activePlanAgents == $planAgents)
                                            @if($activeSubscription->plan->monthly_price >= $plan['monthly_price'] )
                                                <button type="button" class="theme-btn btn-color btn-text btn-size blue_border_btn medium_btn downgrade_plan  {{($enableUpgradeDowngrade == 0)?'theme-btn btn-color btn-text btn-size disabled_btn':''}}"
                                                        data-active-plan-id ="{{$activeSubscription->id}}"
                                                        data-total-additional-agent = "{{$activeSubscription->additional_agents_counts}}"
                                                        data-plan-additional-agent = "{{$plan['additional_agent']}}"
                                                        data-plan-id="{{$plan['id']}}"
                                                        data-select-plan="0"
                                                        id="select_plan"> Downgrade Plan
                                                </button>
                                             @else
                                                <button type="button" class="theme-btn btn-color btn-text btn-size blue_border_btn medium_btn upgrade_plan {{($enableUpgradeDowngrade == 0)?'theme-btn btn-color btn-text btn-size disabled_btn':''}}"
                                                        data-active-plan-id ="{{$activeSubscription->id}}"
                                                        data-plan-id="{{$plan['id']}}"
                                                        data-select-plan="0"
                                                        id="select_plan"> Upgrade plan
                                                </button>
                                             @endif
                                        @endif
                                @endif


                                @if(!empty($activeSubscription) && $activeSubscription->plan_id ==$plan['id'])
                                    <button id="active_current_plan"
                                            data-plan-id = "{{$activeSubscription->plan_id}}"
                                            data-active-subscrption-id = "{{$activeSubscription->id}}"
                                            class="theme-btn btn-color btn-text btn-size half-btn {{($currentPlanCancelled == 0)?'disabled_btn':''}}"
                                            {{($currentPlanCancelled == 0)?'disabled':''}}
                                    >{{($currentPlanCancelled == 0)?'Active':'Reactivate'}}</button>
                                    <button type="button" class="theme-btn btn-text btn-size blue_border_btn medium_btn cancel_plan half-btn"
                                            data-plan-id ="{{$activeSubscription->plan_id}}"
                                            data-active-plan-id ="{{$activeSubscription->id}}"
                                            data-select-plan="0"
                                            id="select_plan"> {{($currentPlanCancelled == 0)?'Cancel':'Cancelled'}}
                                    </button>
                                @endif

                            </div>
                            
                            <div class="subscription_box_body">
                                <p>{{$plan['description']}}</p>
                                
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
            @if(!empty($activeSubscription) && empty($cancelSubscription))
                <div class="row  mt-3">
                    <div class="col-md-12">
                        <p class="btn-color-title">Estimated Next Billing On {{date('d-m-Y',strtotime($renew_date))}}</p>
                        <div class="subscription_table">
                            <div class="table-responsive">
                                <table class="table ">
                                    <tr>
                                        <td>Base Price :</td>
                                        <td>${{$activeSubscription->base_price}}</td>
                                    </tr>
                                    <tr>
                                        <td>Additional Agents:</td>
                                        <td>{{$activeSubscription->additional_agents_counts}}</td>
                                    </tr>
                                    <tr>
                                        <td>Total:</td>
                                        <td>${{$total_price}}</td>
                                    </tr>
                                </table>
                            </div>
                        </div>
                    </div>
                </div>
            @endif

            @if(!empty($cancelSubscription))
                <div class="row mt-3">
                    <div class="col-md-12   auth_box">
                        <div class="d-flex justify-content-center">

                            <p>You cancelled your active plan, Your account will get deactivated on {{$expiry_date}}</p><br>
                        </div>
                    </div>
                </div>
            @endif

        </div>
    </div>
@endsection
@push('custom-styles')
<!-- Include this Page CSS -->
<style>
    .alert {
        position: relative;
        margin: 3px 0;
        width: 100%;
    }

    .blue_border_btn {
        /*color: #2F5BEB;
        border: 1px solid #2F5BEB;
        box-shadow: 4px 4px 10px 0 rgba(0, 0, 0, 0.10);
        border-radius: 3px;
        background-color: #fff;*/
    }
    .blue_btn{
        /*color: #2F5BEB;*/
    }



</style>

@endpush
@push('custom-scripts')
<!-- Include this Page JS -->
<script type="text/javascript">
    var enableUpgradeDowngrade = '{{ $enableUpgradeDowngrade  }}';
    var currentPlanCancelled = '{{ $currentPlanCancelled }}';
    var notFoundUserCard = '{{$notFoundUserCard }}';
</script>
<script type="text/javascript" src="{{ url('public/js/agency/subscription/index.js') }}"></script>
@endpush