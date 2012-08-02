"use strict"
angular.module("nSquared.filters", [])
.filter 'checkThumbs', ->
  (input) ->
    if input.thumb_request
      return input
    if input.media and input.media.length > 0
      return input
    return null
.filter "interpolate", [ "version", (version) ->
  (text) ->
    String(text).replace /\%VERSION\%/g, version
 ]