$(function() {
    var apiUrl = $("form").attr('data-apiurl'),
        operationApiUrl = apiUrl + '/operation',
        billApiUrl = apiUrl + '/bill',
        categoryApiUrl = apiUrl + '/category';

    $(".datepicker").datepicker();
    $(".datepicker").datepicker('setDate', '+0d');

    loadControlBlock();

    ajaxReq('GET', billApiUrl, '',
        function(json) {
            $.each(json.data.response, function(index, item) {
               $("select#bill").append('<option value="'+item.id+'">'+item.name+'</option>');
               insertBillItem(item);
            });
        }
    );

    ajaxReq('GET', categoryApiUrl, '',
        function(json) {
            $.each(json.data.response, function(index, item) {
                var neededForm = (item.type == 'out' ? '#form-spending' : '#form-earning');
                $(neededForm + " select#category").append('<option value="'+item.id+'">'+item.name+'</option>')
            });
        }
    );

    ajaxReq('GET', operationApiUrl,
        {
            limit: 5,
            category_type: 'in'
        },
        function(json) {
            $.each(json.data.response, function(index, item) {
                insertOperationRow($("table#journal-earning").find("tbody"), item);
            });
        }
    );
    ajaxReq('GET', operationApiUrl,
        {
            limit: 5,
            category_type: 'out'
        },
        function(json) {
            $.each(json.data.response, function(index, item) {
                insertOperationRow($("table#journal-spending").find("tbody"), item);
            });
        }
    );

    $("button#submit").click(function() {
        var $button     = $(this),
            $form       = $button.closest("form"),
            amount      = parseFloat($form.find("#amount").val()),
            comment     = $form.find("#comment").val(),
            categoryId  = parseInt($form.find("#category option:selected").val(), 10),
            billId      = parseInt($form.find("#bill option:selected").val(),10);

        if(isNaN(billId) || isNaN(categoryId) || isNaN(amount)) {
            showAnimatedDiv($form, 'Не все обязательные поля заполнены.');
            return false;
        }

        ajaxReq('POST', operationApiUrl,
            {
                amount: amount,
                category_id: categoryId,
                bill_id: billId,
                comment: comment,
                created_at: getDatepickerTimeStamp($(".datepicker"))
            },
            function(json) {
                var tableIdentificator = (json.data.response.category.type == 'out' ? '#journal-spending' : '#journal-earning');
                insertOperationRow($("table"+tableIdentificator).find("tbody"),
                                   json.data.response,
                                   true // prepend(insert to BEGIN of table)
                );
                reloadBillBlock();
                if(json.data.response.category.type == 'out' ) {
                    loadControlBlock();
                }
                showAnimatedDiv($form, 'Операция успешно добавлена.', 'alert alert-success');
            },
            function(xhr) {
                showAnimatedDiv($form, "Не удалось добавить операцию. Попробуйте еще раз.");
            }
        );

        return false;
    });

    function loadControlBlock() {
        $.each(getDateIntervals(), function(index, item) {
            ajaxReq('GET', operationApiUrl,
                {
                    sum       : 'out', // all spending categories
                    time_from : item.from,
                    time_to   : item.to
                },
                function(json) {
                    var response = parseInt(json.data.response, 10),
                        money    = isNaN(response) ? 0 : response;
//                    console.log(index+': '+money);
                    insertControlItem(index, money);
                }
            );
        });
    }

    function reloadBillBlock() {
        ajaxReq('GET', billApiUrl, '',
            function(json) {
                $.each(json.data.response, function(index, item) {
                    $("#bill-control")
                        .find("span:contains('"+ item.name+ "')")
                        .parent()
                        .find("#bill-money")
                        .text(item.money+' РУБ.');
                });
            }
        );
    }

    function insertOperationRow($object, item, prepend) {
        var amount = (parseFloat(item.amount)).toFixed(2);
        var $tr = $("<tr><td>"+item.category.name+"</td><td>"+amount+"</td>"+
            "<td>"+item.bill.name+"</td><td>"+(timeStampToDate(item.created_at))+"</td></tr>");
        if(prepend) {
            $object.prepend($tr);
        } else {
            $object.append($tr);
        }
    }


    function insertBillItem(item) {
        var $p = $('<p class="list-group-item">'+
                    '<span class="glyphicon glyphicon-ruble small"> '+item.name+'</span>'+
                    '<span id="bill-money" class="pull-right text-muted small"><em>'+item.money+' РУБ.</em></span>' +
                    '</p>');
        $p.hide();
        $p.appendTo($("#bill-control"));
        $p.slideDown('slow');
    }

    function insertControlItem(id, money) {
       $("#control-control")
           .find('#'+id)
           .text(money + ' РУБ.')
           .hide()
           .fadeIn('slow');
    }

    function getDatepickerTimeStamp($obj) {
        return dateToTimeStamp($obj.datepicker('getDate'));
    }

    function getDateIntervals() {

        var datesArray = {};

        datesArray['today'] = {};
        datesArray['today'].from = moment().startOf('day').unix();
        datesArray['today'].to   = moment().unix();

        datesArray['yesterday'] = {};
        datesArray['yesterday'].to     = datesArray['today'].from -1;
        datesArray['yesterday'].from   = moment().startOf('day').subtract(1, 'days').unix();

        datesArray['current_week'] = {};
        datesArray['current_week'].from = moment().startOf('week').unix();
        datesArray['current_week'].to = moment().unix();

        datesArray['last_week'] = {};
        datesArray['last_week'].to = datesArray['current_week'].from -1;
        datesArray['last_week'].from = moment().startOf('week').subtract(1, 'week').unix();

        datesArray['current_month'] = {};
        datesArray['current_month'].from = moment().startOf('month').unix();
        datesArray['current_month'].to= moment().unix();

        datesArray['last_month'] = {};
        datesArray['last_month'].to = datesArray['current_month'].from -1;
        datesArray['last_month'].from = moment().startOf('month').subtract(1, 'month').unix();

        return datesArray;


    }

});

