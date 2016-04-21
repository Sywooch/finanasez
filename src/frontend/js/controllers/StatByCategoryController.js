(function() {
    'use strict';
    angular
        .module('app')
        .controller('StatByCategoryController', StatByCategoryController);

    function StatByCategoryController($scope, $uibModal, ApiService) {
        $scope.statList = [];
        $scope.statListLoaded = false;

        $scope.dateFilter = {
            date_interval_from: moment().startOf('month').format('DD.MM.YYYY'),
            date_interval_till: moment().startOf('day').add(1, 'day').format('DD.MM.YYYY')
        };

        $scope.graph = {
            income: {
                labels: [],
                data: []
            },
            spend: {
                labels: [],
                data: []
            },
            compare: {
                labels: ['Расход к доходу'],
                data: [],
                series: ['Расход', 'Доход']
            }
        };

        $scope.$watch('dateFilter', function() {
            loadStats();
        }, true);

        $scope.showDetails = function(categoryName) {
            $uibModal.open({
                size: 'lg',
                templateUrl: '/partials/stat_by_category_transaction_list.html',
                controller: 'StatByCategoryTransactionListController',
                resolve: {
                    categoryName: function() {
                        return categoryName;
                    }
                }
            });
        };

        $scope.showDetailsFromChart = function(points) {
            $scope.showDetails(points[0].label);
        };

        function loadStats() {
            ApiService
                .statByCategory($scope.dateFilter)
                .success(function (response) {

                    $scope.statList = response.data;

                    $scope.graph.income = {
                        labels: [],
                        data: []
                    };

                    $scope.graph.spend = {
                        labels: [],
                        data: []
                    };

                    var spendSum = 0;
                    var incomeSum = 0;

                    if (Object.keys($scope.statList).length > 0) {
                        angular.forEach($scope.statList, function(row) {
                            if (row.is_income == true) {
                                $scope.graph.income.labels.push(row.name);
                                $scope.graph.income.data.push(row.sum);
                                incomeSum += parseInt(row.sum, 10);
                            } else {
                                $scope.graph.spend.labels.push(row.name);
                                $scope.graph.spend.data.push(row.sum);
                                spendSum += parseInt(row.sum, 10);
                            }
                        });
                    } else {
                        $scope.graph.spend.data.push(0);
                        $scope.graph.income.data.push(0);
                    }


                    $scope.graph.compare.data = [];

                    $scope.graph.compare.data[0] = $scope.graph.compare.data[0] || [];
                    $scope.graph.compare.data[0].push(spendSum);

                    $scope.graph.compare.data[1] = $scope.graph.compare.data[1] || [];
                    $scope.graph.compare.data[1].push(incomeSum);

                })
                .finally(function () {
                    $scope.statListLoaded = true;
                });
        }

    }
})();
