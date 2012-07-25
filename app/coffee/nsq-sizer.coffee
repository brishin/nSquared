(($)->
  window.onload = ->
    nsqDiv = $('.nrelate_nsquared').parent().parent()
    nsqWidth = $(nsqDiv).width()
    thumbWidth = $('a.ng-scope').innerWidth()
    thumbsFit = Math.floor nsqWidth / thumbWidth
    resizeDiv = thumbWidth * thumbsFit
    $(nsqDiv).css 'width', resizeDiv
    $(nsqDiv).css 'margin', '0 auto'
    console.log('BETHCES')
)(jQuery)