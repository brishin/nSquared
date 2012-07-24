IndexCtrl = ($scope, $http, $window, PostModel, Helper) ->
  $scope.content = []
  $scope.loadingDisabled = false
  $scope.toolbarUrl = nsq.toolbarUrl
  $scope.filters = []
  console.log PostModel

  $scope.getPage = (pageNum) ->
    PostModel.query pageNum, (data) ->
      $scope.content = $scope.content.concat data
      # TODO: Rewrite to detect for page load
      setTimeout =>
        $scope.loadingDisabled = false
        console.log 'Done loading.'
      , 600

  # Initial page load
  $scope.loadingDisabled = true
  $scope.getPage() if $scope.content?

  $scope.$on '$viewContentLoaded', (event) ->
    console.log 'view loaded'

  $scope.$on 'addFilter', (event, type, data) ->
    switch type
      when 'category'
        PostModel.search 'catID:' + data.id, (data) ->
          replaceWithData(data)
      when 'tag'
        PostModel.search 'tagID:' + data.id, (data) ->
          replaceWithData(data)
      when 'search'
        PostModel.search data.query, (data) ->
          replaceWithData(data)
      when 'color'
        PostModel.searchColor color, (data) ->
          replaceWithData(data)

  replaceWithData = (data) ->


#IndexCtrl.$inject = [ "$scope", "$http" , "$window", "PostModel"]

NavCtrl = ($scope, $http, PostModel) ->
  $scope.addFilter = (type, data) ->
    $scope.$emit 'addFilter', type, data

  if nsq.categories and nsq.tags
    $scope.categories = JSON.parse nsq.categories
    $scope.tags = JSON.parse nsq.tags