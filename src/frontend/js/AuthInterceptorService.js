(function () {
    'use strict';

    angular
        .module('app')
        .factory('AuthInterceptorService', AuthInterceptorService);

        function AuthInterceptorService($q, $window, $location, $rootScope, publicPagesEnum) {

            $rootScope.$on('$locationChangeStart', function () {
                var loggedIn = !!$window.sessionStorage.access_token;

                var onPublicPage = (publicPagesEnum.indexOf($location.path()) !== -1);
                if (loggedIn && onPublicPage) {
                    $location.path('/dashboard').replace();
                } else if (!loggedIn && !onPublicPage) {
                    $location.path('/login').replace();
                }
            });
            return {
                request: function (config) {
                    if ($window.sessionStorage.access_token) {
                        config.headers.Authorization = 'Bearer ' + $window.sessionStorage.access_token;
                    }
                    config.headers.Accept = 'application/json';
                    return config;
                },
                responseError: function (rejection) {
                    if (rejection.status === 401) {
                        $window.sessionStorage.access_token = '';
                        $location.path('/login').replace();
                    }
                    return $q.reject(rejection);
                }
            };
        }
})();

