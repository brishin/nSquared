IndexCtrl = ($scope, $http) ->
  $http.get("json/trendland.json").success (data) ->
    $scope.content = data

  $scope.$evalAsync ->
    console.log 'loaded'
IndexCtrl.$inject = [ "$scope", "$http" ]

SquareCtrl = ($scope) ->
  false