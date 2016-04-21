(function () {
    'use strict';

    angular
        .module('app')
        .config(function ($httpProvider, $locationProvider, $stateProvider, $urlRouterProvider) {
            $stateProvider
                .state('public', {
                    templateUrl: '/templates/public.html',
                    abstract: true
                })
                .state('private', {
                    templateUrl: '/templates/private.html',
                    abstract: true,
                    controller: 'MainController'
                })
                .state('index', {
                    url: '/',
                    parent: 'public',
                    templateUrl: '/partials/index.html',
                    controller: 'IndexController'
                })
                .state('login', {
                    url: '/login',
                    parent: 'public',
                    templateUrl: '/partials/login.html',
                    controller: 'LoginController'
                })
                .state('register', {
                    url: '/register',
                    parent: 'public',
                    templateUrl: '/partials/register.html',
                    controller: 'RegisterController'

                })
                .state('dashboard', {
                    url: '/dashboard',
                    parent: 'private',
                    templateUrl: '/partials/dashboard.html',
                    controller: 'DashboardController'
                })
                .state('bill', {
                    url: '/bill',
                    parent: 'private',
                    templateUrl: '/partials/bill.html',
                    controller: 'BillController'
                })
                .state('category', {
                    url: '/category',
                    parent: 'private',
                    templateUrl: '/partials/category.html',
                    controller: 'CategoryController'
                })
                .state('transaction', {
                    url: '/transaction',
                    parent: 'private',
                    templateUrl: '/partials/transaction.html',
                    controller: 'TransactionController'
                })
                .state('stat-by-month', {
                    url: '/stat-by-month',
                    parent: 'private',
                    templateUrl: '/partials/stat_by_month.html',
                    controller: 'StatByMonthController'
                })
                .state('stat-by-category', {
                    url: '/stat-by-category',
                    parent: 'private',
                    templateUrl: '/partials/stat_by_category.html',
                    controller: 'StatByCategoryController'
                })
                .state('user-settings', {
                    url: '/user-settings',
                    parent: 'private',
                    templateUrl: '/partials/user_settings.html',
                    controller: 'UserSettingsController'
                })
                .state('demo-login', {
                    url: '/demo-login',
                    parent: 'public',
                    templateUrl: '/partials/demo_login.html',
                    controller: 'DemoLoginController'
                });




            $urlRouterProvider.otherwise('/');

            $locationProvider.html5Mode(true);
            $httpProvider.interceptors.push('AuthInterceptorService');
        }
    );
})();