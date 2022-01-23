/**
* About Us Setting admin
*
* This file is used for admin JS
* 
* @package    Laravel
* @subpackage JS
* @since      1.0
*/
//Validate Form


$('form').each(function(key, form) {
    $(form).validate(); 
	});

	$(document).on('change','input[type="file"]',function(){		
		var file = document.getElementById($(this).attr('id')).files[0];   
		console.log(file['type']);		
	    if(file && (file['type'] == "image/x-icon" || file['type'] == "image/jpeg" || file['type'] == "image/svg+xml" || file['type'] == "image/svg" ||  file['type'] == "image/vnd.microsoft.icon" || file['type'] == "image/png" || file['type'] == "application/jpg" || file['type'] == "application/pdf")){	        
	    	$(this).closest('form').find(':submit').attr('disabled',false);
	    	$(this).next('.errorImage').html('');
	    }else{
	    	$(this).next('.errorImage').html('Please upload only .jpg,.ico ,.jpeg, .svg, .png format').css('color','red');
	    	$(this).closest('form').find(':submit').attr('disabled',true);

	    }
	});


	function Validate(event) {
        var regex = new RegExp("^[0-9-!@#$%*()+ ?]");
        var key = String.fromCharCode(event.charCode ? event.which : event.charCode);
        if (!regex.test(key)) {
            event.preventDefault();
            return false;
        }
    }       
    
    $(document).on('blur','#home_admin_email',function(){
    	var str = $(this).val();	
    	var re = /^(([^<>()[\]\\.,;:\s@\"]+(\.[^<>()[\]\\.,;:\s@\"]+)*)|(\".+\"))@((\[[0-9]{1,3}\.[0-9]{1,3}\.[0-9]{1,3}\.[0-9]{1,3}\])|(([a-zA-Z\-0-9]+\.)+[a-zA-Z]{2,}))$/;
	    if(!re.test(str)){	    	
	    	$('.homeEmailError').html("Please enter a valid email address").css('color','red');
	    	$(this).closest('form').find(':submit').attr('disabled',true);
	    }else{
	    	$('.homeEmailError').html('');
	    	$(this).closest('form').find(':submit').attr('disabled',false);
	    }
    });


    $(function () {
    // Replace the <textarea id="editor1"> with a CKEditor
    // instance, using default configuration.
    CKEDITOR.replace('home_info_description');
    CKEDITOR.replace('home_banner_description');
    //bootstrap WYSIHTML5 - text editor   
  })