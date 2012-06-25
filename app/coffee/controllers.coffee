IndexCtrl = ($scope, $http, $window, PostModel) ->
  $scope.content = []
  @isLoading = false

  console.log PostModel

  getPage = (pageNum) =>
    PostModel.query pageNum, (data) =>
      $scope.content = $scope.content.concat data
      # TODO: Rewrite to detect for page load
      setTimeout =>
        @isLoading = false
        console.log 'Done loading.'
      , 600      

  # Initial page load
  getPage() if $scope.content?

  $window.$ =>
    console.log 'scroll inject'
    $(window).scroll =>
      @didScroll = true
    setInterval =>
      if @didScroll and $(window).scrollTop() > \
          $(document).height() - $(window).height() * 1.4 and\
          not @isLoading
        console.log 'Bottom reached'
        @didScroll = false
        @isLoading = true
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
  $http.get("json/trendland1.json").success (data) ->
   for post in data
    if post.category instanceof Array
      for category in post.category
        if categories.indexOf(category) == -1
          categories.push category
    else if post.category instanceof String
      if categories.indexOf(post.category) == -1
        categories.push post.category
  $scope.categories = categories
