$(document).ready(function(){
    $("#upload_logo").change(function() {
        readURL(this);
    });
    function readURL(input) {
        if (input.files && input.files[0]) {
            var reader = new FileReader();

            reader.onload = function(e) {
                $('#logo_change').attr('src', e.target.result);
            }

            reader.readAsDataURL(input.files[0]); // convert to base64 string
        }
    }
    $(".agent_form").validate({
        rules: {
            first_name: {
                required: true,
            },
            last_name:{
                required: true,
            },
            agent_id:{
                required: true,
            },
            country_code:{
                required: true,
            },
            single_mobile_number: {
                required: true,
            },
            email:{
                required: true,
                email: true
            },
        },
        messages: {
            first_name: "Please enter first name",
            last_name: "Please enter last name",
            agent_id: "Please enter agent id",
            country_code: "Please enter country code",
            single_mobile_number: "Please enter mobile number",
        },
        /*errorPlacement: function (error, element) {
            var name = $(element).attr("name");
            error.appendTo($("#" + name + "_validate"));
        },*/

    });
    


})

