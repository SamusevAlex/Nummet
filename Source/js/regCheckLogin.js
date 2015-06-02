$(document).ready(function () {
    $("#login").bind("change", function () {
        var $login = $('#login').val();
        $.ajax({
            type: "POST",
            url: "../Source/js/ajaxCheckLogin.php",
            dataType: "html",
            success: function (html) {
                $("#answer").html(html);
                $("#loadImage").css({
                    'display': 'none'
                });
            },
            data: ({'login': $login})
        });
    });
    $(document).ajaxStart(function () {
        $("#loadImage").css({
            'display': 'inline-block'
        });
    });

});