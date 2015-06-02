jQuery(document).ready(function () {
    jQuery("#login").bind("change", function () {

        var $login = jQuery('#login').val();
        jQuery.ajax({
            type: 'POST',
            url: "ajaxCheckLogin.php",
            data: ({'login': $login})
        });
    });
    jQuery(document).ajaxStart(function () {
        jQuery("#loadImage").css({
            'display': 'inline-block'
        });
    });
    jQuery(document).ajaxSuccess(function () {
        jQuery("#loadImage").css({
            'display': 'none'
        });
        alert("scs");
    });
    jQuery(document).ajaxError()(function () {
        alert(10);
    });
});

//            success: function (html) {
//                alert(1);
//                jQuery("#loadImage").css({'display': 'none'});
//                jQuery("#answer").html(html);
//            }
