(function() {
    'use strict';
    angular
        .module('app')
        .controller('CategoryController', CategoryController);

    function CategoryController($scope, ApiService) {
        $scope.errorCreate = {};
        $scope.categoryHash = [];
        $scope.categoryHashLoaded = false;

        $scope.createModel = {};
        $scope.categoryCreateSubmitted = false;

        $scope.updateModelHash = {};
        $scope.errorUpdate = {};
        $scope.showCategoryUpdateFormHash = {};
        $scope.categoryUpdateSubmitted = false;

        loadCategoryHash();

        $scope.createCategory = function() {
            $scope.categoryCreateSubmitted = true;
            $scope.errorCreate = {};

            ApiService
                .createCategory($scope.createModel)
                .success(function(response) {
                    $scope.createModel = {};

                    var newCategory = response.data;
                    $scope.categoryHash[newCategory.id] = newCategory;
                })
                .error(function(response) {
                    angular.forEach(response.error_description, function(error) {
                        $scope.errorCreate[error.field] = error.message;
                    });
                })
                .finally(function() {
                    $scope.categoryCreateSubmitted = false;
                });
        };

        $scope.removeCategory = function (categoryId) {
            if (!confirm('Вы действительно хотите удалить эту категорию?')) {
                return false;
            }
            ApiService
                .deleteCategory(categoryId)
                .success(function() {
                    delete $scope.categoryHash[categoryId];
                })
                .error(function() {
                    alert('Произошла ошибка. Обновите страницу.');
                });
        };

        $scope.updateCategory = function(categoryId) {
            $scope.categoryUpdateSubmitted = true;

            ApiService
                .updateCategory(categoryId, $scope.updateModelHash[categoryId])
                .success(function(response) {
                    var updatedCategory = response.data;

                    $scope.categoryHash[updatedCategory.id] = updatedCategory;
                    delete $scope.showCategoryUpdateFormHash[categoryId];

                    $scope.updateModelHash[categoryId] = {};
                })
                .error(function(response) {
                    angular.forEach(response.error_description, function(error) {
                        $scope.errorUpdate[error.field] = error.message;
                    });
                })
                .finally(function() {
                    $scope.categoryUpdateSubmitted = false;
                });
        };

        $scope.showUpdateForm = function(categoryId) {
            $scope.showCategoryUpdateFormHash = {};
            $scope.showCategoryUpdateFormHash[categoryId] = true;
        };

        function loadCategoryHash() {
            ApiService
                .getAllCategories()
                .success(function (response) {
                    $scope.categoryHash = response.data;
                })
                .error(function () {
                    alert('Произошла ошибка. Обновите страницу.');
                })
                .finally(function () {
                    $scope.categoryHashLoaded = true;
                });
        }
    }
})();
