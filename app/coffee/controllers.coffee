IndexCtrl = ($scope, $http) ->
  $http.get("json/trendland.json").success (data) ->
    $scope.content = data

  $scope.$evalAsync ->
    return

  $scope.injectData = () ->
    #console.log @$element
IndexCtrl.$inject = [ "$scope", "$http" ]

SquareCtrl = ($scope) ->
  false

NavCtrl = ($scope, $http) ->
  categories = []
  $http.get("json/trendland.json").success (data) ->
   for post in data
    console.log typeof post.category
    if post.category.length > 0 and post.category instanceof Array
      for category in post.category
        if categories.indexOf category is -1
          categories.push category
    else if post.category instanceof String
      if categories.indexOf post.category is -1
          categories.push post.category
  $scope.categories = categories
