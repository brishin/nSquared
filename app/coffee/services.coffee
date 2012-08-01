angular.module('nSquared.services', [])
.factory 'PostModel', ($http, $q, Config, Helper) ->
  PostModel =
    modelPrefix: 'post'
    currentPage: 0
    lastFilter: undefined
    # Sufficiently in the future
    expiryTime: new Date("Fri Jun 22 2013 13:19:25 GMT-0400 (EDT)")
    baseUrl: Config.apiDomain + 'v1/'
    paginationAmount: ->
      Helper.squaresAcross() * (Helper.squaresDown() + 1)
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
          rssid: Config.rssid
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

    search: (query, callback, customSearch) ->
      page = PostModel.currentPage++
      config = 
        method: 'JSONP'
        url: @baseUrl + 'search'
        params:
          domain: Config.applicationDomain
          rssid: Config.rssid
          start: page * (PostModel.paginationAmount() + 1)
          rows: PostModel.paginationAmount()
          # Sanitize query
          search: String(query).replace(/\?|=|&"'/g, '')
          callback: 'JSON_CALLBACK'
      config['params']['search'] = customSearch if customSearch
      $http(config).success (data) ->
        PostModel.processData data
        console.log data
        callback data

    searchWithFilters: (filters, callback) ->
      customSearch = []
      for filter in filters
        data = filter['data']
        switch filter['type']
          when 'category'
            customSearch.push {'catID': data.term_id}
          when 'tag'
            customSearch.push {'tagID': data.term_id}
          when 'search'
            customSearch.push data
          when 'color'
            newFilter = {'color': data}
            if lastFilter
              customSearch.splice customSearch.indexOf(lastFilter), 1
              lastFilter = newFilter
            customSearch.push newFilter
      customSearch = JSON.stringify(customSearch)
      console.log('filters: ' + customSearch)
      PostModel.search '', callback, customSearch

    searchColor: (color, callback) ->
      config = 
        method: 'JSONP'
        url: @baseUrl + 'color'
        params:
          domain: Config.applicationDomain
          rssid: Config.rssid
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

    resetPageNum: ->
      PostModel.currentPage = 0

  PostModel

.factory 'Config', ($http) ->
  Config =
    applicationDomain: nsq.domain
    apiDomain: 'http://209.17.170.12/api/'
    modalType: 'content'
  ( ->
    config = 
      method: 'JSONP'
      url: Config.apiDomain + 'v1/find-rssid'
      params:
        domain: Config.applicationDomain
        callback: 'JSON_CALLBACK'
    $http(config).success (data) ->
      Config.rssid = data
  )()
  Config
.factory 'Helper', ->
  Helper =
    squaresAcross: =>
      pageWidth = jQuery('.nrelate').width()
      squareSize = 150
      paddingSize = 10
      totalSize = squareSize + paddingSize
      result = Math.floor pageWidth / totalSize
      result = 2 if result < 2
      result
    squaresDown: =>
      pageHeight = jQuery(window).height() - 50
      squareSize = 150
      paddingSize = 10
      totalSize = squareSize + paddingSize
      result = Math.floor pageHeight / totalSize
      result = 2 if result < 2
      result
  Helper