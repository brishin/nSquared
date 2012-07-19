angular.module("nSquared.directives", [])
  .directive 'modalInject', ->
    @createdElements = []
    @cleanup = ->
      console.log @createdElements
      if @createdElements.length > 3
        if @createdElements[createdElements.length - 1] == @createdElements[0]
          @createdElements.shift()
        else
          jQuery(@createdElements.shift()).empty()

    (scope, elm, attrs) =>
      $elm = jQuery(elm)
      $elmInject = jQuery($elm.children('.inject-target'))
      #console.log $(elm).is(":visible")

      $elm.on 'show', (e) =>
        console.log $elmInject
        newElement = jQuery '<iframe/>',
          class: 'injected-frame'
          src: scope.square.link
        $elmInject.append newElement
        @createdElements.push $elmInject
        @cleanup()
        # Stop scrolling
        document.body.style.overflow = "hidden"

      $elm.on 'hide', (event) ->
        # Allow scrolling
        document.body.style.overflow = "visible"
  .directive 'scroller', ->
    (scope, elm, attrs) ->
      $elm = jQuery(elm)
      console.log 'scroller injected'
      jQuery(window).scroll =>
        @didScroll = true
      setInterval =>
        if @didScroll and jQuery(window).scrollTop() > \
            jQuery(document).height() - jQuery(window).height() * 1.2 and\
            not scope.loadingDisabled
          console.log 'Bottom reached'
          @didScroll = false
          scope.loadingDisabled = true
          scope.getPage()
      , 200
  .directive 'colorPicker', ->
    ($scope, elm, attrs) ->
      jQuery(elm).ColorPicker
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