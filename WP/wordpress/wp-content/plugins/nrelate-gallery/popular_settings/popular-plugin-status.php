<?php
/**
 * nrelate Plugin Status
 *
 * Activation, Deactivation and Upgrade functions
 *
 * @package nrelate
 * @subpackage Functions
 */

global $nr_mp_std_options, $nr_mp_ad_options, $nr_mp_layout_options;

//Default Options
// ALL options must be listed
$nr_mp_std_options = array(
		"popular_version"=> NRELATE_POPULAR_PLUGIN_VERSION,
		"popular_number_of_posts"=> 3,
		"popular_bar" => "Low",
		"popular_title" => "Most Popular -",
		"popular_max_age_num" => "10",
		"popular_max_age_frame" => "Year(s)",
		"popular_loc_top" => "",
		"popular_loc_bottom" => "on",
		"popular_display_logo" => false,
		"popular_reset" => "",
		"popular_max_chars_per_line" => 100,
		"popular_show_post_excerpt" => "",
		"popular_thumbnail" => "Thumbnails",
		"popular_thumbnail_size" => 110,
		"popular_default_image" => NULL,
		"popular_where_to_show" => array( "is_single" ),
		"popular_showviews" => "on",
		"popular_show_post_title" => 'on',
		"popular_max_chars_post_excerpt" => 25,	
		"popular_nonjs" => 0,
		"popular_view"=>"views"
		);
$nr_mp_ad_options = array(
		"popular_display_ad" => false,
		"popular_ad_animation" => "on",
		"popular_number_of_ads" => 1,
		"popular_ad_placement" => "Last",
		"popular_ad_title" => "More from the Web -"
);
		
$nr_mp_layout_options = array(		
		"popular_thumbnails_style" => "default",
		"popular_thumbnails_style_separate" => "default-2col",
		"popular_text_style" => "default",
		"popular_text_style_separate" => "default-text-2col"
);

/**
 * Upgrade function
 *
 * @since 0.46.0
 */
add_action('admin_init','nr_mp_upgrade');
function nr_mp_upgrade() {
	$popular_settings = get_option('nrelate_popular_options');
	$popular_ad_settings = get_option('nrelate_popular_options_ads');
	$popular_layout_settings = get_option('nrelate_popular_options_styles');
	$current_version = $popular_settings['popular_version'];
	
	// If settings exist and we're running on old version (or version doesn't exist), then this is an upgrade
	if ( ( !empty( $popular_settings ) ) && ( $current_version < NRELATE_POPULAR_PLUGIN_VERSION ) )  {
		
		nrelate_system_check(); // run system check
		
		global $nr_mp_std_options, $nr_mp_layout_options, $nr_mp_ad_options;
			
			// Get the latest
			$popular_settings = get_option('nrelate_popular_options');
			$popular_layout_settings = get_option('nrelate_popular_options_styles');
			$popular_ad_settings = get_option('nrelate_popular_options_ads');
			
			// Update new options if they don't exist
			$popular_settings = wp_parse_args( $popular_settings, $nr_mp_std_options );
			$popular_layout_settings = wp_parse_args( $popular_layout_settings, $nr_mp_layout_options );
			$popular_ad_settings = wp_parse_args( $popular_ad_settings, $nr_mp_ad_options );
			
			// now update again
			update_option('nrelate_popular_options', $popular_settings);
			update_option('nrelate_popular_options_styles', $popular_layout_settings);
			update_option('nrelate_popular_options_ads', $popular_ad_settings);

			// Update version number in DB
			$popular_settings = get_option('nrelate_popular_options');
			$popular_settings['popular_version'] = NRELATE_POPULAR_PLUGIN_VERSION;
			update_option('nrelate_popular_options', $popular_settings);
			
			// Ping nrelate servers about the upgrade
			$body=array(
				'DOMAIN'=>NRELATE_BLOG_ROOT,
				'VERSION'=>NRELATE_POPULAR_PLUGIN_VERSION,
				'KEY'=>get_option('nrelate_key'),
				'PLUGIN'=>"popular"
			);
			$url = 'http://api.nrelate.com/common_wp/'.NRELATE_LATEST_ADMIN_VERSION.'/versionupdate.php';

			$result = wp_remote_post($url,array('body'=>$body,'blocking'=>false, 'timeout'=>15));
			
			// Calculate plugin file path
			$dir = substr( realpath(dirname(__FILE__) . '/..'), strlen(WP_PLUGIN_DIR) );
			$file = key( get_plugins( $dir ) );
			$plugin_file = substr($dir, 1) . '/' . $file;
			// Update the WP database with the new version number and additional info about this plugin
			nrelate_products("popular",NRELATE_POPULAR_PLUGIN_VERSION,NRELATE_POPULAR_ADMIN_VERSION,1,$plugin_file); 
	}
}


 /**
 * Define default options for settings
 *
 * @since 0.1
 */

// Add default values to nrelate_popular_options in wordpress db
// After conversion, send default values to nrelate server with user's home url and rss url
// UPDATE (v.0.2.2): add nrelate ping host to ping list and enable xml-rpc ping
// UPDATE (v.0.2.2): notify nrelate server when this plugin is activated
// UPDATE (v.0.3): send the plugin version info to nrelate server
function nr_mp_add_defaults() {
		nrelate_system_check(); // run system check
		
		// Calculate plugin file path
		$dir = substr( realpath(dirname(__FILE__) . '/..'), strlen(WP_PLUGIN_DIR) );
		$file = key( get_plugins( $dir ) );
		$plugin_file = substr($dir, 1) . '/' . $file;
		
		nrelate_products("popular",NRELATE_POPULAR_PLUGIN_VERSION,NRELATE_POPULAR_ADMIN_VERSION,1,$plugin_file); // add this product to the nrelate_products array
		
		global $nr_mp_std_options, $nr_mp_layout_options, $nr_mp_ad_options;
		
		$tmp = get_option('nrelate_popular_options');
		
	// If popular_reset value is on or if nrelate_popular_options was never created, insert default values
    if(($tmp['popular_reset']=='on')||(!is_array($tmp))) {
		
		update_option('nrelate_popular_options', isset($nr_mp_std_options) ? $nr_mp_std_options : null);
		update_option('nrelate_popular_options_ads', isset($nr_mp_ad_options) ? $nr_mp_ad_options : null);		
		update_option('nrelate_popular_options_styles', isset($nr_mp_layout_options) ? $nr_mp_layout_options : null);
		
		// Convert some values to send to nrelate server
		$number = 3;
		$p_bar = "Low";
		$p_title = "You may also like -";
		$p_max_age = 10;
		$p_max_frame = "Year(s)";
		$p_display_post_title=true;
		$p_max_char_per_line = 100;
		$p_max_char_post_excerpt = 100;
		$p_display_ad = "";
		$p_display_logo = "";
		$popular_reset = "";
		$popular_thumbnail = "Thumbnails";
		$backfillimage = NULL;
		$popular_thumbnail_size=110;
		$p_number_of_ads = 1;
		$p_ad_placement = "Last";
		$p_ad_title = "More from the Web -";
		$popular_showviews =true;
		$p_nonjs = 0;
		$p_view="views";

		// Convert max age time frame to minutes
		switch ($p_max_frame)
		{
		case 'Hour(s)':
		  $maxageposts = $p_max_age * 60;
		  break;
		case 'Day(s)':
		  $maxageposts = $p_max_age * 1440;
		  break;
		case 'Week(s)':
		  $maxageposts = $p_max_age * 10080;
		  break;
		case 'Month(s)':
		  $maxageposts = $p_max_age * 44640;
		  break;
		case 'Year(s)':
		  $maxageposts = $p_max_age * 525600;
		  break;
		}

		// Convert ad parameter
		switch ($p_display_ad)
		{
		case true:
			$ad = 1;
			break;
		default:
			$ad = 0;
		}
   	 	switch ($popular_showviews)
		{
		case true:
			$popular_showviews = 1;
			break;
		default:
			$popular_showviews = 0;
		}
    	// Convert display post title parameter
		switch ($p_display_post_title)
		{
		case 'on':
		  $p_display_post_title = 1;
		  break;
		default:
		 $p_display_post_title = 0;
		}
		// Convert logo parameter
		switch ($p_display_logo)
		{
		case 'on':
		  $logo = 1;
		  break;
		default:
		 $logo = 0;
		}

		// Convert thumbnail option parameter
		switch ($popular_thumbnail)
		{
		case 'Thumbnails':
			$thumb = 1;
			break;
		default:
			$thumb = 0;
		}
		
		$p_show_post_title = isset($p_show_post_title) ? $p_show_post_title : null;
		$p_show_post_excerpt = isset($p_show_post_excerpt) ? $p_show_post_excerpt : null;
		$backfill = isset($backfill) ? $backfill : null;
		
		$body=array(
			'DOMAIN'=>NRELATE_BLOG_ROOT,
			'VERSION'=>NRELATE_POPULAR_PLUGIN_VERSION,
			'KEY'=>get_option('nrelate_key'),
			'NUM'=>$number,
			'HDR'=>$p_title,
			'MAXPOST'=>$maxageposts,
			'SHOWPOSTTITLE'=>$p_show_post_title,
			'MAXCHAR'=>$p_max_char_per_line,
			'SHOWEXCERPT'=>$p_show_post_excerpt,
			'MAXCHAREXCERPT'=>$p_max_char_post_excerpt,
			'ADOPT'=>$ad,
			'THUMB'=>$thumb,
			'LOGO'=>$logo,
			'IMAGEURL'=>$backfill,
			'THUMBSIZE'=>$popular_thumbnail_size,
			'LAYOUT'=>isset($popular_layout) ? $popular_layout : null,
			'ADNUM'=>isset($popular_ad_num) ? $popular_ad_num : null,
			'ADPLACE'=>isset($popular_ad_place) ? $popular_ad_place : null,
			'ADTITLE'=>$p_ad_title,
			'SHOWVIEWS'=>$popular_showviews,
			'NONJS'=>$p_nonjs,
			'VIEW'=>urlencode($p_view)
		);
		$url = 'http://api.nrelate.com/mpw_wp/'.NRELATE_POPULAR_PLUGIN_VERSION.'/processWPpopular.php';
		
		$result = wp_remote_post($url,array('body'=>$body,'blocking'=>false, 'timeout'=>15));
		
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
		'VERSION'=>NRELATE_POPULAR_PLUGIN_VERSION,
		'KEY'=>get_option('nrelate_key'),
		'ADMINVERSION'=>NRELATE_POPULAR_ADMIN_VERSION,
		'PLUGIN'=>'popular',
		'RSSURL'=>$rssurl
	);
	$url = 'http://api.nrelate.com/common_wp/'.NRELATE_POPULAR_ADMIN_VERSION.'/wordpressnotify_activation.php';

	$result = wp_remote_post($url,array('body'=>$body,'blocking'=>false, 'timeout'=>15));
	
}

// Deactivation hook callback
function nr_mp_deactivate(){
	
	$nrelate_active=nrelate_products("popular",NRELATE_POPULAR_PLUGIN_VERSION,NRELATE_POPULAR_ADMIN_VERSION,0);
	
	// If another nrelate plugin is activated, don't delete xmlrpc pinghost and don't delete admin options
	if ($nrelate_active==0){
			// Remove our ping link from ping_sites
			$current_ping_sites = get_option('ping_sites');
			$new_ping_sites = str_replace(array("\nhttp://api.nrelate.com/rpcpinghost/","http://api.nrelate.com/rpcpinghost/"), "", $current_ping_sites);
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

	// Send notification to nrelate server of deactivation
	$action = "DEACTIVATE";

	$body=array(
		'DOMAIN'=>NRELATE_BLOG_ROOT,
		'ACTION'=>$action,
		'RSSMODE'=>$rss_mode,
		'VERSION'=>NRELATE_POPULAR_PLUGIN_VERSION,
		'KEY'=>get_option('nrelate_key'),
		'ADMINVERSION'=>NRELATE_POPULAR_ADMIN_VERSION,
		'PLUGIN'=>'popular',
		'RSSURL'=>$rssurl
	);
	$url = 'http://api.nrelate.com/common_wp/'.NRELATE_POPULAR_ADMIN_VERSION.'/wordpressnotify_activation.php';

	$result = wp_remote_post($url,array('body'=>$body,'blocking'=>false, 'timeout'=>15));
}

// Uninstallation hook callback
function nr_mp_uninstall(){
	
	// Delete nrelate popular options from user's wordpress db
	delete_option('nrelate_popular_options');
	delete_option('nrelate_popular_options_ads');
	delete_option('nrelate_popular_options_styles');
	
	$nrelate_active=nrelate_products("popular",NRELATE_POPULAR_PLUGIN_VERSION,NRELATE_POPULAR_ADMIN_VERSION,-1);

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
		'VERSION'=>NRELATE_POPULAR_PLUGIN_VERSION,
		'KEY'=>get_option('nrelate_key'),
		'ADMINVERSION'=>NRELATE_POPULAR_ADMIN_VERSION,
		'PLUGIN'=>'popular',
		'RSSURL'=>$rssurl
	);
	$url = 'http://api.nrelate.com/common_wp/'.NRELATE_POPULAR_ADMIN_VERSION.'/wordpressnotify_activation.php';

	$result = wp_remote_post($url,array('body'=>$body,'blocking'=>false, 'timeout'=>15));

}

?>