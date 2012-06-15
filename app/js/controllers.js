'use strict';

/* Controllers */


function MyCtrl1() {}
MyCtrl1.$inject = [];


function MyCtrl2() {
}
MyCtrl2.$inject = [];


function IndexCtrl($scope, $http) {
  $http.get('js/example.json').success(function(data){
    $scope.content = data;
  });
}
IndexCtrl.$inject = ['$scope', '$http'];