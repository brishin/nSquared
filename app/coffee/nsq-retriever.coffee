(($)->
  $ ->
    $scope = $('.nrelate_nsquared').scope()
    $toolbar_scope = $('.toolbar').scope()
    console.log $scope
    console.log $toolbar_scope
    $toolbar_scope.categories = JSON.parse nsq.categories
    $toolbar_scope.tags = JSON.parse nsq.tags
)(jQuery)