window.onerror = function(message, url, line) {
    var xhr = new XMLHttpRequest();

    var logUrl = '//v1.api.' + location.hostname + '/frontend-logger';

    var params =    'message=' + message +
                    '&url=' + url +
                    '&line=' + line +
                    '&user_agent=' + window.navigator.userAgent;

    xhr.open("POST", logUrl, true);

    xhr.setRequestHeader("Content-Type", "application/x-www-form-urlencoded");

    xhr.send(params);
};
