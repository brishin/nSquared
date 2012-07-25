(($)->
  $ ->
    $scope = $('.nrelate_nsquared').scope()
    $toolbar_scope = $('.toolbar').scope()
    console.log $scope
    console.log $toolbar_scope
    $toolbar_scope.categories = JSON.parse nsq.categories
    $toolbar_scope.tags = JSON.parse nsq.tags
)(jQuery)

`!function(d,s,id){var js,fjs=d.getElementsByTagName(s)[0];if(!d.getElementById(id)){js=d.createElement(s);js.id=id;js.src="//platform.twitter.com/widgets.js";fjs.parentNode.insertBefore(js,fjs);}}(document,"script","twitter-wjs");`