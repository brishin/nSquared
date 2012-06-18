angular.module("myApp", [ "myApp.filters", "myApp.services", "myApp.directives" ]).config [ "$routeProvider", ($routeProvider) ->
  $routeProvider.when "/",
    templateUrl: "partials/index.html"
    controller: IndexCtrl

  $routeProvider.otherwise redirectTo: "/"
 ]