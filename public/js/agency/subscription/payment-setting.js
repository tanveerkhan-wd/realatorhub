$(document).ready(function () {
    $("form[name='add_card_form']").validate({
        // Specify validation rules
        rules: {
            cardnumber: {
                required: true,
            },
            card_name: {
                required: true,
            },
            cvv: {
                required: true,
            },
        },

    });

    $(document).off('click', '#add_card_btn');
    $(document).on('click', '#add_card_btn', function (e) {
        e.preventDefault();
        var user_id = $('#user_id').val();
        $('.loader-outer-container').css('display', '');
        $.ajax({
            type: "POST",
            url: base_url_route+'/agency/subscription/add-card',
            data: {
                'user_id': user_id
            },
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            },
            success: function (data) {
                console.log(data)
                $('.loader-outer-container').css('display', 'none');
                $('#add_card_modal').html(data.data.view).modal('show');
            }
        });

    });

    $(document).off('click', '#delete_card_btn');
    $(document).on('click', '#delete_card_btn', function (e) {
        e.preventDefault();
        var card_id = $(this).attr('data-stripe-id');
        swal({
            text: "Are you sure you want to delete card?",
            type: 'warning',
            showCancelButton: true,
            confirmButtonClass: 'blue_button alert_btn mr-40',
            cancelButtonClass: 'blue_border_button alert_btn',
            confirmButtonText: 'Yes'
        }).then(function (isConfirm) {
            $('.loader-outer-container').css('display', '');
            $.ajax({
                type: "POST",
                url: base_url_route + '/agency/subscription/delete-card',
                data: {
                    'card_id': card_id
                },
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                },
                success: function (data) {
                    $('.loader-outer-container').css('display', 'none');
                    if (data.status == 200) {
                        toastr.success(data.message);
                        window.location.reload();
                    }
                    else {
                        toastr.error(data.message);
                    }

                }
            });
        });

    });

});