(function() {
    'use strict';

    angular
        .module('app')
        .controller('RegisterController', RegisterController);

    function RegisterController($scope, ApiService, AuthorizeService) {

        $scope.userModel = {};
        $scope.error = {};
        $scope.registerSubmitted = false;
        $scope.registerButtonPressed = false;

        $scope.register = function() {

            if ($scope.userModel.password !== $scope.userModel.password_confirm) {
                $scope.error['password_confirm'] = 'Введенные пароли не совпадают';
                return false;
            }

            $scope.error = {};
            $scope.registerSubmitted = true;
            $scope.registerButtonPressed = true;

            ApiService
                .register($scope.userModel)
                .success(function(response) {
                    AuthorizeService.authorize($scope.userModel.username, response.data.token);
                })
                .error(function(response) {
                   angular.forEach(response.error_description, function(error) {
                       $scope.error[error.field] = error.message;
                   });
                })
                .finally(function() {
                    $scope.registerButtonPressed = false;
                });
        }
    }
})();
