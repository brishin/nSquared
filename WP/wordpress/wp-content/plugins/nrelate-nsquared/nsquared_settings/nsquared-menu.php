<?php
/**
 * Plugin Admin File
 *
 * Settings for this plugin
 *
 * @package nrelate
 * @subpackage Functions
 */


/**
 * Add sub menu
 */
function nrelate_nsquared_setup_admin() {

    // Add our submenu to the custom top-level menu:
	require_once NRELATE_NSQUARED_SETTINGS_DIR . '/nrelate-nsquared-settings.php';
	require_once NRELATE_NSQUARED_SETTINGS_DIR . '/nrelate-nsquared-styles-settings.php';
	// SQ require_once NRELATE_NSQUARED_SETTINGS_DIR . '/nrelate-nsquared-advertising-settings.php';
    $nsquaredmenu = add_submenu_page('nrelate-main', __('nSquared','nrelate'), __('nSquared','nrelate'), 'manage_options', NRELATE_NSQUARED_ADMIN_SETTINGS_PAGE, 'nrelate_nsquared_settings_page');
		add_action('load-'.$nsquaredmenu,'nrelate_nsquared_load_admin_scripts');
};
add_action('admin_menu', 'nrelate_nsquared_setup_admin');


/**
 * Load plugin specific JS
 *
 * Only loads on plugin specific page
 */
function nrelate_nsquared_load_admin_scripts() {
	wp_enqueue_script('nrelate_nsquared_js', NRELATE_NSQUARED_SETTINGS_URL.'/nrelate_nsquared_admin'. ( NRELATE_JS_DEBUG ? '' : '.min') .'.js', array('jquery'));
}

/**
 * Main Related Settings
 *
 * Generates all settings pages
 * since v0.46.0
 */
function nrelate_nsquared_settings_page() {
	global $pagenow;
	
	if ( $pagenow == 'admin.php' && $_GET['page'] == 'nrelate-nsquared' ) : 
    if ( isset ( $_GET['tab'] ) ) : 
        $tab = $_GET['tab']; 
    else: 
        $tab = 'general'; 
    endif; 
    switch ( $tab ) : 
        case 'general' : 
            nrelate_nsquared_do_page(); 
            break; 
        case 'styles' : 
            nrelate_nsquared_styles_do_page(); 
            break;
        case 'advertising' : 
            nrelate_nsquared_ads_do_page(); 
            break;	
    endswitch; 
	endif;
}

/**
 * Tabs for nsquared settings
 *
 * since v0.46.0
 */
function nrelate_nsquared_tabs($current = 0) {

	// Text or Thumbnails?
	$options = get_option('nrelate_nsquared_options');
	$styletype = 'Thumbnails';
	//$styletype = $options['nsquared_thumbnail'];
	
	// What type of ads?
	$nsquared_ad_type = get_option('nrelate_nsquared_options_ads');
	
	// If Ads == Separate, then overwrite $styletype
	if ($nsquared_ad_type['nsquared_ad_placement']=="Separate"){
		$styletype = $styletype . " | " . _('Ads');
	}

    $tabs = array( 'general' =>  __(' General','nrelate'), 'advertising' => __(' Advertising','nrelate'), 'styles' => $styletype . __(' Gallery','nrelate') ); 
    $links = array();
	
		if ( $current == 0 ) {
		if ( isset( $_GET[ 'tab' ] ) ) {
			$current = $_GET[ 'tab' ];
		} else {
			$current = 'general';
		}
	}
	
	echo '<div id="nav">'; 
    foreach( $tabs as $tab => $name ) : 
        if ( $tab == $current ) : 
            $links[] = "<a class='nav-tab nav-tab-active' href='?page=nrelate-nsquared&tab=$tab'>$name</a>"; 
        else : 
            $links[] = "<a class='nav-tab' href='?page=nrelate-nsquared&tab=$tab'>$name</a>"; 
        endif; 
    endforeach; 
    echo '<h2>'; 
    foreach ( $links as $link ) 
        echo $link; 
    echo '</h2>'; 
    echo '</div>'; 
}

/**
 * Header for nsquared settings
 *
 * Common for all settings pages
 * @since v0.46.0
 * @updated 0.50.0
 */
function nrelate_nsquared_settings_header() {
	nrelate_plugin_page_header ( NRELATE_NSQUARED_NAME, NRELATE_NSQUARED_DESCRIPTION );
	// nrelate_index_check();
	nrelate_nsquared_tabs();
}

// Check dashboard messages if on dashboard page in admin
require_once NRELATE_NSQUARED_SETTINGS_DIR . '/nsquared-messages.php';

/**
 * Tells the dashboard that we're active
 * Shows icon and link to settings page
 */
function nr_sq_plugin_active(){ ?>
	<li class="active-plugins">
		<?php echo '<img src="'. NRELATE_NSQUARED_IMAGE_DIR .'/nsquaredcontent.png" style="float:left;" alt="" />'?>
		<a href="admin.php?page=<?php echo NRELATE_NSQUARED_ADMIN_SETTINGS_PAGE ?>">
		<?php echo NRELATE_NSQUARED_NAME ?> &raquo;</a>
	</li>
<?php
};
add_action ('nrelate_active_plugin_notice','nr_sq_plugin_active');



/**
 * Add settings link on plugin page
 *
 * @since 0.40.3
 */
function nrelate_nsquared_add_plugin_links( $links, $file) {
	if( $file == NRELATE_NSQUARED_PLUGIN_BASENAME ){
		return array_merge( array(
			'<a href="admin.php?page='.NRELATE_NSQUARED_ADMIN_SETTINGS_PAGE.'">'.__('Settings', 'nrelate').'</a>',
			'<a href="admin.php?page=nrelate-main">'.__('Dashboard', 'nrelate').'</a>'
		),$links );
	}
	return $links;
}
add_filter('plugin_action_links', 'nrelate_nsquared_add_plugin_links', 10, 2);

/**
 * Add plugin row meta on plugin page
 *
 * @since 0.40.3
 */

function nrelate_nsquared_set_plugin_meta($links, $file) {
	// create link
	if ($file == NRELATE_NSQUARED_PLUGIN_BASENAME) {
		return array_merge( $links, array(
			'<a href="admin.php?page='.NRELATE_NSQUARED_ADMIN_SETTINGS_PAGE.'">'.__('Settings', 'nrelate').'</a>',
			'<a href="admin.php?page=nrelate-main">'.__('Dashboard', 'nrelate').'</a>',
			'<a href="'.NRELATE_WEBSITE_FORUM_URL.'">' . __('Support Forum', 'nrelate') . '</a>'
		));
	}
	return $links;
}
add_filter('plugin_row_meta', 'nrelate_nsquared_set_plugin_meta', 10, 2 );

?>