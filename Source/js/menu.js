jQuery(document).ready(function () {

    var menu = jQuery("#menu");

    jQuery(window).scroll(function () {
        if (jQuery(this).scrollTop() > scrollStart && menu.hasClass("default")) {
            menu.removeClass("default");
            menu.addClass("fixed");
        } else if (jQuery(this).scrollTop() <= scrollStart && menu.hasClass("fixed")) {
            menu.removeClass("fixed");
            menu.addClass("default");
        }
    });
});