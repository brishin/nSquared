if nsq? and nsq.partialsDIR?
  nsq.templateUrl = nsq.partialsDIR + 'index.html'
  nsq.toolbarUrl = nsq.partialsDIR + 'toolbar.html'
else
  nsq = {} if not nsq?
  nsq.templateUrl = '/app/partials/index.html'
  nsq.toolbarUrl = '/app/partials/toolbar.html'
  # nsq.domain = 'trendland.com'

STATIC_DOMAIN = 'http://nsquared.nrelate.com/api/static/'

jQuery.ajax
  url: STATIC_DOMAIN + 'index.html.json'
  dataType: 'jsonp'
  success: (data) ->
    console.log data
  jsonp: weqrio9834938

angular.module("nSquared", [ "nSquared.filters", "nSquared.services", "nSquared.directives" ]).config [ "$routeProvider", ($routeProvider) ->
  console.log $routeProvider
  $routeProvider.when "/",
    templateUrl: nsq.templateUrl
    controller: IndexCtrl

  $routeProvider.otherwise redirectTo: "/"
 ]