angular.module("myApp.directives", []).directive "modalInject", ->
  @createdElements = []
  @cleanup = ->
    if @createdElements.length > 3
      if @createdElements[length - 1] == @createdElements[0]
        @createdElements.shift()
      else
        $(@createdElements.shift()).empty()

  (scope, elm, attrs) =>
    #elm.html scope.square['content:encoded']
    #console.log $(elm).is(":visible")
    $(elm).on 'fetchData', (e) =>
      elm.html scope.square['content:encoded']
      @createdElements.push elm
      @cleanup()