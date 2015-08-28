function getTokenOrDie() {
    var cookie = document.cookie;
    var matches = cookie.match(/token=(.+?);/);

    if(matches[1].length == 0) {
        alertAndExit();
    }

    return matches[1];
}

function alertAndExit() {
    $logout = $("#logout-button");
    if($logout.length) {
        alert("Срок действия сесси истек. Войдите заново.");
        // for preventing multiple clicks remove it
        $logout.click().remove();
    }
}

function findApiUrl($objectWhereToFind) {
    return $($objectWhereToFind).attr("data-apiurl");
}

function showAnimatedDiv($elementBefore, textMessage, cssClass) {
    cssClass = cssClass || 'alert alert-danger';
    $('<div id="result" class="'+cssClass+'">'+textMessage+'</div>')
        .insertBefore($elementBefore)
        .hide()
        .slideDown('slow')
        .delay(1000)
        .slideUp('slow', function() {
            $(this).remove();
        });
}

// send ajax request, sets Bearer auth & throw an error if token expired
function ajaxReq(method, url, data, successCallback, failCallback) {
    $.ajax( {
        type: method,
        url: url,
        data: data,
        dataType: 'json',
        success: successCallback,
        error: function(xhr) {
            if(xhr.status == 401) {
                alertAndExit();
            }
            failCallback(xhr);
        },
        beforeSend: function(xhr) {
            xhr.setRequestHeader('Authorization', 'Bearer '+getTokenOrDie());
        }
    });
}

function dateToTimeStamp(dateString) {
    var date = new Date(dateString);

    return parseInt(date.getTime()/1000, 10);
}

function timeStampToDate(timeStamp) {
    var d = new Date(1000*timeStamp);
    return d.toLocaleDateString("en-GB");
}