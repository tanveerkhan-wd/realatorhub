/**
* OTP
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
      otp: {
        required: true,
      },
    },
     messages: {
      otp: {
        required: "Please Enter OTP",
      },
    },
    errorPlacement: function (error, element) {
            var name = $(element).attr("name");
            error.appendTo($("#" + name + "_validate"));
    },
    // Specify validation error messages
   
    // Make sure the form is submitted to the destination defined
    // in the "action" attribute of the form when valid
    submitHandler: function(form) {
      form.submit();
    }
  });
});