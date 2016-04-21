(function() {
    'use strict';
    angular
        .module('app')
        .controller('TransactionController', TransactionController);

    function TransactionController($scope, ApiService) {
        $scope.transactionHash = [];
        $scope.transactionHashLoaded = false;

        $scope.billHash = [];
        $scope.billHashLoaded = false;

        $scope.categoryList = [];
        $scope.categoryListLoaded = false;

        $scope.updateModel = {};
        $scope.error = {};
        $scope.updateFormHash = {};
        $scope.updateSubmitted = false;

        $scope.limitAttributes = {
            limit: 10,
            offset: 0,
            canLoadMore: true
        };

        $scope.filterAttributes = {};

        loadTransactionHash();
        loadBillList();
        loadCategoryList();

        $scope.loadMore = function() {
            $scope.limitAttributes.offset += 10;
            loadTransactionHash(true);
        };

        $scope.removeTransaction = function(id) {
            if (!confirm('Вы действительно хотите удалить этот счет?')) {
                return false;
            }
            ApiService
                .deleteTransaction(id)
                .success(function() {
                    delete $scope.transactionHash[id];
                })
                .error(function() {
                    alert('Произошла ошибка. Обновите страницу.');
                });
        };

        $scope.updateTransaction = function(id) {
            $scope.updateSubmitted = true;
            $scope.error = {};
            ApiService
                .updateTransaction(id, $scope.updateModel)
                .success(function(response) {
                    $scope.transactionHash[id] = response.data;

                    delete $scope.updateFormHash[id];
                })
                .error(function(response) {
                    angular.forEach(response.error_description, function(error) {
                        $scope.error[error.field] = error.message;
                    });
                })
                .finally(function() {
                    $scope.updateModel = {};
                    $scope.updateSubmitted = false;
                });
        };

        $scope.showUpdateForm = function(id) {
            $scope.updateFormHash = {};
            $scope.updateFormHash[id] = true;
        };

        $scope.$watch('filterAttributes', function(newQuery, oldQuery) {
            if (oldQuery !== newQuery) {
                $scope.limitAttributes = {
                    limit: 10,
                    offset: 0,
                    canLoadMore: true
                };

                loadTransactionHash()
            }
        }, true);

        function loadTransactionHash(appendToExistingList) {
            $scope.transactionHashLoaded = false;

            var requestData = angular.copy($scope.filterAttributes);
            requestData = angular.extend(requestData, $scope.limitAttributes);

            ApiService
                .getAllTransactions(requestData)
                .success(function (response) {
                    if (Object.keys(response.data).length === 0) {
                        $scope.limitAttributes.canLoadMore = false;
                    }

                    if (appendToExistingList) {
                        $scope.transactionHash = angular.extend($scope.transactionHash, response.data);
                    } else {
                        $scope.transactionHash = response.data;
                    }
                })
                .error(function () {
                    alert('Произошла ошибка. Обновите страницу.');
                })
                .finally(function () {
                    $scope.transactionHashLoaded = true;
                });
        }

        function loadCategoryList() {
            ApiService
                .getAllCategories()
                .success(function (response) {
                    $scope.categoryList = response.data;
                })
                .error(function () {
                    alert('Произошла ошибка. Обновите страницу.');
                })
                .finally(function () {
                    $scope.categoryListLoaded = true;
                });
        }
        function loadBillList() {
            ApiService
                .getAllBills()
                .success(function (response) {
                    $scope.billHash = response.data;
                })
                .error(function () {
                    alert('Произошла ошибка. Обновите страницу.');
                })
                .finally(function () {
                    $scope.billHashLoaded = true;
                });
        }
    }
})();
