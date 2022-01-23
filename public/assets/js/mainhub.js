function callAll(jsfiles) {
    var src = document.createElement("script");
    src.setAttribute("type", "text/javascript");
    src.setAttribute("src", jsfiles);
    document.getElementsByTagName("head")[0].appendChild(src);
}
callAll("public/front_end/assets/js/jquery-3.4.1.min.js");
callAll("public/front_end/assets/js/popper.min.js");
callAll("public/front_end/assets/js/bootstrap.min.js");
callAll("public/front_end/assets/js/all.js");
callAll("public/front_end/assets/js/wow.min.js");
callAll("public/front_end/assets/js/owl.carousel.js");
callAll("public/js/sweetalert2.min.js");
callAll("public/js/toastr.min.js");
callAll("public/front_end/assets/js/custom.js");
callAll("public/js/jquery.validate.js");
callAll("public/js/promise.min.js");
callAll("public/js/additional-methods.js");
callAll("public/front_end/assets/js/custom-develop.js");
/*callAll("your/path/to/a/jsfile1.js");
callAll("your/path/to/a/jsfile2.js");
callAll("your/path/to/a/jsfile3.js");*/