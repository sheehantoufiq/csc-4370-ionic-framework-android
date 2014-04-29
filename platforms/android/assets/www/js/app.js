// Ionic Starter App

// angular.module is a global place for creating, registering and retrieving Angular modules
// 'starter' is the name of this angular module example (also set in a <body> attribute in index.html)
// the 2nd parameter is an array of 'requires'
// 'starter.services' is found in services.js
// 'starter.controllers' is found in controllers.js
angular.module('shelfspaceMobile', [
  'ionic',
  'ngCookies',
  'shelfspaceMobile.controllers', 
  'shelfspaceMobile.services'
])

.config(function($stateProvider, $urlRouterProvider) {

  $stateProvider
  .state('login', {
    url: '/login',
    templateUrl: 'templates/login.html',
    controller: 'LoginCtrl'
  })
  .state('register', {
    url: '/register',
    templateUrl: 'templates/register.html',
    controller: 'RegisterCtrl'
  })
  .state('home', {
    url: '/home',
    templateUrl: 'templates/home.html',
    controller: 'HomeCtrl'
  })
  .state('user', {
    url: '/user',
    templateUrl: 'templates/user.html',
    controller: 'UserCtrl'
  })
  .state('add', {
    url: '/add',
    templateUrl: 'templates/add.html',
    controller: 'AddCtrl'
  })
  .state('search', {
    url: '/search',
    templateUrl: 'templates/search.html',
    controller: 'SearchCtrl'
  })
  .state('settings', {
    url: '/settings',
    templateUrl: 'templates/settings.html',
    controller: 'SettingsCtrl'
  })  
  .state('item', {
    url: '/item',
    templateUrl: 'templates/item.html',
    controller: 'ItemCtrl'
  });

  $urlRouterProvider.otherwise("/login");

});

