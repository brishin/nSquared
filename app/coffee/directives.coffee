angular.module("nSquared.directives", [])
.directive 'modalInject', (Config, $compile)->
  @createdElements = []
  @cleanup = ->
    # console.log @createdElements
    if @createdElements.length > 3
      if @createdElements[createdElements.length - 1] == @createdElements[0]
        @createdElements.shift()
      else
        jQuery(@createdElements.shift()).empty()

  ($scope, elm, attrs) =>
    $elm = jQuery(elm)
    $targetElm = jQuery($elm.children('.inject-target'))
    # console.log $(elm).is(":visible")

    $elm.on 'show', (e) =>
      # console.log $scope
      if Config.modalType == 'content'
        template = '<div id="modaltextdiv">
    <div id="modaltext">
        <p>{{square.description}}</p>
        <div id="fader">
        </div>
    </div>
    <div id="modalfooter">
        <p>
            <a ng-href="{{square.link}}" target="_blank">Original post</a><br>
            <iframe allowtransparency="true" frameborder="0" scrolling="no" src="https://platform.twitter.com/widgets/tweet_button.html?url={{square.link}}&amp;text={{square.title}}"
            style=" width:56px; 
                        height:25px;">
            </iframe>
            <iframe src="//www.facebook.com/plugins/like.php?href={{square.link}}&amp;send=false&amp;layout=button_count&amp;width=48&amp;show_faces=false&amp;action=like&amp;colorscheme=light&amp;font&amp;height=25"
            scrolling="no" frameborder="0" style=" border:none; 
                        overflow:hidden; 
                        width:48px; 
                        height:25px;" allowTransparency="true">
            </iframe>
        </p>
    </div>
</div>
<div id="modalpictures">
  <div ng-repeat="image in square.media" class="image-preview">
      <span class="pinterest-button">
          <a ng-href="http://pinterest.com/pin/create/button/?url={{square.link}}&media={{image}}"
          class="pin-it-button" count-layout="none" target="_blank">
              <img border="0" src="//assets.pinterest.com/images/PinExt.png" title="Pin It" />
          </a>
          <img class="modalimg" ng-src="{{image}}">
      </span>
  </div>
</div>'
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
    # console.log 'scroller injected'
    jQuery(window).scroll =>
      @didScroll = true
    setInterval =>
      if @didScroll and jQuery(window).scrollTop() > \
          jQuery(document).height() - jQuery(window).height() * 1.5 and\
          not $scope.loadingDisabled
        # console.log 'Bottom reached'
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
        # console.log $scope.color
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

    spinner = jQuery(elm).spin(opts)

    $scope.$on 'setSpinner', (event, state) ->
      if state
        jQuery(elm).spin(opts)
      else
        jQuery(elm).spin(false)

    $scope.$watch 'endOfData', ->
      if $scope.endOfData == true
        jQuery(elm).spin(false)