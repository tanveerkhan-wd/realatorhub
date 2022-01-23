/**
* Edit Blog
*
* This file is used for admin JS
* 
* @package    Laravel
* @subpackage JS
* @since      1.0
*/
$(function () {
// Replace the <textarea id="editor1"> with a CKEditor
// instance, using default configuration.
CKEDITOR.replace('descripition')
//bootstrap WYSIHTML5 - text editor   
});
//File is image or not
$(document).on('change','input[type="file"]',function(){      
    var file = document.getElementById('blog_image').files[0];   
    if(file && (file['type'] == "image/jpeg" || file['type'] == "image/png" || file['type'] == "application/jpg" || file['type'] == "application/pdf")){            
        $(this).closest('form').find(':submit').attr('disabled',false);
        $(this).next('.errorImage').html('');
    }else{
        $(this).next('.errorImage').html('Please upload only .jpg, .jpeg, .svg, .png format').css('color','red');
        $(this).closest('form').find(':submit').attr('disabled',true);

    }
});