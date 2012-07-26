IndexCtrl = ($scope, $http, $window, PostModel) ->
  $scope.content = []
  $scope.loadingDisabled = false
  $scope.toolbarUrl = nsq.toolbarUrl
  $scope.filters = []

  $scope.getNext = (pageNum) ->
    $scope.loadingDisabled = true
    if $scope.filters.length > 0
      searchWithFilters()
    else
      PostModel.query pageNum, (data) ->
        $scope.content = $scope.content.concat data
        # TODO: Rewrite to detect for page load
        setTimeout =>
          $scope.loadingDisabled = false
          console.log 'Done loading.'
        , 600

  searchWithFilters = ->
    PostModel.searchWithFilters $scope.filters, (data) ->
      replaceContent data

  $scope.$on 'addFilter', (event, type, data) ->
    filterName = data['name'] or data
    # Color cannot be added with other filters for now
    $scope.filters = [] if type == 'color'
    $scope.filters.push({'type': type, 'data': data, 'name': filterName})
    $scope.$broadcast 'updateFilters', $scope.filters
    $scope.getNext()
    
  $scope.$on 'removeFilter', (event, filter) ->
    console.log filter
    filterIndex = $scope.filters.indexOf filter
    $scope.filters.splice filterIndex, 1
    $scope.$broadcast 'updateFilters', $scope.filters
    $scope.getNext()

  replaceContent = (data) ->
    if $scope.filters.length > 0 and not $scope.content
      $scope.tempContent = $scope.content
    $scope.content = data

  # Initial page load
  $scope.getNext()

#IndexCtrl.$inject = [ "$scope", "$http" , "$window", "PostModel"]

NavCtrl = ($scope, $http, PostModel) ->
  $scope.filters = []
  if nsq.categories and nsq.tags
    $scope.categories = JSON.parse nsq.categories
    $scope.tags = JSON.parse nsq.tags

  $scope.$on 'updateFilters', (event, filters) ->
    $scope.filters = filters