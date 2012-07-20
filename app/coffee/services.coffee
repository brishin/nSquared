angular.module('nSquared.services', [])
  .factory 'PostModel', ($http, $q, Config, Helper) ->
    PostModel =
      modelPrefix: 'post'
      currentPage: 0
      # Sufficiently in the future
      expiryTime: new Date("Fri Jun 22 2013 13:19:25 GMT-0400 (EDT)")
      baseUrl: Config.apiDomain + 'v1/'
      paginationAmount: ->
        console.log "squares " + Helper.squaresAcross()
        Helper.squaresAcross() * 4
      query: (page, callback) ->
        console.log 'PostModel#query'
        page ?= PostModel.currentPage++
        console.log page

        storedData = sessionStorage.getItem(PostModel.modelPrefix + '_' + page)
        if storedData and PostModel.expiryTime > new Date() and false
          @queryCache page, storedData, callback
        else
          @queryServer page, callback

      queryServer: (page, callback) ->
        config = 
          method: 'JSONP'
          url: @baseUrl + 'posts'
          params:
            domain: Config.applicationDomain
            start: page * (PostModel.paginationAmount() + 1)
            rows: PostModel.paginationAmount()
            callback: 'JSON_CALLBACK'
        console.log 'GET ' + config.url
        console.log config.params
        $http(config).success (data) ->
          # sessionStorage.setItem PostModel.modelPrefix + '_' + page,\
          #     JSON.stringify(data)
          PostModel.expiryTime = data.expiryTime if data.expiryTime?
          PostModel.processData data
          console.log data
          callback data

      queryCache: (page, storedData, callback) ->
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
            search: String(query).replace(/\?|=|&"'/g, '')
            callback: 'JSON_CALLBACK'
        $http(config).success (data) ->
          PostModel.processData data
          console.log data
          callback data

      searchColor: (color, callback) ->
        config = 
          method: 'JSONP'
          url: @baseUrl + 'color'
          params:
            domain: Config.applicationDomain
            # Sanitize color
            color: String(color).replace(/\?|=|&"'/g, '')
            callback: 'JSON_CALLBACK'
        $http(config).success (data) ->
          PostModel.processData data
          console.log data
          if data.length != 0
            callback data

      processData: (data) ->
        for square in data
          square['img'] = square['thumbnail'] or square['thumb_request']
          if not square['img'] and square['media']
            square['img'] = square['media'][0]

    PostModel

  .factory 'Config', ->
    Config =
      applicationDomain: 'trendland.com'
      apiDomain: 'http://209.17.170.12/api/'
    Config
  .factory 'Helper', ->
    Helper =
      squaresAcross: =>
        pageWidth = jQuery('.nrelate').width()
        squareSize = 150
        paddingSize = 10
        totalSize = squareSize + paddingSize
        Math.floor pageWidth / totalSize
    Helper