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
                            <a class="nav-link active" href="{{route('agency.subscription.transaction')}}">Transaction History</a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link" href="{{route('agency.payment.setting')}}">Payment Settings</a>
                        </li>
                    </ul>
                </div>
            </div>
            <br>
            {{--<div class="table-responsive">
                <table class="table table-hover" id="datatableData">
                    <thead>
                    <tr>
                        <th>Sr No.</th>
                        <th>Transaction ID</th>
                        <th>Date</th>
                        <th>Plan Name</th>
                        <th>Paymant Type</th>
                        <th>Base Price</th>
                        <th>Additional Agent</th>
                        <th>Additional Price</th>
                        <th>Total</th>
                    </tr>
                    </thead>
                    <tbody>
                    </tbody>
                </table>
            </div>--}}
            <div class="">
                <div class="top_searchbar">
                    <div class="row">
                        <div class="col-xs-12 col-md-8"></div>
                        <div class="col-xs-12 col-md-4 text-right">
                            <form id="search-form">
                            
                                <div class="form-group">
                                    <input type="text" class="form-control date_control" name="datefilter" value="" placeholder="Select date range" />
                                    <input type="hidden"  name="start_date" id="start_date" value="" />
                                    <input type="hidden"  name="end_date" id="end_date" value="" />
                                </div>

                            
                            </form>
                        </div>
                    </div>
                </div>
                <div class="table-responsive">
                    <table class="table table-hover" id="datatableData">
                        <thead>
                        <tr>
                            <th>No</th>
                            <th>Transaction Id</th>
                            <th>Date</th>
                            <th>Plan Name</th>
                            <th>Paymant Type</th>
                            <th>Base Price</th>
                            <th>Additional Agent</th>
                            <th>Additional Agent Price</th>
                            <th>Total</th>
                            <th></th>
                        </tr>
                        </thead>
                        <tbody>
                        </tbody>
                    </table>
                </div>
                <div class="col-md-3"></div>
            </div>
        </div>
    </div>

@endsection
@push('custom-styles')
<!-- Include this Page CSS -->
@endpush
@push('custom-scripts')
<!-- Include this Page JS -->
<script type="text/javascript" src="{{ url('public/js/agency/subscription/transaction.js') }}"></script>
@endpush