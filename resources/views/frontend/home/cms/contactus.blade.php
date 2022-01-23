@extends('frontend.home.layout.app')
@section('title',$contact_data['contact_us_meta_title'])
@section('content')
<section class="contact-section content-remove" id="contact">
    <div class="contact-banner">
        <div class="container">
            <div class="row align-items-center">
                <div class="col-md-6">
                    <div class="contact-banner-text">
                        <h2>{{$contact_data['contact_us_heading']}}</h2>
                        <p>{{$contact_data['contact_us_sub_heading']}}</p>
                    </div>
                </div>
                <div class="col-md-6">
                    <div class="contact-img">
                        <img src="{{ url('public/') }}/assets/images/ic_chat_bubble_big.png" class="wow wobble infinite">
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="container">
        <div class="contact_white_box">
            <div class="row">
                <div class="col-md-1"></div>
                <div class="col-md-10">
                    <div class="contact-form">
                        <form method="post" action="javascript:void(0)" id="contact_us_form" name="contact_us_form"> 
                            <div>   
                                <input type="text" class="form-control wow zoomIn "  name="name" placeholder="Name">
                                <div class="name_validation" style="color:red"> </div>     
                            </div>
                            <div>  
                                <input type="text"  class="form-control wow zoomIn " name="email" placeholder="Email Id">
                                <div class="email_validation" style="color:red"> </div>   
                            </div>
                            <div>
                                <textarea class="form-control wow zoomIn" name="message" placeholder="Type your message"></textarea>           
                                <div class="message_validation" style="color:red"> </div>   
                            </div>
                            <div>
                                
                            </div>
                        
                            <div class="text-center">
                                <a href="javascript:void(0)" id="contact_us_button" name="contact_us_button" class="theme-btn btn-size btn-color btn-text wow zoomIn slow">Submit</a>
                            </div>
                        </form>
                    </div>
                </div>
                <div class="col-md-1"></div>
                
            </div>
        </div>
    </div>
    <input type="hidden" name="current_url">
    <div class="modal fade theme_modal" id="exampleModalCenter" tabindex="-1" role="dialog" aria-labelledby="exampleModalCenterTitle" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered" role="document">
          <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title text-center">Thank You</h5>
                      <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                      </button> 
            </div>
            <div class="modal-body ">
                <div class="contact_body text-center"></div>
                <br> 
                <div class="text-center">
                    <button  class="btn grad_btn modal_btn" data-dismiss="modal">OK</button>
                </div>
            </div>
          </div>
        </div>
      </div>
</section>
@endsection
@push('custom-styles')
<meta property="og:title" content="{{ $contact_data['contact_us_meta_title'] }}">
<meta name="description" content="{{ $contact_data['contact_us_meta_description'] }}">
<meta property="og:description" content="{{ $contact_data['contact_us_meta_description'] }}">
@endpush
@push('custom-scripts')
<!-- Include this Page JS -->
<!--<script type="text/javascript" src="{{ url('public/js/home/contact_us/contact.js') }}"></script>-->
<script>
var base_url = $('#web_base_url').val();
$('#contact_us_button').click(function() {

    var name = $('input[name=name]').val();
    var email = $('input[name=email]').val();
    var message = $('textarea[name=message]').val();
    var form_validate = true;

    if (name == '') {
        $('.name_validation').text('Name is required');
        form_validate = false;
    }

    if (email == '') {
        $('.email_validation').text('Email is required');
        form_validate = false;
    }

    if (message == '') {
        $('.message_validation').text('Message is required');
        form_validate = false;
    }

    if (name != '' && email != '' && message != '') {
        form_validate = true;
    }

    if (form_validate == true) {
        var form = $('#contact_us_form').serialize();

        $.ajax({
            type: "POST",
            url: '{{route("user.contact.us.store")}}',
            data: form,
            dataType: 'json',
            // contentType: false, // NEEDED, DON'T OMIT THIS (requires jQuery 1.6+)
            // processData: false,
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            },
            success: function(data) {
                if (data.success == true) {
                    $('#exampleModalCenter').modal('show');
                    $('.contact_body').text(data.message);

                    // toastr.success(data.message);
                    $('input[name=name]').val('');
                    $('input[name=email]').val('');
                    $('textarea[name=message]').val('');
                    $('.name_validation').text('');
                    $('.email_validation').text('');
                    $('.message_validation').text('');
                } else {
                    toastr.error(data.message);
                }
            }
        });
    }

});
</script>
@endpush
