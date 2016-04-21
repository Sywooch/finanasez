(function () {
    'use strict';
    angular
        .module('app')
        .controller('UserSettingsController', UserSettingsController);

    function UserSettingsController($scope, ApiService) {

        $scope.error = {};
        $scope.userModel = {};

        $scope.updateSubmitted = false;
        $scope.updateButtonPressed = false;
        $scope.statusSuccess = false;
        $scope.userLoaded = false;

        ApiService
            .dashboard()
            .success(function (response) {
                $scope.userModel.email = response.data.email;
                $scope.userLoaded = true;
            });

        $scope.updateUser = function () {
            $scope.updateSubmitted = true;

            if ($scope.userModel.password !== $scope.userModel.password_confirm) {
                $scope.error['password_confirm'] = 'Введенные пароли не совпадают';
                return false;
            }

            $scope.updateButtonPressed = true;
            $scope.error = {};
            $scope.statusSuccess = false;

            ApiService
                .updateUser($scope.userModel)
                .success(function () {
                    $scope.statusSuccess = true;
                })
                .error(function (response) {
                    angular.forEach(response.error_description, function (error) {
                        $scope.error[error.field] = error.message;
                    });
                })
                .finally(function () {
                    $scope.updateButtonPressed = false;
                });
        };

    }
})();
