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
   for category in data
    if categories.indexOf category is -1
      categories.push category
  $scope.categories = categories
