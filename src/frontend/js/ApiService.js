(function () {
    'use strict';

    angular
        .module('app')
        .service('ApiService', ApiService);

    function ApiService($http, config) {
        var service = this;

        service.login = function(data) {
            return $http.post(config.apiUrl + '/login', data);
        };

        service.demoLogin = function() {
            return $http.post(config.apiUrl + '/demo-login', {});
        };

        service.dashboard = function() {
            return $http.get(config.apiUrl + '/dashboard');
        };

        service.register = function(data) {
            return $http.post(config.apiUrl + '/register', data);
        };

        service.createBill = function(data) {
            return $http.post(config.apiUrl + '/bill', data);
        };

        service.getAllBills = function() {
            return $http.get(config.apiUrl + '/bill');
        };

        service.updateBill = function(id, data) {
            return $http.put(config.apiUrl + '/bill/' + id, data);
        };

        service.deleteBill = function(id) {
            return $http.delete(config.apiUrl + '/bill/' + id);
        };

        service.getAllCategories = function() {
            return $http.get(config.apiUrl + '/category');
        };

        service.updateCategory = function(id, data) {
            return $http.put(config.apiUrl + '/category/' + id, data);
        };

        service.createCategory = function(data) {
            return $http.post(config.apiUrl + '/category', data);
        };

        service.deleteCategory = function(id) {
            return $http.delete(config.apiUrl + '/category/' + id);
        };

        service.createTransaction = function(data) {
            return $http.post(config.apiUrl + '/transaction', data);
        };

        service.getAllTransactions = function(data) {
            return $http.get(config.apiUrl + '/transaction', {params: data});
        };

        service.createTransfer = function(data) {
            return $http.post(config.apiUrl + '/transfer', data);
        };

        service.statDashboard = function() {
            return $http.get(config.apiUrl + '/stat-dashboard');
        };

        service.statByMonth = function(data) {
            return $http.post(config.apiUrl + '/stat-by-month', data);
        };

        service.statByCategory = function(data) {
            return $http.post(config.apiUrl + '/stat-by-category', data);
        };

        service.deleteTransaction = function(id) {
            return $http.delete(config.apiUrl + '/transaction/' + id);
        };

        service.updateTransaction = function(id, data) {
            return $http.put(config.apiUrl + '/transaction/' + id, data);
        };

        service.updateUser = function(data) {
            return $http.put(config.apiUrl + '/user/update', data);
        };


    }
})();
