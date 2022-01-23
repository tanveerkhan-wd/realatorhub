/**
* Edit Admin Profile
*
* This file is used for admin JS
* 
* @package    Laravel
* @subpackage JS
* @since      1.0
*/
//Validate form for Profile
$(function() {
  // Initialize form validation on the registration form.
  // It has the name attribute "registration"
  $("form[name='loginForm']").validate({
    // Specify validation rules
    rules: {
      name:{
        required:true,
      },
      email: {
        required: true,
        email: true
      },      
      /**/

    },
    // Specify validation error messages
    messages: {
      name:{
        required : "Please provide name",
      },
      password: {
        required: "Please provide a password",
        minlength: "Your password must be at least 5 characters long"
      },
      email: "Please enter a valid email address"
    },
    // Make sure the form is submitted to the destination defined
    // in the "action" attribute of the form when valid
    submitHandler: function(form) {
      form.submit();
    }
  });

//Validate form for Change Password
  $("form[name='change-password-form']").validate({
     rules: {
                new_password:{
                    required:true,
                    minlength:6
                  },
                   confirm_password:{
                    required:true,
                     equalTo: "#new_password",
                     minlength:6
                  },
     },
      messages: {
         password: {
          required: "Please provide a password",
          minlength: "Password must be at least 6 characters long."
        },
        confirm_password:{
          equalTo:'New password and Confirm password does not match',
        },
      },

      submitHandler: function(form) {
      form.submit();
    }
  });
});

//Validate file image or not
/*$(document).on('change','input[type="file"]',function(){    
  var file = document.getElementById($(this).attr('id')).files[0];   
    if(file && (file['type'] == "image/jpeg" || file['type'] == "image/png" || file['type'] == "application/jpg" || file['type'] == "application/pdf")){          
      $(this).closest('form').find(':submit').attr('disabled',false);
      $(this).next('.errorImage').html('');
    }else{
      $(this).next('.errorImage').html('Please upload only .jpg, .jpeg, .svg, .png format').css('color','red');
      $(this).closest('form').find(':submit').attr('disabled',true);

    }
});*/


$.ajaxSetup({
headers: {
    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
}
});


$uploadCrop = $('#upload-demo').croppie({
    enableExif: true,
    viewport: {
        width: 200,
        height: 200,
        type: 'circle'
    },
    boundary: {
        width: 300,
        height: 300
    }
});


$('#upload_profile').on('change', function () { 


    var file = document.getElementById('upload_profile').files[0];
    console.log(file['type'])
    if(!file || (file['type'] != "image/jpeg" && file['type'] != "image/png" && file['type'] != "application/jpg"))
    {    
        sweetAlert('Error','Please upload only .jpg, .jpeg, .svg, .png format','error');
        return false;
    } 
    console.log(file['type']); 

    var reader = new FileReader();
    console.log(reader);

    reader.onload = function (e) {
        $uploadCrop.croppie('bind', {
            url: e.target.result
        }).then(function(){
            console.log('jQuery bind complete');
        });
    }
    reader.readAsDataURL(this.files[0]);
});


$('.upload-result').on('click', function (ev) {
    $uploadCrop.croppie('result', {
        type: 'canvas',
        size: 'viewport'
    }).then(function (resp) {
        if(resp == 'data:,' || resp == 'data:image/png;base64,iVBORw0KGgoAAAANSUhEUgAAAKAAAACgCAYAAACLz2ctAAACzElEQVR4Xu3SMQ0AAAzDsJU/6cHI4xKoFHlnCoQFFn67VuAAhCAtAGCa3zmADKQFAEzzOweQgbQAgGl+5wAykBYAMM3vHEAG0gIApvmdA8hAWgDANL9zABlICwCY5ncOIANpAQDT/M4BZCAtAGCa3zmADKQFAEzzOweQgbQAgGl+5wAykBYAMM3vHEAG0gIApvmdA8hAWgDANL9zABlICwCY5ncOIANpAQDT/M4BZCAtAGCa3zmADKQFAEzzOweQgbQAgGl+5wAykBYAMM3vHEAG0gIApvmdA8hAWgDANL9zABlICwCY5ncOIANpAQDT/M4BZCAtAGCa3zmADKQFAEzzOweQgbQAgGl+5wAykBYAMM3vHEAG0gIApvmdA8hAWgDANL9zABlICwCY5ncOIANpAQDT/M4BZCAtAGCa3zmADKQFAEzzOweQgbQAgGl+5wAykBYAMM3vHEAG0gIApvmdA8hAWgDANL9zABlICwCY5ncOIANpAQDT/M4BZCAtAGCa3zmADKQFAEzzOweQgbQAgGl+5wAykBYAMM3vHEAG0gIApvmdA8hAWgDANL9zABlICwCY5ncOIANpAQDT/M4BZCAtAGCa3zmADKQFAEzzOweQgbQAgGl+5wAykBYAMM3vHEAG0gIApvmdA8hAWgDANL9zABlICwCY5ncOIANpAQDT/M4BZCAtAGCa3zmADKQFAEzzOweQgbQAgGl+5wAykBYAMM3vHEAG0gIApvmdA8hAWgDANL9zABlICwCY5ncOIANpAQDT/M4BZCAtAGCa3zmADKQFAEzzOweQgbQAgGl+5wAykBYAMM3vHEAG0gIApvmdA8hAWgDANL9zABlICwCY5ncOIANpAQDT/M4BZCAtAGCa3zmADKQFAEzzOweQgbQAgGl+5wAykBYAMM3vHEAG0gIApvmdA8hAWgDANL9zABlICwCY5nf+kmEAoaOpQZEAAAAASUVORK5CYII=')
        {
            sweetAlert('Error','Please select a profile picture','error');
            return false;
        }
        $.ajax({
            url: base_url+"/admin/change-profile",
            type: "POST",
            data: {"image":resp},
            success: function (data) {
                $('.loader-outer-container').css('display','none');
                if(data.code == 201){
                    sweetAlert('Error',data.message,'error');
                    return false;
                }else{
                     sweetAlert('Success',data.message,'success');
                     setTimeout(function(){
                         location.reload(true);
                     },1500)
                }
            }
        });
    });
});