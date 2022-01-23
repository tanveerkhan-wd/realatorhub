$(document).ready(function () {
    $(document).off('click','#select_plan');
    $(document).on('click','#select_plan',function (e) {
        e.preventDefault();
        var selected_plan = $(this).attr('data-select-plan');
        if(selected_plan == 0){
            $('.select_plan').removeClass('blue_btn').addClass('blue_border_btn');
            $(this).removeClass('blue_border_btn').addClass('blue_btn');
            $(this).attr('data-select-plan',1);
            // $(this).html('Selected');
        }
        else{
            $(this).removeClass('blue_btn').addClass('blue_border_btn ');
            $(this).attr('data-select-plan',0)
            // $(this).html('Select');
        }
    })

    $(document).off('click','#start_trial');
    $(document).on('click','#start_trial',function (e) {
        var selected_plan = [];
        $(".select_plan").each(function() {
            var val=$(this).is('[data-select-plan=1]');
            if(val){
                selected_plan.push($(this).attr("data-plan-id"));
            }
        })
        if (selected_plan.length === 0) {
            toastr.error('Please select plan');
        }
        else {
            $('.loader-outer-container').css('display','table');
            var form_data = new FormData();
            form_data.append('selected_plan', selected_plan);
            $.ajax({
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                },
                type: "POST",
                url: base_url_route + '/agency/post-subscription-plan',
                datatype: JSON,
                processData: false,
                contentType: false,
                cache: false,
                data: form_data, // a JSON object to send back
                success: function (data) {
                    $('.loader-outer-container').css('display', 'none');
                   if (data.code == 200) {
                        toastr.success(data.message);
                        window.location.href = base_url_route + '/agency/payment-details';
                    }
                    else if (data.code == 302) {
                        toastr.warning(data.message);
                    }
                    else {
                        toastr.warning(data.message);
                    }

                }
            });
        }
    });
})