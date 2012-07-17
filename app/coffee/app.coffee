nsq = {}
if nsqPath?
  nsq.templateUrl = nsqPath.partialsDIR + 'index.html'
  nsq.toolbarUrl = nsqPath.partialsDIR + 'toolbar.html'
else
  nsq.templateUrl = '/app/partials/index.html'
  nsq.toolbarUrl = '/app/partials/toolbar.html'

angular.module("myApp", [ "myApp.filters", "myApp.services", "myApp.directives" ]).config [ "$routeProvider", ($routeProvider) ->
  $routeProvider.when "/",
    templateUrl: nsq.templateUrl
    controller: IndexCtrl

  $routeProvider.otherwise redirectTo: "/"
 ]