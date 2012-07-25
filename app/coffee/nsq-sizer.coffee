window.onload = =>
  nsqDiv = jQuery('.nrelate_nsquared').parent().parent()
  nsqWidth = jQuery(nsqDiv).width()
  thumbWidth = jQuery('a.ng-scope').innerWidth()
  thumbsFit = Math.floor nsqWidth / thumbWidth
  resizeDiv = thumbWidth * thumbsFit
  headerWidth = jQuery('h1').width()
  titlePush = headerWidth - resizeDiv
  titlePush = titlePush/2
  jQuery(nsqDiv).css 'width', resizeDiv
  jQuery(nsqDiv).css 'margin', '0 auto'
  jQuery('h1').css 'margin-left', titlePush
  return titlePush