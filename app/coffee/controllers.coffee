IndexCtrl = ($scope, $http, $window, PostModel) ->
  $scope.content = []
  $scope.loadingDisabled = false
  $scope.toolbarUrl = nsq.toolbarUrl
  console.log PostModel

  getPage = (pageNum) ->
    PostModel.query pageNum, (data) ->
      $scope.content = $scope.content.concat data
      # TODO: Rewrite to detect for page load
      setTimeout =>
        $scope.loadingDisabled = false
        console.log 'Done loading.'
      , 600      

  # Initial page load
  $scope.loadingDisabled = true
  getPage() if $scope.content?

  $window.jQuery =>
    console.log 'scroller injected'
    jQuery(window).scroll =>
      @didScroll = true
    setInterval =>
      if @didScroll and jQuery(window).scrollTop() > \
          jQuery(document).height() - jQuery(window).height() * 1.2 and\
          not $scope.loadingDisabled
        console.log 'Bottom reached'
        @didScroll = false
        $scope.loadingDisabled = true
        getPage()
    , 200

  $scope.$on 'search', (event, query) ->
    if query != ''
      $scope.tempContent = $scope.content
      PostModel.search query, (data) ->
        $scope.content = data
      $scope.loadingDisabled = true
    else
      $scope.content = $scope.tempContent
      $scope.loadingDisabled = false

  $scope.$on '$viewContentLoaded', (event) ->
    console.log 'view loaded'
 
  $scope.$on 'categoryFilter', (event, category, id) ->
    if category and id
      $scope.category = category
      PostModel.search 'catID:' + id, (data) ->
        $scope.tempContent = $scope.content
        $scope.content = data
        $scope.loadingDisabled = true
    else
      if $scope.loadingDisabled
        $scope.content = $scope.tempContent
        $scope.tempContent = null
        $scope.loadingDiabled = false

  $scope.$on 'tagFilter', (event, tag, id) ->


  $scope.$on 'color', (event, color) ->
    if color != ''
      $scope.tempContent = $scope.content
      PostModel.searchColor color, (data) ->
        $scope.content = data
      $scope.loadingDisabled = true
    else
      $scope.content = $scope.tempContent
      $scope.loadingDisabled = false

IndexCtrl.$inject = [ "$scope", "$http" , "$window", "PostModel"]

SquareCtrl = ($scope) ->
  false

NavCtrl = ($scope, $http, PostModel) ->
  $scope.categories = JSON.parse nsqDomain.categories
  $scope.tags = JSON.parse nsqDomain.tags
  $scope.$evalAsync ->
    jQuery('.colorSelector').ColorPicker
      color: '#EFEFEF'
      onShow: (colpkr) ->
        jQuery(colpkr).fadeIn(500)
        false
      onHide: (colpkr) ->
        jQuery(colpkr).fadeOut(500)
        $scope.$emit('color', $scope.color)
        console.log $scope.color
        false
      onChange: (hsb, hex, rgb) ->
        jQuery('.selectorBackground').css('backgroundColor', '#' + hex)
        $scope.color = hex