// Generated by CoffeeScript 1.3.3

angular.module("myApp", ["myApp.filters", "myApp.services", "myApp.directives"]).config([
  "$routeProvider", function($routeProvider) {
    $routeProvider.when("/", {
      templateUrl: "wp-content/plugins/nsquared/partials/index.html",
      controller: IndexCtrl
    });
    return $routeProvider.otherwise({
      redirectTo: "/"
    });
  }
]);
