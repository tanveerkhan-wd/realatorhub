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
    <div class="row">
        <div class="col-6">
            <div class="path_link">
                <a href="{{url('agency/agent')}}" class="current_page">My Agent</a>
            </div>
        </div>
        <div class="col-6 text-right">
            <a href="{{ url('agency/agent/add') }}" class="theme-btn btn-color btn-text btn-size mb-2 add_btn" data-url="{{ url('agency/agent/add') }}">Add Agent</a>
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
                  <th>No.</th>
                  <th>Agent ID</th>
                  <th>First Name</th>
                  <th>Last Name</th>
                  <th>Email ID</th>
                  <th>Phone</th>
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
                                    <select name="agent" class="form-control select2" required="">
                                        <option value="">Select Agent</option>
                                        @foreach($agentList as $agent)
                                          <option value="{{$agent->id}}">{{$agent->first_name}} {{$agent->last_name}}</option>
                                        @endforeach
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
<script type="text/javascript" src="{{ url('public/js/agent/list.js') }}"></script>
<script type="text/javascript">
  setTimeout(function(){
    console.log($('.datatableData_info'));
    $('.datatableData_info').parent().parent().addClass('testClass');
  },2500)
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
$('.select2').select2();
  
</script>
@endpush