(function() {
    'use strict';
    angular
        .module('app')
        .controller('MainController', MainController);

    function MainController($scope, $rootScope, $location, $window, $timeout, Helper) {
        $scope.isCurrentPath = function (path) {
            return $location.path() === path;
        };

        $scope.loggedIn = function () {
            return !!$window.sessionStorage.access_token;
        };

        $scope.logout = function () {
            delete $window.sessionStorage.access_token;
            $location.path('/login').replace();
        };

        $scope.getUserName = function () {
            if (!$rootScope.username) {
                $rootScope.username = $window.sessionStorage.username;
            }
            return $rootScope.username;
        };
    }
})();