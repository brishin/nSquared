angular.module('myApp.directives', [])
  .directive 'modalInject', ->
    (scope, elm, attrs) ->
      elm.text 'asdf'


angular.module("myApp.directives", []).directive "appVersion", [ "version", (version) ->
  (scope, elm, attrs) ->
    elm.html scope.square['content:encoded']
    console.log elm
    console.log $(elm).is(":visible")
 ]