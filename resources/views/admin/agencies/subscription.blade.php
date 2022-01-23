@extends('admin.layout.app_with_login')
@section('title','Agency View')
@section('content')
<style>
    .subscription_box_header {
    padding: 30px 15px;
    background-color: #fcfcfc;
    position: relative;
    }
    .subscription_box {
    box-shadow: 0 0 5px rgba(0,0,0,0.18);
    background-color: #ffffff;
    padding: 0;
    border-radius: 10px;
    overflow: hidden;
    margin: 15px 0px;
    transition: 0.4s leinear;
}
.subscription_box_body {
    padding: 25px 15px;
}
.subscription_box_header button {
    border-radius: 4px;
    /* opacity: 0.4; */
}
.subscription_box_body p {
    font-size: 16px;
    opacity: 0.5;
}
.subscription_box_body h4 {
    margin-bottom: 10px;
    padding-bottom: 10px;
    border-bottom: 1px solid #f6f6f6;
    font-size: 18px;
}
h2.subscription_title {
    font-size: 24px;
}
</style>
<!-- 
View File for  List Credits
@package    Laravel
@subpackage View
@since      1.0
-->

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
<div class="container-fluid">
    <div class="row">
        <div class="col-lg-12">	
            <ul class="nav nav-tabs">
                <li><a href="{{route('admin.agency.view',$id)}}">Profile</a></li>
                <li><a href="{{route('admin.agency.view.property',$id)}}">Properties</a></li>
                <li class='active'><a href="{{route('admin.agency.view.subscription.plan',$id)}}">Subscription Plans</a></li>
                <li><a href="{{route('admin.agency.view.transaction',$id)}}">Transactions</a></li>
                <li><a href="{{route('admin.agency.view.agent',$id)}}">Agents</a></li>
                <li><a href="{{route('admin.agency.view.customer',$id)}}">Customers</a></li>
            </ul>

                <div id="subscriptionPlans" class="">
                    @if(!empty($subscriptionPlans->toArray()))
                    <div class="row equal_height">
                        @foreach($subscriptionPlans->toArray()  as $plan)
                        <div class="col-md-6 col-lg-4 equal_height_container ">
                            <div class="subscription_box text-center subscription_box_space">

                                <div class="subscription_box_header">
                                    <h2 class="subscription_title">{{$plan['plan_name']}}</h2>
                                    <h3>$ {{$plan['monthly_price']}} <span>/ Month</span></h3>
                                    @php
                                    if($plan['status']==1){
                                    $plan_name='Active';
                                    }else if($plan['status']==2){
                                    $plan_name='Cancelled';
                                    }else if($plan['status']==3){
                                    $plan_name='Completed';
                                    }
                                    @endphp

                                    <button type="button" class="theme-btn btn-color btn-text btn-size blue_border_btn  select_plan"
                                            data-plan-id="{{$plan['id']}}"
                                            data-select-plan="0"
                                            id="select_plan"> {{$plan_name}}
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
                                    <p>Renew On</p>
                                    <h4>{{ date("d-m-Y", strtotime($plan['end_date']))}}</h4>
                                </div>
                            </div>
                        </div>                       
                        @endforeach
                    </div>
                    @else
                    <div class="col-lg-12">
                    <h4 class="well">No Data Found !</h4>
                    </div>
                    @endif
                </div>
                
                
               
              
            </div>
        </div>
    </div>
<!-- End Content Body -->
<!-- modal_end -->

@endsection
@push('custom-styles')
<!-- Include this Page CSS -->
<link rel="stylesheet" type="text/css" href="https://stackpath.bootstrapcdn.com/font-awesome/4.7.0/css/font-awesome.min.css">
@endpush
