window.onload = ->
  nsqWidth = jQuery('.nr_inner').width()
  thumbWidth = jQuery('a.ng-scope').innerWidth()
  thumbsFit = Math.floor nsqWidth / thumbWidth
  resizeDiv = thumbWidth * thumbsFit
  jQuery('.nr_inner').css 'padding-left', (nsqWidth - resizeDiv)/2
  jQuery('.nr_inner').css 'margin', '0 auto'
  jQuery('.entry-title').css 'padding-left', (nsqWidth - resizeDiv)/2
  return
jQuery(window).resize ->
  nsqWidth = jQuery('.nr_inner').width()
  thumbWidth = jQuery('a.ng-scope').innerWidth()
  thumbsFit = Math.floor nsqWidth / thumbWidth
  resizeDiv = thumbWidth * thumbsFit
  jQuery('.nr_inner').css 'padding-left', (nsqWidth - resizeDiv)/2
  jQuery('.nr_inner').css 'margin', '0 auto'
  return

`!function(d,s,id){var js,fjs=d.getElementsByTagName(s)[0];if(!d.getElementById(id)){js=d.createElement(s);js.id=id;js.src="https://platform.twitter.com/widgets.js";fjs.parentNode.insertBefore(js,fjs);}}(document,"script","twitter-wjs")`