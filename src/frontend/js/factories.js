(function () {
    'use strict';
    angular
        .module('app')
        .factory('Helper', function() {
            function getApiTransactionType(type) {
                switch (type) {
                    case 'spend'    : return 1;
                    case 'income'   : return 2;
                    case 'transfer' : return 3;
                }
            }

            function getHumanReadableTransactionType(type) {
                switch (type) {
                    case '1': return 'Расход';
                    case '2': return 'Доход';
                    case '3': return 'Перевод';
                }
            }

            function getHumanReadableCategoryType(type) {
                if (type) {
                    return 'Доход';
                } else {
                    return 'Расход';
                }
            }

            return {
                getApiTransactionType: getApiTransactionType,
                getHumanReadableTransactionType: getHumanReadableTransactionType,
                getHumanReadableCategoryType: getHumanReadableCategoryType
            };
        });
})();
