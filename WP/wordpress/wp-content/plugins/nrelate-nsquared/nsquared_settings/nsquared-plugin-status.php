<?php
/**
 * nrelate Plugin Status
 *
 * Activation, Deactivation and Upgrade functions
 *
 * @package nrelate
 * @subpackage Functions
 */
 

global $nr_sq_std_options, $nr_sq_ad_options, $nr_sq_layout_options, $nr_sq_old_checkbox_options, $nr_sq_ad_old_checkbox_options;

// Default Options
// ALL options must be listed
$nr_sq_std_options = array(
		"nsquared_version" => NRELATE_NSQUARED_PLUGIN_VERSION,
		"nsquared_number_of_posts"=> 20,
		// "nsquared_bar" => "Low",
		"nsquared_title" => "nSquared",
		// "nsquared_max_age_num" => "10",
		// "nsquared_max_age_frame" => "Year(s)",
		// "nsquared_loc_top" => "",
		// "nsquared_loc_bottom" => "on",
		// "nsquared_display_logo" => false,
		"nsquared_reset" => "",
		// "nsquared_blogoption" => array(),
		"nsquared_show_post_title" => 'on',
		// "nsquared_max_chars_per_line" => 100,
		// "nsquared_show_post_excerpt" => "",
		// "nsquared_max_chars_post_excerpt" => 25,		
		// "nsquared_thumbnail" => "Thumbnails",
		"nsquared_thumbnail_size" => 110,
		"nsquared_default_image" => NULL,
		// "nsquared_number_of_posts_ext" => 3,
		// "nsquared_where_to_show" => array( "is_single" ),
		// "nsquared_nonjs" => 0
	);
// $nr_sq_ad_options = array(
// 		"nsquared_display_ad" => false,
// 		"nsquared_ad_animation" => "on",
// 		"nsquared_validate_ad" => NULL,
// 		"nsquared_number_of_ads" => 1,
// 		"nsquared_ad_placement" => "Last",
// 		"nsquared_ad_title" => "More from the Web -"
// 	);
		
$nr_sq_layout_options = array(		
		"nsquared_thumbnails_style" => "default",
		"nsquared_thumbnails_style_separate" => "default-2col",
		"nsquared_text_style" => "default",
		"nsquared_text_style_separate" => "default-text-2col"
);


 /**
 * Define default options for settings
 *
 */
 
// Add default values to nrelate_nsquared_options in wordpress db
// After conversion, send default values to nrelate server with user's home url and rss url
// UPDATE (v.0.2.2): add nrelate ping host to ping list and enable xml-rpc ping
// UPDATE (v.0.2.2): notify nrelate server when this plugin is activated
// UPDATE (v.0.3): send the plugin version info to nrelate server

function nr_sq_add_defaults() {
	nrelate_system_check(); // run system check

	// Calculate plugin file path
	$dir = substr( realpath(dirname(__FILE__) . '/..'), strlen(WP_PLUGIN_DIR) );
	$file = key( get_plugins( $dir ) );
	$plugin_file = substr($dir, 1) . '/' . $file;

	nrelate_products("nsquared",NRELATE_NSQUARED_PLUGIN_VERSION,NRELATE_NSQUARED_ADMIN_VERSION,1, $plugin_file); // add this product to the nrelate_products array
	
	global $nr_sq_std_options, $nr_sq_ad_options, $nr_sq_layout_options;

	$tmp = get_option('nrelate_nsquared_options');
	// If nsquared_reset value is on or if nrelate_nsquared_options was never created, insert default values
    if(($tmp['nsquared_reset']=='on')||(!is_array($tmp))) {
		
		update_option('nrelate_nsquared_options', $nr_sq_std_options);
		// update_option('nrelate_nsquared_options_ads', $nr_sq_ad_options);		
		update_option('nrelate_nsquared_options_styles', $nr_sq_layout_options);

		// Convert some values to send to nrelate server
		$number = 3;
		$r_bar = "Low";
		$r_title = "You may also like -";
		// $r_max_age = 10;
		// $r_max_frame = "Year(s)";
		$r_display_post_title = true;
		// $r_max_char_per_line = 100;
		// $r_max_char_post_excerpt = 100;
		// $r_display_ad = "";
		// $r_display_logo = "";
		$r_nsquared_reset = "";
		$nsquared_blogoption = array();
		$nsquared_thumbnail = "Thumbnails";
		$backfillimage = NULL;
		$number_ext = 3;
		$nsquared_thumbnail_size=110;
		// $r_number_of_ads = 0;
		// $r_ad_placement = "Last";
		// $r_ad_title = "More from the Web -";
		// $r_nonjs = 0;
		// // Convert max age time frame to minutes
		// switch ($r_max_frame)
		// {
		// case 'Hour(s)':
		//   $maxageposts = $r_max_age * 60;
		//   break;
		// case 'Day(s)':
		//   $maxageposts = $r_max_age * 1440;
		//   break;
		// case 'Week(s)':
		//   $maxageposts = $r_max_age * 10080;
		//   break;
		// case 'Month(s)':
		//   $maxageposts = $r_max_age * 44640;
		//   break;
		// case 'Year(s)':
		//   $maxageposts = $r_max_age * 525600;
		//   break;
		// }

		// // Convert ad parameter
		// switch ($r_display_ad)
		// {
		// case true:
		// 	$ad = 1;
		// 	break;
		// default:
		// 	$ad = 0;
		// }

		// Convert display post title parameter
		switch ($r_display_post_title)
		{
		case 'on':
		  $r_display_post_title = 1;
		  break;
		default:
		 $r_display_post_title = 0;
		}
		
		// // Convert logo parameter
		// switch ($r_display_logo)
		// {
		// case 'on':
		//   $logo = 1;
		//   break;
		// default:
		//  $logo = 0;
		// }

		// // Convert blogroll option parameter
		// if ( is_array($nsquared_blogoption) && count($nsquared_blogoption) > 0 ) {
		// 	$blogroll = 1;
		// } else {
		// 	$blogroll = 0;
		// }

		// // Convert thumbnail option parameter
		// switch ($nsquared_thumbnail)
		// {
		// case 'Thumbnails':
		// 	$thumb = 1;
		// 	break;
		// default:
		// 	$thumb = 0;
		// }

		// Get the wordpress root url and the rss url
		$bloglist = nrelate_get_blogroll();
		// Write the parameters to be sent
		
		$r_show_post_title = isset($r_show_post_title) ? $r_show_post_title : null;
		// $r_show_post_excerpt = isset($r_show_post_excerpt) ? $r_show_post_excerpt : null;
		$backfill = isset($backfill) ? $backfill : null;
		
		$body=array(
			'DOMAIN'=>NRELATE_BLOG_ROOT,
			'VERSION'=>NRELATE_NSQUARED_PLUGIN_VERSION,
			'KEY'=>get_option('nrelate_key'),
			'NUM'=>$number,
			'NUMEXT'=>$number_ext,
			'R_BAR'=>$r_bar,
			'HDR'=>$r_title,
			// 'BLOGOPT'=>$blogroll,
			// 'BLOGLI'=>$bloglist,
			// 'MAXPOST'=>$maxageposts,
			'SHOWPOSTTITLE'=>$r_show_post_title,
			// 'MAXCHAR'=>$r_max_char_per_line,
			// 'SHOWEXCERPT'=>$r_show_post_excerpt,
			// 'MAXCHAREXCERPT'=>$r_max_char_post_excerpt,
			// 'ADOPT'=>$ad,
			'THUMB'=>$thumb,
			// 'LOGO'=>$logo,
			'IMAGEURL'=>$backfill,
			'THUMBSIZE'=>$nsquared_thumbnail_size,
			// 'ADNUM'=>$r_number_of_ads,
			// 'ADPLACE'=>$r_ad_placement,
			// 'ADTITLE'=>$r_ad_title,
			// 'NONJS'=>$r_nonjs
		);
		$url = 'http://api.nrelate.com/nsq_wp/'.NRELATE_NSQUARED_PLUGIN_VERSION.'/processWPnsquaredAll.php';
		
		$result = wp_remote_post($url, array('body'=>$body,'blocking'=>false,'timeout'=>15));
	}

	// RSS mode is sent again just incase if the user already had nrelate_nsquared_options in their wordpress db
	// and doesn't get sent above
	$excerptset = get_option('rss_use_excerpt');
	$rss_mode = "FULL";
	if ($excerptset != '0') { // are RSS feeds set to excerpt
		update_option('nrelate_admin_msg', 'yes');
		$rss_mode = "SUMMARY";
	}

	$rssurl = get_bloginfo('rss2_url');

	// Add our ping host to the ping list
	$current_ping_sites = get_option('ping_sites');
	$pingexist = strpos($current_ping_sites, "http://api.nrelate.com/rpcpinghost/");
	if($pingexist == false){
	$pinglist = <<<EOD
$current_ping_sites
http://api.nrelate.com/rpcpinghost/
EOD;
	update_option('ping_sites',$pinglist);
	}
	// Enable xmlrpc for the user
	update_option('enable_xmlrpc',1);


	//Set up a unique nrelate key, for secure feed access
	$key = get_option( 'nrelate_key' );
	if ( empty( $key ) ) {
		$key = wp_generate_password( 24, false, false );
		update_option( 'nrelate_key', $key );
	}



	// Send notification to nrelate server of activation and send us rss feed mode information
	$action = "ACTIVATE";
	$body=array(
		'DOMAIN'=>NRELATE_BLOG_ROOT,
		'ACTION'=>$action,
		'RSSMODE'=>$rss_mode,
		'VERSION'=>NRELATE_NSQUARED_PLUGIN_VERSION,
		'KEY'=>get_option('nrelate_key'),
		'ADMINVERSION'=>NRELATE_NSQUARED_ADMIN_VERSION,
		'PLUGIN'=>'nsquared',
		'RSSURL'=>$rssurl
	);
	$url = 'http://api.nrelate.com/common_wp/'.NRELATE_NSQUARED_ADMIN_VERSION.'/wordpressnotify_activation.php';
	
	$result = wp_remote_post($url, array('body'=>$body,'blocking'=>false,'timeout'=>15));
}
 
 
// Deactivation hook callback
function nr_sq_deactivate(){
	$nrelate_active=nrelate_products("nsquared",NRELATE_NSQUARED_PLUGIN_VERSION,NRELATE_NSQUARED_ADMIN_VERSION,0);
	
	if($nrelate_active==0){
		// Remove our ping link from ping_sites
		$current_ping_sites = get_option('ping_sites');
		$new_ping_sites = str_replace("\nhttp://api.nrelate.com/rpcpinghost/", "", $current_ping_sites);
		update_option('ping_sites',$new_ping_sites);
	}
	
	// RSS mode is sent again just incase if the user already had nrelate_nsquared_options in their wordpress db
	// and doesn't get sent above
	$excerptset = get_option('rss_use_excerpt');
	$rss_mode = "FULL";
	if ($excerptset != '0') { // are RSS feeds set to excerpt
		update_option('nrelate_admin_msg', 'yes');
		$rss_mode = "SUMMARY";
	}

	$rssurl = get_bloginfo('rss2_url');

	// Send notification to nrelate server of deactivation
	$action = "DEACTIVATE";

	$body=array(
		'DOMAIN'=>NRELATE_BLOG_ROOT,
		'ACTION'=>$action,
		'RSSMODE'=>$rss_mode,
		'VERSION'=>NRELATE_NSQUARED_PLUGIN_VERSION,
		'KEY'=>get_option('nrelate_key'),
		'ADMINVERSION'=>NRELATE_NSQUARED_ADMIN_VERSION,
		'PLUGIN'=>'nsquared',
		'RSSURL'=>$rssurl
	);
	$url = 'http://api.nrelate.com/common_wp/'.NRELATE_NSQUARED_ADMIN_VERSION.'/wordpressnotify_activation.php';
	
	$result = wp_remote_post($url, array('body'=>$body,'blocking'=>false,'timeout'=>15));
}

// Uninstallation hook callback
function nr_sq_uninstall(){
	// Delete nrelate nsquared options from user's wordpress db
	delete_option('nrelate_nsquared_options');
	delete_option('nrelate_nsquared_options_ads');
	delete_option('nrelate_nsquared_options_styles');
	
	$nrelate_active=nrelate_products("nsquared",NRELATE_NSQUARED_PLUGIN_VERSION,NRELATE_NSQUARED_ADMIN_VERSION,-1);
	
	if ($nrelate_active<0){
		// This occurs if the user is deleting all of nrelate's products
		
		// Remove our ping link from ping_sites
		$current_ping_sites = get_option('ping_sites');
		$new_ping_sites = str_replace(array("\nhttp://api.nrelate.com/rpcpinghost/","http://api.nrelate.com/rpcpinghost/"), "", $current_ping_sites);
		update_option('ping_sites',$new_ping_sites);
		
		// Delete nrelate admin options from users wordpress db
		delete_option('nrelate_products');
		delete_option('nrelate_admin_msg');
		delete_option('nrelate_admin_options');
		$current_ping_sites = get_option('ping_sites');
		$new_ping_sites = str_replace("\nhttp://api.nrelate.com/rpcpinghost/", "", $current_ping_sites);
		update_option('ping_sites',$new_ping_sites);
	}
	
	// RSS mode is sent again just incase if the user already had nrelate_popular_options in their wordpress db
	// and doesn't get sent above
	$excerptset = get_option('rss_use_excerpt');
	$rss_mode = "FULL";
	if ($excerptset != '0') { // are RSS feeds set to excerpt
		update_option('nrelate_admin_msg', 'yes');
		$rss_mode = "SUMMARY";
	}
	
	$rssurl = get_bloginfo('rss2_url');
	
	// Send notification to nrelate server of uninstallation
	$action = "UNINSTALL";
	$body=array(
		'DOMAIN'=>NRELATE_BLOG_ROOT,
		'ACTION'=>$action,
		'RSSMODE'=>$rss_mode,
		'VERSION'=>NRELATE_NSQUARED_PLUGIN_VERSION,
		'KEY'=>get_option('nrelate_key'),
		'ADMINVERSION'=>NRELATE_NSQUARED_ADMIN_VERSION,
		'PLUGIN'=>'nsquared',
		'RSSURL'=>$rssurl
	);
	$url = 'http://api.nrelate.com/common_wp/'.NRELATE_NSQUARED_ADMIN_VERSION.'/wordpressnotify_activation.php';
	
	$result = wp_remote_post($url, array('body'=>$body,'blocking'=>false,'timeout'=>15));
}

?>