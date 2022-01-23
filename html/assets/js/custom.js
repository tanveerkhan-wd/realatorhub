// old

// responsive menu
$(document).ready(function () {
    $('#slide').click(function () {
        var hidden = $('.sideoff-off');
        var hidden1 = $('.overlay');
        if (hidden.hasClass('visible')) {
            hidden.animate({"right": "-1300px"}, 500).removeClass('visible');
            $(".overlay").css('display', 'none');
        } else {
            hidden.animate({"right": "0px"}, 500).addClass('visible');
            hidden1.fadeIn(500);
            $( "body" ).addClass( "noscroll" );
        }
    });
     $('#slideclose').click(function () {
        var hidden = $('.sideoff-off');
        var hidden1 = $('.overlay');
        hidden.animate({"right": "-1300px"}, 500).removeClass('visible');
        hidden1.fadeOut(500);
        $( "body" ).removeClass( "noscroll" );
    });
    
    /* banner animation */
    animateDiv('.home_bg_icon1');
    animateDiv('.home_bg_icon2');
    animateDiv('.home_bg_icon3'); 

    

    $('.welcom_intro_slider').owlCarousel({        
        margin:10,
        responsiveClass:true,
        nav: true,
        loop: true,
        autoplay: true,
        dots:false,
        responsive:{
            0:{
                items:1.5,
            },
            481:{
                items:2.5,
            },
            768:{
                items:2,               
            },
            992:{
                items:2.5,            
            }
        }
    })

    $('.home_slider').owlCarousel({       
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
    })

    $('.team_slider').owlCarousel({       
        margin:10,
        responsiveClass:true,
        nav: true,
        loop: true,
        autoplay: false,
        dots:false,
        responsive:{
            0:{
                items:1,               
            },
            576:{
                items:2,       
            },
            768:{
                items:3,               
            },
            992:{
                items:3.6,              
           }
        }
    })

    $('.testimonial_slider').owlCarousel({       
        margin:0,
        responsiveClass:true,
        nav: false,
        loop: true,
        autoplay: true,
        dots:true,
        responsive:{
            0:{
                items:1,               
            },
            768:{
                items:1,               
            },
            992:{
                items:2,              
           }
        }
    })

    $('.product_slider').owlCarousel({       
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

});
function makeNewPosition(){           
    // Get viewport dimensions (remove the dimension of the div)
    var h = $(window).height() - 50;
    var w = $(window).width() - 50;
    
    var nh = Math.floor(Math.random() * h);
    var nw = Math.floor(Math.random() * w);
    
    return [nh,nw];    
    
}
function animateDiv(myclass){
    var newq = makeNewPosition();
    $(myclass).animate({ top: newq[0], left: newq[1] }, 4000,   function(){
      animateDiv(myclass);        
    });
    
};
function closeOverlay()
{
    var hidden = $('.sideoff-off');
    var hidden1 = $('.overlay');
    hidden.animate({"right": "-1000px"}, 500).removeClass('visible');
    hidden1.fadeOut(500);
    $( "body" ).removeClass( "noscroll" );
}

// navbar fixed on top
// jQuery to collapse the navbar on scroll
//$(document).ready(collapseNavbar);
 $(window).scroll(function() {
    if ($(document).scrollTop() > 85) {
         $(".top_menu").addClass("top-nav-collapse");
    }
    else {
        $(".top_menu").removeClass("top-nav-collapse");
    }
});

//faq toggle stuff 
$(function () {
  $("dd").slideUp(1);
  $("dt, dt .faq-toggle").click(function () {
    var $this = $(this),$parent = $this.parent(),outer = true;
    if ($this.is('.faq-toggle')) {$parent = $parent.parent();outer = false;}
    if ($parent.hasClass('active')) {
      $parent.removeClass('active').find('dd').slideUp(500);
    } else {
      $parent.siblings().removeClass('active').find('dd').slideUp(500);
      $parent.addClass('active').find('dd').slideDown(500);
    }
    return outer;
  });
});

/* js for modal when modal open on modal */
$(document).on('click','.sideoff-off.visible',function(){
    $('.sideoff-off').removeClass('visible');
});

$(document).on('click','#open_verify',function(){
    $('#forgot').modal('hide'); 
    setTimeout(function(){
        $('body').addClass('modal-open');
       },1000);
});
$(document).on('click','#open_reset',function(){
    $('#verify').modal('hide'); 
    setTimeout(function(){
        $('body').addClass('modal-open');
       },1000);
});

$(document).on('click','.modal button.close span',function(){
    $('body').removeClass('modal-open');
    $( "body" ).removeClass( "noscroll" );
    var hidden = $('.sideoff-off');
    var hidden1 = $('.overlay');
    hidden.animate({"right": "-1300px"}, 500).removeClass('visible');
    hidden1.fadeOut(500);
});



/* custon range input slider */
function modifyOffset() {     
    var el, newPoint, newPlace, offset, siblings, k;
    width    = this.offsetWidth;
    newPoint = (this.value - this.getAttribute("min")) / (this.getAttribute("max") - this.getAttribute("min"));
    offset   = -1;
    if (newPoint < 0) { newPlace = 0;  }
    else if (newPoint > 1) { newPlace = width; }
    else { newPlace = width * newPoint + offset; offset -= newPoint;}
    siblings = this.parentNode.childNodes;
    for (var i = 0; i < siblings.length; i++) {
        sibling = siblings[i];
        if (sibling.id == this.id) { k = true; }
        if ((k == true) && (sibling.nodeName == "OUTPUT")) {
            outputTag = sibling;
        }
    }
    console.log((outputTag.getAttribute('for')));
    if(outputTag.getAttribute('for') == 'duration'){
      outputTag.innerHTML = this.value+' Months'; 
    }else if(outputTag.getAttribute('for') == 'a_fee'){
      outputTag.innerHTML = this.value+' %';  
    }else if(outputTag.getAttribute('for') == 'i_rate'){
      outputTag.innerHTML = this.value+' %';  
    }  
    else{
      outputTag.innerHTML        = '$ '+this.value ;
    }
    outputTag.style.left       = newPlace + "px";
    outputTag.style.marginLeft = offset + "%";
    //outputTag.innerHTML        = this.value+' jojoi';
}
function modifyInputs() {
    var inputs = document.getElementsByTagName("input");
    for (var i = 0; i < inputs.length; i++) {
        if (inputs[i].getAttribute("type") == "range") {
            inputs[i].onchange = modifyOffset;            
            // the following taken from http://stackoverflow.com/questions/2856513/trigger-onchange-event-manually
            if ("fireEvent" in inputs[i]) {
                inputs[i].fireEvent("onchange");
            } else {
                var evt = document.createEvent("HTMLEvents");
                evt.initEvent("change", false, true);
                inputs[i].dispatchEvent(evt);
            }
        }
    }
}
modifyInputs();

/* signup tab change */
$(document).on('click',"input[name='inlineRadioOptions']",function(){
     if($("input[name='inlineRadioOptions']:checked"). val() == 'Customer')
     {
        $('.customer_signup').css('display','block');
        $('.staff_signup').css('display','none');
     }
     else if($("input[name='inlineRadioOptions']:checked"). val() == 'Staff')
     {
        $('.customer_signup').css('display','none');
        $('.staff_signup').css('display','block');
     }
})