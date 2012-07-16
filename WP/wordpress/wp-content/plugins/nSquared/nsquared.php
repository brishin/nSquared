<?php
/*
Plugin Name: nSquared
Plugin URI: http://www.nrelate.com
Description: Present all your posts in an easy to browse and filterable interface. TEST
Author: <a href="http://www.nrelate.com">nrelate</a> and <a href="http://www.slipfire.com">SlipFire</a>
Version: 0.10.1
Author URI: http://nrelate.com/
*/


/**
 * Define Path constants
 */
define( 'NSQUARED_PLUGIN_BASENAME', plugin_basename( __FILE__ ) );
define( 'NSQUARED_PLUGIN_NAME', trim( dirname( NSQUARED_PLUGIN_BASENAME ), '/' ) );
define( 'NSQUARED_PLUGIN_DIR', plugin_dir_url(__FILE__) );
define( 'NSQUARED_PLUGIN_URL', plugins_url(__FILE__) );
define( 'NSQUARED_ADMIN_DIR', NSQUARED_PLUGIN_DIR .'/admin/');
define( 'NSQUARED_JS_DIR', NSQUARED_PLUGIN_DIR .'/js/');
define( 'NSQUARED_LIB_DIR', NSQUARED_PLUGIN_DIR .'/lib/');
define( 'NSQUARED_CSS_DIR', NSQUARED_PLUGIN_DIR .'/css/');
define( 'NSQUARED_PART_DIR', NSQUARED_PLUGIN_DIR .'/partials/');

// will contain all options and configuration variables
$nsquared_js_config = array(
	'pluginDIR' => NSQUARED_PLUGIN_DIR,
	'partialsDIR' => NSQUARED_PART_DIR,
	'domain' => get_option('home')
	); 

if (is_admin()) {
	//load options menu
	require_once( NSQUARED_ADMIN_DIR . 'nsquared-options.php' );		
}

function nsquared_install() {

	global $wpdb;

    // sets defaults
	$tmp = get_option('nsquared_options');
	//tmp[] causing unexpected output error. WTF
    if(($tmp['chk_default_options_db']=='1') || (!is_array($tmp))){
		delete_option('nsquared_options'); 
		$arr = array(	"nsq_title" => "nSquared",
						"nsq_slug" => "nsquared",
						"nsq_thumbsize" => "150",
						"chk_default_options_db" => "",
						"nsq_page_id" => ""
		);
		update_option('nsquared_options', $arr);
	}

	$opts = get_option('nsquared_options');
	$page_id = $opts['nsq_page_id'];
	$title = $opts['nsq_title'];
	$slug = $opts['nsq_slug'];

	$page = get_page($page_id);
	if (!$page) {
		// Create post object
		$nsq_post = array(
			'post_title' => $title,
			'post_name' => $slug,
			'post_content' => "This is new content",
			'post_status' => 'publish',
			'post_type' => 'page',
			'comment_status' => 'closed',
			'ping_status' => 'closed',
			'post_parent' => '0',
		);
		// Insert the post into the database
		$page_id = wp_insert_post($nsq_post);
		$opts['nsq_page_id'] = $page_id;
		// adds page id to options
		update_option('nsquared_options', $opts);
	} 
	else {
		// takes out the pre-existing nSquared page from trash 
		$page_id = $page->ID;
		$page->post_status = 'publish';
		$page->post_content = 'Updated';
		$page_id = wp_update_post( $page );
		$opts['nsq_page_id'] = $page_id;
		// adds page id to options
		update_option('nsquared_options', $opts);
	}

}

function nsquared_deactivate() {
	global $wpdb;

	$opts = get_option('nsquared_options');
	$page_id = $opts['nsq_page_id'];

	if( $page_id ){
		wp_delete_post( $page_id ); // this will trash, not delete
	}
}


function nsquared_uninstall(){
	delete_option('nsquared_options');
}

/**
* adds nSquared plugin css
*/
function nsquared_add_css(){
	$options = get_option('nsquared_options');
	$page_id = $options['nsq_page_id'];
	if(is_page($page_id)){
		wp_enqueue_style('app', NSQUARED_CSS_DIR. 'app.css');
		wp_enqueue_style('bootstrap', NSQUARED_CSS_DIR. 'bootstrap.css');
		wp_enqueue_style('bootstrap-responsive', NSQUARED_CSS_DIR. 'bootstrap-responsive.css');
		wp_enqueue_style('nrelate', NSQUARED_CSS_DIR. 'nrelate.css');
		wp_enqueue_style('pint_style', NSQUARED_CSS_DIR. 'pint_style.css');
	}
}
add_action('get_header', 'nsquared_add_css');

/**
* gets the site's categories and tags
*/
function nsquared_tax_getter(){
	global $nsquared_js_config;

	$cat_json = $tag_json = '';
	//orders results by name
	$args=array(
		'orderby' => 'name',
		'order' => 'ASC');

	$categories = get_categories($args);
	$cat_json = json_encode($categories);
	$tags = get_tags($args);
	$tag_json = json_encode($tags);

	$nsquared_js_config['categories'] = $cat_json;
	$nsquared_js_config['tags'] = $tag_json;
}

function nrelate_add_js($content){
	global $nsquared_js_config;

	$options = get_option('nsquared_options');
	$page_id = $options['nsq_page_id'];
	if(is_page($page_id)){

		nsquared_tax_getter();

		// passes categories and tags data to nsq-retriever
		wp_enqueue_script('nsq-retriever', NSQUARED_JS_DIR.'nsq-retriever.js');
		wp_localize_script('nsq-retriever', 'nsqTaxonomy', $nsquared_js_config);

		wp_enqueue_script('angular', NSQUARED_LIB_DIR.'angular/angular.js');
		
		// passes plugin directory to app.js
		wp_enqueue_script('app', NSQUARED_JS_DIR.'app.js');
		wp_localize_script('app', 'nsqPath', $nsquared_js_config);
		
		wp_enqueue_script('services', NSQUARED_JS_DIR.'services.js');
		wp_localize_script('services', 'nsqDomain', $nsquared_js_config);
		
		wp_enqueue_script('controllers', NSQUARED_JS_DIR.'controllers.js');
		wp_enqueue_script('filters', NSQUARED_JS_DIR.'filters.js');
		wp_enqueue_script('directives', NSQUARED_JS_DIR.'directives.js');
		wp_enqueue_script('bootstrap', NSQUARED_LIB_DIR.'bootstrap.js');
		wp_enqueue_script('spin', NSQUARED_LIB_DIR.'spin.min.js');

		
		$content = '';
		$content .= '<div class="container-fluid" ng-app="myApp">
    <div class="row-fluid" ng-view></div>
  </div>'; //end 
	}
	return $content;
}
add_filter('the_content', 'nrelate_add_js');

register_activation_hook(__FILE__,'nsquared_install'); 
register_deactivation_hook( __FILE__, 'nsquared_deactivate' );
register_uninstall_hook(__FILE__, 'nsquared_uninstall')


?>
