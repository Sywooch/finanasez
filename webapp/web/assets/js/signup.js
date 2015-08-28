$(function() {

   $("form input").on('blur input', function() {
       if($(this).val().length < 6) {
           if($(this).siblings("div#err-length").length === 0) {
               prependDiv($(this), 'err-length', 'Длина поля должна быть не менее 6 символов',
                            'alert alert-danger');
           }
           $(this).closest("div.form-group").removeClass('has-success').addClass('has-error');
       } else {
           removeClosestDiv($(this), 'err-length');
           $(this).closest("div.form-group").removeClass("has-error").addClass('has-success')
       }
   });

    $("#submit").click(function(evt) {
       var username = $("#username").val(),
           password = $("#password").val(),
           password2= $("#password2").val(),
           apiUrl   = $("form").attr('data-apiurl') + '/user/signup',
           successUrl=$("form").attr('data-successurl'),
           $button  = $(this);

        if(password !== password2 ) {
            if($("#err-password").length === 0) {
                prependDiv($button, 'err-password', "Введенные пароли не совпадают.",
                                'alert alert-danger');
            }
            return false;
        } else {
            removeClosestDiv($button, 'err-password');
        }

        // remove previous div results
        removeClosestDiv($button, 'result');

        // send signup request
        $.ajax({
            type: "POST",
            url: apiUrl,
            data: $("form").serialize(),
            dataType: 'json'
        }).done(function(response) {

            // success
            var msg = 'Вы успешно зарегистрированы в сервисе! Теперь вы можете '+
                        '<a href="'+successUrl+'">авторизоваться</a>.';
            prependDiv($("form"), 'result', msg, 'alert alert-success');
            $("form").slideUp('slow');
        }).fail(function(xhr, textStatus) {
            var msg = 'Произошла неизвестная ошибка. Попробуйте еще раз.';
            if(xhr.status == 406) {
                msg = 'Вероятно, пользователь с таким именем уже зарегистрирован.' +
                    ' Попробуйте изменить имя пользователя и повторить попытку.';

            }
            prependDiv($button, 'result', msg,'alert alert-danger');
        });

        return false;
    });

});

function prependDiv($object, id, textMessage, cssClass) {
    $errDiv = $("<div>")
        .attr('id', id)
        .addClass(cssClass)
        .html(textMessage); // I use html instead of text() because need some urls to pass in
    $errDiv.insertBefore($object);
    $errDiv.hide().slideDown('slow');
}

function removeClosestDiv($object, id) {
    $object.siblings("#"+id).slideUp('slow', function() {
        $(this).remove();
    });
}