IndexCtrl = ($scope, $http, $window, PostModel) ->
  $scope.content = []
  $scope.loadingDisabled = false

  console.log PostModel

  getPage = (pageNum) ->
    PostModel.query pageNum, (data) ->
      $scope.content = $scope.content.concat data
      # TODO: Rewrite to detect for page load
      setTimeout =>
        $scope.loadingDisabled = false
        console.log 'Done loading.'
      , 600      

  # Initial page load
  $scope.loadingDisabled = true
  getPage() if $scope.content?

  $window.$ =>
    console.log 'scroller injected'
    $(window).scroll =>
      @didScroll = true
    setInterval =>
      if @didScroll and $(window).scrollTop() > \
          $(document).height() - $(window).height() * 1.2 and\
          not $scope.loadingDisabled
        console.log 'Bottom reached'
        @didScroll = false
        $scope.loadingDisabled = true
        getPage()
    , 200

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
 
  $scope.$on 'categoryFilter', (event, cat) ->
    if cat
      $scope.category = 'category': cat
    else
      $scope.category = null

IndexCtrl.$inject = [ "$scope", "$http" , "$window", "PostModel"]

SquareCtrl = ($scope) ->
  false

NavCtrl = ($scope, $http) ->
  categories = []
  # Get list of categories
  $scope.categories = categories
