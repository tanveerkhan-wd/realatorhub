
$(".accordions").on("click", ".accordions_title", function() {

    $('.active').removeClass('active');
    if (false == $(this).next().is(':visible')) {
        $('.accordions > .accordions_content').slideUp(300);
        $(this).addClass('active');
    }
    $(this).next().slideToggle(300);
});