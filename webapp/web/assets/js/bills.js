$(function() {
    var apiUrl = findApiUrl($("form")) + '/bill';

    ajaxReq('GET', apiUrl, '',
        function(json) {
            $.each(json.data.response, function(index, item) {
                insertBillRow($("table tbody"), item);
            });
        },
        function(xhr) {
            //
        }
    );

    $("#newBillButton").click(function() {
        var name = $("#newname").val(),
            balance    = parseFloat($("#newbalance").val()),
            $button  = $(this);

        if(name.length == 0 || isNaN(balance)) {
            showAnimatedDiv($button, 'Введите имя счета и введите его начальный баланс.');

            return false;
        }

        ajaxReq('POST', apiUrl,
            {
                name: name,
                money: balance
            },
            function(json) {
                showAnimatedDiv($button, "Счет добавлен!", 'alert alert-success');
                insertBillRow($("table tbody"), json.data.response);
                $("form input").val(''); // clear the form text fields
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

    $("table tbody").on('click', '#remove-bill', function() {
        var $tr = $(this).closest('tr'),
            billId = $tr.attr('data-id');

        if($("table tbody tr:visible").length <= 1) {
            showAnimatedDiv($("table"), "Должен быть хотя бы один счет.");
            return false;
        }

        if(!confirm("Вы уверены, что хотите удалить счет?")) {
            return false;
        }

        if(!billId) {
            showAnimatedDiv($("table"), "Не удалось удалить счет.");
            return false;
        }

        ajaxReq('DELETE', apiUrl+'/'+billId, '',
            function(json) {
                showAnimatedDiv($("table"), 'Счет удален.', 'alert alert-success');
                $tr.fadeOut('slow', function() { $(this).remove(); });
            },
            function(xhr) {
                showAnimatedDiv($("table"), 'Произошла ошибка при удалении счета. Вероятно, есть операции в этом счете.');
            }
        );



        return false;
    });

    $("table tbody").on('click', "#edit-bill", function() {

        var $tr         = $(this).closest('tr');
        var billId  = $tr.attr('data-id');

        if(!billId) {
            showAnimatedDiv($("table"), "Не удалось удалить изменить счет.");
            return false;
        }

        var  $tdName = $tr.find("#td-name"),
            previousName = $tdName.text(),
            $tdBalance = $tr.find("#td-balance"),
            previousBalance = $tdBalance.text(),
            $tdAction = $tr.find("#td-action");

        $tdName.text(''); // clear last name
        // draw input with value="last name"
        $tdName
            .append('<input id="input-name" type="text" class="form-control" value="'+previousName+'">')
            .hide()
            .fadeIn('slow');
        // same with comment
        $tdBalance.text('');
        $tdBalance
            .append('<input id="input-balance" type="text" class="form-control" value="'+previousBalance+'">')
            .hide()
            .fadeIn('slow');
        // clear type column

        // draw "save" button instead of action logo's
        $tdAction.html('');
        $tdAction
            .append('<button id="save-bill" class="btn btn-primary btn-xs">Сохранить</button>')
            .hide()
            .fadeIn('slow');


        return false;
    });

    $("table tbody").on("click", "#save-bill", function() {
        // algorytm : send ajaxReq, remove row, append freshly row
        var $tr         = $(this).closest('tr'),
            billId      = $tr.attr('data-id'),
            balance     = parseFloat($tr.find("#input-balance").val()),
            name        = $tr.find("#input-name").val();

        if(name.length == 0 || isNaN(balance)) {
            showAnimatedDiv($("table"), "Поля заполнены некорректно.");
            return false;
        }

        ajaxReq('PUT', apiUrl+'/'+billId,
            {
                name   : name,
                money: balance
            }, function(json) {
                // insert freshly row before old
                insertBillRow($tr, json.data.response, true);
                // finally remove old row !
                $tr.fadeOut('slow', function() { $(this).remove(); });
            }, function(xhr) {
                showAnimatedDiv($("table"), 'Произошла ошибка. Повторите операцию.');
            }
        );
    });

    function insertBillRow($tableObject, jsonItem, insertBefore) {
        var $tableRow = $('<tr data-id="'+jsonItem.id+'"><td id="td-name">'+jsonItem.name+"</td><td id=\"td-balance\">"+jsonItem.money+"</td>"+
            '<td id="td-action"><a id="edit-bill" href=""><span class="glyphicon glyphicon-edit" aria-hidden="true"></span></a><a id="remove-bill" href=""><span class="glyphicon glyphicon-trash" aria-hidden="true"></span></a></td></tr');
        if(insertBefore) {
            $tableRow.insertBefore($tableObject);
        } else {
            $tableRow.prependTo($tableObject);
        }
        $($tableRow).hide().fadeIn('slow');
    }
});