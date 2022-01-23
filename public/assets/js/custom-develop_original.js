$(document).ready(function() {
    // $(function() {
    //     $("#booking_date").datepicker();
    // });

    var date = new Date();
    date.setDate(date.getDate() - 1);
    var currentTime = date.getHours() + ":" + date.getMinutes();



    function formatDate() {
        var d = new Date(),
            month = '' + (d.getMonth() + 1),
            day = '' + d.getDate(),
            year = d.getFullYear();

        if (month.length < 2)
            month = '0' + month;
        if (day.length < 2)
            day = '0' + day;

        return [year, month, day].join('-');
    }


    // $('input[id=booking_time]').datetimepicker({
    //     format: 'LT'
    // });
    // $(function() {
    //     $('input[id=booking_time]').timepicker({
    //         timeFormat: 'h:mm p',
    //         interval: 60,
    //         minTime: '10',
    //         maxTime: '6:00pm',
    //         defaultTime: '11',
    //         startTime: '10:00',
    //         dynamic: false,
    //         dropdown: true,
    //         scrollbar: true
    //     });
    // });





    $('.switch').text('');

    $('.form_time').click(function() {
        $('.switch').text('');
    });

    $('.end_time').click(function() {
        $('.switch').text('');
    });

    $('th[class=switch]').click(function() {
        $(this).text('');
    });

    $('datetimepicker-hours').change(function() {
        $('.switch').text('');
    });

    $("input[name=file1]").change(function() {
        $('.loader-outer-container').css('display', 'table');
        var check_list_id = $('input[name=list_id]').val();

        var files = $(this)[0].files;
        var formData = new FormData();
        for (var i = 0; i < files.length; i++) {
            var file = files[i];
            formData.append('photos', file, file.name);
            formData.append('check_list_id', check_list_id);
            formData.append('type', 1);
        }

        $.ajax({
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            },
            type: "POST",
            url: base_url_route + '/customer/fileupload-proofoffund',
            dataType: 'json',
            processData: false,
            contentType: false,
            cache: false,
            data: formData, // a JSON object to send back                
            success: function(data) {
                $('.loader-outer-container').css('display', 'none');
                if (data.status == true) {
                    toastr.success(data.message);
                    $("input[name=file1]").val('');

                    setTimeout(function() { location.reload(); }, 2000);
                } else {
                    toastr.error(data.message);
                    $("input[name=file1]").val('');
                }
            }
        });
    })

    $("input[name=file2]").change(function() {
        $('.loader-outer-container').css('display', 'table');
        var check_list_id = $('input[name=list_id]').val();
        var files = $(this)[0].files;
        var formData = new FormData();
        for (var i = 0; i < files.length; i++) {
            var file = files[i];
            formData.append('photos', file, file.name);
            formData.append('check_list_id', check_list_id);
            formData.append('type', 2);
        }

        $.ajax({
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            },
            type: "POST",
            url: base_url_route + '/customer/fileupload-proofoffund',
            dataType: 'json',
            processData: false,
            contentType: false,
            cache: false,
            data: formData, // a JSON object to send back                
            success: function(data) {
                $('.loader-outer-container').css('display', 'none');
                if (data.status == true) {
                    toastr.success(data.message);
                    $("input[name=file2]").val('');
                    setTimeout(function() { location.reload(); }, 2000);
                } else {
                    toastr.error(data.message);
                    $("input[name=file2]").val('');
                }
            }
        });
    })

    $("button[id=in_person_meeting_booking_type]").click(function() {
        $('input[name=booking_appintment_type]').val(1);
        $('#booking_appintment_type_title').text('In-Person Meeting');
    });

    $("button[id=over_the_phone_consultant_type]").click(function() {
        $('input[name=booking_appintment_type]').val(2);
        $('#booking_appintment_type_title').text('Over the Phone Consultation');
    });

    $("button[id=schedule_a_showing]").click(function() {
        $('input[name=booking_appintment_type]').val(3);
        $('#booking_appintment_type_title').text('Schedule a showing');
        //$('#booking_appintment_type_title').text('Schedule a showing');
    });

    $("button[id=final_walkthrough_type]").click(function() {
        $('input[name=booking_appintment_type]').val(4);
        $('#booking_appintment_type_title').text('Final Walkthrough');
    });

    $("button[id=final_walkthrough_second_type]").click(function() {
        $('input[name=booking_appintment_type]').val(4);
        $('#booking_appintment_type_title').text('Final Walkthrough');
    });

    $("button[id=photography_booking_type]").click(function() {
        $('input[name=booking_appintment_type]').val(5);
        $('#booking_appintment_type_title').text('Photography Appointment');
    });

    $("button[name=booking_form_submit]").click(function() {
        let date = $('input[name=date]').val();
        let time = $('input[name=time]').val();
        let end_time = $('input[name=end_time]').val();
        let location = $('input[name=location]').val();
        let note = $('textarea[name=note]').val();
        // let check_list_id = $('input[name=check_list_id]').val();
        let form_validation = true;

        if (date == '' || time == '' || location == '' || note == '' || end_time == '') {
            form_validation = false;
            toastr.error('All fields are required.');
        }

        /*if (date == formatDate()) {
            if (currentTime < time) {
                form_validation = false;
                toastr.error('start time is not allowed to before the current time in  current date');
            }

            if (currentTime < end_time) {
                form_validation = false;
                toastr.error('End time is not allowed to before the current time in  current date');
            }
        }*/



        if (time > end_time) {
            toastr.error('End time is not accept less to Start time');
        }

        if (form_validation == true) {
            var bookingform = $('#booking_appointment_form').serialize();
            $('.loader-outer-container').css('display', 'table');
            $.ajax({
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                },
                type: "POST",
                url: base_url_route + '/customer/add-booking-appointment',
                dataType: 'json',
                // processData: false,
                // contentType: false,
                // cache: false,
                // async: false,
                data: bookingform, // a JSON object to send back                
                success: function(data) {
                    $('.loader-outer-container').css('display', 'none');

                    if (data.status == true) {
                        toastr.success(data.message);
                        $('input[name=date]').val('');
                        $('input[name=time]').val('');
                        $('input[name=location]').val('');
                        $('textarea[name=note]').val('');
                        $('input[name=end_time]').val('');
                        $('.flow_detail_modal').modal('hide');
                    } else {
                        toastr.error(data.message);
                    }
                }
            });
        }

    });

    $('#in_person_meeting').on('hidden.bs.modal', function(e) {
        $('input[name=date]').val('');
        $('input[name=time]').val('');
        $('input[name=location]').val('');
        $('textarea[name=note]').val('');
        $('input[name=end_time]').val('');
    });
    // $('#in_person_meeting').on('click', function() {
    //     $('input[name=date]').val('');
    //     $('input[name=time]').val('');
    //     $('input[name=location]').val('');
    //     $('textarea[name=note]').val('');
    //     $('input[name=end_time]').val('');
    // })

    $("button[name=appraisal_form_submit]").click(function() {
        let date = $('input[name=appraisal_date]').val();
        let time = $('input[name=appraisal_time]').val();
        let property_address = $('input[name=property_address]').val();
        // let check_list_id = $('input[name=check_list_id]').val();

        console.log(date);
        console.log(time);
        console.log(property_address);


        let form_validation = true;

        if (date == '' || time == '' || property_address == '') {
            form_validation = false;
            toastr.error('All fields are required');
        }

        if (form_validation == true) {
            var appraisalform = $('#appraisal_form').serialize();
            $('.loader-outer-container').css('display', 'table');
            $.ajax({
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                },
                type: "POST",
                url: base_url_route + '/customer/add-appraisal',
                dataType: 'json',
                // processData: false,
                // contentType: false,
                // cache: false,
                // async: false,
                data: appraisalform, // a JSON object to send back                
                success: function(data) {
                    $('.loader-outer-container').css('display', 'none');

                    if (data.status == true) {
                        toastr.success(data.message);
                        $('input[name=date]').val('');
                        $('input[name=time]').val('');
                        $('input[name=property_address]').val('');
                        $('.flow_detail_modal').modal('hide');
                    } else {
                        toastr.error(data.message);
                    }
                }
            });
        }

    });

    $('#appraisal_modal').on('hidden.bs.modal', function(e) {
        $('input[name=date]').val('');
        $('input[name=time]').val('');
        $('input[name=property_address]').val('');
    })
    $(document).ready(function() {
        $("#customer_offer_form").validate({
            rules: {
                customer_offer_contact_name: "required",
                customer_offer_property_address: "required",
                customer_offer_purchase_price: "required",
                customer_offer_earnest_money_amount: "required",
                customer_offer_closing_date: "required",
                customer_offer_earnest_money_amount: "required",
            },
            /*messages: {
              customer_offer_contact_name: "Please enter contact name",
              customer_offer_property_address: "Please enter contact address",
              customer_offer_purchase_price: "Please enter purchase price",
              customer_offer_earnest_money_amount: "Please enter earnest amount",
              customer_offer_closing_date: "required",
              customer_offer_earnest_money_amount: "required",
            },*/
            submitHandler: function(form) {
                //form.submit();
            }
        });
    });
    $("button[name=customer_offer_form_submit]").click(function() {
        let customer_offer_contact_name = $('input[name=customer_offer_contact_name]').val();
        let customer_offer_property_address = $('input[name=customer_offer_property_address]').val();
        let customer_offer_purchase_price = $('input[name=customer_offer_purchase_price]').val();
        let customer_offer_earnest_money_amount = $('input[name=customer_offer_earnest_money_amount]').val();
        let customer_offer_closing_date = $('input[name=customer_offer_closing_date]').val();
        var $form = $('#customer_offer_form');

        // check if the input is valid
        if (!$form.valid()) return false;
        // let check_list_id = $('input[name=check_list_id]').val();
        let form_validation = true;

        if (customer_offer_contact_name == '' ||
            customer_offer_property_address == '' ||
            customer_offer_purchase_price == '' ||
            customer_offer_earnest_money_amount == '' ||
            customer_offer_closing_date == '') {
            form_validation = false;
            //toastr.error('All fields are required.');
        }

        if (form_validation == true) {
            $('#pageLoadingDiv').css('display', 'inline-table');
            var customer_offer_form = $('#customer_offer_form').serialize();
            $.ajax({
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                },
                type: "POST",
                url: base_url_route + '/customer/add-customer-offer',
                dataType: 'json',
                // processData: false,
                // contentType: false,
                // cache: false,
                // async: false,
                data: customer_offer_form, // a JSON object to send back                
                success: function(data) {
                    $('.loader-outer-container').css('display', 'none');
                    console.log(data.status);
                    if (data.status == true) {
                        toastr.success(data.message);
                        $('input[name=customer_offer_contact_name]').val('');
                        $('input[name=customer_offer_property_address]').val('');
                        $('input[name=customer_offer_purchase_price]').val('');
                        $('input[name=customer_offer_earnest_money_amount]').val('');
                        $('input[name=customer_offer_closing_date]').val('');
                        $('.flow_detail_modal').modal('hide');
                    } else {
                        toastr.error(data.message);
                    }
                }
            });
        }

    });

    $('#submit_an_offer').on('hidden.bs.modal', function(e) {
        $('input[name=customer_offer_contact_name]').val('');
        $('input[name=customer_offer_property_address]').val('');
        $('input[name=customer_offer_purchase_price]').val('');
        $('input[name=customer_offer_earnest_money_amount]').val('');
        $('input[name=customer_offer_closing_date]').val('');
    })

    // $(document).on('change', 'input[name=filei1]', function() {
    //     var form_data_new = new FormData();
    //     var totalfiles = $('input[name=filei1]')[0].files;
    //     form_data_new = totalfiles
    //     console.log(form_data_new);

    //     $.ajax({
    //         headers: {
    //             'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
    //         },
    //         type: "POST",
    //         url: base_url_route + '/customer/fileupload-proofoffund',
    //         processData: false, // tell jQuery not to process the data
    //         contentType: false,
    //         // datatype: JSON,
    //         // processData: false,
    //         // contentType: false,
    //         // cache: false,
    //         data: form_data_new, // a JSON object to send back                
    //         success: function(data) {


    //         }
    //     });
    // });

    /*$(".how_we_work_scroll").click(function() {
        $('html, body').animate({
            scrollTop: $("#how_we_work").offset().top - 130
        }, 2000);
    });*/
    $(document).on('click', '#checkOTP', function() {
        var otp = $('#otp').val();
        var user_id = $('#user_id').val();
        if (otp == '' || otp == null) {
            toastr.error('Please enter the OTP');
            return false;
        } else {
            $('.loader-outer-container').css('display', 'table');
            var form_data = new FormData();
            form_data.append('user_id', $('#user_id').val());
            form_data.append('otp', $('#otp').val());
            $.ajax({
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                },
                type: "POST",
                url: base_url_route + '/verify-otp',
                datatype: JSON,
                processData: false,
                contentType: false,
                cache: false,
                data: form_data, // a JSON object to send back                
                success: function(data) {
                    $('.loader-outer-container').css('display', 'none');
                    if (data.code == 201) {
                        toastr.error(data.message);
                        //swal(data.message);
                        return false;
                    } else if (data.code == 300) {
                        $('#otp_popup_lender_verify').html(data.view).modal('show');
                        return false;
                    } else {
                        toastr.success(data.message);
                        setTimeout(function() {
                            window.location.href = base_url_route + data.action;
                        }, 1500);
                    }

                }
            });
        }
    });

    $(document).on('click', '#checkOTPForgot', function() {
        var otp = $('#otp_forgot').val();
        var user_id = $('#user_id').val();
        if (otp == '' || otp == null) {
            toastr.error('Please enter the OTP');
            return false;
        } else {
            $('.loader-outer-container').css('display', 'table');
            var form_data = new FormData();
            form_data.append('user_id', $('#user_id').val());
            form_data.append('otp', $('#otp_forgot').val());
            $.ajax({
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                },
                type: "POST",
                url: base_url_route + '/verify-otp-forgot',
                datatype: JSON,
                processData: false,
                contentType: false,
                cache: false,
                data: form_data, // a JSON object to send back                
                success: function(data) {
                    $('.loader-outer-container').css('display', 'none');
                    if (data.code == 201) {
                        toastr.error(data.message);
                        //swal(data.message);
                        return false;
                    } else {
                        toastr.success(data.message);
                        $('#otp_popup_lender_verify').html(data.data.view).modal('show');
                    }

                }
            });
        }
    });

});

$(document).on('click', '.forgotPasswordModel', function() {
    $.ajax({
        headers: {
            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
        },
        type: "POST",
        url: base_url_route + '/forgot-password-modal',
        datatype: JSON,
        processData: false,
        contentType: false,
        cache: false,
        // data: form_data, // a JSON object to send back                
        success: function(data) {
            $('#otp_popup_lender_verify').html(data.data.view).modal('show');
        }
    });
});


$(document).on('click', '#forgotPasswordButton', function() {
    var email = $('#email_id_forgot').val();
    var re = /^\w+([-+.'][^\s]\w+)*@\w+([-.]\w+)*\.\w+([-.]\w+)*$/;
    var emailFormat = re.test(email); // this return result in boolean type  



    if ($.trim(email) == '' || email == null) {
        toastr.error('Please enter email');
        return false;
    }
    if (!emailFormat) {
        toastr.error('Please enter valid Email');
        return false;
    } else {
        $('#email_id_forgot_error').html('');
        $('.loader-outer-container').css('display', 'table');
        var form_data = new FormData();
        form_data.append('email', email);
        $.ajax({
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            },
            type: "POST",
            url: base_url_route + '/forgot-password-post',
            datatype: JSON,
            processData: false,
            contentType: false,
            cache: false,
            data: form_data, // a JSON object to send back                
            success: function(data) {
                $('.loader-outer-container').css('display', 'none');
                if (data.code == 201) {
                    toastr.error(data.message);
                    //swal(data.message);
                    return false;
                } else if (data.code == 200) {
                    toastr.success(data.message);
                    $('#otp_popup_lender_verify').html(data.data.view).modal('show');
                }
            }
        });

    }
});


$(document).on('click', '.siginclickModel', function() {
    $.ajaxSetup({
        beforeSend: function(xhr, type) {
            if (!type.crossDomain) {
                xhr.setRequestHeader('X-CSRF-Token', $('meta[name="csrf-token"]').attr('content'));
            }
        },
    });
    $.ajax({
        headers: {
            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
        },
        type: "POST",
        url: base_url_route + '/sign-in-modal',
        datatype: JSON,
        processData: false,
        contentType: false,
        cache: false,
        data: { "_token": $('meta[name="csrf-token"]').attr('content') }, // a JSON object to send back                
        success: function(data) {
            $('#otp_popup_lender_verify').html(data.data.view).modal('show');
        }
    });
});


$(document).on('click', '#resetPasswordCheck', function() {
    var new_password_reset = $('#new_password_reset').val();
    var confirm_password_reset = $('#confirm_password_reset').val();

    if ($.trim(new_password_reset) == '' || new_password_reset == 'null') {
        toastr.error('New Password is required');
        return false;
    }
    if ($.trim(confirm_password_reset) == '' || confirm_password_reset == 'null') {
        toastr.error('Confirm Password is required');
        return false;
    }
    if ($.trim(confirm_password_reset) != $.trim(new_password_reset)) {
        toastr.error('New and Confirm Password must be same');
        return false;
    }
    if (new_password_reset.length < 6) {
        toastr.error('Please enter at least 6 characters.');
        return false;
    } else {
        $('#new_password_reset_error').html('');
        $('#confirm_password_reset_error').html('');
    }
    $('.loader-outer-container').css('display', 'table');
    var form_data = new FormData();
    form_data.append('new_password_reset', new_password_reset);
    form_data.append('confirm_password_reset', confirm_password_reset);
    form_data.append('user_id', $('#user_id').val());
    $.ajax({
        headers: {
            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
        },
        type: "POST",
        url: base_url_route + '/reset-passsword-post',
        datatype: JSON,
        processData: false,
        contentType: false,
        cache: false,
        data: form_data, // a JSON object to send back                
        success: function(data) {
            $('.loader-outer-container').css('display', 'none');
            if (data.code == 201) {
                toastr.error(data.message);
                return false;
            } else {
                toastr.success(data.message);
                setTimeout(function() {
                    location.href = base_url_route;
                }, 1500)

            }

        }
    });

});

$(document).on('click', '.signincheck', function() {
    var emailId = $('#email_id_signin').val();
    var password = $('#password_signin').val();

    var re = /^\w+([-+.'][^\s]\w+)*@\w+([-.]\w+)*\.\w+([-.]\w+)*$/;
    var emailFormat = re.test(emailId); // this return result in boolean type    

    if ($.trim(emailId) == '' || emailId == null) {
        toastr.error('Please enter email');
        return false;
    }
    if ($.trim(password) == '' || password == null) {
        toastr.error('Please enter password');
        return false;
    }
    if (password.length < 6) {
        toastr.error('Please enter at least 6 characters.');
        return false;
    }
    if (!emailFormat) {
        toastr.error('Please enter valid Email');
        return false;
    }

    $('#emailIdError').html('');
    $('#passwordError').html('');
    $('.loader-outer-container').css('display', 'table');
    var form_data = new FormData();
    form_data.append('emailId', emailId);
    form_data.append('password', password);
    $.ajax({
        headers: {
            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
        },
        type: "POST",
        url: base_url_route + '/login-check-front',
        datatype: JSON,
        processData: false,
        contentType: false,
        cache: false,
        data: form_data, // a JSON object to send back                
        success: function(data) {
            $('.loader-outer-container').css('display', 'none');
            if (data.code == 201) {
                toastr.error(data.message);
                //swal(data.message);
                return false;
            } else if (data.code == 500) {
                toastr.warning(data.message);
                $('#otp_popup_lender_verify').html(data.data.view).modal('show');
            } else {
                toastr.success(data.message);

                setTimeout(function() {


                    var currLoc = $(location).attr('href');
                    if (currLoc == base_url_route + '/preview-details') {
                        location.href = base_url_route + '/save-flow-details';
                    } else if (currLoc == base_url_route + '/preview-details#') {
                        location.href = base_url_route + '/save-flow-details';
                    } else {
                        location.href = base_url_route + '/' + data.action;
                    }

                }, 1500)

            }

        }
    });

});


$.fn.digits = function() {
    return this.each(function() {
        $(this).text($(this).text().replace(/(\d)(?=(\d\d\d)+(?!\d))/g, "$1,"));
    })
}


function ReplaceNumberWithCommas(yourNumber) {
    //Seperates the components of the number
    var n = yourNumber.toString().split(".");
    //Comma-fies the first part
    n[0] = n[0].replace(/\B(?=(\d{3})+(?!\d))/g, ",");
    //Combines the two sections
    return n.join(".");
}

$(document).on('click', '.siginclickModel', function() {
    $.ajax({
        headers: {
            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
        },
        type: "POST",
        url: base_url_route + '/sign-in-modal',
        datatype: JSON,
        processData: false,
        contentType: false,
        cache: false,
        // data: form_data, // a JSON object to send back                
        success: function(data) {
            $('#otp_popup_lender_verify').html(data.data.view).modal('show');
        }
    });
});
$(document).on('click', '.signupClickModel', function() {
    $.ajax({
        headers: {
            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
        },
        type: "POST",
        url: base_url_route + '/sign-up-modal',
        datatype: JSON,
        processData: false,
        contentType: false,
        cache: false,
        // data: form_data, // a JSON object to send back                
        success: function(data) {
            $('#otp_popup_lender_verify').html(data.data.view).modal('show');
        }
    });
});
$(document).on('click', '.signupcheck', function() {
    var firstName = $('#first_name').val();
    var lastName = $('#last_name').val();
    var phoneNumber = $('#phone_number').val();
    var emailId = $('#email_id_signin').val();
    var password = $('#password_signin').val();

    var re = /^\w+([-+.'][^\s]\w+)*@\w+([-.]\w+)*\.\w+([-.]\w+)*$/;
    var emailFormat = re.test(emailId); // this return result in boolean type    
    if ($.trim(firstName) == '' || firstName == null) {
        toastr.error('Please enter first name');
        return false;
    }
    if ($.trim(lastName) == '' || lastName == null) {
        toastr.error('Please enter last name');
        return false;
    }
    if ($.trim(emailId) == '' || emailId == null) {
        toastr.error('Please enter email');
        return false;
    }
    if (!emailFormat) {
        toastr.error('Please enter valid Email');
        return false;
    }
    if ($.trim(phoneNumber) == '' || phoneNumber == null) {
        toastr.error('Please enter phone number');
        return false;
    }
    if ($.trim(password) == '' || password == null) {
        toastr.error('Please enter password');
        return false;
    }
    if (password.length < 6) {
        toastr.error('Please enter at least 6 characters.');
        return false;
    }


    $('#emailIdError').html('');
    $('#passwordError').html('');
    $('.loader-outer-container').css('display', 'table');
    var form_data = new FormData();
    form_data.append('firstName', firstName);
    form_data.append('lastName', lastName);
    form_data.append('email', emailId);
    form_data.append('password', password);
    form_data.append('phoneNumber', phoneNumber);
    $.ajax({
        headers: {
            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
        },
        type: "POST",
        url: base_url_route + '/sign-up',
        datatype: JSON,
        processData: false,
        contentType: false,
        cache: false,
        data: form_data, // a JSON object to send back                
        success: function(data) {
            $('.loader-outer-container').css('display', 'none');
            if (data.code == 201) {
                toastr.error(data.message);
                //swal(data.message);
                return false;
            } else if (data.code == 500) {
                toastr.warning(data.message);

            } else {
                toastr.success(data.message);
                setTimeout(function() {
                    var currLoc = $(location).attr('href');
                    $('#otp_popup_lender_verify').html(data.data.view).modal('show');
                    /*if(currLoc==base_url_route+'/preview-details' ){
                        //
                       location.href = base_url_route+'/save-flow-details';
                    }else if(currLoc==base_url_route+'/preview-details#'){
                        location.href = base_url_route+'/save-flow-details';
                    }
                    else{
                        location.href = base_url_route+'/'+data.action;
                    }*/
                }, 1500)

            }

        }
    });

});
$(document).on('click', '#resetPasswordCheck', function() {
    var new_password_reset = $('#new_password_reset').val();
    var confirm_password_reset = $('#confirm_password_reset').val();

    if ($.trim(new_password_reset) == '' || new_password_reset == 'null') {
        toastr.error('New Password is required');
        return false;
    }
    if ($.trim(confirm_password_reset) == '' || confirm_password_reset == 'null') {
        toastr.error('Confirm Password is required');
        return false;
    }
    if ($.trim(confirm_password_reset) != $.trim(new_password_reset)) {
        toastr.error('New and Confirm Password must be same');
        return false;
    }
    if (new_password_reset.length < 6) {
        toastr.error('Please enter at least 6 characters.');
        return false;
    } else {
        $('#new_password_reset_error').html('');
        $('#confirm_password_reset_error').html('');
    }
    $('.loader-outer-container').css('display', 'table');
    var form_data = new FormData();
    form_data.append('new_password_reset', new_password_reset);
    form_data.append('confirm_password_reset', confirm_password_reset);
    form_data.append('user_id', $('#user_id').val());
    $.ajax({
        headers: {
            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
        },
        type: "POST",
        url: base_url_route + '/reset-passsword-post',
        datatype: JSON,
        processData: false,
        contentType: false,
        cache: false,
        data: form_data, // a JSON object to send back                
        success: function(data) {
            $('.loader-outer-container').css('display', 'none');
            if (data.code == 201) {
                toastr.error(data.message);
                return false;
            } else {
                toastr.success(data.message);
                setTimeout(function() {
                    location.href = base_url_route;
                }, 1500)

            }

        }
    });
});
$(document).on('click', '.checkbox-select', function() {
    var message = '';
    var status = '';
    var label_name = '';

    if ($(this).prop("checked") == true) {
        message = "Are you sure you want to check?";
        status = 1;
    } else if ($(this).prop("checked") == false) {
        message = "Are you sure you want to un-check?";
        status = 0;
    }
    var check_list_id = $(this).data('checklistid');
    var taxtid = $(this).data('textid');
    label_name = $(this).attr('label_name');
    swal({
        text: message,
        type: 'info',
        showCancelButton: true,
        confirmButtonClass: 'blue_button alert_btn mr-40',
        cancelButtonClass: 'blue_border_button alert_btn',
        confirmButtonText: 'Yes'
    }).then(function(isConfirm) {
        if (isConfirm.value == true) {
            $('.loader-outer-container').css('display', 'table');
            $.ajax({
                type: "POST",
                url: base_url_route + '/checklistStaticTextChecked',
                data: { checklistid: check_list_id, taxtid: taxtid, status: status, label_name: label_name },
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                },
                success: function(data) {
                    $('.loader-outer-container').css('display', 'none');
                    if (data.code == 200) {
                        swal('Success', data.message, 'success');

                    } else {
                        swal('Error', data.message, 'error');
                    }
                }
            });
        } else {
            setTimeout(function() { location.reload(); }, 1000);
            /*if($(this).prop("checked") == true){
                console.log('1');
                $(this).prop("checked", false);
            }
            else if($(this).prop("checked") == false){
                console.log('2');
                $(this).prop("checked", true);
            }*/
        }
    });
    /*$('input[id=customer_offer_closing_date]').val(formatDate());

    $('input[id=customer_offer_closing_date]').datepicker({
        format: 'yyyy-mm-dd',
        // todayHighlight: true,
        autoclose: true,
        startDate: formatDate(),
    });
    $('input[id=booking_date]').val(formatDate());

    $('input[id=booking_date]').datepicker({
        format: 'yyyy-mm-dd',
        // todayHighlight: true,
        autoclose: true,
        startDate: formatDate(),
    });*/
    /*$('.form_time').datetimepicker({
        // language: 'fr',
        weekStart: 1,
        // todayBtn: 1,
        autoclose: 1,
        todayHighlight: 1,
        startView: 1,
        minView: 0,
        maxView: 1,
        forceParse: 0
    });

    $('.end_time').datetimepicker({
        // language: 'fr',
        weekStart: 1,
        // todayBtn: 1,
        autoclose: 1,
        // todayHighlight: 1,
        startView: 1,
        // minView: 0,
        // maxView: 1,
        // forceParse: 0
    });*/
})