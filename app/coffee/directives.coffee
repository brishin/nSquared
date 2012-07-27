angular.module("nSquared.directives", [])
.directive 'modalInject', (Config, $compile)->
  @createdElements = []
  @cleanup = ->
    console.log @createdElements
    if @createdElements.length > 3
      if @createdElements[createdElements.length - 1] == @createdElements[0]
        @createdElements.shift()
      else
        jQuery(@createdElements.shift()).empty()

  ($scope, elm, attrs) =>
    $elm = jQuery(elm)
    $targetElm = jQuery($elm.children('.inject-target'))
    #console.log $(elm).is(":visible")

    $elm.on 'show', (e) =>
      console.log $scope
      if Config.modalType == 'content'
        template = '<ul>
  <li ng-repeat="image in square.media" class="image-preview">
    <span class="pinterest-button">
      <a ng-href="http://pinterest.com/pin/create/button/?url={{square.link}}&media={{image}}" class="pin-it-button" count-layout="none"></a>
      <img ng-src="{{image}}">
    </span>
  </li>
</ul>
<p>
  {{square.description}}
</p>
<a ng-href="{{square.link}}">Read the full article here</a>'
        newElement = $compile(template)($scope)
        $scope.$apply()
      else if Config.modalType == 'iframe'
        newElement = jQuery '<iframe/>',
          class: 'injected-frame'
          src: $scope.square.link
      $targetElm.append newElement
      @createdElements.push $targetElm
      @cleanup()
      # Stop scrolling on main page
      document.body.style.overflow = "hidden"

    $elm.on 'hide', (event) ->
      # Allow scrolling
      document.body.style.overflow = "visible"


.directive 'scroller', ->
  ($scope, elm, attrs) ->
    $elm = jQuery(elm)
    console.log 'scroller injected'
    jQuery(window).scroll =>
      @didScroll = true
    setInterval =>
      if @didScroll and jQuery(window).scrollTop() > \
          jQuery(document).height() - jQuery(window).height() * 1.5 and\
          not $scope.loadingDisabled
        console.log 'Bottom reached'
        @didScroll = false
        $scope.loadingDisabled = true
        $scope.getNext()
    , 200


.directive 'colorPicker', ->
  ($scope, elm, attrs) ->
    jQuery(elm).ColorPicker
      onShow: (picker) ->
        jQuery(picker).fadeIn(500)
        false
      onHide: (picker) ->
        jQuery(picker).fadeOut(500)
        $scope.$emit 'addFilter', 'color', $scope.color
        console.log $scope.color
        false
      onChange: (hsb, hex, rgb) ->
        jQuery('.selectorBackground').css('backgroundColor', '#' + hex)
        $scope.color = hex

.directive 'spinner', ->
  ($scope, elm, attrs) ->
    opts =
      lines: 13
      length: 7
      width: 4
      radius: 10
      rotate: 0
      color: "#000"
      speed: 1
      trail: 60
      shadow: false
      hwaccel: false
      className: "spinner"
      zIndex: 2e9
      top: "auto"
      left: "auto"

    spinner = new Spinner(opts).spin(elm)

    $scope.$on 'setSpinner', (event, mode) ->
      if mode == 'start'
        elm.start()
      else
        elm.stop()