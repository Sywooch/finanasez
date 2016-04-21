(function() {
    'use strict';

    angular
        .module('app')
        .controller('LoginController', LoginController);

    function LoginController($scope, ApiService, AuthorizeService) {

        $scope.userModel = {};
        $scope.error = {};
        $scope.loginSubmitted = false;
        $scope.loginButtonPressed = false;

        $scope.login = function () {
            $scope.loginSubmitted = true;
            $scope.loginButtonPressed = true;
            $scope.error = {};

            ApiService
                .login($scope.userModel)
                .success(function(response) {
                    AuthorizeService.authorize($scope.userModel.username, response.data.token);
                })
                .error(function(response) {
                    angular.forEach(response.error_description, function(error) {
                       $scope.error[error.field] = error.message;
                    });
                })
                .finally(function() {
                   $scope.loginButtonPressed = false;
                });

        };
    }
})();
