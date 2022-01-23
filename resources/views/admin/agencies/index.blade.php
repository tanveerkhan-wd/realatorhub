@extends('admin.layout.app_with_login')
@section('title','Agencies')
@section('content')
<!-- Content Body -->
<section class="content">
    <div class="row new_added_div">
        <!-- left column -->
        <div class="col-md-12">
            
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
                        <div class="col-md-6">
                            <div class="box-header">
                                <h3 class="box-title current_page">Agencies</h3>
                            </div>
                        </div>
                        <div class="col-md-3 col-sm-6">
                            <div class="form-group "> 
                                <!-- <label>Status</label> -->
                                <select name="agency_status" class="form-control select2 dropdown_control" required="">
                                    <option value="">Status</option>
                                    <option value="1">Active</option>
                                    <option value="0">Inactive</option>
                                </select>
                                <div id="status_validate"></div>
                            </div>
                        </div>
                         <div class="col-md-3 col-sm-6">
                            <div class="form-group "> 
                                <input type="text" placeholder="Search" class="form-control icon_control search_control" name="agency_all_search">
                            </div>
                        </div>
                    </div>
                </form>
            </div>
        </div>
        <div class="table-responsive">
            <table class="table table-hover" id="agency-tabel">
                <thead>
                    <tr>
                        <th>No.</th>
                        <th>Agency Name</th>
                        <th>Agency Owner Name</th>
                        <th>Email & Phone</th>
                        <!--<th>Phone</th>-->
                        <th>Active Plan</th>
                        <th>Created Date</th>
                        <th>User Status</th>
                        <th>Admin Status</th>
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
    var table = $('#agency-tabel').DataTable({
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
            url: '{{route("admin.agency.datatable.list")}}',
            type: 'post',
            headers: {'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')},
            data: function(d) {
                d.agency_status = $('select[name=agency_status]').val();
                d.agency_search = $('input[name=agency_all_search]').val();
            }
        },
        columns: [
            {data: 'DT_RowIndex', name: 'DT_RowIndex'},
            {data: 'agency_name', name: 'agency_name'},
            {data: 'first_name', name: 'first_name'},
            {data: 'email', name: 'email'},
            //{data: 'phone', name: 'phone'},
            {data: 'plan_name', name: 'plan_name'},
            {data: 'created_at', name: 'created_at'},
            {data: 'user_status', name: 'user_status'},
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
        var type = $(this).data('type');
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
                    data: {status: status,type:type},
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

        $(document).on('click', '.deleteData', function() {
        var url = $(this).data('url');
        swal({
            text: 'Are you sure you want to delete agency?',
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
                    url: url,
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