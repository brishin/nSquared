window.onload = =>
  nsqDiv = jQuery('.nrelate_nsquared').parent().parent()
  nsqWidth = jQuery(nsqDiv).width()
  thumbWidth = jQuery('a.ng-scope').innerWidth()
  thumbsFit = Math.floor nsqWidth / thumbWidth
  resizeDiv = thumbWidth * thumbsFit
  jQuery(nsqDiv).css 'width', resizeDiv
  jQuery(nsqDiv).css 'margin', '0 auto'
  console.log('BETHCES')