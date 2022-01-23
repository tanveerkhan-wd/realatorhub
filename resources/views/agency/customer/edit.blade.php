@extends('agency.layout.app_with_login')
@section('title','Edit Customer')
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
        <a href="{{route('agency.customer.list')}}" class="parent_page">Customer > </a><a href="#" class="current_page">Edit Customer</a>
    </div>
    <div class="row">
        <div class="col-lg-3 col-md-2"></div>
        <div class="col-lg-6 col-md-8">
            <form method="post" action="{{route('agency.customer.update')}}" class="agent_form" id="customer_form" enctype="multipart/form-data">
                {{ csrf_field() }}
                <div class="form-row">
                    <div class="col-md-6">
                        <div class="form-group">
                            <label>First Name*</label>
                            <input type="hidden" name="id" placeholder="Enter first name" class="form-control" required="" value="{{$user_data->id}}" id="user_id">
                            <input type="text" name="first_name" placeholder="Enter first name" class="form-control" required="" value="{{$user_data->first_name}}">
                            <div id="first_name_validate"></div>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="form-group">
                            <label>Last Name*</label>
                            <input type="text" name="last_name" placeholder="Enter last name" class="form-control" required="" value="{{$user_data->last_name}}">
                        </div>
                        <div id="last_name_validate"></div>
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
                                        <?php $phone_code = "+" . $val->calling_code; ?>
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
                    <div class="col-md-12">
                        <div class="form-group">
                            <label for="agency_logo">Profile Image</label>
                            <div class="upload-file-group">
                                <div class="choose_imd_box text-center">
                                    <?php if (isset($user_data->profile_img) && !empty($user_data->profile_img)) { ?>
                                        <img src="{{ url('public/uploads/profile_photos').'/'.$user_data->profile_img }}" class="" id="agency_logo_image"><br>
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
                    </div>
                    <div class="col-md-12">
                        <div class="form-group ">
                            <label>Timezone</label>
                            <select class="select2 form-control icon_control dropdown_control timezone" name="timezone" id="timezone">
                                <option value="">Select timezone</option>
                                @foreach($timezones as $timeZone)
                                <option value="{{$timeZone->id}}" @if($user_data->timezone==$timeZone->id) selected @endif>{{trim($timeZone->timezone)}}</option>
                                @endforeach
                            </select>
                            <div id="timezone_validate"></div>    
                        </div>
                    </div>
                    <div class="col-12">
                        <div class="text-center two_btns">
                            <button type="submit" class="theme-btn btn-color btn-text btn-size auth_btn">Submit</button>
                            <a href="{{url(url('agency/agent/add'))}}" class="theme-btn btn-color btn-text btn-size auth_btn grey_btn">Cancel</a>
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
                                        $(document).on('click', '#changeEmailButton', function(e) {
                                            $('#change-email-model').modal('show');
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
                                        })
                                        $(document).on('click', '#changeEmailModelButton', function(e) {
                                            var formStatus = $('#chnage_email_form').validate().form();
                                            //var hourly_rate_error=
                                            if (formStatus == true) {
                                                e.preventDefault();
                                                var email = $("#modelemail").val();
                                                var user_id = $("#user_id").val();
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
                                                            url: "{{route('agency.customer.change.mail')}}",
                                                            data: {email: email, id: user_id},
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
                                                                    window.setTimeout(function() {
                                                                        window.location.reload();
                                                                    }, 2000);
                                                                }
                                                            }
                                                        });
                                                    }
                                                });
                                            }
                                        });
                                        $(document).ready(function() {
                                            $("#chnage_email_form").validate({
                                                rules: {
                                                    email: {
                                                        required: true,
                                                        email: true,
                                                        remote: {
                                                            url: '{{route("agency.customer.check.email")}}',
                                                            data: {id: '{{$user_data->id}}'},
                                                            type: "get"
                                                        }
                                                    },
                                                },
                                                messages: {
                                                    email: {
                                                        required: "Please enter email",
                                                        email: "Please enter valid email",
                                                        remote: "Email already in use!"
                                                    },
                                                },
                                                /*errorPlacement: function (error, element) {
                                                 var name = $(element).attr("name");
                                                 error.appendTo($("#" + name + "_validate"));
                                                 },*/

                                            });
                                        });
                                        function readURL(input) {
                                            if (input.files && input.files[0]) {
                                                var reader = new FileReader();

                                                reader.onload = function(e) {
                                                    $('#agency_logo_image').attr('src', e.target.result);
                                                }

                                                reader.readAsDataURL(input.files[0]);
                                            }
                                        }
                                        $("#customer_form").validate({
                                            rules: {
                                                first_name: {
                                                    required: true,
                                                },
                                                last_name: {
                                                    required: true,
                                                },
                                                country_code: {
                                                    required: true,
                                                },
                                                single_mobile_number: {
                                                    required: true,
                                                },
                                                email: {
                                                    required: true,
                                                    email: true
                                                },
                                                timezone: {
                                                    required: true
                                                },
                                            },
                                            messages: {
                                                first_name: "Please Enter First Name",
                                                last_name: "Please Enter Last Name",
                                                email: {
                                                    required: "Please Enter Email Address.",
                                                },
                                                country_code: "Please Select Country Code.",
                                                single_mobile_number: "Please Enter Mobile Number.",
                                                timezone: {
                                                    required: "Please Select Timezone."
                                                },
                                            }
                                        });
                                        $(document).on('click', '#otpVerifyModelButton', function(e) {
                                            var formStatus = $('#verificationForm').validate().form();
                                            //var hourly_rate_error=
                                            if (formStatus == true) {
                                                e.preventDefault();
                                                var email = $("#modelemail").val();
                                                var email_otp = $("#email_otp").val();
                                                var uid = '{{$user_data->id}}';
                                                $.ajax({
                                                    type: "POST",
                                                    url: "{{route('agency.customer.change.email.verify')}}",
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

                                        });
</script>
@endpush