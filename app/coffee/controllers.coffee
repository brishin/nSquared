IndexCtrl = ($scope, $http) ->
  $http.get("json/trendland.json").success (data) ->
    $scope.content = data
IndexCtrl.$inject = [ "$scope", "$http" ]