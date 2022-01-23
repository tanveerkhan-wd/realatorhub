/**
* Edit Blog Category
*
* This file is used for admin JS
* 
* @package    Laravel
* @subpackage JS
* @since      1.0
*/
//Validate form
$('#logo-form').validate({
     errorPlacement: function (error, element) {
            var name = $(element).attr("name");
            error.appendTo($("#" + name + "_validate"));
    },
  });