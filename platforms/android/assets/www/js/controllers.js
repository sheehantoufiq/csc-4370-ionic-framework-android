angular.module('shelfspaceMobile.controllers', [])

.controller('HeaderCtrl', function($scope, $cookieStore, $location) {

	$scope.loggedIn = $cookieStore.get('loggedin');
  if ($scope.loggedIn == "true") {
    $scope.loggedOut = "";
  } else {
    $scope.loggedOut = "true";
    $location.path('#/login'); 
  }

})

.controller('LogheadCtrl', function($scope, $cookieStore, $location) {

  $scope.loggedIn = $cookieStore.get('loggedin');
  if ($scope.loggedIn == "true") {
    $scope.loggedOut = "";
    $location.path('#/home');
  } else {
    $scope.loggedOut = "true";
  }

})

.controller('LoginCtrl', function($scope, $http, $location, $cookieStore) {

  $scope.email = "";
  $scope.password = "";
  $scope.msg = "";

  $scope.login = function(){
      $scope.msg = "";

      var config = {
          url: 'http://localhost:8888/shelfspace/api/v2/login',
          method: 'POST',
          data: JSON.stringify({
            email: $scope.email,
            password: $scope.password
          }),
          headers: {'Content-Type': 'application/x-www-form-urlencoded'}
      }

      console.log('User: ' + $scope.email + $scope.password);
      
      $http(config)
      .success(function(data,status,headers,config){
          if(data.msg != 'Wrong Credentials'){
              //successfull login
              $cookieStore.put('loggedin', 'true');
              $location.path('/home');
          }
          else{
              $cookieStore.put('loggedin', '');
              $scope.msg = "Login Information Incorrect.";
          }
      })
      .error(function(data,status,headers,config){
          $cookieStore.put('loggedin', '');
      }) 
  }

  $scope.logout = function() {
    $cookieStore.put('loggedin', '');
    $location.path('/login');
  }
})

.controller('RegisterCtrl', function($scope) {
  $scope.email = "";
  $scope.password = "";
  $scope.msg = "";

  $scope.register = function(){
      $scope.msg = "";

      var config = {
          url: 'http://localhost:8888/shelfspace/api/v2/login',
          method: 'POST',
          data: JSON.stringify({
            email: $scope.email,
            password: $scope.password
          }),
          headers: {'Content-Type': 'application/json; charset=utf-8'}
      }

      $http(config)
      .success(function(data,status,headers,config){
          if(data.msg != 'Failure to create user'){
              $location.path('/login');
          }
          else{
              $scope.msg = "Email already exists.";          
          }
      })
      .error(function(data,status,headers,config){

      })
    }
})

.controller('UserCtrl', function($scope) {
})

.controller('AddCtrl', function($scope) {
})

.controller('SearchCtrl', function($scope) {
})

.controller('SettingsCtrl', function($scope) {
})

.controller('ItemCtrl', function($scope) {
});
