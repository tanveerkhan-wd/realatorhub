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
                <li class='active'><a href="{{route('admin.agency.view.property',$id)}}">Properties</a></li>
                <li><a href="{{route('admin.agency.view.subscription.plan',$id)}}">Subscription Plans</a></li>
                <li><a href="{{route('admin.agency.view.transaction',$id)}}">Transactions</a></li>
                <li><a href="{{route('admin.agency.view.agent',$id)}}">Agents</a></li>
                <li><a href="{{route('admin.agency.view.customer',$id)}}">Customers</a></li>
            </ul>
            <div class="tab-content">
            <form id="search-form">
                <div class="row">
                    <div class="col-md-3"></div>
                    <div class="col-md-3">
                        <div class="form-group "> 
                            <!-- <label>Property Type</label><br> -->
                            <select name="property_type" class="form-control dropdown_control" required="">
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
                    <div class="col-md-3">
                        <div class="form-group "> 
                            <!-- <label>Purpose</label> -->
                            <select name="property_purpose" class="form-control dropdown_control" required="">
                                <option value="">Purpose</option>
                                <option value="1">Buy</option>
                                <option value="2">Rent</option>
                            </select>
                            <div id="status_validate"></div>
                        </div>
                    </div>
                    <div class="col-md-3">
                        <div class="form-group "> 
                            <!-- <label>Status</label> -->
                            <select name="property_status" class="form-control dropdown_control" required="">
                                <option value="">Status</option>
                                <option value="1">Active</option>
                                <option value="2">Inactive</option>
                            </select>
                            <div id="status_validate"></div>
                        </div>
                    </div>
                </div>
            </form>
            <div class="table-responsive">
                <table class="table table-hover" id="property-tabel">
                    <thead>
                        <tr>
                            <th>Sr. No.</th>
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

<!-- End Content Body -->
<div class="modal fade auth_modal" id="agent-model-popup" tabindex="-1" role="dialog" aria-labelledby="" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered" role="document">
        <div class="modal-content">
            <div class="text-center modal-header">

                <div class="modal-title">Change Agent</div>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true"><img src="{{ url('public/assets/images/ic_close.png') }}" alt="close" class="img-close"></span>
                </button>
            </div>

            <div class="modal-body">
                <form method="POST" action="" name="verificationForm" id="verificationForm">
                    <div class="container">
                        <div class="row" id="model_note_content">
                            <div class="col-12">
                                <div class="form-group ">  
                                    <input type="hidden" name="add-agent-url">                                   
                                    <select id="model-agent-list" name="agent" class="form-control agent-select" required="">

                                    </select>
                                    <div id="agent_validate"></div>
                                </div>
                                <div class="text-center">
                                    <button type="submit" class="theme-btn btn-color btn-text btn-size auth_btn" id="saveAgent">Save</button>
                                </div>
                            </div>
                        </div>
                        <div class="row" id="model_message_content">
                            <div class="col-12">
                                <p id="view_model_message" style="text-align: center;"></p>
                            </div>
                        </div>
                    </div>
                </form>
            </div>
        </div>

    </div>  
</div>
<!-- modal_end -->

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
            url: '{{route("admin.agency.property.list")}}',
            type: 'post',
            headers: {'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')},
            data: function(d) {
                d.id = '{{$id}}';
                d.property_type = $('select[name=property_type]').val();
                d.property_purpose = $('select[name=property_purpose]').val();
                d.property_status = $('select[name=property_status]').val();

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
            text: 'Are you sure you want to change the status?',
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
        $(".country_code").select2({
            templateResult: formatState,
            templateSelection: formatState
        });
        $('.timezone').select2();

        function formatState(opt) {
            if (!opt.id) {
                return opt.text.toUpperCase();
            }

            var optimage = $(opt.element).attr('data-image');
            // console.log(optimage)
            if (!optimage) {
                return opt.text.toUpperCase();
            } else {
                var $opt = $(
                        '<span><img src="' + optimage + '" width="60px" /> ' + opt.text.toUpperCase() + '</span>'
                        );
                return $opt;
            }
        }
        ;
    });

    /**
     * Comment
     */

    //Function used for Delete Image
    $(document).on('click', '.deletePropertyData', function() {
        var propertyId = $(this).data('id');
        var deleteAjaxSource = $(this).data('url');
        swal({
            text: 'Are you sure you want to delete this property',
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
                    url: deleteAjaxSource,
                    data: {propertyId: propertyId},
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
    $(document).on('click', '.changepropertystatus', function() {
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
</script>
@endpush