$(document).ready(function(){
    $("#upload_logo").change(function() {
        readURL(this);
    });
    function readURL(input) {
        if (input.files && input.files[0]) {
            var reader = new FileReader();

            reader.onload = function(e) {
                $('#logo_change').attr('src', e.target.result);
                console.log( e.target.result)
            }
            $('.upload_file').val(input.files[0].name);
            console.log(input.files[0]);
            reader.readAsDataURL(input.files[0]); // convert to base64 string
        }
    }
    $("#sign_up_form").validate({
        rules: {
            first_name: {
                required: true,
            },
            last_name:{
                required: true,
            },
            agency_name:{
                required: true,
            },
            agency_slug:{
                required: true,
                remote: {
                    url: base_url_route+'/agency/checkslug',
                    type: "get",
                }
            },
            country_code:{
                required: true,
            },
            mobile_number: {
                required: true,
            },
            email:{
                required: true,
                email: true
            },
            timezone:{
                required:true
            },
            agency_logo: {
                extension: "png|jpe?g|gif",
            },
            terms_agree:{
                required: true,
            },
            password: {
                required: true,
                minlength: 6
            },
            password_confirm: {
                required: true,
                minlength: 6,
                equalTo: "#password"
            }
        },
        messages: {
            first_name: "Please Enter First Name",
            last_name: "Please Enter Last Name",
            email:{
                required:"Please Enter Email Address.",
            },
            agency_name: "Please Enter Agency Name.",
            //agency_slug: "Please enter  agency slug",
            agency_slug: {
                required:"Please Enter Agency Slug",
                remote: "Slug Already In Use!",
            },
            country_code: "Please Select Country Code.",
            mobile_number: "Please Enter Mobile Number.",
            agency_logo: "File Must be JPG, GIF or PNG Format.",
            timezone:{
              required:"Please Select Timezone."
            },
            password: {
                required: 'Please Enter Password',
            },
            password_confirm: {
                required:'Please Enter Confirm Password',
                equalTo:'Password and Confirm Password Must be Same.'
            },
            terms_agree: "Please Check Terms And Conditions.",
        },
        errorPlacement: function (error, element) {
            var name = $(element).attr("name");
            error.appendTo($("#" + name + "_validate"));
        },

    });
    $(document).off('click','#signupForm')
    $(document).on('click','#signupForm',function (e) {
        e.preventDefault();
        var firstName = $('#first_name').val();
        var lastName = $('#last_name').val();
        var agency_name = $('#agency_name').val();
        var agency_slug = $('#agency_slug').val();
        // var agency_logo = $('#agency_logo').val();
        var country_code = $('#country_code').val();
        var phoneNumber = $('#mobile_number').val();
        var mobile_no = country_code+' '+$.trim(phoneNumber);
        var emailId = $('#email').val();
        var timezone = $('#timezone').val();
        var terms_check = $('#terms_agree'). prop("checked");
        var files = $('#upload_logo')[0].files[0];
        // if (typeof files === "undefined") {
        //     $("#agency_logo_validate").append('<div class="alert alert-danger">Please Upload Agency Logo.</div>');
        //     validationCheck = false;
        // }

        var validationCheck = true
        var password = $('#password').val();
        // var coun_password = $('#coun_password').val();

        var re = /^\w+([-+.'][^\s]\w+)*@\w+([-.]\w+)*\.\w+([-.]\w+)*$/;
        var emailFormat = re.test(emailId);// this return result in boolean type
      /*  if($.trim(firstName) == '' ||  firstName == null){
            $("#first_name_validate").append('<div class="alert alert-danger">Please enter First name.</div>');
            validationCheck = false;
        }
        if($.trim(lastName) == '' ||  lastName == null){
            $("#last_name_validate").append('<div class="alert alert-danger">Please enter Last name.</div>');
            validationCheck = false;
        }
        if($.trim(agency_name) == '' ||  agency_name == null){
            $("#agency_name_validate").append('<div class="alert alert-danger">Please enter Agency name.</div>');
            validationCheck = false;
        }
        if($.trim(agency_slug) == '' ||  agency_slug == null){
            $("#agency_slug_validate").append('<div class="alert alert-danger">Please enter Agency slug.</div>');
            validationCheck = false;
        }
        /!*if($.trim(agency_logo) == '' ||  agency_logo == null){
            $("#agency_logo_validate").append('<div class="alert alert-danger">Please Upload Agency Logo.</div>');
            validationCheck = false;
        }*!/
        if($.trim(country_code) == '' ||  country_code == null){
            $("#country_code_validate").append('<div class="alert alert-danger">Please select country code</div>');
            validationCheck = false;
        }
        if($.trim(phoneNumber) == '' ||  phoneNumber == null){
            $("#mobile_number_validate").append('<div class="alert alert-danger">Please enter phone number</div>');
            validationCheck = false;
        }

        if($.trim(timezone) == '' ||  timezone == null){
            $("#timezone_validate").append('<div class="alert alert-danger">Please select timezone</div>');
            validationCheck = false;
        }
*/
   /*     if($.trim(emailId) == '' ||  emailId == null){
            $("#email_id_validate").append('<div class="alert alert-danger">Please enter email.</div>');
            validationCheck = false;
        }
        else{
            if(!emailFormat){
                $("#email_id_validate").append('<div class="alert alert-danger">Please enter valid Email Id.</div>');
                validationCheck = false;
            }
        }
        if ($.trim(password) == '' || password == null) {
            $("#passwordError").append('<div class="alert alert-danger">Please enter password</div>');
            validationCheck = false;
            return false;
        }

        if(terms_check == false){
            $("#terms_validate").append('<div class="alert alert-danger">Please select terms and conditions</div>');
            validationCheck = false;
            return false;
        }

        $('#email_id_validate').html('');
        $('#passwordError').html('');
        $("#terms_validate").html('');
        $("#timezone_validate").html('');*/
         $validation = $("#sign_up_form").valid();
         console.log($validation);
        if($validation) {
            $('.loader-outer-container').css('display','table');
            var form_data = new FormData();
            form_data.append('first_name', firstName);
            form_data.append('last_name', lastName);
            form_data.append('agency_name', agency_name);
            form_data.append('agency_slug', agency_slug);
            form_data.append('country_code', country_code);
            form_data.append('mobile_number', mobile_no);
            form_data.append('single_mobile_number', phoneNumber);
            form_data.append('email', emailId);
            form_data.append('password', password);
            form_data.append('user_type', 1);
            form_data.append('admin_status', 1);
            form_data.append('user_status', 1);
            form_data.append('email_verified', 0);
            form_data.append('email_notification', 1);
            form_data.append('push_notification', 1);
            form_data.append('timezone', timezone);
            form_data.append('user_image', files);
            $.ajax({
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                },
                type: "POST",
                url: base_url_route + '/agency/sign-up',
                datatype: JSON,
                processData: false,
                contentType: false,
                cache: false,
                data: form_data, // a JSON object to send back
                success: function (data) {
                    $('#form_id').trigger("reset");
                    $('.loader-outer-container').css('display', 'none');

                     if (data.code == 301) {
                        $('#sign_in_form').trigger("reset");
                        $('#otp_popup_student_verify').html(data.data.view).modal('show');
                        $('.resend-otp').attr('data-user', data.data.user);
                    }
                    else if (data.code == 200) {
                        $('#sign_in_form').trigger("reset");
                         toastr.success(data.message);
                         console.log(base_url_route + '/agency/email-verification');
                         window.location.href = base_url_route + '/agency/email-verification';
                    }
                    else if (data.code == 302) {
                        $('#sign_in_form').trigger("reset");
                        toastr.warning(data.message);
                        // $('#otp_popup_student_verify').html(data.data.view).modal('show');
                        // $('.mobile_number_block').hide();
                        // $('.mobile_otp_block').show();
                    }
                    else {
                         toastr.warning(data.message);
                        // location.href = base_url_route+'/'+data.action;

                    }

                }
            });
        }


    })


})

