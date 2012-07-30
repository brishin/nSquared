redoDivs = ->
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

redoModal = ->
  wwidth = jQuery(window).width
  wheight = jQuery(window).height
  

window.onload = redoDivs()
jQuery(window).resize redoDivs()

`!function(d,s,id){var js,fjs=d.getElementsByTagName(s)[0];if(!d.getElementById(id)){js=d.createElement(s);js.id=id;js.src="https://platform.twitter.com/widgets.js";fjs.parentNode.insertBefore(js,fjs);}}(document,"script","twitter-wjs")`