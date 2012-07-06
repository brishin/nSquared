angular.module("myApp.directives", []).directive "modalInject", ->
  @createdElements = []
  @cleanup = ->
    console.log @createdElements
    if @createdElements.length > 3
      if @createdElements[createdElements.length - 1] == @createdElements[0]
        @createdElements.shift()
      else
        $(@createdElements.shift()).empty()

  (scope, elm, attrs) =>
    $elm = $(elm)
    $elmInject = $($elm.children('.inject-target'))
    #console.log $(elm).is(":visible")

    $elm.on 'show', (e) =>
      console.log $elmInject
      newElement = $ '<iframe/>',
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