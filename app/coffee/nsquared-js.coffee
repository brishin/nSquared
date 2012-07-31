window.onload = ->
  nsqDiv = jQuery('.nrelate_nsquared').parent().parent().parent()
  nsqWidth = jQuery(nsqDiv).width()
  thumbWidth = jQuery('a.ng-scope').innerWidth()
  thumbsFit = Math.floor nsqWidth / thumbWidth
  resizeDiv = thumbWidth * thumbsFit
  headerWidth = jQuery('h1').width()
  titlePush = headerWidth - resizeDiv
  titlePush = titlePush/2
  jQuery(nsqDiv).css 'width', resizeDiv
  jQuery(nsqDiv).css 'margin', '0 auto'

jQuery(window).resize ->
  nsqDiv = jQuery('.nrelate_nsquared').parent().parent().parent()
  nsqWidth = jQuery(nsqDiv).width()
  thumbWidth = jQuery('a.ng-scope').innerWidth()
  thumbsFit = Math.floor nsqWidth / thumbWidth
  resizeDiv = thumbWidth * thumbsFit
  headerWidth = jQuery('h1').width()
  titlePush = headerWidth - resizeDiv
  titlePush = titlePush/2
  jQuery(nsqDiv).css 'width', resizeDiv
  jQuery(nsqDiv).css 'margin', '0 auto'

`!function(d,s,id){var js,fjs=d.getElementsByTagName(s)[0];if(!d.getElementById(id)){js=d.createElement(s);js.id=id;js.src="https://platform.twitter.com/widgets.js";fjs.parentNode.insertBefore(js,fjs);}}(document,"script","twitter-wjs")`

# jQuery('div.clear').fadeIn 1000, ->
#   jQuery('div.clear').delay(1000).fadeOut 1000
#   return
