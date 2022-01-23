function closeOverlay(){var e=$(".sideoff-off"),o=$(".overlay");e.animate({right:"-1000px"},500).removeClass("visible"),o.fadeOut(500),$("body").removeClass("noscroll")}$(document).ready(function(){$(".how_we_work_scroll").click(function(){$("html, body").animate({scrollTop:$("#how_we_work").offset().top-130},2e3)}),$("#filter_btn").click(function(){$(".filter_box").css("opacity","1"),$(".filter_box").slideToggle("slow"),$(".filter_box").css("transform","translateY(0px)")}),$(".map_view").click(function(){$("#map_view_data").css("display","block"),$("#list_view_data").slideToggle("slow"),$(".map_view").removeClass("deactive"),$(".list_view").addClass("deactive")}),$(".list_view").click(function(){$("#list_view_data").css("display","block"),$("#map_view_data").slideToggle("slow"),$(".list_view").removeClass("deactive"),$(".map_view").addClass("deactive")}),$(window).scroll(function(){$(document).scrollTop()>85?$(".top_menu").addClass("top-nav-collapse"):$(".top_menu").removeClass("top-nav-collapse")}),$(window).scroll(function(){$(document).scrollTop()>100?($(".responsive_getstarted_btn").css("position","fixed"),$(".responsive_getstarted_btn").css("bottom","0px"),$(".responsive_getstarted_btn").css("display","inline-block")):$(document).scrollTop()<100&&$(".responsive_getstarted_btn").css("display","none")}),$("#slide").click(function(e){var o=$(".sideoff-off"),s=$(".overlay");o.hasClass("visible")?(o.animate({right:"-1300px"},500).removeClass("visible"),$(".overlay").css("display","none")):(o.animate({right:"0px"},500).addClass("visible"),s.fadeIn(500),$("body").addClass("noscroll"),e.preventDefault())}),$("#slideclose").click(function(){var e=$(".sideoff-off"),o=$(".overlay");e.animate({right:"-1300px"},500).removeClass("visible"),o.fadeOut(500),$("body").removeClass("noscroll")}),$(".responsive_menu a").click(function(){var e=$(".sideoff-off"),o=$(".overlay");e.animate({right:"-1300px"},500).removeClass("visible"),o.fadeOut(500),$("body").removeClass("noscroll")}),$(".testimonial_slider").owlCarousel({loop:!0,margin:10,responsiveClass:!0,responsive:{0:{items:1,nav:!0},768:{items:1,nav:!1},1000:{items:1,nav:!0,loop:!0,margin:20}}}),$(".deals_slider").owlCarousel({loop:!0,margin:10,responsiveClass:!0,nav:!0,responsive:{0:{items:1,dots:!1},768:{items:2},1000:{items:3,loop:!0,margin:20}}}),$(".tab_slider").owlCarousel({loop:!1,margin:10,responsiveClass:!0,responsive:{0:{items:4,nav:!0,loop:!0,margin:5},768:{items:4,nav:!0,loop:!1,margin:5},1000:{items:4,nav:!0,loop:!1,margin:0}}}),$(".property_slider").owlCarousel({margin:10,responsiveClass:!0,nav:!0,loop:!0,autoplay:!0,responsive:{0:{items:1},768:{items:1},992:{items:1}}})}),$(".togglefaq").click(function(e){e.preventDefault(),$(".activefaq").not(this).toggleClass("activefaq").next(".faqanswer").slideToggle(600),$(this).toggleClass("activefaq").next().slideToggle("slow")}),$(document).on("click",".sideoff-off.visible",function(){$(".sideoff-off").removeClass("visible")}),$(document).on("click","#open_login",function(){$("#signup-popup").modal("hide"),setTimeout(function(){$("body").addClass("modal-open")},1e3)}),$(document).on("click","#open_signup",function(){$("#login-popup").modal("hide"),setTimeout(function(){$("body").addClass("modal-open")},1e3)}),$(document).on("click","#open_forgot",function(){$("#login-popup").modal("hide"),setTimeout(function(){$("body").addClass("modal-open")},1e3)}),$(document).on("click","#open_OTP",function(){$("#forgot_popup").modal("hide"),setTimeout(function(){$("body").addClass("modal-open")},1e3)}),$(document).on("click","#open_resetpwd",function(){$("#otp_popup").modal("hide"),setTimeout(function(){$("body").addClass("modal-open")},1e3)}),$(document).on("click","auth_modal .img-close",function(){$("body").removeClass("modal-open")}),$.fn.digits=function(){return this.each(function(){$(this).text($(this).text().replace(/(\d)(?=(\d\d\d)+(?!\d))/g,"$1,"))})},$(document).on("click",".deleteNotification",function(){var e=$(this).attr("data-notification-id"),o=$("#user_id").val();parseFloat($(".noti_counter").html())-1>0&&$(".noti_counter").html(parseFloat($(".noti_counter").html())-1),$(this).parent().fadeOut(),$(".loader-outer-container").css("display","table");var s=new FormData;s.append("notification_id",e),s.append("user_id",o),$.ajax({headers:{"X-CSRF-TOKEN":$('meta[name="csrf-token"]').attr("content")},type:"POST",url:base_url_route+"/deleteNotification",datatype:JSON,processData:!1,contentType:!1,cache:!1,data:s,success:function(e){if($(".loader-outer-container").css("display","none"),201==e.code)return toastr.error(e.message),!1;toastr.success(e.message)}})}),setTimeout(function(){$(".alert.alert-danger").css("display","none"),$(".alert.alert-success").css("display","none")},2500);

//homepage slider
$(document).ready(function() {
    var owl = $('.feature_slider');
    owl.owlCarousel({
        stagePadding: 20,
        margin: 50,
        nav: true,
        loop: false,
        autoplay: true,
        autoPlaySpeed: 100,
        autoPlayTimeout: 5000000,
        autoplayHoverPause: true,
        responsive: {
            0: {
                items: 1,
                nav: true,
                loop: false,
                
            },
            600: {
                items: 2,
                nav: true,
                loop: false,
                
            },
            1000: {
                items: 3,
                nav: true,
                loop: false,
                
            }
        }
    })

    /*js for Chat page responsive layout*/
    $('.open_list').on('click', function(){
        $('.inbox_sidebar').addClass('open',1000);
    });
    $('.inbox_sidebar ul').on('click', function(){
        $('.inbox_sidebar').removeClass('open',2000);
    });
})

$(window).scroll(function() {
    $('#animatedElement').each(function() {
        var imagePos = $(this).offset().top;

        var topOfWindow = $(window).scrollTop();
        if (imagePos < topOfWindow + 400) {
            $(this).addClass("slideUp");
        }
    });
});


//scrolling 
 $(document).ready(function () {
     $(document).on("scroll", onScroll);
     var hashid = window.location.hash;
    $('.menu-center').find('.nav-link').each(function () {
           if($(this).attr('href')==hashid){
               $(this).addClass("active");
           }
        });
    //smoothscroll
    $('.menu-center a[href^="#"]').on('click', function (e) {
        e.preventDefault();
        $(document).off("scroll");
        
        $('a').each(function () {
            $(this).removeClass('active');
        })
        $(this).addClass('active');
      
        var target = this.hash,
            menu = target;
        $target = $(target);
        console.log('at',$target.offset().top);
        $('html, body').stop().animate({
            'scrollTop': $target.offset().top - 70
        }, 1000, 'swing', function () {
            // window.location.hash = target;
            $(document).on("scroll", onScroll);
        });
    });
});

function onScroll(event){
    var scrollPos = $(document).scrollTop();
    $('.menu-center a').each(function () {
        var currLink = $(this);
        var refElement = $(currLink.attr("href"));
        if (refElement.position().top <= scrollPos && refElement.position().top + refElement.height() > scrollPos) {
            $('.menu-center ul li a').removeClass("active");
            currLink.addClass("active");
        }
        else{
           currLink.removeClass("active");
        }
    });
}