(($)->
  $ ->
    $scope = $('.nrelate_nsquared').scope()
    $toolbar_scope = $('.toolbar').scope()
    console.log $scope
    console.log $toolbar_scope
    $toolbar_scope.categories = JSON.parse nsqDomain.categories
    $toolbar_scope.tags = JSON.parse nsqDomain.tags
)(jQuery)