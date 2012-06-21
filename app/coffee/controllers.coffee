IndexCtrl = ($scope, $http, $window) ->
  $scope.content = []
  @isLoading = false
  @currentPage = 1

  getPage = (pageNum) ->
    $http.get("json/trendland" + pageNum + ".json").success (data) ->
      $scope.content = $scope.content.concat data
      @currentPage += 1
      @isLoading = false

  loadNext = (amount) =>
    getPage(@currentPage + 1)

  getPage(@currentPage)
  @currentPage += 1

  $window.$ =>
    console.log 'inject'
    $(window).scroll =>
      @didScroll = true
    setInterval =>
      if @didScroll and $(window).scrollTop() > \
          $(document).height() - $(window).height() * 1.4 and\
          not @isLoading
        console.log 'bottom'
        @didScroll = false
        @isLoading = true
        loadNext(50)
    , 200

  $scope.$on 'search', (event, query) ->
    $scope.query = query
 
  $scope.$on 'categoryFilter', (event, cat) ->
    $scope.category = 
      'category': cat

IndexCtrl.$inject = [ "$scope", "$http" , "$window"]

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
