@extends('agency.layout.app_with_login')
@section('title','Add Agent')
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
            <a href="{{url('agency/agent')}}" class="parent_page">Agent > </a><a href="#" class="current_page">Add Agent</a>
        </div>
        <div class="row">
            <div class="col-lg-3 col-md-2"></div>
            <div class="col-lg-6 col-md-8">
                <form method="post" action="{{url('agency/agent/addAgentPost')}}" class="agent_form">
                    {{ csrf_field() }}
                    <div class="form-row">
                        <div class="col-md-6">
                            <div class="form-group">
                                <label>First Name*</label>
                                <input type="text" name="first_name" placeholder="Enter first name" class="form-control" required="">
                                <div id="first_name_validate"></div>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group">
                                <label>Last Name*</label>
                                <input type="text" name="last_name" placeholder="Enter last name" class="form-control" required="">
                                <div id="last_name_validate"></div>
                            </div>
                        </div>
                        <div class="col-md-12">
                            <div class="form-group">
                                <label>Agent ID*</label>
                                <input type="text" name="agent_id" placeholder="Enter Agent ID" class="form-control" required="">
                                <div id="agent_id_validate"></div>
                            </div>
                        </div>
                        <div class="col-md-12">
                            <div class="form-group">
                                <label>Email ID*</label>
                                <input type="text" name="email" placeholder="Enter Email ID" class="form-control" required="">
                                <div id="email_validate"></div>
                            </div>
                        </div>
                        <div class="col-md-12">
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
                                        <input type="text" name="single_mobile_number" placeholder="Enter number" class="form-control" required="">
                                        <div id="single_mobile_number_validate"></div>
                                    </div>
                                </div>
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
$(document).ready(function(){
$("form[name='verificationForm']").validate({
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
$(".agent_form").validate({
        rules: {
            first_name: {
                required: true,
            },
            last_name:{
                required: true,
            },
            agent_id:{
                required: true,
            },
            country_code:{
                required: true,
            },
            single_mobile_number: {
                required: true,
            },
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
            first_name: "Please enter first name",
            last_name: "Please enter last name",
            agent_id: "Please enter agent id",
            country_code: "Please enter country code",
            single_mobile_number: "Please enter mobile number",
            email:{
                required:"Please enter email",
                email:"Please enter valide email",
                remote: "Email already in use!"
            },
        },
        errorPlacement: function (error, element) {
            var name = $(element).attr("name");
            error.appendTo($("#" + name + "_validate"));
        },
    });
});
</script>

<!-- <script type="text/javascript" src="{{ url('public/js/agent/signup/signup.js') }}"></script> -->
@endpush