IndexCtrl = ($scope, $http, $window, PostModel, Helper) ->
  $scope.content = []
  $scope.loadingDisabled = false
  $scope.toolbarUrl = nsq.toolbarUrl
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

  $scope.$on 'search', (event, query) ->
    if query != ''
      $scope.tempContent = $scope.content
      PostModel.search query, (data) ->
        $scope.content = data
      $scope.loadingDisabled = true
    else
      $scope.content = $scope.tempContent
      $scope.loadingDisabled = false

  $scope.$on '$viewContentLoaded', (event) ->
    console.log 'view loaded'
 
  $scope.$on 'categoryFilter', (event, category, id) ->
    if category? and category != '' and id
      $scope.category = category
      PostModel.search 'catID:' + id, (data) ->
        $scope.tempContent = $scope.content
        $scope.content = data
        $scope.loadingDisabled = true
    else
      if $scope.loadingDisabled
        $scope.content = $scope.tempContent
        $scope.tempContent = null
        $scope.loadingDiabled = false

  $scope.$on 'tagFilter', (event, tag, id) ->
    if tag? and tag != '' and id
      if not $scope.tag?
        $scope.tag = []
      $scope.tag.push({'tag': tag, 'id': id})
      PostModel.search 'tagID:' + id, (data) ->
        $scope.tempContent = $scope.content
        $scope.content = data
        console.log $scope.content
        $scope.loadingDisabled = true
    else
      $scope.content = $scope.tempContent
      $scope.tempContent = null
      $scope.loadingDiabled = false

  $scope.$on 'color', (event, color) ->
    if color != ''
      $scope.tempContent = $scope.content
      PostModel.searchColor color, (data) ->
        $scope.content = data
      $scope.loadingDisabled = true
    else
      $scope.content = $scope.tempContent
      $scope.tempContent = null
      $scope.loadingDiabled = false

#IndexCtrl.$inject = [ "$scope", "$http" , "$window", "PostModel"]

SquareCtrl = ($scope) ->
  false

NavCtrl = ($scope, $http, PostModel) ->
  if nsq.categories and nsq.tags
    $scope.categories = JSON.parse nsq.categories
    $scope.tags = JSON.parse nsq.tags