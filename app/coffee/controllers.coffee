IndexCtrl = ($scope, $http, $window, PostModel) ->
  $scope.content = []
  $scope.isLoading = false

  console.log PostModel

  getPage = (pageNum) ->
    PostModel.query pageNum, (data) ->
      $scope.content = $scope.content.concat data
      # TODO: Rewrite to detect for page load
      setTimeout =>
        $scope.isLoading = false
        console.log 'Done loading.'
      , 600      

  # Initial page load
  getPage() if $scope.content?

  $window.$ =>
    console.log 'scroller injected'
    $(window).scroll =>
      @didScroll = true
    setInterval =>
      if @didScroll and $(window).scrollTop() > \
          $(document).height() - $(window).height() * 1.4 and\
          not $scope.isLoading
        console.log 'Bottom reached'
        @didScroll = false
        $scope.isLoading = true
        getPage()
    , 200

  $scope.$on 'search', (event, query) ->
    $scope.query = query
 
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
