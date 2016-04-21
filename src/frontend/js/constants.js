(function () {
    'use strict';
    angular
        .module('app')
        .constant('config', {
            'apiUrl': '//v1.api.' + location.hostname
        })
        .constant('publicPagesEnum', [
            '/login',
            '/register',
            '/',
            '/demo-login'
        ]);
})();
