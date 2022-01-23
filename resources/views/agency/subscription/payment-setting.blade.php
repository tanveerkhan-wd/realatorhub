@extends('agency.layout.app_with_login')
@section('title','Dashboard')
@section('content')

    <div class="dash_tab">
        <div class="container-fluid">
            <div class="row">
                <div class="col-12 text-center">
                    <ul class="nav nav-tabs center_tabs theme_tab">
                        <li class="nav-item">
                            <a class="nav-link " href="{{route('agency.subscription')}}">My Subscription Plans</a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link " href="{{route('agency.subscription.transaction')}}">Transaction History</a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link active" href="{{route('agency.payment.setting')}}">Payment Settings</a>
                        </li>
                    </ul>
                </div>
            </div>
            <div class="card_container">
                <div class="row">
                    <div class="col-lg-2"></div>
                    <div class="col-lg-8">
                        @if(!empty($userCards))
                            @foreach($userCards as $userCard)
                                <div class="payment_card p-3 mb-3">
                                    
                                    <p>Card Number</p>
                                    <p>XXXX XXXX XXXX {{$userCard->card_last_four}}</p>
                                     
                                    <div class="btn-div"> 
                                        <input type="hidden" name="user_id" id="user_id" value="{{Auth::user()->id}}">
                                        <button class="theme-btn btn-color btn-text btn-size" type="button" name="add_card_btn" id="add_card_btn">Change</button>
                                        <button class="cancel_plan theme-btn btn-color btn-text btn-size medium_btn mt-2 mt-md-0 grey_btn" type="button"  data-stripe-id="{{$userCard->stripe_id}}" name="delete_card_btn" id="delete_card_btn">Remove</button>
                                    </div>
                                       
                                </div>
                            @endforeach
                        @endif
                            @if(count($userCards)== 0)
                                <div class="text-center">
                                    <input type="hidden" name="user_id" id="user_id" value="{{Auth::user()->id}}">
                                    <button class="theme-btn btn-color btn-text btn-size" type="button" name="add_card_btn" id="add_card_btn">Add Card</button>
                                </div>
                            @endif
                    </div>
                    <div class="col-lg-2"></div>
                </div>
            </div>

            <div class="text-center">
                
            </div>
        </div>
        <div class="col-lg-2"></div>
            <br>
        </div>
    <!-- Modal -->
    <div class="modal fade auth_modal" id="add_card_modal" tabindex="-1" role="dialog" aria-labelledby="" aria-hidden="true">

    </div>
    <!-- End model -->
    {{--</div>--}}


@endsection
@push('custom-styles')
<!-- Include this Page CSS -->
@endpush
@push('custom-scripts')
<!-- Include this Page JS -->
<script src="https://js.stripe.com/v3/"></script>
<script type="text/javascript" src="{{ url('public/js/agency/subscription/payment-setting.js') }}"></script>
@endpush