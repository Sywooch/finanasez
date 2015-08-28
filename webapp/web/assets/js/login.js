'use strict';
$(function() {
    var $button = $("#submit");
    var $form   = $("form");
    var $imgLoad =$("#img-load");
    var apiUrl  = $form.attr('data-apiurl')+'/user/login';
    var token   = null;

    $button.click(function(clickEvent) {
        var username = $("#username").val(),
            password = $("#password").val(),
            token    = null;

        if(0 == username.length || 0 == password.length) {
            showAnimatedDiv($button, 'Введите логин и пароль!');
            return false;
        }

        clickEvent.preventDefault();
        $form.slideUp('slow');
        $imgLoad.slideDown('slow');
        $.ajax({
            type: "POST",
            data: {
                username: $form.find("#username").val(),
                password: $form.find("#password").val()
            },
            dataType: 'json',
            url: apiUrl
        }).done(function(jsonResponse) {
            token = jsonResponse.data.response.access_token;
            document.cookie = "token="+token;
            $button.unbind('click').click();
        }).fail(function(xhr) {
            $form.slideDown('slow');
            $imgLoad.slideUp('slow');
            showAnimatedDiv($button, 'Неверный логин или пароль!');
        });
    });

});
