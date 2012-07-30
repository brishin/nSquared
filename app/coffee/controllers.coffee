IndexCtrl = ($scope, $http, $window, PostModel) ->
  $scope.content = []
  $scope.loadingDisabled = false
  $scope.toolbarUrl = nsq.toolbarUrl
  $scope.filters = []
  $scope.prevFilters = '[]'

  $scope.getNext = (pageNum) ->
    $scope.loadingDisabled = true
    if $scope.prevFilters != JSON.stringify($scope.filters)
      console.log 'pages reset'
      PostModel.resetPageNum()
    if $scope.filters.length > 0
      PostModel.searchWithFilters $scope.filters, (data) ->
        pushContent data
    else
      PostModel.query pageNum, (data) ->
        pushContent data    

  $scope.$on 'addFilter', (event, type, data) ->
    return if data == ''
    PostModel.resetPageNum()
    filterName = data['name'] or data
    # Color cannot be added with other filters for now
    $scope.filters = [] if type == 'color'
    newFilter = {'type': type, 'data': data, 'name': filterName}
    return if ($scope.filters.indexOf newFilter) != -1
    $scope.filters.push newFilter
    $scope.$broadcast 'updateFilters', $scope.filters
    $scope.getNext()
    
  $scope.$on 'removeFilter', (event, filter) ->
    console.log filter
    filterIndex = $scope.filters.indexOf filter
    $scope.filters.splice filterIndex, 1
    $scope.$broadcast 'updateFilters', $scope.filters
    clearContent()
    $scope.getNext()

  pushContent = (data) ->
    if $scope.prevFilters == JSON.stringify($scope.filters)
      $scope.content = $scope.content.concat data
    else
      $scope.content = data
    $scope.prevFilters = JSON.stringify($scope.filters)
    $scope.$evalAsync ->
      $scope.loadingDisabled = false

  clearContent = ->
    $scope.content = []

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