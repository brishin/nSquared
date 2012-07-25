(($)->
  window.onload = ->
    nsqDiv = $('.nrelate_nsquared').parent().parent()
    nsqWidth = $(nsqDiv).width()
    thumbWidth = $('a.ng-scope').innerWidth()
    thumbsFit = Math.floor nsqWidth / thumbWidth
    resizeDiv = thumbWidth * thumbsFit
    headerWidth = $('h1').width();
    titlePush = headerWidth-resizeDiv
    $(nsqDiv).css 'width', resizeDiv
    $(nsqDiv).css 'margin', '0 auto'
    $('header').css 'padding-left', titlePush
)(jQuery)