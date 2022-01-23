
$(document).ready(function (e) {

    $('#smtp_settings_form').validate({
        rules: {
            mail_driver: {
                required: true
            },
            mail_host: {
                required: true
            },
            mail_port:{
                required: true
            },
            mail_password:{
                required: true
            },
            mail_from_address:{
                required:true
            }
        },
    });


})