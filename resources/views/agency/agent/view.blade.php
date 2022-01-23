@extends('agency.layout.app_with_login')
@section('title','Agent List')
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
    <div class="path_link">
        <a href="#" class="parrent_page">My Properties </a> > <a href="#" class="current_page">View</a>
    </div>
    <div class="row">
        <div class="col-lg-12">	
            <ul class="nav nav-tabs text-center theme_tab" id="myTab" role="tablist">
                <li class="nav-item active">
                    <a class="nav-link active" id="agentProperty-tab" data-toggle="tab" href="#agentProperty" role="tab" aria-controls="agentProperty" aria-selected="false">Agent Properties</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" id="designSettings-tab" href="{{url('agency/agent/leads/'.$agentid)}}" >Agent Leads</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" id="contactForm-tab" data-toggle="tab" href="#contactForm" role="tab" aria-controls="contactForm" aria-selected="false">Agent Chat Threads</a>
                </li>
            </ul>
            <div class="tab-content theme_tab_content" id="myTabContent">
                <div class="tab-pane active in show" id="agentProperty" role="tabpanel" aria-labelledby="agentProperty-tab">
                    <form id="search-form">
                        <div class="row">
                            <div class="col-md-3"></div>
                            <div class="col-md-2 col-sm-6">
                                <div class="form-group "> 
                                    <!-- <label>Property Type</label> -->
                                    <select name="property_type" class="form-control select2 dropdown_control" required="">
                                        <option value="">Property Type</option>
                                        <option value="1">Single Home</option>
                                        <option value="2">Multifamily</option>
                                        <option value="3">Commercial</option>
                                        <option value="4">Industrial</option>
                                        <option value="5">Lot</option>
                                    </select>
                                    <div id="status_validate"></div>
                                </div>
                            </div>
                            <div class="col-md-2 col-sm-6">
                                <div class="form-group "> 
                                    <!-- <label>Purpose</label> -->
                                    <select name="property_purpose" class="form-control select2 dropdown_control" required="">
                                        <option value="">Purpose</option>
                                        <option value="1">Buy</option>
                                        <option value="2">Rent</option>
                                    </select>
                                    <div id="status_validate"></div>
                                </div>
                            </div>
                            <div class="col-md-2 col-sm-6">
                                <div class="form-group "> 
                                    <!-- <label>Status</label> -->
                                    <select name="property_status" class="form-control select2 dropdown_control" required="">
                                        <option value="">Status</option>
                                        <option value="1">Active</option>
                                        <option value="2">Inactive</option>
                                    </select>
                                    <div id="status_validate"></div>
                                </div>
                            </div>
                            <div class="col-md-3 col-sm-6">
                                <div class="form-group "> 
                                    <input type="text" placeholder="Search" class="form-control search_control" name="property_all_search">
                                </div>
                            </div>
                        </div>
                    </form>
                    <div class="table-responsive">
                        <table class="table table-hover center_table" id="property-tabel">
                            <thead>
                                <tr>
                                    <th>No.</th>
                                    <th>Property ID</th>
                                    <th>Address</th>
                                    <th>Purpose</th>
                                    <th>Type</th>
                                    <th>Created Date</th>
                                    <th>Status</th>
                                    <th><div class="action_div">Action</div></th>
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
@push('custom-styles')
<!-- Include this Page CSS -->
<link rel="stylesheet" type="text/css" href="https://stackpath.bootstrapcdn.com/font-awesome/4.7.0/css/font-awesome.min.css">
@endpush
@push('custom-scripts')
<script type="text/javascript">
    var table = $('#property-tabel').DataTable({
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
            url: '{{route("agency.agent.view.property.list")}}',
            type: 'post',
            headers: {'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')},
            data: function(d) {
                d.property_type = $('select[name=property_type]').val();
                d.property_purpose = $('select[name=property_purpose]').val();
                d.property_status = $('select[name=property_status]').val();
                d.agentid = '{{$agentid}}';
                d.property_all_search = $('input[name=property_all_search]').val();
            }
        },
        columns: [
            {data: 'DT_RowIndex', name: 'DT_RowIndex'},
            {data: 'id', name: 'id'},
            {data: 'address', name: 'address'},
            {data: 'purpose', name: 'purpose'},
            {data: 'type', name: 'type'},
            {data: 'created_at', name: 'created_at'},
            {data: 'status', name: 'status'},
            {data: 'action', name: 'action'},
        ]
    });
    $('#search-form input').on('keyup', function(e) {
        table.draw();
        e.preventDefault();
    });
    $('#search-form select').on('change', function(e) {
        table.draw();
        e.preventDefault();
    });
    $(document).on('click', '.changeStatus', function() {
        var changeStatus = $(this).data('url');
        var status = $(this).data('status');
        swal({
            text: 'Are you sure you want to change status',
            type: 'info',
            showCancelButton: true,
            confirmButtonClass: 'blue_button alert_btn mr-40',
            cancelButtonClass: 'blue_border_button alert_btn',
            confirmButtonText: 'Yes'
        }).then(function(isConfirm) {
            if (isConfirm.value == true) {
                $('.loader-outer-container').css('display', 'table');
                $.ajax({
                    type: "POST",
                    url: changeStatus,
                    data: {status: status},
                    headers: {
                        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                    },
                    success: function(data) {
                        $('.loader-outer-container').css('display', 'none');
                        if (data.code == '200') {
                            toastr.success(data.message);
                        } else {
                            toastr.error(data.message);
                        }
                        table.draw();
                    }
                });
            }
        });
    });
//Function used for Delete Image
$(document).on('click','.deleteData',function(){
    var propertyId=$(this).data('id');
    var deleteAjaxSource = $(this).data('url');
    swal({
            text: 'Are you sure you want to delete this property',
            type: 'info',
            showCancelButton: true,
            confirmButtonClass: 'blue_button alert_btn mr-40',
            cancelButtonClass: 'blue_border_button alert_btn',
            confirmButtonText: 'Yes'
        }).then(function (isConfirm) {
            if (isConfirm.value == true) {  
                $('.loader-outer-container').css('display','table');              
                $.ajax({
                    type: "POST",
                    url: deleteAjaxSource,
                    data:{propertyId:propertyId},
                    headers: {
                        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                    },
                    success: function (data) {
                        $('.loader-outer-container').css('display','none');
                        if (data.code == '200') {
                            toastr.success(data.message);
                        } else {
                            toastr.error(data.message);
                        }
                        table.draw();
                    }
                });
            }
        });
});
</script>
@endpush