(function () {
    'use strict';

    angular
        .module('app')
        .factory('AuthorizeService', AuthorizeService);

    function AuthorizeService($window, $state, $rootScope) {
        return {
            authorize: function(username, token) {
                $window.sessionStorage.access_token = token;
                $state.go('dashboard');

                $rootScope.username = username;
                $window.sessionStorage.username = username;
            }
        }
    }
})();