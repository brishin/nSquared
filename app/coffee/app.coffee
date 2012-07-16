tempPath = nsqPath.partialsDIR + 'index.html'
toolPath = nsqPath.partialsDIR + 'toolbar.html'

angular.module("myApp", [ "myApp.filters", "myApp.services", "myApp.directives" ]).config [ "$routeProvider", ($routeProvider) ->
  $routeProvider.when "/",
    templateUrl: tempPath
    controller: IndexCtrl

  $routeProvider.otherwise redirectTo: "/"
 ]