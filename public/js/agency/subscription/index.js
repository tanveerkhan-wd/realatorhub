$(document).ready(function () {

    $(document).off('click', '.upgrade_plan')
    $(document).on('click', '.upgrade_plan', function (e) {
        var plan_id = $(this).attr('data-plan-id');
        var active_subscription_id = $(this).attr('data-active-plan-id');
        if(notFoundUserCard == '1') {
            if (enableUpgradeDowngrade == '1') {
                swal({
                    // title:'Cancel Booking',
                    text: "Are you sure you want to Upgrade Plan ?",
                    type: 'warning',
                    showCancelButton: true,
                    confirmButtonClass: 'blue_button alert_btn mr-40',
                    cancelButtonClass: 'blue_border_button alert_btn',
                    confirmButtonText: 'Upgrade Plan'
                }).then(function (isConfirm) {
                    if (isConfirm.value == true) {
                        $('.loader-outer-container').css('display', 'table');
                        $.ajax({
                            type: "POST",
                            url: base_url_route + '/agency/subscription/upgrade-subscription',
                            data: {
                                'plan_id': plan_id,
                                'active_subscription_id': active_subscription_id
                            },
                            headers: {
                                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                            },
                            success: function (data) {
                                $('.loader-outer-container').css('display', 'none');
                                if (data.code == 200) {
                                    toastr.success(data.message)
                                    window.location.reload();
                                }
                                else {
                                    toastr.error(data.message)
                                }

                            }
                        });
                    }
                });
            }
            else {
                swal("Oops!", "You Can upgrade or downgrade plan on or before one day of expiration or renewal", "warning");
            }
        }
        else{
            swal({
                title:'Oops!',
                text: "Please add card for Payment",
                type: 'warning',
                showCancelButton: true,
                confirmButtonClass: 'blue_button alert_btn mr-40',
                cancelButtonClass: 'blue_border_button alert_btn',
                confirmButtonText: 'Ok'
            }).then(function (isConfirm) {
                if (isConfirm.value == true) {
                    window.location.href = base_url_route  + '/agency/subscription/payment-setting'
                }
            });
        }
    });

    $(document).off('click', '.downgrade_plan')
    $(document).on('click', '.downgrade_plan', function (e) {
        var plan_id = $(this).attr('data-plan-id');
        var active_subscription_id = $(this).attr('data-active-plan-id');
        var total_additional_agent = $(this).attr('data-total-additional-agent');
        var plan_additional_agent = $(this).attr('data-plan-additional-agent');
        if(notFoundUserCard == '1') {
            if (enableUpgradeDowngrade == '1') {
                if (parseInt(plan_additional_agent) < parseInt(total_additional_agent)) {
                    swal("Please Remove Additional Agent", "Your agents should be less or equal to plan agents.", "warning")
                }
                else {
                    swal({
                        // title:'Cancel Booking',
                        text: "Are you sure want to Downgrade Plan ?",
                        type: 'warning',
                        showCancelButton: true,
                        confirmButtonClass: 'blue_button alert_btn mr-40',
                        cancelButtonClass: 'blue_border_button alert_btn',
                        confirmButtonText: 'Downgrade Plan'
                    }).then(function (isConfirm) {
                        if (isConfirm.value == true) {
                            $('.loader-outer-container').css('display', 'table');
                            $.ajax({
                                type: "POST",
                                url: base_url_route + '/agency/subscription/upgrade-subscription',
                                data: {
                                    'plan_id': plan_id,
                                    'active_subscription_id': active_subscription_id
                                },
                                headers: {
                                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                                },
                                success: function (data) {
                                    $('.loader-outer-container').css('display', 'none');
                                    if (data.code == 200) {
                                        toastr.success(data.message)
                                        window.location.reload();
                                    }
                                    else {
                                        toastr.error(data.message)
                                    }

                                }
                            });
                        }
                    });
                }
            }
            else {
                swal("Oops!", "You can switch the plan on or before one day of expiration or renewal date.", "warning");
                // swal("You Can upgrade Or downgrade plan on before one day of expiration", "warning")
            }
        }
        else{
            swal({
                title:'Oops!',
                text: "Please add card for Payment",
                type: 'warning',
                showCancelButton: true,
                confirmButtonClass: 'blue_button alert_btn mr-40',
                cancelButtonClass: 'blue_border_button alert_btn',
                confirmButtonText: 'Ok'
            }).then(function (isConfirm) {
                if (isConfirm.value == true) {
                    window.location.href = base_url_route  + '/agency/subscription/payment-setting'
                }
            });
        }

    });

    $(document).off('click', '.cancel_plan')
    $(document).on('click', '.cancel_plan', function (e) {
        var plan_id = $(this).attr('data-plan-id');
        var active_subscription_id = $(this).attr('data-active-plan-id');
        if(currentPlanCancelled == '0') {
            swal({
                // title:'Cancel Booking',
                text: "Are you sure you want to cancel Plan ?",
                type: 'warning',
                showCancelButton: true,
                confirmButtonClass: 'blue_button alert_btn mr-40',
                cancelButtonClass: 'blue_border_button alert_btn',
                confirmButtonText: 'Cancel Plan'
            }).then(function (isConfirm) {
                if (isConfirm.value == true) {
                    $('.loader-outer-container').css('display', 'table');
                    $.ajax({
                        type: "POST",
                        url: base_url_route + '/agency/subscription/cancel-subscription',
                        data: {
                            'plan_id': plan_id,
                            'active_subscription_id': active_subscription_id
                        },
                        headers: {
                            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                        },
                        success: function (data) {
                            $('.loader-outer-container').css('display', 'none');
                            if (data.code == 200) {
                                toastr.success(data.message)
                                window.location.reload();
                            }
                            else {
                                toastr.error(data.message)
                            }

                        }
                    });
                }
            });
        }else{
            swal("Oops!", "You have already cancelled your current plan.", "warning");

        }

    });

    $(document).off('click','#active_current_plan');
    $(document).on('click','#active_current_plan',function (e) {
        var plan_id = $(this).attr('data-plan-id');
        var active_subscription_id = $(this).attr('data-active-subscrption-id');
        console.log(notFoundUserCard)
        console.log(notFoundUserCard == '1')
        if(notFoundUserCard == '1') {
        swal({
            // title:'Cancel Booking',
            text: "Are you sure you want to activate this Plan ?",
            type: 'warning',
            showCancelButton: true,
            confirmButtonClass: 'blue_button alert_btn mr-40',
            cancelButtonClass: 'blue_border_button alert_btn',
            confirmButtonText: 'Active Plan'
        }).then(function (isConfirm) {
            if (isConfirm.value == true) {
                $('.loader-outer-container').css('display', 'table');
                $.ajax({
                    type: "POST",
                    url: base_url_route + '/agency/subscription/active-subscription',
                    data: {
                        'plan_id': plan_id,
                        'active_subscription_id': active_subscription_id
                    },
                    headers: {
                        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                    },
                    success: function (data) {
                        $('.loader-outer-container').css('display', 'none');
                        if (data.code == 200) {
                            toastr.success(data.message)
                            window.location.reload();
                        }
                        else {
                            toastr.error(data.message)
                        }

                    }
                });
            }
        });
    }
    else{
        swal({
            title:'Oops!',
            text: "Please add card for Payment",
            type: 'warning',
            showCancelButton: true,
            confirmButtonClass: 'blue_button alert_btn mr-40',
            cancelButtonClass: 'blue_border_button alert_btn',
            confirmButtonText: 'Ok'
        }).then(function (isConfirm) {
            if (isConfirm.value == true) {
                window.location.href = base_url_route  + '/agency/subscription/payment-setting'
            }
        });
    }
    })
})