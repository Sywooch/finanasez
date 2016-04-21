(function() {
    'use strict';
    angular
        .module('app')
        .controller('StatByCategoryTransactionListController', StatByCategoryTransactionListController);

    function StatByCategoryTransactionListController($scope, $uibModalInstance, ApiService, categoryName) {
        $scope.categoryName = categoryName;

        $scope.transactionHash = [];
        $scope.transactionHashLoaded = false;

        $scope.close = function () {
            $uibModalInstance.dismiss();
        };

        ApiService
            .getAllTransactions({category_name: $scope.categoryName})
            .success(function (response) {
                $scope.transactionHash = response.data;
            })
            .finally(function () {
                $scope.transactionHashLoaded = true;
            });
    }
})();