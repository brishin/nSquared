if not nsq?
  nsq = {}
# nsq.domain = 'digitalscrapbookplace.com'
# nsq.domain = 'trendland.com'

STATIC_DOMAIN = 'http://nsquared.nrelate.com/static/'

# jQuery.ajax
#   url: STATIC_DOMAIN + 'partials/index.html.json'
#   dataType: 'jsonp'
#   success: (data) ->
#     console.log data
#   jsonpCallback: 'weqrio9834938'

angular.module("nSquared", [ "nSquared.filters", "nSquared.services", "nSquared.directives" ]).config [ "$routeProvider", ($routeProvider) ->
  $routeProvider.when "/",
    # templateUrl: nsq.templateUrl
    template: '<div></div>'
    controller: IndexCtrl

  $routeProvider.otherwise redirectTo: "/"
 ]