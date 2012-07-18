if nsq? and nsq.partialsDIR?
  nsq.templateUrl = nsq.partialsDIR + 'index.html'
  nsq.toolbarUrl = nsq.partialsDIR + 'toolbar.html'
else
  nsq = {}
  nsq.templateUrl = '/app/partials/index.html'
  nsq.toolbarUrl = '/app/partials/toolbar.html'

angular.module("myApp", [ "myApp.filters", "myApp.services", "myApp.directives" ]).config [ "$routeProvider", ($routeProvider) ->
  $routeProvider.when "/",
    templateUrl: nsq.templateUrl
    controller: IndexCtrl

  $routeProvider.otherwise redirectTo: "/"
 ]