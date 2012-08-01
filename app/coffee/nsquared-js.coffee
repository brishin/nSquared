watchDiv = jQuery('.container-fluid').parent().parent().parent().parent().parent().parent().parent()

jQuery(window).load ->
  nsqWidth = jQuery('.row-fluid').width()
  thumbWidth = jQuery('a.ng-scope').innerWidth()
  thumbsFit = Math.floor nsqWidth / thumbWidth
  newnsqWidth = thumbWidth * thumbsFit
  jQuery('.row-fluid').css 'padding-left', (nsqWidth - newnsqWidth)/2
  jQuery('.row-fluid').css 'margin', '0 auto'
  winW = $(window).width()
  winH = $(window).height()
jQuery(window).resize ->
  nsqWidth = jQuery('.row-fluid').width()
  thumbWidth = jQuery('a.ng-scope').innerWidth()
  thumbsFit = Math.floor nsqWidth / thumbWidth
  resizeDiv = thumbWidth * thumbsFit
  jQuery('.row-fluid').css 'padding-left', (nsqWidth - resizeDiv)/2
  jQuery('.row-fluid').css 'margin', '0 auto'

`!function(d,s,id){var js,fjs=d.getElementsByTagName(s)[0];if(!d.getElementById(id)){js=d.createElement(s);js.id=id;js.src="https://platform.twitter.com/widgets.js";fjs.parentNode.insertBefore(js,fjs);}}(document,"script","twitter-wjs")`
