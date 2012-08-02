# watchDiv = jQuery('.container-fluid').parent().parent().parent().parent().parent().parent().parent()

window.onload = ->
  nsqDiv = jQuery('.row-fluid').parent()
  nsqWidth = jQuery(nsqDiv).width()
  thumbWidth = jQuery('a.ng-scope').innerWidth()
  thumbsFit = Math.floor nsqWidth / thumbWidth
  resizeDiv = thumbWidth * thumbsFit
  jQuery(nsqDiv).css 'padding-left', (nsqWidth - resizeDiv)/2
  jQuery(nsqDiv).css 'margin', '0 auto'
  return
jQuery(window).resize ->
  nsqDiv = jQuery('.row-fluid').parent()
  nsqWidth = jQuery(nsqDiv).width()
  thumbWidth = jQuery('a.ng-scope').innerWidth()
  thumbsFit = Math.floor nsqWidth / thumbWidth
  resizeDiv = thumbWidth * thumbsFit
  jQuery(nsqDiv).css 'padding-left', (nsqWidth - resizeDiv)/2
  jQuery(nsqDiv).css 'margin', '0 auto'
  return

`!function(d,s,id){var js,fjs=d.getElementsByTagName(s)[0];if(!d.getElementById(id)){js=d.createElement(s);js.id=id;js.src="https://platform.twitter.com/widgets.js";fjs.parentNode.insertBefore(js,fjs);}}(document,"script","twitter-wjs")`
