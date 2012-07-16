// Generated by CoffeeScript 1.3.3

var indexPath = nsqPath.partialsDIR + "index.html";

angular.module("myApp", ["myApp.filters", "myApp.services", "myApp.directives"]).config([
  "$routeProvider", function($routeProvider) {
    $routeProvider.when("/", {
      templateUrl: indexPath,
      controller: IndexCtrl
    });
    return $routeProvider.otherwise({
      redirectTo: "/"
    });
  }
]);
