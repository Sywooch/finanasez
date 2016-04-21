(function () {
    'use strict';
    angular
        .module('app')
        .filter('hashSize', function() {
            return function(hash) {
                return Object.keys(hash).length;
            }
        })
        .filter('billWithBalanceFilter', function(){
            return function(billList) {
                var filteredBillHash = {};

                angular.forEach(billList, function(bill) {
                    if (bill.balance != 0) {
                        filteredBillHash[bill.id] = bill;
                    }
                });

                return filteredBillHash;
            };
        })
        .filter('billBalanceSum', function(){
            return function(billList) {
                var sum = 0;

                angular.forEach(billList, function(bill) {
                    sum += bill.balance;
                });

                return sum;
            };
        })
        .filter('categoryFilter', function() {
           return function(categoryHash, categoryType) {
               var filteredCategoryHash = {};

               angular.forEach(categoryHash, function(category) {
                   if (categoryType == 'income' && category.is_income ||
                       categoryType == 'spend' && !category.is_income
                   ) {
                       filteredCategoryHash[category.id] = category;
                   }
               });

               return filteredCategoryHash;
           }
        })
        .filter('transactionOrderFilter', function() {
            return function(transactionHash) {

                var transactionArray = [];

                for (var transactionId in transactionHash) {
                    transactionArray.push(transactionHash[transactionId]);
                }

                return transactionArray.sort(function(first, second) {
                    return second.datetime_local > first.datetime_local ? 1 : -1;
                });

            }
        })
        .filter('humanReadableTransactionType', function(Helper, $sce) {
            return function(type) {
                var typeAsString =  Helper.getHumanReadableTransactionType(type);
                switch (typeAsString) {
                    case 'Расход':   return $sce.trustAsHtml('<span class="label label-danger">' + typeAsString + '</span>');
                    case 'Доход':    return $sce.trustAsHtml('<span class="label label-success">' + typeAsString + '</span>');
                    case 'Перевод':  return $sce.trustAsHtml('<span class="label label-primary">' + typeAsString + '</span>');
                }
            }
        })
        .filter('prettyDateFilter', function() {
            return function(date, format) {
                format = format || 'DD.MM.YYYY';
                return moment(date, 'DD.MM.YYYY HH:mm:ss').format(format);
            }
        })
        .filter('humanReadableCategoryType', function(Helper) {
            return function(type) {
                return Helper.getHumanReadableCategoryType(type);
            }
        })
        .filter('transactionSpendSum', function(Helper){
            return function(transactionList) {
                return transactionSumForType(transactionList, Helper.getApiTransactionType('spend'));
            };
        })
        .filter('transactionIncomeSum', function(Helper){
            return function(transactionList) {
                return transactionSumForType(transactionList, Helper.getApiTransactionType('income'));
            };
        });

    function transactionSumForType(transactionList, type) {
        var sum = 0;

        angular.forEach(transactionList, function(transaction) {
            if (transaction.type == type) {
                sum += parseInt(transaction.amount);
            }
        });

        return sum;
    }



})();
