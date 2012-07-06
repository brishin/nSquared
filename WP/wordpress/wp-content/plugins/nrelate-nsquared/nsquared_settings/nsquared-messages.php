<?php
/**
 * nrelate Admin Messages
 *
 * Does system checks and sets messages for this particular nrelate plugin
 *
 * @package nrelate
 * @subpackage Functions
 */

function nr_nsq_message_set(){

	 // Get nsquared options
	$nsquared_options = get_option('nrelate_nsquared_options');
	
	// Related Thumbnail options
	$show_thumbnails = $nsquared_options['nsquared_thumbnail'];
	$thumbnailurl = $nsquared_options['nsquared_default_image'];
	// Related ad options
	$adcodeopt = isset($nsquared_options['nsquared_display_ad']) ? $nsquared_options['nsquared_display_ad'] : null;
	$msg = '';
	// Thumbnail
	if ($show_thumbnails == 'Thumbnails') {
		// Is there a default thumbnail set?
		if ($thumbnailurl == null || $thumbnailurl == '') {
				$msg = $msg . '<li><div class="red">nSquared requires thumbnail images for each post. It\'s a good idea to add a default image just in case a post does not have images in it. Add your <a href="admin.php?page=nrelate-nsquared">default image here</a>.</div></li>';
		} else {
				$msg = $msg . '<li><div class="green">Default thumbnail is set.</div></li>';
		}
	};
	echo $msg;
};
add_action ('nrelate_admin_messages','nr_sq_message_set');


		
?>