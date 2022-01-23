@extends('agency.layout.app_with_login')
@section('title','Edit Agent')
@section('content')

    <!-- Start Content Body -->
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
            <a href="{{url('agency/agent')}}" class="parent_page">Agent > </a><a href="#" class="current_page">Edit Agent</a>
        </div>
        <div class="row">
            <div class="col-lg-3 col-md-2"></div>
            <div class="col-lg-6 col-md-8">
                <form method="post" action="{{url('agency/agent/editAgentPost')}}" class="agent_form">
                    {{ csrf_field() }}
                    <div class="form-row">
                        <div class="col-md-6">
                            <div class="form-group">
                                <label>First Name*</label>
                                <input type="hidden" name="id" placeholder="Enter first name" class="form-control" required="" value="{{$user_data->id}}" id="user_id">
                                <input type="text" name="first_name" placeholder="Enter first name" class="form-control" required="" value="{{$user_data->first_name}}">
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group">
                                <label>Last Name*</label>
                                <input type="text" name="last_name" placeholder="Enter last name" class="form-control" required="" value="{{$user_data->last_name}}">
                            </div>
                        </div>
                        <div class="col-md-12">
                            <div class="form-group">
                                <label>Agent Id*</label>
                                <input type="text" name="agent_id" placeholder="Enter Agent Id" class="form-control" required="" value="{{$user_data->agent_unique_id}}">
                            </div>
                        </div>
                        <div class="col-md-12">
                            <div class="form-group">
                                <label for="email" class="edit_icon" id="changeEmailButton">Email ID <i class="far fa-edit"></i></label>
                                <input type="email"  name="old_email" class="form-control" id="email" placeholder="Enter Email Id" value="@if(isset($user_data->email)){{$user_data->email}} @endif" readonly="">
                                <div id="email_validate"></div>
                            </div>
                        </div>
                        <div class="col-md-12">
                            <div class="form-group">
                                <label>Phone*</label>
                                <div class="form-row">
                                    <div class="col-4">
                                        <select class="form-control dropdown_control country_code" name="country_code" id="country_code" required="">
                                            <option value="">Select Code</option>
                                            @foreach($country_code as $key=>$val)
                                                <?php $phone_code="+".$val->calling_code; ?>
                                                <option value="+{{$val->calling_code}}"  data-image="{{ url('public/uploads/country_images/'.$val->flag) }}" @if($user_data->phone_code==$phone_code) selected @endif >{{"+".$val->calling_code." ".$val->name}}</option>
                                            @endforeach
                                        </select>
                                    </div>
                                    <div class="col-8">
                                        <input type="text" name="single_mobile_number" placeholder="Enter number" class="form-control" required="" value="{{$user_data->phone}}">
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="col-12">
                            <div class="text-center two_btns">
                                <button type="submit" class="theme-btn btn-color btn-text btn-size auth_btn">Submit</button>
                                <a href="{{url(url('agency/agent/add'))}}" class="theme-btn btn-color btn-text btn-size auth_btn grey_btn">Cancle</a>
                            </div>
                        </div>
                    </div>
                </form>
            </div>
            <div class="col-lg-3 col-md-2"></div>
        </div>
    </div>

    
    <!-- End Content Body -->
<div class="modal fade auth_modal" id="change-email-model" tabindex="-1" role="dialog" aria-labelledby="" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered" id="change-email-model" role="document">
        <div class="modal-content">
            <div class="text-center modal-header">

                <div class="modal-title" id="">Change Email</div>

                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true"><img src="{{ url('public/assets/images/ic_close.png') }}" alt="close" class="img-close"></span>
                </button>
            </div>

            <div class="modal-body">
                <form id="chnage_email_form" method="POST" action="{{url('agency/changeEmail')}}">
                    <div class="container">
                        <div class="row">
                            <div class="col-12">
                                <div class="form-group">
                                    {{ @csrf_field() }} 
                                    <label class=''>Email Id</label>
                                    <input name="email" id="modelemail" class='form-control' type="email" required="">
                                </div>
                                <div class="text-center">
                                    <button id="changeEmailModelButton" class="auth_btn theme-btn btn-color btn-text btn-size" type="submit">Save</button>
                                </div>
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
@endpush
@push('custom-scripts')
<script src="https://www.jqueryscript.net/demo/jQuery-International-Telephone-Input-With-Flags-Dial-Codes/build/js/intlTelInput.js"></script>
<script type="text/javascript">
    $(".country_code").select2({
        templateResult: formatState,
        templateSelection: formatState
    });
    $(document).on('click', '#changeEmailButton', function (e) {
        $('#change-email-model').modal('show');
    });
    $('.timezone').select2();

    function formatState (opt) {
        if (!opt.id) {
            return opt.text.toUpperCase();
        }

        var optimage = $(opt.element).attr('data-image');
        // console.log(optimage)
        if(!optimage){
            return opt.text.toUpperCase();
        } else {
            var $opt = $(
                '<span><img src="' + optimage + '" width="60px" /> ' + opt.text.toUpperCase() + '</span>'
            );
            return $opt;
        }
    };

    $(document).on('keypress','#user_name',function(e){
        if(e.which === 32)
        {
            return false;
        }
    })
$(document).on('click','#changeEmailModelButton',function(e){
     var formStatus = $('#chnage_email_form').validate().form();
      //var hourly_rate_error=
  if(formStatus==true){
    e.preventDefault();
    var email=$("#modelemail").val();
    var user_id=$("#user_id").val();
    swal({
            text: "Are you sure you want to change the email?",
            type: 'info',
            showCancelButton: true,
            confirmButtonClass: 'blue_button alert_btn mr-40',
            cancelButtonClass: 'blue_border_button alert_btn',
            confirmButtonText: 'Yes'
        }).then(function (isConfirm) {
            if (isConfirm.value == true) {                
                $.ajax({
                    type: "POST",
                    url: base_url_route+'/agency/agent/changeEmail',
                    data:{email:email,id:user_id},
                    headers: {
                        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                    },
                    success: function (data) {
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
    }
});
$(document).ready(function(){
$("#chnage_email_form").validate({
        rules: {
            email:{
                required: true,
                email: true,
                remote: {
                    url: '<?= url("agency/checkemail") ?>',
                    type: "get"
                }
            },
        },
        messages: {
            email:{
                required:"Please enter email",
                email:"Please enter valide email",
                remote: "Email already in use!"
            },
        },
        /*errorPlacement: function (error, element) {
            var name = $(element).attr("name");
            error.appendTo($("#" + name + "_validate"));
        },*/

    });
});
</script>

<script type="text/javascript" src="{{ url('public/js/agent/signup/signup.js') }}"></script>
@endpush