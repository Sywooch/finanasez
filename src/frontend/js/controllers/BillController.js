(function() {
    'use strict';
    angular
        .module('app')
        .controller('BillController', BillController);

    function BillController($scope, ApiService) {
        $scope.billHash = [];
        $scope.billHashLoaded = false;

        $scope.createModel = {};
        $scope.errorCreate = {};
        $scope.billCreateSubmitted = false;

        $scope.updateModelHash = {};
        $scope.errorUpdate = {};
        $scope.showBillUpdateFormHash = {};
        $scope.billUpdateSubmitted = false;

        loadBillHash();

        $scope.createBill = function() {
            $scope.billCreateSubmitted = true;
            $scope.errorCreate = {};

            ApiService
                .createBill($scope.createModel)
                .success(function(response) {
                    $scope.createModel = {};

                    var newBill = response.data;
                    $scope.billHash[newBill.id] = newBill;
                })
                .error(function(response) {
                    angular.forEach(response.error_description, function(error) {
                       $scope.errorCreate[error.field] = error.message;
                    });
                })
                .finally(function() {
                    $scope.billCreateSubmitted = false;
                });
        };

        $scope.removeBill = function (billId) {
            if (!confirm('Вы действительно хотите удалить этот счет? Все операции по этому счету будут также удалены!')) {
                return false;
            }
            ApiService
                .deleteBill(billId)
                .success(function() {
                    delete $scope.billHash[billId];
                })
                .error(function(response) {
                    alert('Произошла ошибка. Обновите страницу.');
                });
        };

        $scope.updateBill = function(billId) {
            $scope.billUpdateSubmitted = true;
            $scope.errorUpdate = {};

            ApiService
                .updateBill(billId, $scope.updateModelHash[billId])
                .success(function(response) {
                    var updatedBill = response.data;
                    $scope.billHash[updatedBill.id] = updatedBill;

                    delete $scope.showBillUpdateFormHash[billId];

                    $scope.updateModelHash[billId] = {};
                })
                .error(function(response) {
                    angular.forEach(response.error_description, function(error) {
                        $scope.errorUpdate[error.field] = error.message;
                    });
                })
                .finally(function() {
                    $scope.billUpdateSubmitted = false;
                });
        };

        $scope.showUpdateForm = function(billId) {
            $scope.showBillUpdateFormHash = {};
            $scope.showBillUpdateFormHash[billId] = true;
        };

        function loadBillHash() {
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
