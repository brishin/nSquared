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
if (!defined('NSQUARED_PLUGIN_BASENAME')) { define( 'NSQUARED_PLUGIN_BASENAME', plugin_basename( __FILE__ ) ); }
if (!defined( 'NSQUARED_PLUGIN_NAME')) { define( 'NSQUARED_PLUGIN_NAME', trim( dirname( NSQUARED_PLUGIN_BASENAME ), '/' ) ); }
if (!defined( 'NSQUARED_PLUGIN_DIR')) { define( 'NSQUARED_PLUGIN_DIR', WP_PLUGIN_DIR . '/' . NSQUARED_PLUGIN_NAME ); }
if (!defined('NSQUARED_ADMIN_DIR')) { define( 'NSQUARED_ADMIN_DIR', NSQUARED_PLUGIN_DIR .'/admin/'); }
if (!defined('NSQUARED_JS_DIR')) { define( 'NSQUARED_JS_DIR', NSQUARED_PLUGIN_DIR .'/js/'); }
if (!defined('NSQUARED_LIB_DIR')) { define( 'NSQUARED_LIB_DIR', NSQUARED_PLUGIN_DIR .'/lib/'); }
if (!defined('NSQUARED_CSS_DIR')) { define( 'NSQUARED_CSS_DIR', NSQUARED_PLUGIN_DIR .'/css/'); }
if (!defined('NSQUARED_PART_DIR')) { define( 'NSQUARED_PART_DIR', NSQUARED_PLUGIN_DIR .'/partials/'); }

// will contain all options and configuration variables
$nsquared_config = array(
	'plugin_dir' => NSQUARED_PLUGIN_DIR,
	'partials_dir' => NSQUARED_PART_DIR
	); 

if (is_admin()) {
	//load options menu
	require_once( NSQUARED_ADMIN_DIR . 'nsquared-options.php' );		
}

function nsquared_install() {

	global $wpdb;


	$nsq_post_title = 'nSquared';
	//TODO set title option
	$nsq_post_name = 'nsquared';
	//TODO set slug option
	global $nsquared_config;

	$page = get_page_by_title($nsq_post_title);
	if (!$page) {
		// Create post object
		$nsq_post = array(
			'post_title' => $nsq_post_title,
			'post_name' => $nsq_post_name,
			'post_content' => "This is new content",
			'post_status' => 'publish',
			'post_type' => 'page',
			'comment_status' => 'closed',
			'ping_status' => 'closed',
			'post_parent' => '0',
		);
		// Insert the post into the database
		$nsq_page_id = wp_insert_post($nsq_post);
		$nsquared_config['post_info'] = $nsq_post;
		$nsquared_config['post_id'] = $nsq_page_id;
	} 
	else {
		// takes out the pre-existing nSquared page fromtarsh 
		$page_id = $page->ID;
		$page->post_status = 'publish';
		$page->post_content = 'Thewfjeiafea';
		$nsq_page_id = wp_update_post( $page );
		$nsquared_config['post_id'] = $nsq_page_id;
	}

	delete_option( 'nsquared_page_id' );
    add_option( 'nsquared_page_id', $nsq_page_id );

    // sets defaults
	$tmp = get_option('nsquared_options');
    if(($tmp['chk_default_options_db']=='1')||(!is_array($tmp))) {
		delete_option('nsquared_options'); 
		$arr = array(	"chk_button1" => "1",
						"chk_button3" => "1",
						"nsq_title" => "Set the title of the plugin here.",
						"textarea_two" => "This text area control uses the TinyMCE editor to make it super easy to add formatted content.",
						"textarea_three" => "Another TinyMCE editor! It is really easy now in WordPress 3.3 to add one or more instances of the built-in WP editor.",
						"txt_one" => "Enter whatever you like here..",
						"drp_select_box" => "four",
						"chk_default_options_db" => "",
						"rdo_group_one" => "one",
						"rdo_group_two" => "two"
		);
		update_option('nsquared_options', $arr);
	}

}

function nsquared_uninstall() {
	global $wpdb;

	$nsq_post_title = get_option( "nsquared_page_title" );
	$nsq_post_name = get_option( "nsquared_page_name" );

	$page_id = get_option( 'nsquared_page_id' );
	if( $page_id ){
		wp_delete_post( $page_id ); // this will trash, not delete
	}

	delete_option("nsquared_page_title");
	delete_option("nsquared_page_name");
	delete_option("nsquared_page_id");

	delete_option('nsquared_options');


}

/**
* adds nSquared plugin css
*/
function nsquared_add_css(){
	$page_id = get_option( 'nsquared_page_id' );
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
	global $nsquared_config;

	$cat_json = $tag_json = '';
	//orders results by name
	$args=array(
		'orderby' => 'name',
		'order' => 'ASC');

	$categories=get_categories($args);
	$cat_json = json_encode($categories);
	$tags=get_tags($args);
	$tag_json = json_encode($tags);

	$nsquared_config['categories'] = $cat_json;
	$nsquared_config['tags'] = $tag_json;
}

function nrelate_add_js($content){
	global $nsquared_config;

	$page_id = get_option( 'nsquared_page_id' );
	if(is_page($page_id)){

		nsquared_tax_getter();

		// passes categories and tags data to nsq-retriever
		wp_enqueue_script('nsq-retriever', NSQUARED_JS_DIR.'nsq-retriever.js');
		wp_localize_script( 'nsq-retriever', 'nsqRetriever', $nsquared_config);
		wp_enqueue_script('angular', NSQUARED_LIB_DIR.'angular/angular.js');
		// passes plugin directory to app.js
		wp_enqueue_script('app', NSQUARED_JS_DIR.'app.js');
		wp_enqueue_script('services', NSQUARED_JS_DIR.'services.js');
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
register_deactivation_hook( __FILE__, 'nsquared_uninstall' );


?>
