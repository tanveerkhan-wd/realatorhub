$(document).ready(function () {

    var publicKey = 'pk_test_8Im8LcpAnf57FXByLtAHpJI3';
    var stripe = Stripe(publicKey);
    var elements = stripe.elements();
    var card = elements.create('card');
    card.mount("#card-element");

    function createSubscription(customerId,result, planId)
    {
        var paymentmethod = result.paymentMethod.id
        var last4 = result.paymentMethod.card.last4
        $('.loader-outer-container').css('display', 'table');
        $.ajax({
            type: "POST",
            url: base_url_route + '/agency/store-subscriptions',
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            },
            data: {
                'plan_id':planId,
                'payment_method_id': paymentmethod,
                'customer_id':customerId,
                'last4':last4
            },
            success: function (data) {
                console.log(data)
                $('.loader-outer-container').css('display', 'none');
                if (data.code == 200) {
                    toastr.success(data.message);
                    window.location.href = base_url_route + '/agency/complete-agency-signup';
                }
                else{
                    toastr.warning(data.message);
                }
            }
        });


    }

    var stripeElements = function(setupIntent) {

        var clientSecret = setupIntent.client_secret
        var customer = setupIntent.customer
        // var cardholderName = document.getElementById('card_name');
        var cardButton = document.getElementById('submit_payment');
        cardButton.addEventListener('click', function(ev) {
            var card_name = $('#card_name').val();
            var plan = $('#plan_id').val();

            if(card_name) {
                stripe.createPaymentMethod({
                type: 'card',
                card: card,
                billing_details: {
                    // Include any additional collected billing details.
                    name: card_name,
                },
            }).then((result) => {
                console.log(result)
            if (result.error) {
                toastr.error(result.error.message);
            } else {
                createSubscription(customer, result, plan);
            }
        });
            }
            else {
                toastr.error('Please enter card holder name');
            }
        });
        // $('.loader-outer-container').css('display', '');

    };

    var getSetupIntent = function (publicKey) {
        $.ajax({
            // fetch("/student/payment-setting/create-setup-intent", {
            type: "post",
            url: base_url_route + '/agency/create-setup-intent',
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            },
        }).then(function (setupIntent) {

            stripeElements(setupIntent);
        });
    };

    // Show a spinner on payment submission

    getSetupIntent(publicKey);
});


