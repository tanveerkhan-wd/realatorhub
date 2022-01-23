@extends('admin.layout.app_with_login')
@section('title','Property List')
@section('content')
<!-- Content Body -->
<section class="content">
    <div class="row new_added_div">
        <!-- left column -->
        <div class="col-md-12">
            <div class="box-header">
                <h3 class="box-title current_page">Properties</h3>
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
        </div>
    </div>
    <div class="container-fluid">
        <div class="header_bar">
            <div class="">
                <form id="search-form">
                    <div class="row">
                        <div class="col-md-2 col-sm-6"><div class="form-group "> 
                                <!-- <label>Agency</label> -->
                                <select name="agency" class="form-control select2 dropdown_control" required="">
                                    <option value="">Agency</option>
                                    @foreach($agency_list as $key=>$agency)
                                    <option value="{{$agency->user_id}}">{{$agency->agency_name}}</option>
                                    @endforeach
                                </select>
                                <div id="status_validate"></div>
                            </div>
                        </div>
                        <div class="col-md-3 col-sm-6">
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
                        <div class="col-md-3">
                            <div class="form-group "> 
                                <input type="text" placeholder="Search" class="form-control search_control" name="property_all_search">
                            </div>
                        </div>
                    </div>
                </form>
            </div>
        </div>
        <div class="table-responsive">
            <table class="table table-hover" id="property-tabel">
                <thead>
                    <tr>
                        <th>No.</th>
                        <th>Property ID</th>
                        <th>Agency Name</th>
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
</section>

<!-- End Content Body -->
@endsection
@push('custom-scripts')
<!-- Include this Page JS -->
@endpush
@push('custom-styles')

<style type="text/css">
    .mt-10{
        margin-top: 20px;
    }
</style>
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
            url: '{{route("admin.property.datatable.list")}}',
            type: 'post',
            headers: {'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')},
            data: function(d) {
                d.agency = $('select[name=agency]').val();
                d.property_type = $('select[name=property_type]').val();
                d.property_purpose = $('select[name=property_purpose]').val();
                d.property_status = $('select[name=property_status]').val();
                d.property_all_search = $('input[name=property_all_search]').val();
            }
        },
        columns: [
            {data: 'DT_RowIndex', name: 'DT_RowIndex'},
            {data: 'id', name: 'id'},
            {data: 'agency_name', name: 'agency_name'},
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
    });
//Function used for Delete Image
$(document).on('click','.deleteData',function(){
    var propertyId=$(this).data('id');
    var deleteAjaxSource = $(this).data('url');
    swal({
            text: 'Are you sure you want to delete this property?',
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