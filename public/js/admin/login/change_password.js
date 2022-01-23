/**
* Change or Reset Password
*
* This file is used for admin JS
* 
* @package    Laravel
* @subpackage JS
* @since      1.0
*/
//Validate Form
$(function() {
  // Initialize form validation on the registration form.
  // It has the name attribute "registration"
  $("form[name='loginForm']").validate({
    // Specify validation rules
    rules: {
      new_password: {
        required: true,
        minlength:6,
      },
      confirm_password: {
        required: true,        
        equalTo:"#new_password"
      },
    },
    // Specify validation error messages
    messages: {    
      confirm_password: {
        required:"Please Confirm New Password",
        equalTo:"New Password and Confirm Password Must be Same"
      }, 
      new_password: {
        required:"Please Enter New Password",
        minlength:"Password Must Be At Least 6 Characters Long",
      }
    },
    errorPlacement: function (error, element) {
            var name = $(element).attr("name");
            error.appendTo($("#" + name + "_validate"));
    },
    // Make sure the form is submitted to the destination defined
    // in the "action" attribute of the form when valid
    submitHandler: function(form) {
      form.submit();
    }
  });
});