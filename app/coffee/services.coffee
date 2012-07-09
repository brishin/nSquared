angular.module('myApp.services', [])
  .factory 'PostModel', ($http, $q, Config) ->
    PostModel =
      modelPrefix: 'post'
      currentPage: 1
      # Sufficiently in the future
      expiryTime: new Date("Fri Jun 22 2013 13:19:25 GMT-0400 (EDT)")
      baseUrl: Config.apiDomain + 'v1/'
      paginationAmount: ->
        15
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
          url: @baseUrl + 'posts'
          params:
            domain: Config.applicationDomain
            start: page * PostModel.paginationAmount()
            rows: PostModel.paginationAmount()
            wt: 'json'
            'callback': 'JSON_CALLBACK'
        console.log 'GET ' + config.url
        console.log config.params
        $http(config).success (data) ->
          sessionStorage.setItem PostModel.modelPrefix + '_' + page,\
              JSON.stringify(data)
          PostModel.expiryTime = data.expiryTime if data.expiryTime?
          PostModel.processData data
          console.log data
          callback data

      queryCache: (page, callback) ->
        console.log 'Post in cache.'
        data = JSON.parse(storedData)
        PostModel.processData data
        console.log data
        callback data

      search: (query, callback) ->
        config = 
          method: 'JSONP'
          url: @baseUrl + 'search'
          params:
            domain: Config.applicationDomain
            # Sanitize query
            search: String(query).replace(/\?|=|&/g, '')
            rows: '15'
            wt: 'json'
            'callback': 'JSON_CALLBACK'
        $http(config).success (data) ->
          PostModel.processData data
          console.log data
          callback data

      processData: (data) ->
        square['img'] = square['thumbnail'] or square['media'][0] for square in data

    PostModel

  .factory 'Config', ->
    Config =
      applicationDomain: 'trendland.com'
      apiDomain: 'http://taleyarn.com/api/'
      #apiDomain: 'http://127.0.0.1:5000/'
    Config