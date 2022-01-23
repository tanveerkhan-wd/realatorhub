@extends('agent.layout.app_with_login')
@section('title','My Account')
@section('content')
<!-- 
View File for About us Setting
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
	@php               				
		$_REQUEST['data'] = (isset($_REQUEST['data']) && !empty($_REQUEST['data']))?$_REQUEST['data']:'editProfile';
@endphp
<?php //echo "<pre>"; print_r($data); exit; ?>


	          		
<div class="container-fluid">
	<div class="row">
		<div class="col-lg-12">	
			<ul class="nav nav-tabs text-center theme_tab" id="myTab" role="tablist">
			  <li class="nav-item @if($_REQUEST['data'] == 'editProfile') active @endif">
			    <a class="nav-link @if($_REQUEST['data'] == 'editProfile') active @endif" id="editProfile-tab" data-toggle="tab" href="#editProfile" role="tab" aria-controls="editProfile" aria-selected="false">Edit Profile</a>
			  </li>
			  <li class="nav-item @if($_REQUEST['data'] == 'changePassword') active @endif">
			    <a class="nav-link @if($_REQUEST['data'] == 'changePassword') active @endif" id="changePassword-tab" data-toggle="tab" href="#changePassword" role="tab" aria-controls="changePassword" aria-selected="false">Change Password</a>
			  </li>
<!--			  <li class="nav-item @if($_REQUEST['data'] == 'deactivateAccount') active @endif">
			    <a class="nav-link @if($_REQUEST['data'] == 'deactivateAccount') active @endif" id="deactivateAccount-tab" data-toggle="tab" href="#deactivateAccount" role="tab" aria-controls="deactivateAccount" aria-selected="false">Deactivate Account</a>
			  </li>-->
			</ul>

			<div class="tab-content theme_tab_content" id="myTabContent">								  
		  		<div class="tab-pane fade @if($_REQUEST['data'] == 'editProfile') active in show @endif" id="editProfile" role="tabpanel" aria-labelledby="editProfile-tab">
		  			<form id="sign_up_form" method="POST" action="{{ route('agent.edit.my.profile') }}" enctype="multipart/form-data">
							{{ @csrf_field() }}	
						<div class="row">
		                    <div class="col-md-3"></div>
	                      	<div class="col-md-6">
								<div class="form-row">
                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <input type="hidden" name="id" class="form-control" value="@if(isset($agency_data->id)){{$agency_data->id}} @endif">
                                            <label for="first_name">First Name</label>
                                            <input type="text"  pattern="\w+" name="first_name" class="form-control"
                                                   id="first_name" aria-describedby="emailHelp" placeholder="Enter First Name" value="@if(isset($agency_data->first_name)){{$agency_data->first_name}}@endif">
                                            <span class="form-control-feedback"></span>
                                            <div id="first_name_validate"></div>
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label for="last_name">Last Name</label>
                                            <input type="text"  pattern="\w+" name="last_name" class="form-control"
                                                   id="last_name" aria-describedby="emailHelp" placeholder="Enter Last Name" value="@if(isset($agency_data->last_name)){{$agency_data->last_name}}@endif">
                                            <span class="form-control-feedback"></span>
                                            <div id="last_name_validate"></div>
                                        </div>
                                    </div>
                                </div>
                                <div class="form-group">
                                    <label for="email" class="edit_icon" id="changeEmailButton">Email ID <i class="far fa-edit"></i></label>
                                        <input type="email"  name="old_email" class="form-control" id="email" placeholder="Enter Email Id" value="@if(isset($agency_data->email)){{$agency_data->email}} @endif" readonly="">
                                       <div id="email_validate"></div>
                                </div>
                                <div class="form-group">
                                    <label for="email">Agent ID</label>
                                    <input type="text"  name="agent_id" class="form-control" id="agent_id"  value="{{$agent_id->agent_unique_id}}" readonly>
                                </div>
                                <div class="form-group">
                                    <label>Phone*</label>
                                    <div class="form-row">
                                        <div class="col-4">
                                            <select class="form-control dropdown_control country_code" name="country_code" id="country_code">
                                                <option value="">Select Code</option>
                                                @foreach($country_code as $key=>$val)
                                                <?php $phone_code="+".$val->calling_code; ?>
                                                    <option value="+{{$val->calling_code}}"  data-image="{{ url('public/uploads/country_images/'.$val->flag) }}" @if($agency_data->phone_code==$phone_code) selected @endif>{{"+".$val->calling_code." ".$val->name}}</option>
                                                @endforeach
                                            </select>
                                            <div id="country_code_validate"></div>
                                        </div>
                                        <div class="col-8">
                                            <input  type="text"  name="mobile_number" class="form-control" id="mobile_number" aria-describedby="emailHelp" placeholder="Enter Number" value="@if(isset($agency_data->phone)){{$agency_data->phone}} @endif">
                                            <div id="mobile_number_validate"></div>
                                        </div>
                                    </div>
                                </div>
                                <div class="form-group">
                                    <label for="agency_logo">Profile Image</label>
                                    <div class="upload-file-group">
                                        <div class="choose_imd_box text-center">
                                            <?php if(isset($agency_data->profile_img) && !empty($agency_data->profile_img)){?>
                                        	<img src="{{ url('public/uploads/profile_photos').'/'.$agency_data->profile_img }}" class="" id="agency_logo_image"><br>
                                            <?php }else{?> 
                                            <div class="text-center">
                                                <img src="{{ url('public/assets/')}}/images/ic_sad_emoji.png" id="agency_logo_image"><br>
                                            </div>
                                            <?php } ?>
                                        	<!-- <button class="theme-btn btn-color btn-text btn-size small_btn">Change</button> -->
                                        </div>
                                        
                                        <p class="file_upload_btn btn-color btn-text">
                                            <input type="file" name="agency_logo" class="agency_logo file_control" id="upload_logo" onchange="readURL(this);">
                                            Choose
                                        </p>
                                    </div>
                                    <div id="agency_logo_validate"></div>
                                </div>
<!--                                <div class="form-group">
                                    <label for="agency_slug" class="w-100">Agency URL(slug)</label>
                                    <div class="agency_url_div">
                                        <label for="agency_slug">Realtorhubs.com/</label>
                                        <input type="text"  pattern="\w+" name="agency_slug" class="form-control"
                                           id="agency_slug" aria-describedby="emailHelp" placeholder="Enter agency slug" value="@if(isset($agency_data->agency['slug'])){{$agency_data->agency['slug']}}@endif">
                                    </div>
                                    <span class="form-control-feedback"></span>
                                    <div id="agency_slug_validate"></div>
                                </div>-->
                                <div class="form-row">
<!--                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label for="agency_name">Agency Name</label>
                                            <input type="text"  pattern="\w+" name="agency_name" class="form-control"
                                                   id="agency_name" aria-describedby="emailHelp" placeholder="Enter Agency Name" value="@if(isset($agency_data->agency['agency_name'])){{$agency_data->agency['agency_name']}}@endif">
                                            <span class="form-control-feedback"></span>
                                            <div id="agency_name_validate"></div>
                                        </div>
                                    </div>-->
                                    <div class="col-md-12">
                                        <div class="form-group ">
                                            <label>Timezone</label>
                                            <select class="select2 form-control icon_control dropdown_control timezone" name="timezone" id="timezone">
                                                    <option value="">Select timezone</option>
                                                    @foreach($timezones as $timeZone)
                                                        <option value="{{$timeZone->id}}" @if($agency_data->timezone==$timeZone->id) selected @endif>{{trim($timeZone->timezone)}}</option>
                                                    @endforeach
                                                </select>
                                                <div id="timezone_validate"></div>    
                                        </div>
                                    </div>
                                </div>
                                
							    <div class="form-group text-center">
									<input type="submit" value="Save" name="save" class="auth_btn theme-btn btn-color btn-text btn-size save_home_banner_data" id="save_home_banner_data" >
								</div>
							</div>
	                    	<div class="col-md-3"></div>
	                	</div>
	                </form>
		  		</div>
		  		<div class="tab-pane fade @if($_REQUEST['data'] == 'changePassword') active show  @endif in" id="changePassword" role="tabpanel" aria-labelledby="changePassword-tab">
		  			<form method="POST" action="{{ route('agent.profile.change.password')}}" enctype="multipart/form-data" id="change-password-form" name="change-password-form">
					{{ @csrf_field() }}	
		  				<div class="row">
	                    	<div class="col-md-3"></div>
	                      	<div class="col-md-6">
	                      		<div class="text-center">
	                      			<h2 class="profile_title">Change Password</h2>
	  								<img src="{{ url('public/assets/')}}/images/ic_lock.png" class="">
	                      		</div>
	                    		<input type="hidden" name="user_id" id="user_id" value="{{ Session::get('user_id') }}">
								<div class="form-group has-feedback">
                                    <label>Old Password</label>
                                    <input type="password" class="form-control" placeholder="Old Password" name="old_password" id="old_password" required="">
                                    <div id="old_password_validate"></div>
                                </div>
                                <div class="form-group has-feedback">
                                    <label>New Password</label>
                                    <input type="password" class="form-control" placeholder="New Password" name="new_password" id="new_password" required="">
                                    <div id="new_password_validate"></div>
                                </div>

                                <div class="form-group has-feedback mb-0">
                                    <label>Confirm Password</label>
                                    <input type="password" class="form-control" placeholder="Confirm Password" name="confirm_password" id="confirm_password" required="">
                                    <div id="confirm_password_validate"></div>
                                </div>
								<div class="form-group text-center">
									<input type="submit" value="Save" name="save" class="theme-btn btn-color btn-text btn-size auth_btn save_home_banner_data" id="save_home_banner_data" >
								</div>
							</div>
							<div class="col-md-3"></div>
	                	</div>
	                </form>
	  			</div>
		  		<div class="tab-pane fade @if($_REQUEST['data'] == 'deactivateAccount') active show @endif in" id="deactivateAccount" role="tabpanel" aria-labelledby="deactivateAccount-tab">
		  			<div class="container-fluid">
		  			<div class="row">
		  				<div class="col-md-3"></div>
		  				<div class="col-md-6">
		  					
		  					<div class="text-center">
		  						<h4 class="profile_title">Deactivate Your Account </h4>
	  							<img src="{{ url('public/assets/')}}/images/ic_sad_emoji.png" class="">
	  						</div>
	  						<p class="title_subtext text-center">To re-activate your account, log in using your old email and password. You will then be able to use and access the site like you used to.</p>
							<div class="text-center de_btn">
		  						<button class="auth_btn theme-btn btn-color btn-text btn-size grey_btn deact_btn deactive_account">Deactive</button>
		  						<!-- <button class="theme_btn big_btn deact_btn deleteProfile">Delete Permanently</button> -->
		  					</div>
	  					</div>
	  					<div class="col-md-3"></div>
		  			</div>
		  		</div>
	  			</div>
	  			<div class="tab-pane fade @if($_REQUEST['data'] == 'seoSetting') active show @endif in" id="seoSetting" role="tabpanel" aria-labelledby="seoSetting-tab">
		  			<div class="row mt-10">
	                    <div class="col-md-3"></div>
	                      <div class="col-md-6">
	                    	<!-- Start Home Header Logo Banner -->
							<form method="POST" action="{{ url('agency/saveSEOSettings') }}" enctype="multipart/form-data">
							{{ @csrf_field() }}									
								<div class="row mt-10">
									<div class="form-group">
										<label>Title</label>
										<input type="text" class="form-control" name="meta_title" value="<?php if(isset($data['meta_title'])){ echo $data['meta_title']; }?>">  
									</div>
									<div class="form-group">
										<label>Discription</label>
										<textarea required class="form-control" name="meta_description" id="meta_description"><?php if(isset($data['meta_description'])){ echo $data['meta_description']; }?></textarea>
									</div>
								    <div class="form-group text-center">
										<input type="submit" value="Save" name="save" class="btn btn-primary save_home_banner_data" id="save_home_banner_data" >
									</div>
								</div>
							</form>
	                    </div>
	                    <div class="col-md-3"></div>
	                </div>
	  			</div>
			</div>
		</div>
	</div>
</div>
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
<div class="modal fade auth_modal" id="otp-verify-model" tabindex="-1" role="dialog" aria-labelledby="" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered verify-otp-model" role="document">
        <div class="modal-content">
            <div class="text-center modal-header">

                <div class="modal-title" id="">Verify Otp</div>

                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true"><img src="{{ url('public/assets/images/ic_close.png') }}" alt="close" class="img-close"></span>
                </button>
            </div>

            <div class="modal-body">
                <form method="POST" action="{{url('agency/changeEmail')}}" name="verificationForm" id="verificationForm">
                    <div class="container">
                        <div class="row">
                            <div class="col-12">
                                <div class="form-group mb-0">
                                    <label for="email_otp">OTP </label>
                                    <input type="text" class="form-control email_otp"  name="email_otp" id="email_otp" aria-describedby="textHelp" placeholder="Enter OTP" >
                                    <div name="email_otp_validate" id="email_otp_validate"></div>
                                </div>
                                <div class="text-center">
                                    <button type="submit" class="theme-btn btn-color btn-text btn-size auth_btn" id="otpVerifyModelButton">Submit</button>
                                </div>
                            </div>
                        </div>
                    </div>
                </form>
            </div>
        </div>
        
    </div>	
</div>			
<!-- End Content Body -->
@endsection
@push('custom-styles')
<!-- Include this Page CSS -->
<link rel="stylesheet" type="text/css" href="https://stackpath.bootstrapcdn.com/font-awesome/4.7.0/css/font-awesome.min.css">
<style type="text/css">
	.mt-10{
		margin-top: 20px;
	}
</style>
@endpush
@push('custom-scripts')
<!-- Include this Page Js -->
<script type="text/javascript">
	var base_url_route ='<?php echo url('');?>';
	function readURL(input) {
        if (input.files && input.files[0]) {
            var reader = new FileReader();

            reader.onload = function (e) {
                $('#agency_logo_image').attr('src', e.target.result);
            }

            reader.readAsDataURL(input.files[0]);
        }
    }
</script>
<script type="text/javascript">
     $(document).on('click', '#changeEmailButton', function (e) {
        $('#change-email-model').modal('show');
     });
    $(".country_code").select2({
        templateResult: formatState,
        templateSelection: formatState
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
    });
$(document).on('click','#changeEmailModelButton',function(e){
    var formStatus = $('#chnage_email_form').validate().form();
      //var hourly_rate_error=
  if(formStatus==true){
    e.preventDefault();
    var email=$("#modelemail").val();
    swal({
            text: "Are you sure you want to change email",
            type: 'info',
            showCancelButton: true,
            confirmButtonClass: 'blue_button alert_btn mr-40',
            cancelButtonClass: 'blue_border_button alert_btn',
            confirmButtonText: 'Yes'
        }).then(function (isConfirm) {
            if (isConfirm.value == true) {                
                $.ajax({
                    type: "POST",
                    url: "{{route('agent.change.email')}}",
                    data:{email:email},
                    headers: {
                        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                    },
                    success: function (data) {
                        if (data.code == '200') {
                            toastr.success(data.message);
                            $('#otp-verify-model').modal('show');
                            $('#change-email-model').modal('hide');
                        }else{
                            toastr.error(data.message);
                        }
                    }
                });
            }
        });
    }
});  
$(document).on('click','#otpVerifyModelButton',function(e){
    var formStatus = $('#verificationForm').validate().form();
      //var hourly_rate_error=
  if(formStatus==true){
        e.preventDefault();
        var email=$("#modelemail").val();
        var email_otp=$("#email_otp").val();
        $.ajax({
                type: "POST",
                url: "{{route('agent.change.email.verify')}}",
                data:{email_otp:email_otp,email:email},
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
$(document).on('click','.deactive_account',function(){
    swal({
            text: "Are you sure you want Deactive Account?",
            type: 'info',
            showCancelButton: true,
            confirmButtonClass: 'blue_button alert_btn mr-40',
            cancelButtonClass: 'blue_border_button alert_btn',
            confirmButtonText: 'Yes'
        }).then(function (isConfirm) {
            if (isConfirm.value == true) {                
                $.ajax({
                    type: "POST",
                    url: base_url_route+'/agency/deactive_account',
                    headers: {
                        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                    },
                    success: function (data) {
                        if (data.code == '200') {
                            toastr.success(data.message);
                            setTimeout(function(){
                                location.reload(true);
                            },1500)
                        }else{
                            toastr.error(data.message);
                        }
                    }
                });
            }
        });
});
$(document).ready(function(){
$("#verificationForm").validate({
        // Specify validation rules
    rules: {
        email_otp: {
            required: true,
        },
    },
    // Specify validation error messages
    messages: {
        email_otp: {
            required:"Please enter Email OTP",
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
    jQuery.validator.addMethod("notEqual", function(value, element, param) {
   var old = $('#old_password').val();
   var new_pwd = $('#new_password').val();
  if(old==new_pwd){
      return false;
  }
  return true;
}, "Old Password and New Password Should not Same");
    $('#change-password-form').validate({
        rules: {
        old_password:{
        required:true,
        },
      new_password: {
        required: true,
        minlength:6,
        notEqual:true
      },
      confirm_password: {
        required: true,        
        equalTo:"#new_password"
      },
    },
    // Specify validation error messages
    messages: {   
    old_password:{
        required:'Please Enter Current Password'
        },
      confirm_password: {
        required:"Please Confirm New Password",
        equalTo:"New Password And Confirm Password Does Not Match."
      }, 
      new_password: {
        required:"Please Enter New Password.",
        minlength:"Password Must Be At Least 6 Characters Long.",
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
</script>
<script type="text/javascript" src="{{ url('public/js/agency/signup/edit_signup.js') }}"></script>
<!-- <script type="text/javascript" src="{{ url('public/js/admin/dashboard/profile.js') }}"></script> -->
  <!-- <script type="text/javascript" src="{{ url('public/js/admin/setting/about.js') }}"></script> -->
@endpush