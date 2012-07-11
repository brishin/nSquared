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

if (!defined('NSQUARED_PLUGIN_NAME')) {define('NSQUARED_PLUGIN_NAME', 'nsquared');}
if (!defined('NSQUARED_PLUGIN_DIR')) { define( 'NSQUARED_PLUGIN_DIR', WP_PLUGIN_URL . '/' . NSQUARED_PLUGIN_NAME ); }
if (!defined('NSQUARED_JS_DIR')) { define( 'NSQUARED_JS_DIR', NSQUARED_PLUGIN_DIR .'/js/'); }
if (!defined('NSQUARED_LIB_DIR')) { define( 'NSQUARED_LIB_DIR', NSQUARED_PLUGIN_DIR .'/lib/'); }
if (!defined('NSQUARED_CSS_DIR')) { define( 'NSQUARED_CSS_DIR', NSQUARED_PLUGIN_DIR .'/css/'); }
if (!defined('NSQUARED_PART_DIR')) { define( 'NSQUARED_PART_DIR', NSQUARED_PLUGIN_DIR .'/partials/'); }


function nsquared_install() {

	global $wpdb;

	$nsquared_page_title = 'nSquared';
	//TODO set title option
	$nsquared_page_name = 'nsquared';
	//TODO set slug option

	$page = get_page_by_title($nsquared_page_title);
	if (!$page) {
		// Create post object
		$new_post = array(
			'post_title' => $nsquared_page_title,
			'post_name' => $nsquared_page_name,
			'post_content' => "This is new content",
			'post_status' => 'publish',
			'post_type' => 'page',
			'comment_status' => 'closed',
			'ping_status' => 'closed',
			'post_parent' => '0',
		);

		// Insert the post into the database
		$page_id = wp_insert_post($new_post);
		$nrelate_page_id = $page_id;
	} 
	else {
		// takes out the pre-existing nSquared page fromtarsh 
		$page_id = $page->ID;
		$page->post_status = 'publish';
		$page->post_content = 'Thewfjeiafea';
		$page_id = wp_update_post( $page );
		$nrelate_page_id = $page_id;
	}
	delete_option( 'nsquared_page_id' );
    add_option( 'nsquared_page_id', $nrelate_page_id );

}

function nsquared_uninstall() {
	global $wpdb;

	$nsquared_page_title = get_option( "nsquared_page_title" );
	$nsquared_page_name = get_option( "nsquared_page_name" );

	$page_id = get_option( 'nsquared_page_id' );
	if( $page_id ){
		wp_delete_post( $page_id ); // this will trash, not delete
	}

	delete_option("nsquared_page_title");
	delete_option("nsquared_page_name");
	delete_option("nsquared_page_id");

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
function nsquared_retriever(){
	$cat_json = $tag_json = $inject = '';
	//orders results by name
	$args=array(
		'orderby' => 'name',
		'order' => 'ASC');

	$categories=get_categories($args);
	$cat_json = json_encode($categories);
	
	$tags=get_tags($args);
	$tag_json = json_encode($tags);

	echo <<<EOT
  <script type = "text/javascript" id="nsq-retriever"> var nsqCategories = $cat_json; var nsqTags = $tag_json; </script>
EOT;
}
add_action('get_header', 'nsquared_retriever');

function nrelate_add_js($content){
	$page_id = get_option( 'nsquared_page_id' );
	if(is_page($page_id)){

		wp_enqueue_script('angular', NSQUARED_LIB_DIR.'angular/angular.js');
		wp_enqueue_script('app', NSQUARED_JS_DIR.'app.js');
		wp_enqueue_script('services', NSQUARED_JS_DIR.'services.js');
		wp_enqueue_script('controllers', NSQUARED_JS_DIR.'controllers.js');
		wp_enqueue_script('filters', NSQUARED_JS_DIR.'filters.js');
		wp_enqueue_script('directives', NSQUARED_JS_DIR.'directives.js');
		wp_enqueue_script('bootstrap', NSQUARED_LIB_DIR.'bootstrap.js');
		wp_enqueue_script('spin', NSQUARED_LIB_DIR.'spin.min.js');
		
		$content = '';
		$content .= '<script type = "text/javascript"> var $ = jQuery; </script> <div class="container-fluid" ng-app="myApp">
    <div class="row-fluid" ng-view></div>
  </div>'; //end 
	}
	return $content;
}
add_filter('the_content', 'nrelate_add_js');


register_activation_hook(__FILE__,'nsquared_install'); 
register_deactivation_hook( __FILE__, 'nsquared_uninstall' );


?>
