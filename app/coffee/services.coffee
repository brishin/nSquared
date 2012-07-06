angular.module('myApp.services', [])
  .factory 'PostModel', ($http, $q, Config) ->
    PostModel =
      modelPrefix: 'post'
      currentPage: 0
      # Sufficiently in the future
      expiryTime: new Date("Fri Jun 22 2013 13:19:25 GMT-0400 (EDT)")
      baseUrl: ->
        Config.apiDomain + 'api/v1/posts'
      query: (page, callback) ->
        console.log 'PostModel#query'
        page ?= PostModel.currentPage++
        console.log page

        storedData = sessionStorage.getItem(PostModel.modelPrefix + '_' + page)
        if storedData and PostModel.expiryTime > new Date() and false
          @queryCache page, callback
        else
          @queryServer page, callback

      queryServer: (page, callback) ->
        config = 
          method: 'JSONP'
          url: @baseUrl()
          params:
            domain: Config.applicationDomain
            start: page * 10 || PostModel.currentPage * 10
            rows: '10'
            wt: 'json'
            'callback': 'JSON_CALLBACK'
        console.log 'GET ' + config.url
        console.log config.params
        $http(config).success (data) ->
          console.log data
          sessionStorage.setItem PostModel.modelPrefix + '_' + page,\
              JSON.stringify(data)
          PostModel.expiryTime = data.expiryTime if data.expiryTime?
          callback data

      queryCache: (page, callback) ->
        console.log 'Post in cache.'
        console.log JSON.parse(storedData)
        data = JSON.parse(storedData)
        callback data

    PostModel

  .factory 'Config', ->
    Config =
      applicationDomain: 'trendland.com'
      apiDomain: 'http://taleyarn.com/'
    Config