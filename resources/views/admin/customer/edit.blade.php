@extends('admin.layout.app_with_login')
@section('title','Customer')
@section('content')
<!-- Content Body -->
<section class="content">
    <div class="row new_added_div">
        <!-- left column -->
        <div class="col-md-12">
            <div class="path_link">
                    <a href="#" class="parent_page">Customer > </a><a href="#" class="current_page">Edit</a>
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
            <div class="row">
                    <form id="sign_up_form" method="POST" action="{{ route('admin.agency.customer.update') }}" enctype="multipart/form-data">
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
                                    <label for="email" class="" id="changeEmailButton">Email ID <i class="far fa-edit"></i></label>
                                    <input type="email"  name="old_email" class="form-control" id="email" placeholder="Enter Email Id" value="@if(isset($agency_data->email)){{$agency_data->email}} @endif" readonly="">
                                    <div id="email_validate"></div>
                                </div>
                                <div class="form-group">
                                    <label>Phone*</label>
                                    <div class="row">
                                        <div class="col-xs-4">
                                            <select class="form-control dropdown_control country_code" name="country_code" id="country_code">
                                                <option value="">Select Code</option>
                                                @foreach($country_code as $key=>$val)
                                                <?php $phone_code = "+" . $val->calling_code; ?>
                                                <option value="+{{$val->calling_code}}"  data-image="{{ url('public/uploads/country_images/'.$val->flag) }}" @if($agency_data->phone_code==$phone_code) selected @endif>{{"+".$val->calling_code." ".$val->name}}</option>
                                                @endforeach
                                            </select>
                                            <div id="country_code_validate"></div>
                                        </div>
                                        <div class="col-xs-8">
                                            <input  type="text"  name="mobile_number" class="form-control" id="mobile_number" aria-describedby="emailHelp" placeholder="Enter Number" value="@if(isset($agency_data->phone)){{$agency_data->phone}} @endif">
                                            <div id="mobile_number_validate"></div>
                                        </div>
                                    </div>
                                </div>
                                <div class="form-group">
                                    <label for="agency_logo">Profile Image</label>
                                    <div class="upload-file-group">
                                        <div class="choose_imd_box text-center">
                                            <?php if (isset($agency_data->profile_img) && !empty($agency_data->profile_img)) { ?>
                                                <img src="{{ url('public/uploads/profile_photos').'/'.$agency_data->profile_img }}" class="" id="agency_logo_image"><br>
                                            <?php } else { ?> 
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
</section>
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
    var base_url_route = '<?php echo url(''); ?>';
    function readURL(input) {
        if (input.files && input.files[0]) {
            var reader = new FileReader();

            reader.onload = function(e) {
                $('#agency_logo_image').attr('src', e.target.result);
            }

            reader.readAsDataURL(input.files[0]);
        }
    }
</script>
<script type="text/javascript">
    $(document).on('click', '#changeEmailButton', function(e) {
        $('#change-email-model').modal('show');
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

    $(document).on('keypress', '#user_name', function(e) {
        if (e.which === 32)
        {
            return false;
        }
    });
    $(document).on('click', '#changeEmailModelButton', function(e) {
        var formStatus = $('#chnage_email_form').validate().form();
        //var hourly_rate_error=
        if (formStatus == true) {
            e.preventDefault();
            var email = $("#modelemail").val();
            var uid = '{{$agency_data->id}}';
            var agencyid = '{{$agency_id}}';
            swal({
                text: "Are you sure you want to change the email?",
                type: 'info',
                showCancelButton: true,
                confirmButtonClass: 'blue_button alert_btn mr-40',
                cancelButtonClass: 'blue_border_button alert_btn',
                confirmButtonText: 'Yes'
            }).then(function(isConfirm) {
                if (isConfirm.value == true) {
                    $.ajax({
                        type: "POST",
                        url: '{{route('admin.agency.customer.change.mail')}}',
                        data: {email: email, id: uid,agencyid:agencyid},
                        headers: {
                            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                        },
                        success: function(data) {
                            if (data.code == '200') {
                                toastr.success(data.message);
                                $('#otp-verify-model').modal('show');
                                $('#change-email-model').modal('hide');
                            } else {
                                toastr.error(data.message);
                            }
                        }
                    });
                }
            });
        }
    });
    $(document).on('click', '#otpVerifyModelButton', function(e) {
        var formStatus = $('#verificationForm').validate().form();
        //var hourly_rate_error=
        if (formStatus == true) {
            e.preventDefault();
            var email = $("#modelemail").val();
            var email_otp = $("#email_otp").val();
            var uid = '{{$agency_data->id}}';
            $.ajax({
                type: "POST",
                url: "{{route('admin.agency.change.email.verify')}}",
                data: {email_otp: email_otp, email: email, id: uid},
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                },
                success: function(data) {
                    if (data.code == '200') {
                        toastr.success(data.message);
                        window.setTimeout(function() {
                            window.location.reload();
                        }, 2000);
                    } else {
                        toastr.error(data.message);
                        window.setTimeout(function() {
                            window.location.reload();
                        }, 2000);
                    }
                }
            });
        }
    });
    $(document).ready(function() {
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
                    required: "Please enter Email OTP",
                }
            },
            errorPlacement: function(error, element) {
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
                email: {
                    required: true,
                    email: true,
                    remote: {
                        url: '{{route("admin.agency.customer.check.email")}}',
                        data: {id: '{{$agency_data->id}}',agency_id:'{{$agency_id}}'},
                        type: "get"
                    }
                },
            },
            messages: {
                email: {
                    required: "Please enter email",
                    email: "Please enter valide email",
                    remote: "Email already in use!"
                },
            },
            /*errorPlacement: function (error, element) {
             var name = $(element).attr("name");
             error.appendTo($("#" + name + "_validate"));
             },*/

        });
        $('#change-password-form').validate({
            rules: {
                old_password: {
                    required: true
                },
                new_password: {
                    required: true,
                    minlength: 6,
                },
                confirm_password: {
                    required: true,
                    equalTo: "#new_password"
                },
            },
            // Specify validation error messages
            messages: {
                old_password: {
                    required: 'Please Enter Old Password'
                },
                confirm_password: {
                    required: "Please enter Confirm Password",
                    equalTo: "New password and Confirm password does not match"
                },
                new_password: {
                    required: "Please enter New Password",
                    minlength: "Password must be at least 6 characters long.",
                }
            },
            errorPlacement: function(error, element) {
                var name = $(element).attr("name");
                error.appendTo($("#" + name + "_validate"));
            },
            // Make sure the form is submitted to the destination defined
            // in the "action" attribute of the form when valid
            submitHandler: function(form) {
                form.submit();
            }
        });
        $('#agency_slug').change(function() {
            var slug = $(this).val();
            var uid = '{{$agency_data->id}}';
            $.ajax({
                type: "POST",
                url: "{{route('admin.agency.check.unique.slug')}}",
                data: {slug: slug, id: uid},
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                },
                success: function(data) {
                    if (data.code == '201') {
                        toastr.error(data.message);
                    }
                }
            });
        });
    });
</script>
<script type="text/javascript" src="{{ url('public/js/agency/signup/edit_signup.js') }}"></script>
<!-- <script type="text/javascript" src="{{ url('public/js/admin/dashboard/profile.js') }}"></script> -->
  <!-- <script type="text/javascript" src="{{ url('public/js/admin/setting/about.js') }}"></script> -->
@endpush