if nsq? and nsq.partialsDIR?
  nsq.templateUrl = nsq.partialsDIR + 'index.html'
  nsq.toolbarUrl = nsq.partialsDIR + 'toolbar.html'
else
  if not nsq?
    nsq = {}
  nsq.templateUrl = '/app/partials/index.html'
  nsq.toolbarUrl = '/app/partials/toolbar.html'

angular.module("nSquared", [ "nSquared.filters", "nSquared.services", "nSquared.directives" ]).config [ "$routeProvider", ($routeProvider) ->
  $routeProvider.when "/",
    templateUrl: nsq.templateUrl
    controller: IndexCtrl

  $routeProvider.otherwise redirectTo: "/"
 ]