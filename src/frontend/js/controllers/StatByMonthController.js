(function() {
    'use strict';
    angular
        .module('app')
        .controller('StatByMonthController', StatByMonthController);

    function StatByMonthController($scope, $filter, $timeout, ApiService, Helper) {
        $scope.statList = [];
        $scope.statListLoaded = false;

        $scope.billList = [];
        $scope.billListLoaded = false;
        $scope.billFilterSelected = {};

        $scope.graph = {};
        $scope.graph.series = ['Расход', 'Доход'];
        $scope.graph.labels = [];
        $scope.graph.data = [];

        loadBills();

        $scope.$watch('billFilterSelected', function() {
            loadStats();
        }, true);

        function loadBills() {
            ApiService
                .getAllBills()
                .success(function (response) {
                    angular.forEach(response.data, function (hashValue) {
                        $scope.billList.push(hashValue);
                    });
                })
                .finally(function () {
                    $scope.billListLoaded = true;
                });
        }

        function loadStats() {
            ApiService
                .statByMonth({bill_ids: prepareBillFilter()})
                .success(function (response) {

                    $scope.statList = response.data;

                    $scope.graph.labels = [];
                    $scope.graph.data = [];

                    if ($scope.statList.length > 0) {
                        angular.forEach($scope.statList, function(row) {
                            $scope.graph.labels.push($filter('prettyDateFilter')(row.month));

                            $scope.graph.data[0] = $scope.graph.data[0] || [];
                            $scope.graph.data[0].push(row.spend_sum);

                            $scope.graph.data[1] = $scope.graph.data[1] || [];
                            $scope.graph.data[1].push(row.income_sum);
                        });
                    } else {
                        $scope.graph.data[0] = [];
                        $scope.graph.data[0].push(0);

                        $scope.graph.data[1] = [];
                        $scope.graph.data[1].push(0);
                    }
                })
                .finally(function () {
                    $scope.statListLoaded = true;
                });
        }

        function prepareBillFilter() {
            var billFilter = [];
            angular.forEach($scope.billFilterSelected, function(hashValue) {
                billFilter.push(hashValue.id);
            });
            return billFilter;
        }
    }
})();
