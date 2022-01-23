@extends('admin.layout.app_with_login')
@section('title','Agency View')
@section('content')
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
                <li><a href="{{route('admin.agency.view.subscription.plan',$id)}}">Subscription Plans</a></li>
                <li class="active"><a href="{{route('admin.agency.view.transaction',$id)}}">Transactions</a></li>
                <li><a href="{{route('admin.agency.view.agent',$id)}}">Agents</a></li>
                <li><a href="{{route('admin.agency.view.customer',$id)}}">Customers</a></li>
            </ul>
            <div class="tab-content">
                <div id="transactions">
                    <form id="search-date-form">
                        <div class="row">
                            <div class="col-md-2">
                                <div class="form-group "> 
                                    <label>From</label>
                                    <input type='date' name='start_date' onchange="getDateFilter(event)" class="form-control">
                                </div>
                            </div>
                            <div class="col-md-2">
                                <div class="form-group "> 
                                    <label>To</label>
                                    <input type='date' name='end_date' onchange="getDateFilter(event)" class="form-control">
                                </div>
                            </div>
                        </div>
                    </form>
                    <div class="table-responsive">
                        <table class="table table-hover" id="transaction-tabel">
                            <thead>
                                <tr>
                                    <th>Sr. No.</th>
                                    <th>Transaction ID</th>
                                    <th>Start Date</th>
                                    <th>End Date</th>
                                    <th>Plan Name</th>
                                    <th>Amount</th>
                                </tr>
                            </thead>
                            <tbody>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
@push('custom-scripts')
<script type="text/javascript">
    var trans_table = $('#transaction-tabel').DataTable({
        processing: true,
        serverSide: true,
        searching: false,
        "bLengthChange": false,
        "bInfo": false,
        "dom": '<"top"i>rt<"bottom"flp><"clear">',
        scrollX: true,
        "bSort": false,
        "initComplete": function(settings, json) {
            $(".checkall").closest("th").removeClass("sorting_asc");
        },
        ajax: {
            url: '{{route("admin.agency.transaction.list")}}',
            type: 'post',
            headers: {'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')},
            data: function(d) {
                d.id = '{{$id}}';
                d.start_date = $('input[name=start_date]').val();
                d.end_date = $('input[name=end_date]').val();
            }
        },
        columns: [
            {data: 'DT_RowIndex', name: 'DT_RowIndex'},
            {data: 'subscription_id', name: 'subscription_id'},
            {data: 'start_date', name: 'start_date'},
            {data: 'end_date', name: 'end_date'},
            //{data: 'first_name', name: 'first_name'},
            {data: 'plan_name', name: 'plan_name'},
            {data: 'total_amount', name: 'total_amount'},
        ]
    });
    /**
     * Comment
     */
    function getDateFilter(e) {
        trans_table.draw();
        e.preventDefault();
    }
</script>
@endpush
