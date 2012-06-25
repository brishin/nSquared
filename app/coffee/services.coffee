angular.module('myApp.services', [])
  .factory 'PostModel', ($http, $q) ->
    PostModel =
      modelPrefix: 'post'
      currentPage: 0
      # Sufficiently in the future
      expiryTime: new Date("Fri Jun 22 2013 13:19:25 GMT-0400 (EDT)")
      query: (page) =>
        console.log 'PostModel#query'
        deferred = $q.defer()
        page ?= ++PostModel.currentPage
        console.log PostModel.currentPage

        storedData = sessionStorage.getItem(PostModel.modelPrefix + '_' + page)
        if storedData and PostModel.expiryTime > new Date()
          console.log 'Post in cache.'
          console.log JSON.parse(storedData)
          deferred.resolve JSON.parse(storedData)
        else
          console.log 'GET ' + "json/trendland" + page + ".json"
          $http.get("json/trendland" + page + ".json").success (data) ->
            sessionStorage.setItem PostModel.modelPrefix + '_' + page,\
                JSON.stringify(data)
            PostModel.expiryTime = data.expiryTime if data.expiryTime?
            deferred.resolve data
        deferred.promise
    PostModel