@extends('agency.layout.app_with_login')
@section('title','Customer List')
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
        <div class="col-6">
            <div class="path_link">
                <a href="{{url('agency/agent')}}" class="current_page">My Customers</a>
            </div>
        </div>
    </div>
    
    <div class="header_bar">
      <div class="">
        <form id="search-form">
        <div class="row">
          <div class="col-md-5"></div>
          <div class="col-md-3">
              <div class="form-group ">                                     
                  <select name="status" class="form-control select2 dropdown_control" required="">
                      <option value="">Status</option>
                      <option value="1">Active</option>
                      <option value="0">In Active</option>
                  </select>
                  <div id="status_validate"></div>
                </div>
          </div>
          <div class="col-md-4">
              <div class="form-group">
                  <input class="form-control search_control" placeholder="Search" type="text" name="title" id="title">
              </div>
          </div>
        </div>
        </form>
      </div>
    </div>
    <div class="table-responsive">
      <table class="table table-hover center_table" id="datatableData">
          <thead>
              <tr>
                  <th>Sr. No.</th>
                  <th>Customer Name</th>
                  <th>Email</th>
                  <th>Phone</th>
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



<!-- Modal -->
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
<!-- End Content Body -->
@endsection
@push('custom-styles')
<!-- Include this Page CSS -->
<style type="text/css">
  .form-group.auto-width .btn-primary{
    width: 190px;
  }
</style>
@endpush
@push('custom-scripts')
<script type="text/javascript">
  var base_url='<?php echo url(''); ?>'
</script>
<!-- Include this Page JS -->
<script type="text/javascript">
  var agent_table = $('#datatableData').DataTable({
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
            url: '{{route("agency.customer.list.ajax")}}',
            type: 'post',
            headers: {'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')},
            data: function(d) {
                d.agent_status = $('select[name=status]').val();
                d.agent_all = $('input[name=title]').val();
            }
        },
        columns: [
            {data: 'DT_RowIndex', name: 'DT_RowIndex'},
           // {data: 'agent_unique_id', name: 'agent_unique_id'},
            {data: 'first_name', name: 'first_name'},
            //{data: 'last_name', name: 'last_name'},
            //{data: 'first_name', name: 'first_name'},
            {data: 'email', name: 'email'},
            {data: 'phone', name: 'phone'},
            {data: 'created_at', name: 'created_at'},
            {data: 'user_status', name: 'user_status'},
            {data: 'admin_status', name: 'admin_status'},
            {data: 'action', name: 'action'},
        ]
    });
    $('#search-form input').on('keyup', function(e) {
        agent_table.draw();
        e.preventDefault();
    });
    $('#search-form select').on('change', function(e) {
        agent_table.draw();
        e.preventDefault();
    });
    
     $(document).on('click', '.changeagentstatus', function() {
        var changeStatus = $(this).data('url');
        var status = $(this).data('status');
        var type = $(this).data('type');
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
                        agent_table.draw();
                    }
                });
            }
        });
    });
    $(document).on('click','.deleteData',function(){
    var deleteAjaxSource = $(this).data('url');
    var id=$(this).data('id');
    $("input[name=add-agent-url]").val(deleteAjaxSource);
    swal({
            text: 'Are you sure you want to delete this Customer?',
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
                    data:{user_id:id},
                    headers: {
                        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                    },
                    success: function (data) {
                        $('.loader-outer-container').css('display','none');
                        agent_table.draw();
                    }
                });
            }
        });
});
  
</script>
@endpush