

<div class="modal-dialog modal-dialog-centered add_card_modal" role="document">
    <div class="modal-content">
        <div class="text-center modal-header">

            <div class="modal-title" id="">Add Card</div>

            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                <span aria-hidden="true"><img src="{{ url('public/assets/images/ic_close.png') }}" alt="close" class="img-close"></span>
            </button>
        </div>

        <div class="modal-body">
            <form  id="add_card_form" name="add_card_form" data-secret="{{ env('STRIPE_KEY') }}">
                <div class="container">
                    <div class="row">
                        <div class="col-12">
                            <div class="form-group">
                                <label class=''>Name on Card</label>
                                <input name="cardame" id="card_name" class='form-control' size='4' type='text'>
                                <span class="card_name_error"></span>
                                <!-- Used to display form errors -->
                                <div id="card-errors"></div>
                            </div>
                            <div class="form-group">
                                <div id="card-element" class="form-control"></div>
                            </div>
                            <!-- Used to display form errors. -->
                            <div id="card-errors" role="alert"></div>
                            <div class="text-center">
                                <button id="store_card_btn" class="auth_btn theme-btn btn-color btn-text btn-size" type="button">Save Card</button>
                            </div>
                        </div>
                    </div>
                </div>
            </form>
        </div>
    </div>
    <script type="text/javascript" src="{{ url('public/js/agency/subscription/card.js') }}"></script>
    <style>
        .StripeElement {
            padding: 10px 12px;
            /*box-sizing: border-box;
            height: 40px;
            border: 1px solid transparent;
            border-radius: 4px;
            background-color: white;
            box-shadow: 0 1px 3px 0 #e6ebf1;
            -webkit-transition: box-shadow 150ms ease;
            transition: box-shadow 150ms ease;*/
        }

        .StripeElement--focus {
            /*box-shadow: 0 1px 3px 0 #cfd7df;*/
        }

        .StripeElement--invalid {
            border-color: #fa755a;
        }

        .StripeElement--webkit-autofill {
            background-color: #fefde5 !important;
        }
    </style>
</div>