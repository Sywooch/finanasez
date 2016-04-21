(function() {
    'use strict';

    angular
        .module('app')
        .controller('DemoLoginController', DemoLoginController);

    function DemoLoginController($scope, ApiService, AuthorizeService) {

        $scope.error = {};
        $scope.showLoading = true;
        ApiService
            .demoLogin()
            .success(function(response) {
                AuthorizeService.authorize('demo', response.data.token);
            })
            .error(function() {
                $scope.error['message'] = 'Не удалось выполнить демо-вход.';
            })
            .finally(function() {
                $scope.showLoading = false;
            });
    }
})();
