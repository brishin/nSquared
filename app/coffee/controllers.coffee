IndexCtrl = ($scope, $http, $window, PostModel, $compile) ->
  console.log 'index'
  STATIC_DOMAIN = 'http://nsquared.nrelate.com/static/'
  jQuery.ajax
    url: STATIC_DOMAIN + 'partials/index.html.json'
    dataType: 'jsonp'
    success: (data) ->
      template = data
      newElement = $compile(template)($scope)
      console.log newElement
      jQuery('.row-fluid').append newElement
    jsonpCallback: 'nSquared_callback_index_html'

  $scope.content = []
  $scope.loadingDisabled = false
  $scope.endOfData = false
  $scope.toolbarUrl = nsq.toolbarUrl
  $scope.filters = []
  $scope.prevFilters = '[]'
  $scope.showSpinner = true

  $scope.getNext = (pageNum) ->
    $scope.loadingDisabled = true
    if $scope.prevFilters != JSON.stringify($scope.filters)
      # console.log 'pages reset'
      $scope.endOfData = false
      PostModel.resetPageNum()
    else
      return if $scope.endOfData
    changeSpinnerState(true)
    if $scope.filters.length > 0
      PostModel.searchWithFilters $scope.filters, (data) ->
        pushContent data
    else
      PostModel.query pageNum, (data) ->
        pushContent data    

  $scope.$on 'addFilter', (event, type, data) ->
    return if data == ''
    PostModel.resetPageNum()
    return if not data
    filterName = data['name'] or data
    # Color cannot be added with other filters for now
    $scope.filters = [] if type == 'color'
    newFilter = {'type': type, 'data': data, 'name': filterName}
    return if ($scope.filters.indexOf newFilter) != -1
    $scope.filters.push newFilter
    $scope.$broadcast 'updateFilters', $scope.filters
    $scope.getNext()
    
  $scope.$on 'removeFilter', (event, filter) ->
    # console.log filter
    filterIndex = $scope.filters.indexOf filter
    $scope.filters.splice filterIndex, 1
    $scope.$broadcast 'updateFilters', $scope.filters
    clearContent()
    $scope.getNext()

  pushContent = (data) ->
    if $scope.prevFilters == JSON.stringify($scope.filters)
      $scope.content = $scope.content.concat data
    else
      $scope.content = data
    $scope.prevFilters = JSON.stringify($scope.filters)
    changeSpinnerState(false)
    $scope.$evalAsync ->
      $scope.loadingDisabled = false
      if data.length == 0
        $scope.endOfData = true
      handleMessages(data)
  
  handleMessages = (data) ->
    if data.length == 0
      if PostModel.currentPage == 1
        divToShow = '#noresults'
      else
        divToShow = '#nomorediv'
      jQuery(divToShow).fadeIn 1000, ->
        jQuery(divToShow).delay(1000).fadeOut 1000
        return

  clearContent = ->
    $scope.content = []

  changeSpinnerState = (state) ->
    $scope.$broadcast 'setSpinner', state

  $scope.getClasses = ->
    classes = []
    if nsq.thumbsize?
      classes.push nsq.thumbsize
    else
      classes.push 'nr_200'
    if nsq.style?
      classes.push nsq.style
    else
      classes.push 'nrelate_nsquared'
    classes

  # Initial page load
  $scope.getNext()

#IndexCtrl.$inject = [ "$scope", "$http" , "$window", "PostModel"]

NavCtrl = ($scope, $http, PostModel, $compile) ->
  STATIC_DOMAIN = 'http://nsquared.nrelate.com/static/'
  jQuery.ajax
    url: STATIC_DOMAIN + 'partials/toolbar.html.json'
    dataType: 'jsonp'
    success: (data) ->
      template = data
      newElement = $compile(template)($scope)
      console.log newElement
      jQuery('.nav').append newElement
    jsonpCallback: 'nSquared_callback_toolbar_html'

  $scope.filters = []
  if nsq.categories and nsq.tags
    $scope.categories = JSON.parse nsq.categories
    $scope.tags = JSON.parse nsq.tags

  $scope.$on 'updateFilters', (event, filters) ->
    $scope.filters = filters

  $scope.displayFilter = (filter) ->
    if filter.type == 'color'
      return '  '
    else
      return filter.name

  $scope.getStyle = (filter) ->
    style = {}
    if filter.type == 'color'
      style['background-color'] = '#' + filter.name
    style