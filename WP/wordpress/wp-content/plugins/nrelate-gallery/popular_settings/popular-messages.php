<?php
/**
 * nrelate Admin Messages
 *
 * Does system checks and sets messages for this particular nrelate plugin
 *
 * @package nrelate
 * @subpackage Functions
 */

function nr_mp_message_set(){

	 // Get popular options
	$popular_options = get_option('nrelate_popular_options');
	
	// Popular Thumbnail options
	$show_thumbnails = $popular_options['popular_thumbnail'];
	$thumbnailurl = $popular_options['popular_default_image'];
	// Popular ad option
	$adcodeopt = isset($popular_options['popular_display_ad']) ? $popular_options['popular_display_ad'] : null;
	$msg='';
	// Thumbnail
	if ($show_thumbnails == 'Thumbnails') {
		// Is there a default thumbnail set?
		if ($thumbnailurl == null || $thumbnailurl == '') {
				$msg = $msg . '<li><div class="red">Popular Content is set to show thumbnails. It\'s a good idea to add a default image just in case a post does not have images in it. Add your <a href="admin.php?page=nrelate-popular">default image here</a>.</div></li>';
		} else {
				$msg = $msg . '<li><div class="green">Popular Content will show thumbnails, and default thumbnail is set.</div></li>';
		}
	};

	echo $msg;
};
add_action ('nrelate_admin_messages','nr_mp_message_set');


		
?>