(function() {
    'use strict';
    angular
        .module('app')
        .controller('DashboardController', DashboardController);

    function DashboardController($scope, $q, ApiService) {
        $scope.error = {};

        $scope.billHash = [];
        $scope.billHashLoaded = false;

        $scope.categoryList = [];
        $scope.categoryListLoaded = false;

        $scope.statHash = {};
        $scope.statHashLoaded = false;

        $scope.monthlyTransactionList = [];
        $scope.monthlyTransactionListLoaded = false;
        $scope.monthlyTransactionCountToDisplayInJournal = 10;

        $scope.operationModel = {};
        $scope.operationSubmitted = false;
        $scope.operationButtonBlocked = false;

        loadMonthlyTransactionList();
        loadBillList();
        loadCategoryList();
        loadStatList();

        $scope.createSpend = function() {
            $scope.operationModel.type = 'spend';
            delete $scope.operationModel.source_bill_id;

            transactionRequest()
                .then(function() {
                    $scope.billHash[$scope.operationModel.bill_id].balance -= parseInt($scope.operationModel.amount, 10);

                    $scope.clearOperationModel();
                });
        };

        $scope.createIncome = function() {
            $scope.operationModel.type = 'income';
            delete $scope.operationModel.source_bill_id;

            transactionRequest()
                .then(function() {
                    $scope.billHash[$scope.operationModel.bill_id].balance += parseInt($scope.operationModel.amount, 10);

                    $scope.clearOperationModel();
                });
        };

        $scope.createTransfer = function() {
            $scope.operationModel.type = 'transfer';
            transactionRequest()
                .then(function() {
                    $scope.billHash[$scope.operationModel.source_bill_id].balance      -= parseInt($scope.operationModel.amount, 10);
                    $scope.billHash[$scope.operationModel.bill_id].balance += parseInt($scope.operationModel.amount, 10);

                    $scope.clearOperationModel();
                });
        };

        $scope.transactionJournalLoadMore = function() {
            $scope.monthlyTransactionCountToDisplayInJournal += 5;
        };

        $scope.clearOperationModel = function() {
            $scope.operationModel = {};
        };

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

        function loadMonthlyTransactionList() {
            ApiService
                .getAllTransactions({date_interval_from: moment().startOf('month').format()})
                .success(function(response) {
                    $scope.monthlyTransactionList = response.data;
                })
                .error(function() {
                    alert('Произошла ошибка. Обновите страницу.');
                })
                .finally(function() {
                    $scope.monthlyTransactionListLoaded = true;
                });
        }

        function transactionRequest() {
            var deferred = $q.defer();

            $scope.error = {};
            $scope.operationSubmitted = true;
            $scope.operationButtonBlocked = true;

            ApiService
                .createTransaction($scope.operationModel)
                .success(function(response) {
                    var newTransaction = response.data;
                    $scope.monthlyTransactionList[newTransaction.id] = newTransaction;

                    $scope.statHash = {};
                    $scope.statHashLoaded = false;
                    loadStatList();

                    return deferred.resolve();
                })
                .error(function(response) {
                    angular.forEach(response.error_description, function(error) {
                        $scope.error[error.field] = error.message;
                    });
                    return deferred.reject();
                })
                .finally(function() {
                    $scope.operationButtonBlocked = false;
                });

            return deferred.promise;
        }

        function loadStatList() {
            ApiService
                .statDashboard()
                .success(function(response) {
                    $scope.statHash = response.data;
                })
                .error(function() {
                    alert('Произошла ошибка. Обновите страницу.');
                })
                .finally(function() {
                    $scope.statHashLoaded = true;
                });
        }

    }
})();
