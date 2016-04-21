(function () {
    'use strict';
    angular
        .module('app')
        .directive('inputMask', function() {

            return {
                restrict: 'A',
                link: function (scope, el, attrs) {
                    $(el).mask(attrs.inputMask);
                }
            };

        });
})();
