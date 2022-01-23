@extends('admin.layout.app_with_login')
@section('title','Subscriptions')
@section('content')
    <!-- Content Body -->
    <section class="content">
        <div class="row new_added_div">
            <!-- left column -->
            <div class="col-md-12">
                <div class="box-header">
                    <h3 class="box-title">Subscriptions</h3>
                </div>
                <!-- general form elements -->
                <div class="box box-primary box-solid">
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
                    @if(\Session::has('error'))
                        <div class="alert alert-danger">
                            <a href="#" class="close" data-dismiss="alert" aria-label="close">&times;</a>
                            <ul>
                                <li>{!! \Session::get('error') !!}</li>
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
                    <?php
//                    $check = isset($_REQUEST['data'])?$_REQUEST['data']:'plans';
                    ?>
                    <div class="container">
                        <div class="row">
                            <div class="col-lg-12">
                                        <div class="row mt-10">
                                            <div class="text-right">
                                                <button type="button" class="btn btn-primary"
                                                        onclick="window.location='{{route('add.new.subscription')}}'">
                                                    <i class="fa fa-plus"></i>
                                                    Add New Subscription
                                                </button>
                                            </div>

                                            <div class="box-body">
                                                <table class="table table-hover" id="datatableData">
                                                    <thead>
                                                    <tr>
                                                        <th>No</th>
                                                        <th>Created Date</th>
                                                        <th>Plan Name</th>
                                                        <th>Monthly Price</th>
                                                        <th>No Of agents</th>
                                                        <th>Active Agencies</th>
                                                        <th>Is Deleted</th>
                                                        <th width="100px">Action</th>
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
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>
    <!-- End Content Body -->
@endsection
@push('custom-scripts')
<!-- Include this Page JS -->
<script type="text/javascript" src="{{ url('public/js/admin/subscription/listPlan.js') }}"></script>
@endpush
@push('custom-styles')
<!-- Include this Page CSS -->
<link rel="stylesheet" type="text/css" href="https://stackpath.bootstrapcdn.com/font-awesome/4.7.0/css/font-awesome.min.css">
<style type="text/css">
    .mt-10{
        margin-top: 20px;
    }
</style>
@endpush