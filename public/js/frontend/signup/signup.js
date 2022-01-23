$(document).ready(function(){
    /*$("#upload_logo").change(function() {
        readURL(this);
    });*/
    /*function readURL(input) {
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
    }*/
    $("#sign_up_form").validate({
        rules: {
            first_name: {
                required: true,
            },
            last_name:{
                required: true,
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
            first_name: "Please enter first name",
            last_name: "Please enter last name",
            email:{
                required:"Please enter email",
            },
            country_code: "Please enter country code",
            mobile_number: "Please enter mobile number",
            timezone:{
              required:"Please select timezone"
            },
            password: {
                required: 'Please enter password',
            },
            password_confirm: {
                required:'Please enter confirm password',
                equalTo:'Please enter confirm password as same as password'
            },
            terms_agree: "You must check terms and conditions",
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
        // var agency_logo = $('#agency_logo').val();
        var country_code = $('#country_code').val();
        var phoneNumber = $('#mobile_number').val();
        var mobile_no = country_code+' '+$.trim(phoneNumber);
        var emailId = $('#email').val();
        var timezone = $('#timezone').val();
        var terms_check = $('#terms_agree'). prop("checked");

        var validationCheck = true
        var password = $('#password').val();
        // var coun_password = $('#coun_password').val();

        var re = /^\w+([-+.'][^\s]\w+)*@\w+([-.]\w+)*\.\w+([-.]\w+)*$/;
        var emailFormat = re.test(emailId);// this return result in boolean type
         $validation = $("#sign_up_form").valid();
         console.log($validation);
        if($validation) {
            $('.loader-outer-container').css('display','table');
            var form_data = new FormData();
            form_data.append('first_name', firstName);
            form_data.append('last_name', lastName);
            form_data.append('country_code', country_code);
            form_data.append('mobile_number', mobile_no);
            form_data.append('single_mobile_number', phoneNumber);
            form_data.append('email', emailId);
            form_data.append('password', password);
            form_data.append('user_type', 3);
            form_data.append('admin_status', 1);
            form_data.append('user_status', 1);
            form_data.append('email_verified', 0);
            form_data.append('email_notification', 1);
            form_data.append('push_notification', 1);
            form_data.append('timezone', timezone);
            $.ajax({
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                },
                type: "POST",
                url: base_url_route + '/'+slug+'/sign-up',
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
                         window.location.href = base_url_route + '/'+slug+'/email-verification';
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

