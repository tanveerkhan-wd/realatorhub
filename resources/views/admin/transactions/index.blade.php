@extends('admin.layout.app_with_login')
@section('title','Transactions')
@section('content')
    <!-- Content Body -->
    <section class="content">
        <div class="row new_added_div">
            <!-- left column -->
            <div class="col-md-12">
                <div class="box-header">
                    <h3 class="box-title">Transactions</h3>
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

                </div>
                <div class="row">
                    <div class="box-header">
                        <div class="row">
                            <form id="search-form">
                                <div class="col-xs-12 col-md-12 text-right">
                                    <div class="form-group">
                                        <input type="text" class="form-control" name="datefilter" value="" placeholder="Select date range" />
                                        <input type="hidden"  name="start_date" id="start_date" value="" />
                                        <input type="hidden"  name="end_date" id="end_date" value="" />
                                    </div>
                                    <div class="form-group">
                                        <select class="select2 form-control icon_control dropdown_control" name="search_plan" id="search_plan">
                                            <option value="" >Please select plan</option>
                                            @foreach($plans as $plan)
                                                <option value="{{$plan->id}}" >{{$plan->plan_name}}</option>
                                            @endforeach
                                        </select>
                                    </div>
                                    <div class="form-group">
                                        <select class="select2 form-control icon_control dropdown_control" name="search_agency" id="search_agency">
                                            <option value="" >Please select agency</option>
                                            @foreach($agency as $a)
                                                <option value="{{$a->user_id}}" >{{$a->agency_name}}</option>
                                            @endforeach
                                        </select>
                                    </div>

                                </div>
                            </form>
                        </div>
                    </div>
                    <div class="box-body">
                        <table class="table table-hover" id="datatableData" width="100%">
                            <thead>
                            <tr>
                                <th>No</th>
                                <th>Transaction Id</th>
                                <th>Date</th>
                                <th>Agency Name</th>
                                <th>Plan Name</th>
                                <th>Payment Type</th>
                                <th>Base Price</th>
                                <th>Additional Agent</th>
                                <th>Additional Agent Price</th>
                                <th>Total</th>
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
    </section>
    <?php $adminSettings=getSettings(); 
    //echo "<pre>"; print_r($adminSettings); exit;
    //echo date('F j, Y g:i A',strtotime('2020-07-28 19:57:56'));
    ?>
    <!-- End Content Body -->
@endsection
@push('custom-scripts')
<!-- Include this Page JS -->
<script type="text/javascript">
    var data_format='<?= $adminSettings['date_format'] ?>';
    var time_format='<?= $adminSettings['time_format'] ?>';
</script>
<script type="text/javascript" src="{{ url('public/js/admin/transaction/index.js') }}"></script>
@endpush
@push('custom-styles')

<style type="text/css">
    .mt-10{
        margin-top: 20px;
    }
</style>
@endpush