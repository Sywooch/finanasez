$(function() {
    var apiUrl = findApiUrl($("form")) + '/category';

    ajaxReq('GET', apiUrl, '',
        function(json) {
            // success
            $.each(json.data.response, function(index, item) {
                insertCategoryRow($("table tbody"), item);
            });
        },
        function(xhr) {
            // failure
        }
    );

    $("#newCategoryButton").click(function() {
        var name = $("#newname").val(),
            comment = $("#newcomment").val(),
            categoryType    = $("#newtype").val(),
            $button  = $(this);

        if(name.length == 0 || categoryType.length == '') {
            showAnimatedDiv($button, 'Введите имя категории и выберите тип.');

            return false;
        }

        ajaxReq('POST', apiUrl,
            {
                name: name,
                comment: comment,
                type: categoryType
            },
            function(json) {
                showAnimatedDiv($button, "Категория добавлена!", 'alert alert-success');
                insertCategoryRow($("table tbody"), json.data.response);
                $("form input[type=text]").val(''); // clear the form text fields
            },
            function(xhr) {
                if(xhr.status == 406) {
                    showAnimatedDiv($button, 'Вероятно, категория с таким имененем уже существует.');
                } else {
                    showAnimatedDiv($button, 'Произошла ошибка. Попробуйте еще раз.');
                }
            }
        );


        return false;
    });

    $("table tbody").on('click', '#remove-cat', function() {
        if(!confirm('Вы уверены, что хотите удалить категорию? ')) {
            return false;
        }

        var $tr = $(this).closest('tr'),
            categoryType = $tr.find("#td-type").text();
            categoryId = $tr.attr('data-id');

        if(!categoryId) {
            showAnimatedDiv($("table"), "Не удалось удалить категорию.");
            return false;
        }

        var categoryCount = $("table tbody tr:visible:contains('"+categoryType+"')").length;

        if(categoryCount <= 1) {
            showAnimatedDiv($("table"), "Должно быть не менее одной расходной и доходной категории.");
            return false;
        }

        ajaxReq('DELETE', apiUrl+'/'+categoryId, '',
            function(json) {
                showAnimatedDiv($("table"), "Категория удалена", 'alert alert-success');
                // remove row from table
                $("table tr[data-id="+categoryId+"]").fadeOut('slow', function() {
                    $(this).remove();
                });
            },
            function(xhr) {
                showAnimatedDiv($("table"), "Не удалось удалить категорию. Вероятно, есть операции в этой категории.");
            }
        );

        return false;
    });

    $("table tbody").on('click', "#edit-cat", function() {

        var $tr         = $(this).closest('tr');
        var categoryId  = $tr.attr('data-id');

        if(!categoryId) {
            showAnimatedDiv($("table"), "Не удалось удалить изменить категорию.");
            return false;
        }

        // draw text fields and select fields to edit category
        var  $tdName = $tr.find("#td-name"),
            previousName = $tdName.text(),
            $tdComment = $tr.find("#td-comment"),
            previousComment = $tdComment.text(),
            $tdType = $tr.find("#td-type"),
            $tdAction = $tr.find("#td-action");

        $tdName.text(''); // clear last name
        // draw input with value="last name"
        $tdName
            .append('<input id="input-name" type="text" class="form-control" value="'+previousName+'">')
            .hide()
            .fadeIn('slow');
        // same with comment
        $tdComment.text('');
        $tdComment
            .append('<input id="input-comment" type="text" class="form-control" value="'+previousComment+'">')
            .hide()
            .fadeIn('slow');
        // clear type column
        $tdType.text('');
        // draw <select> in this column
        $tdType
            .append('<select class="form-control" size="1"><option value="out">Расход</option><option value="in">Доход</option></select>')
            .hide()
            .fadeIn('slow');

        // draw "save" button instead of action logo's
        $tdAction.html('');
        $tdAction
            .append('<button id="save-cat" class="btn btn-primary btn-xs">Сохранить</button>')
            .hide()
            .fadeIn('slow');


        return false;
    });

    $("table tbody").on('click', "#save-cat", function() {
        // algorytm : send ajaxReq, remove row, append freshly row
        var $tr         = $(this).closest('tr'),
            categoryId  = $tr.attr('data-id'),
            comment     = $tr.find("#input-comment").val(),
            name        = $tr.find("#input-name").val(),
            type        = $tr.find("select option:selected").val();

        if(name.length == 0 || type.length ==0) {
            showAnimatedDiv($("table"), "Не заполнены обязательные поля");
            return false;
        }

        ajaxReq('PUT', apiUrl+'/'+categoryId,
            {
                comment: comment,
                name   : name,
                type   : type
            }, function(json) {
                // insert freshly row before old
                insertCategoryRow($tr, json.data.response, true);
                // finally remove old row !
                $tr.fadeOut('slow', function() { $(this).remove(); });
            }, function(xhr) {
                showAnimatedDiv($("table"), 'Произошла ошибка. Повторите операцию.');
            }
        );

    });

    function insertCategoryRow($tableObject, jsonItem, insertBefore) {
        var $tableRow = $('<tr data-id="'+jsonItem.id+'"><td id="td-name">'+jsonItem.name+"</td><td id=\"td-comment\">"+jsonItem.comment+"</td><td id=\"td-type\">"+(jsonItem.type == 'out' ? 'Расход' : 'Доход')+"</td>"+
            '<td id="td-action"><a id="edit-cat" href=""><span class="glyphicon glyphicon-edit" aria-hidden="true"></span></a><a id="remove-cat" href=""><span class="glyphicon glyphicon-trash" aria-hidden="true"></span></a></td></tr');
        if(insertBefore) {
            $tableRow.insertBefore($tableObject);
        } else {
            $tableRow.prependTo($tableObject);
        }
        $($tableRow).hide().fadeIn('slow');
    }


});

