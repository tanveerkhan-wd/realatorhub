@extends('agency.layout.app_without_login')
@section('title','Login')
@section('content')
    <section class="grey_bg auth_section">
        <div class="container-fluid path_link_container">
            <div class="path_link">
                <a href="#" class="current_page">Personal Information </a><a href="#"> > Verify Email > </a><a href="#" class="">Select Subscription</a>
            </div>
        </div>

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
        <div class="container">
            <div class="row">
                <div class="col-lg-1"></div>
                <div class="col-lg-10">
                    <div class="white_box auth_box">
                        <div class="row">
                            <div class="col-lg-3"></div>
                            <div class="col-lg-6">
                                <div class="text-center">
                                    <h1 class="auth_title">Sign Up</h1>
                                </div>
                                <form class="" id="sign_up_form" action="javascript:void(0)">
                                    <div class="form-row">
                                        <div class="col-md-6">
                                            <div class="form-group">
                                                <label for="first_name">First Name</label>
                                                <input type="text"  pattern="\w+" name="first_name" class="form-control"
                                                       id="first_name" aria-describedby="emailHelp" placeholder="Enter First Name" value="">
                                                <span class="form-control-feedback"></span>
                                                <div id="first_name_validate"></div>
                                            </div>
                                        </div>
                                        <div class="col-md-6">
                                            <div class="form-group">
                                                <label for="last_name">Last Name</label>
                                                <input type="text"  pattern="\w+" name="last_name" class="form-control"
                                                       id="last_name" aria-describedby="emailHelp" placeholder="Enter Last Name" value="">
                                                <span class="form-control-feedback"></span>
                                                <div id="last_name_validate"></div>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="form-group">
                                        <label for="email">Email ID</label>
                                            <input type="email"  name="email" class="form-control" id="email" placeholder="Enter Email Id">
                                           <div id="email_validate"></div>
                                    </div>
                                    <div class="form-group">
                                        <label>Phone*</label>
                                        <div class="form-row">
                                            <div class="col-4">
                                                <select class="form-control dropdown_control country_code" name="country_code" id="country_code">
                                                    <option value="">Select Code</option>
                                                    @foreach($country_code as $key=>$val)
                                                        <option value="+{{$val->calling_code}}"  data-image="{{ url('public/uploads/country_images/'.$val->flag) }}" >{{"+".$val->calling_code." ".$val->name}}</option>
                                                    @endforeach
                                                </select>
                                                <div id="country_code_validate"></div>
                                            </div>
                                            <div class="col-8">
                                                <input  type="text"  name="mobile_number" class="form-control" id="mobile_number" aria-describedby="emailHelp" placeholder="Enter Number">
                                                <div id="mobile_number_validate"></div>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="form-group">
                                        <label for="agency_logo">Logo</label>
                                        <div class="upload-file-group">
                                            <input type="text" name="" class="form-control upload_control upload_file" readonly="" placeholder="Upload here">
                                            <button class="file_upload_btn btn-color">
                                                <input type="file" name="agency_logo" class="agency_logo file_control" id="upload_logo">
                                                <img src="{{url('public/assets/images/ic_upload.png') }}">
                                            </button>
                                        </div>
                                        <div id="agency_logo_validate"></div>
                                    </div>
                                    <div class="form-group">
                                        <label for="agency_slug" class="w-100">Agency URL(slug)</label>
                                        <div class="agency_url_div">
                                            <label for="agency_slug">Realtorhubs.com/</label>
                                            <input type="text"  pattern="\w+" name="agency_slug" class="form-control"
                                               id="agency_slug" aria-describedby="emailHelp" placeholder="Enter agency slug" value="">
                                        </div>
                                        <span class="form-control-feedback"></span>
                                        <div id="agency_slug_validate"></div>
                                    </div>
                                    <div class="form-row">
                                        <div class="col-md-6">
                                            <div class="form-group">
                                                <label for="agency_name">Agency Name</label>
                                                <input type="text" name="agency_name" class="form-control"
                                                       id="agency_name" placeholder="Enter Agency Name" value="">
                                                <span class="form-control-feedback"></span>
                                                <div id="agency_name_validate"></div>
                                            </div>
                                        </div>
                                        <div class="col-md-6">
                                            <div class="form-group ">
                                                <label>Timezone</label>
                                                <select class="select2 form-control icon_control dropdown_control timezone" name="timezone" id="timezone">
                                                    <option value="">Select timezone</option>
                                                    @foreach($timezones as $timeZone)
                                                        <option value="{{$timeZone->id}}" >{{trim($timeZone->timezone)}}</option>
                                                    @endforeach
                                                </select>
                                                <div id="timezone_validate"></div>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="form-group">
                                        <label for="password">Password</label>
                                        <input name="password"
                                               type="password" class="form-control" id="password" placeholder="Enter Password">
                                        <div id="password_validate"></div>
                                    </div>
                                    <div class="form-group">
                                        <label for="confirm_password">Confirm Password</label>
                                        <input name="password_confirm"
                                               type="password" class="form-control" id="password_confirm" placeholder="Enter Confirm Password">
                                        <div id="password_confirm_validate"></div>
                                    </div>
                                    <div class="form-group form-check">
                                        <input type="checkbox" class="form-check-input check_val" name="terms_agree" id="terms_agree" value="">
                                        <label class="custom_checkbox"></label>
                                        <label class="form-check-label label-text" for="terms_agree">I am read and accept all
                                            <a href="https://digiteau.com/policies/terms-of-service" target="_blanck" class="link-page">Terms & Conditions</a> and
                                            <a href="https://digiteau.com/policies/privacy-policy" target="_blanck" class="link-page">Privacy Policy</a>
                                        </label>
                                        <span id="terms_span"></span>
                                        <div id="terms_agree_validate"></div>
                                    </div>

                                    <div class="text-center">
                                        <button type="submit" class="theme-btn btn-color btn-text btn-size auth_btn" id="signupForm">Sign Up</button>
                                    </div>
                                    <div class="bottom-link text-center">
                                        <p>Already have an account?<a href="{{ url('agency/login') }}"> Login</a></p>
                                    </div> 
                                </form>
                            </div>
                            <div class="col-lg-3"></div>
                        </div>
                    </div>
                </div>
                <div class="col-lg-1"></div>
            </div>
        </div>
    </section>
@endsection
@push('custom-styles')
<style>
    .alert {
        position: relative;
        margin: 3px 0;
        width: 100%;
    }
</style>
@endpush

@push('custom-scripts')
<script src="https://www.jqueryscript.net/demo/jQuery-International-Telephone-Input-With-Flags-Dial-Codes/build/js/intlTelInput.js"></script>
<script type="text/javascript">
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
    })

</script>

<script type="text/javascript" src="{{ url('public/js/agency/signup/signup.js') }}"></script>
@endpush