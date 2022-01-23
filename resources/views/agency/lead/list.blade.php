@extends('agency.layout.app_with_login')
@section('title','Leads List')
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
    
    <div class="header_bar">
      <div class="">
        <form id="search-form">
        <div class="row">
          <div class="col-md-3 col-sm-12">
              <div class="path_link">
                  <a href="{{url('agency/agent')}}" class="current_page">My Leads</a>
              </div>
          </div>
          <div class="col-md-3 col-sm-4">
            <div class="form-group ">                                     
                <select name="address" class="form-control select2 dropdown_control" required="">
                    <option value="">Address</option>
                    @foreach($addresses as $address)
                      <option value="{{$address->address}}">{{$address->address}}</option>
                    @endforeach
                </select>
                <div id="status_validate"></div>
            </div>
          <div class="col-md-3 col-sm-4">
            <div class="form-group ">                                     
                <select name="status" class="form-control select2 dropdown_control" required="">
                    <option value="">Status</option>
                    <option value="0">Pending</option>
                    <option value="1">Spam</option>
                    <option value="2">Done</option>
                </select>
                <div id="status_validate"></div>
              </div>
          </div>
          <div class="col-md-3 col-sm-4">
              <div class="form-group">
                  <input class="form-control search_control" placeholder="Search" type="text" name="title" id="title">
              </div>
          </div>
        </div>
        </form>
      </div>
    </div>
    <div class="table-responsive">
      <table class="table table-hover" id="datatableData">
          <thead>
              <tr>
                  <th>Sr. No.</th>
                  <th>Property ID</th>
                  <th>Address</th>
                  <th>Customer Name</th>
                  <th>Customer Email & Phone</th>
                  <th>Status</th>
                  <th>Action</th>
              </tr>
          </thead>
          <tbody>
          </tbody>
      </table>
    </div>
      
</div>

<!-- End Content Body -->
<div class="modal fade auth_modal" id="message-model-popup" tabindex="-1" role="dialog" aria-labelledby="" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered verify-otp-model" role="document">
        <div class="modal-content">
            <div class="text-center modal-header">

                <div class="modal-title" id="model_not_message">Add Note</div>
                <div class="modal-title" id="model_message">Message</div>

                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true"><img src="{{ url('public/assets/images/ic_close.png') }}" alt="close" class="img-close"></span>
                </button>
            </div>

            <div class="modal-body">
                <form method="POST" action="{{url('agency/changeEmail')}}" name="verificationForm" id="verificationForm">
                    <div class="container">
                        <div class="row" id="model_note_content">
                            <div class="col-12">
                                <div class="form-group mb-0">
                                    <label for="email_otp">Note </label>
                                    <input type="hidden" name="id" >
                                    <textarea class="form-control email_otp" id="view_model_note"  name="note" aria-describedby="textHelp" placeholder="Type here..."></textarea>
                                </div>
                                <div class="text-center">
                                    <button type="submit" class="theme-btn btn-color btn-text btn-size auth_btn" id="saveNoteModelButton">Save</button>
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
<script type="text/javascript" src="{{ url('public/js/agency/leads/list.js') }}"></script>
<script type="text/javascript">
$(document).on('click','.view-note-message',function(e){
  var type=$(this).data('type');
  var id=$(this).data('id');
  var message=$(this).data('message');
  $('#message-model-popup').modal('show');
  if(type==1){
    $('#model_not_message').css('display','none');
    $('#model_message').css('display','block');
    $('#model_note_content').css('display','none');
    $('#model_message_content').css('display','block');
    $('#view_model_message').html(message);
  }else{
    $('#model_message').css('display','none');
    $('#model_not_message').css('display','block');
    $('#model_message_content').css('display','none');
    $('#model_note_content').css('display','block');
     $('#view_model_note').val(message);
    $('input[name=id]').val(id);
  }
});
$(document).on('click','#saveNoteModelButton',function(e){
    var formStatus = $('#verificationForm').validate().form();
      //var hourly_rate_error=
  if(formStatus==true){
        e.preventDefault();
        $('.loader-outer-container').css('display','table'); 
        var id=$("input[name=id]").val();
        var note=$("#view_model_note").val();
        $.ajax({
                type: "POST",
                url: base_url_route+'/agency/leads/addNotes',
                data:{id:id,note:note},
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
        note: {
            required: true,
        },
    },
    // Specify validation error messages
    messages: {
        note: {
            required:"Please enter note",
        }
    },
    // Make sure the form is submitted to the destination defined
    // in the "action" attribute of the form when valid
     submitHandler: function(form) {
     form.submit();
     }
  });
}); 
</script>
@endpush