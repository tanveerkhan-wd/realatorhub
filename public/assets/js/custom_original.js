// old


// responsive menu
$(document).ready(function () {

    // navbar fixed on top
// jQuery to collapse the navbar on scroll
/*function collapseNavbar() {
    alert("Hello")
    if ($(".navbar").offset().top > 50) {
        $(".top_menu").addClass("top-nav-collapse");
    } else {
        $(".top_menu").removeClass("top-nav-collapse");
    }
}
$(window).scroll(collapseNavbar());
$(document).ready(collapseNavbar());*/
    
       $(".how_we_work_scroll").click(function() { 
           $('html, body').animate({    
                  scrollTop: $("#how_we_work").offset().top - 130
           }, 2000);    
    }); 


    $("#filter_btn").click(function(){
        $('.filter_box').css('opacity','1');
        $('.filter_box').slideToggle("slow");
        $('.filter_box').css('transform','translateY(0px)');
    });
    $(".map_view").click(function(){
        $('#map_view_data').css('display','block');
        $('#list_view_data').slideToggle("slow");
        $('.map_view').removeClass("deactive");
        $('.list_view').addClass("deactive");

    });
    $(".list_view").click(function(){
        $('#list_view_data').css('display','block');
        $('#map_view_data').slideToggle("slow");
        $('.list_view').removeClass("deactive");
        $('.map_view').addClass("deactive");  
    });
 $(window).scroll(function() {
    if ($(document).scrollTop() > 85) {
         $(".top_menu").addClass("top-nav-collapse");
    }
    else {
        $(".top_menu").removeClass("top-nav-collapse");
    }
});
$(window).scroll(function() {
     if ($(document).scrollTop() > 100) {
         $(".responsive_getstarted_btn").css("position","fixed");
         $(".responsive_getstarted_btn").css("bottom","0px");
        $(".responsive_getstarted_btn").css("display","inline-block");

    }
    else if ($(document).scrollTop() < 100) {
        $(".responsive_getstarted_btn").css("display","none");
    }
});


    $('#slide').click(function (e) {
        var hidden = $('.sideoff-off');
        var hidden1 = $('.overlay');
        if (hidden.hasClass('visible')) {
            hidden.animate({"right": "-1300px"}, 500).removeClass('visible');
            $(".overlay").css('display', 'none');
        } else {
            hidden.animate({"right": "0px"}, 500).addClass('visible');
            hidden1.fadeIn(500);
             
            $( "body" ).addClass( "noscroll" );
            e.preventDefault();
        }
    });
     $('#slideclose').click(function () {
        var hidden = $('.sideoff-off');
        var hidden1 = $('.overlay');
        hidden.animate({"right": "-1300px"}, 500).removeClass('visible');
        hidden1.fadeOut(500);
        $( "body" ).removeClass( "noscroll" );
    });
    $('.responsive_menu a').click(function () {
        var hidden = $('.sideoff-off');
        var hidden1 = $('.overlay');
        hidden.animate({"right": "-1300px"}, 500).removeClass('visible');
        hidden1.fadeOut(500);
        $( "body" ).removeClass( "noscroll" );
    });
    $('.testimonial_slider').owlCarousel({
        loop: true,
        margin: 10,
        responsiveClass: true,
        responsive: {
          0: {
            items: 1,
            nav: true
          },
          768: {
            items: 1,
            nav: false
          },
          1000: {
            items: 1,
            nav: true,
            loop: true,
            margin: 20
          }
        }
      });


    $('.deals_slider').owlCarousel({
        loop: true,
        margin: 10,
        responsiveClass: true,
        nav:true,
        responsive: {
          0: {
            items: 1,
            dots: false,
          },
          768: {
            items: 2,
          },
          1000: {
            items: 3,
            loop: true,
            margin: 20
          }
        }
      });

        $('.tab_slider').owlCarousel({
            loop: false,
            margin: 10,
            responsiveClass: true,
            responsive: {
              0: {
                items: 4,
                nav: true,
                loop: true,
                margin: 5
              },
              768: {
                items: 4,
                nav: true,
                loop: false,
                margin: 5
              },
              1000: {
                items: 4,
                nav: true,
                loop: false,
                margin: 0
              }
            }
          });

        $('.property_slider').owlCarousel({       
        margin:10,
        responsiveClass:true,
        nav: true,
        loop: true,
        autoplay: true,
        responsive:{
            0:{
                items:1,               
            },
            768:{
                items:1,               
            },
            992:{
                items:1,              
           }
        }
    });

        
        

    // custom range input
 //    var slider = document.getElementById("formControlRange");
	// var output = document.getElementById("demo");
	// output.innerHTML = slider.value;

	// slider.oninput = function() {
	//   output.innerHTML = this.value;
	// }
    
});

function closeOverlay()
{
    var hidden = $('.sideoff-off');
    var hidden1 = $('.overlay');
    hidden.animate({"right": "-1000px"}, 500).removeClass('visible');
    hidden1.fadeOut(500);
    $( "body" ).removeClass( "noscroll" );
}



//faq toggle stuff 
$('.togglefaq').click(function(e) {
e.preventDefault();
var notthis = $('.activefaq').not(this);
notthis.toggleClass('activefaq').next('.faqanswer').slideToggle(600);
 $(this).toggleClass('activefaq').next().slideToggle("slow");
});

/* js for modal when modal open on modal */
$(document).on('click','.sideoff-off.visible',function(){
    $('.sideoff-off').removeClass('visible');
});
$(document).on('click','#open_login',function(){
       $('#signup-popup').modal('hide');
       setTimeout(function(){
        $('body').addClass('modal-open');
       },1000);    
});
$(document).on('click','#open_signup',function(){
    $('#login-popup').modal('hide'); 
    setTimeout(function(){
        $('body').addClass('modal-open');
       },1000);
});
$(document).on('click','#open_forgot',function(){
    $('#login-popup').modal('hide'); 
    setTimeout(function(){
        $('body').addClass('modal-open');
       },1000);
});
$(document).on('click','#open_OTP',function(){
    $('#forgot_popup').modal('hide'); 
    setTimeout(function(){
        $('body').addClass('modal-open');
       },1000);
});
$(document).on('click','#open_resetpwd',function(){
    $('#otp_popup').modal('hide'); 
    setTimeout(function(){
        $('body').addClass('modal-open');
       },1000);
});
$(document).on('click','auth_modal .img-close',function(){
    $('body').removeClass('modal-open');
});

$.fn.digits = function(){ 
    return this.each(function(){ 
        $(this).text( $(this).text().replace(/(\d)(?=(\d\d\d)+(?!\d))/g, "$1,") ); 
    })
}

$(document).on('click','.deleteNotification',function(){

    var notification_id = $(this).attr('data-notification-id'),
        user_id = $('#user_id').val();
        if((parseFloat($('.noti_counter').html())-1) > 0)
        {
            $('.noti_counter').html((parseFloat($('.noti_counter').html())-1))
        }//parseFloat($('.noti_counter').html())-1);
        $(this).parent().fadeOut();
        
    $('.loader-outer-container').css('display','table');
        var form_data = new FormData();      
        form_data.append('notification_id',notification_id);         
        form_data.append('user_id',user_id);
        $.ajax({
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                },
            type: "POST",
            url: base_url_route+'/deleteNotification',
            datatype: JSON,
            processData: false,
            contentType: false,
            cache: false,
            data: form_data, // a JSON object to send back                
            success: function (data) {
                $('.loader-outer-container').css('display','none');
                if(data.code == 201){
                    toastr.error(data.message);
                    //swal(data.message);
                    return false;
                }else{              
                    toastr.success(data.message);
                    /*setTimeout(function(){
                        location.reload(true);
                    },1500)*/
                }
                
            }
        });     
});

setTimeout(function(){
    $('.alert.alert-danger').css('display','none');
    $('.alert.alert-success').css('display','none');
},2500);