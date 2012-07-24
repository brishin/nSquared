appWidth = $('.entry-content').width()
thumbwidth = $('.nrelate_nsquared .nr_panel').width()
thumbwidth  += 20
thumbsfit = appwidth / thumbwidth
thumbsfit = thumbsfit.toFixed(0)
appwidth = thumbsfit*thumbwidth
$('.entry-content').css 'width', appwidth
$('.entry-content').css 'margin', '0px auto'