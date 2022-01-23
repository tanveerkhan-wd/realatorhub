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
                <li><a href="{{route('admin.agency.view.transaction',$id)}}">Transactions</a></li>
                <li class="active"><a href="{{route('admin.agency.view.agent',$id)}}">Agents</a></li>
                <li><a href="{{route('admin.agency.view.customer',$id)}}">Customers</a></li>
            </ul>

            <div class="tab-content">
                <div id="agencyAgents">
                    <form id="search-agent-form">
                        <div class="row">
                            <div class="col-md-6"></div>
                            <div class="col-md-3">
                                <div class="form-group "> 
                                            <!-- <label>Status</label> -->
                                            <select name="agent_status" class="form-control dropdown_control" required="">
                                                <option value="">Status</option>
                                                <option value="1">Active</option>
                                                <option value="0">Inactive</option>
                                            </select>
                                            <div id="status_validate"></div>
                                </div>
                            </div>
                            <div class="col-md-3">
                            <div class="form-group "> 
                                <input type="text" placeholder="Search" class="form-control search_control" name="agent_all_search">
                            </div>
                        </div>
                        </div>
                    </form>
                    <div class="table-responsive">
                        <table class="table table-hover" id="agent-tabel">
                            <thead>
                                <tr>
                                    <th>Sr. No.</th>
                                    <th>Agent ID</th>
                                    <th>First Name</th>
                                    <th>Last Name</th>
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
                <div id="customers" class="tab-pane fade">
                    <h3>Menu 3</h3>
                    <p>Eaque ipsa quae ab illo inventore veritatis et quasi architecto beatae vitae dicta sunt explicabo.</p>
                </div>
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
    var agent_table = $('#agent-tabel').DataTable({
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
            url: '{{route("admin.agency.agent.list")}}',
            type: 'post',
            headers: {'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')},
            data: function(d) {
                d.id = '{{$id}}';
                d.agent_status = $('select[name=agent_status]').val();
                d.agent_all = $('input[name=agent_all_search]').val();
            }
        },
        columns: [
            {data: 'DT_RowIndex', name: 'DT_RowIndex'},
            {data: 'agent_unique_id', name: 'agent_unique_id'},
            {data: 'first_name', name: 'first_name'},
            {data: 'last_name', name: 'last_name'},
            //{data: 'first_name', name: 'first_name'},
            {data: 'email', name: 'email'},
            {data: 'phone', name: 'phone'},
            {data: 'created_at', name: 'created_at'},
            {data: 'user_status', name: 'user_status'},
            {data: 'admin_status', name: 'admin_status'},
            {data: 'action', name: 'action'},
        ]
    });
    $('#search-agent-form input').on('keyup', function(e) {
        agent_table.draw();
        e.preventDefault();
    });
    $('#search-agent-form select').on('change', function(e) {
        agent_table.draw();
        e.preventDefault();
    });
    //Function used for Delete Image
$(document).on('click','.deletePropertyData',function(){
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
//Function used for Delete Image
$(document).on('click','.deleteAgentData',function(){
    var deleteAjaxSource = $(this).data('url');
    var id=$(this).data('id');
    $("input[name=add-agent-url]").val(deleteAjaxSource);
    swal({
            text: 'Are you sure you want to delete this agent',
            type: 'info',
            showCancelButton: true,
            confirmButtonClass: 'blue_button alert_btn mr-40',
            cancelButtonClass: 'blue_border_button alert_btn',
            confirmButtonText: 'Yes'
        }).then(function (isConfirm) {
            if (isConfirm.value == true) {  
                $('#agent-model-popup').modal('show');
                
                $('.loader-outer-container').css('display','table');              
                $.ajax({
                    type: "POST",
                    url: '{{url("/common/getAgencyAgent")}}',
                    data:{user_id:id},
                    headers: {
                        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                    },
                    success: function (data) {
                        $('#agent-model-popup').modal('show');
                        $('.loader-outer-container').css('display','none');
                        $('#model-agent-list').html(data);
                        /*if (data == 1) {
                            swal('Success','Agent deleted successfully','success');
                        }
                        dTable.fnDraw(true);*/
                    }
                });
            }
        });
});
$(document).on('click','#saveAgent',function(e){
    var formStatus = $('#verificationForm').validate().form();
      //var hourly_rate_error=
  if(formStatus==true){
        e.preventDefault();
        $('.loader-outer-container').css('display','table'); 
        var agent=$("select[name=agent]").val();
        var url=$("input[name=add-agent-url]").val();
        $.ajax({
                type: "POST",
                url: url,
                data:{agent:agent},
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                },
                success: function (data) {
                    $('.loader-outer-container').css('display','none');
                    if (data.code == '200') {
                        toastr.success(data.message);
                        window.setTimeout(function(){window.location.reload();
                            },2000);
                    }else{
                        toastr.error(data.message);
                        window.setTimeout(function(){window.location.reload();
                            },2000);
                    }
                }
            });
    }
}); 
$(document).ready(function(){
$("#verificationForm").validate({
        // Specify validation rules
    rules: {
        agent: {
            required: true,
        },
    },
    // Specify validation error messages
    messages: {
        agent: {
            required:"Please select agent",
        }
    },
    errorPlacement: function (error, element) {
        var name = $(element).attr("name");
        error.appendTo($("#" + name + "_validate"));
    },
    // Make sure the form is submitted to the destination defined
    // in the "action" attribute of the form when valid
     submitHandler: function(form) {
     form.submit();
     }
  });
}); 
$('.agent-select').select2();
</script>
@endpush