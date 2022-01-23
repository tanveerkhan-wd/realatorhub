$(document).ready(function () {
    var publicKey =  'pk_test_8Im8LcpAnf57FXByLtAHpJI3';
    var stripe = Stripe(publicKey);
    var elements = stripe.elements();
    var card = elements.create('card');
    card.mount("#card-element");
    var stripeElements = function(setupIntent) {

        var clientSecret = setupIntent.client_secret
        // var cardholderName = document.getElementById('card_name');
        var cardButton = document.getElementById('store_card_btn');
        cardButton.addEventListener('click', function(ev) {
            var card_name = $('#card_name').val();
            if(card_name) {
                stripe
                    .confirmCardSetup(clientSecret, {
                        payment_method: {
                            card: card,
                            billing_details: {name: card_name}
                        }
                    })
                    .then(function (result) {
                        if (!(result.error)) {
                            stripe.retrieveSetupIntent(clientSecret).then(function (result) {
                                console.log('result')
                                console.log(result)
                                var setupIntent = result.setupIntent;
                                var setupIntentJson = JSON.stringify(setupIntent, null, 2);
                                $('.loader-outer-container').css('display', '');


                                $.ajax({
                                    type: "POST",
                                    url: base_url_route + '/agency/subscription/store-card',
                                    data: {
                                        'stripe_id': setupIntent.payment_method,
                                        'name': card_name
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

                                        $('.loader-outer-container').css('display', 'none');

                                    }
                                });

                            });
                        }
                        if(result.error){
                            toastr.error(result.error.message);
                        }

                    });
            }
            else {
                toastr.error('Please enter card holder name');
            }
        });
    };


    var getSetupIntent = function(publicKey) {
        $.ajax({
         // fetch("/student/payment-setting/create-setup-intent", {
            type: "post",
            url: base_url_route+'/agency/create-setup-intent',
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            },
        })
            .then(function(setupIntent) {
                stripeElements(setupIntent);
            });
    };
    // Show a spinner on payment submission

    getSetupIntent(publicKey);

})