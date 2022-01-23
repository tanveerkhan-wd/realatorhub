$(document).ready(function () {
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

    $(document).off('click','.resend-otp');
    $(document).on('click','.resend-otp',function() {
        $('.loader-outer-container').css('display','table');
        var user_id = $('#user_id').val();

        var form_data = new FormData();
        form_data.append('user_id', user_id);
        $.ajax({
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            },
            type: "POST",
            url: base_url_route + '/agency/resend-otp',
            datatype: JSON,
            processData: false,
            contentType: false,
            cache: false,
            data: form_data, // a JSON object to send back
            success: function (data) {
                $('.loader-outer-container').css('display', 'none');
                if (data.code == 200) {
                   toastr.success(data.message);
                } else if (data.code == 500) {
                    toastr.error(data.message);
                }
                else{
                    toastr.error(data.message);
                }

            }
        });
    });

    })

