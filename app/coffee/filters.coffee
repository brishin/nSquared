"use strict"
angular.module("nSquared.filters", []).filter "interpolate", [ "version", (version) ->
  (text) ->
    String(text).replace /\%VERSION\%/g, version
 ]