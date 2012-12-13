if not nsq?
  nsq = {}
# nsq.domain = 'digitalscrapbookplace.com'
# nsq.domain = 'trendland.com'

STATIC_DOMAIN = 'http://nsquared.nrelate.com/static/'

angular.module("nSquared", [ "nSquared.filters", "nSquared.services", "nSquared.directives" ]).config [ "$routeProvider", ($routeProvider) ->
  $routeProvider.when "/",
    # templateUrl: nsq.templateUrl
    template: '<div></div>'
    controller: IndexCtrl

  $routeProvider.otherwise redirectTo: "/"
 ]