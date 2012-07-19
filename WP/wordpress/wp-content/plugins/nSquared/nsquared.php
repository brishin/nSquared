<?php
/*
Plugin Name: nSquared
Plugin URI: http://www.nrelate.com
Description: Present all your posts in an easy to browse and filterable interface. 
Author: <a href="http://www.nrelate.com">nrelate</a>
Version: 0.70.1
Author URI: http://nrelate.com/
*/


/**
 * Define Path constants
 */
define( 'NSQUARED_PLUGIN_BASENAME', plugin_basename( __FILE__ ) );
define( 'NSQUARED_PLUGIN_NAME', trim( dirname( NSQUARED_PLUGIN_BASENAME )) );
define( 'NSQUARED_PLUGIN_DIR', plugin_dir_url(__FILE__) );
define( 'NSQUARED_PLUGIN_URL', plugins_url(__FILE__) );
// define( 'NSQUARED_ADMIN_DIR', NSQUARED_PLUGIN_DIR .'admin/');
define( 'NSQUARED_JS_DIR', NSQUARED_PLUGIN_DIR .'/app/js/');
define( 'NSQUARED_LIB_DIR', NSQUARED_PLUGIN_DIR .'/app/lib/');
define( 'NSQUARED_CSS_DIR', NSQUARED_PLUGIN_DIR .'/app/css/');
define( 'NSQUARED_PART_DIR', NSQUARED_PLUGIN_DIR .'app/partials/');

// will contain all options and configuration variables
$nsquared_js_config = array(
	'pluginDIR' => NSQUARED_PLUGIN_DIR,
	'partialsDIR' => NSQUARED_PART_DIR,
	'domain' => get_option('home')
	); 

if (is_admin()) {
	//load options menu
	require_once('nsquared-options.php' );		
}

function nsquared_activate() {
	global $wpdb;

    // sets defaults
	$tmp = get_option('nsquared_options');
    if(($tmp['chk_default_options_db']=='1') || (!is_array($tmp))){
		delete_option('nsquared_options'); 
		delete_option('nsquared_page_id');
		$arr = array(	"nsq_title" => "nSquared",
						"nsq_slug" => "nsquared",
						"nsq_thumbsize" => "150",
						"chk_default_options_db" => "",
		);
		$emp = '';
		$emp_array = array('0');
		update_option('nsquared_options', $arr);
		update_option('nsquared_page_id', $emp);
		update_option('nsquared_exc_cats', $emp_array);
		update_option('nsquared_exc_tags', $emp_array);
	}

	$opts = get_option('nsquared_options');
	$page_id = get_option('nsquared_page_id');
	$title = $opts['nsq_title'];
	$slug = $opts['nsq_slug'];

	$page = get_page($page_id);
	if (!$page) {
		// Create post object
		$nsq_page = array(
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
		$new_page_id = wp_insert_post($nsq_page);
		update_option('nsquared_page_id', $new_page_id);
		update_option('nsquared_exc_cats', $emp_array);
		update_option('nsquared_exc_tags', $emp_array);

	} 
	else {
		// takes out the pre-existing nSquared page from trash 
		$page_id = $page->ID;
		$page->post_status = 'publish';
		$page->post_content = 'Updated';
		$opts['nsq_title'] = $page->post_title;
		$opts['nsq_slug'] = $page->post_name;
		$new_page_id = wp_update_post( $page );
		// updates id, title, slug
		update_option('nsquared_page_id', $new_page_id);
		update_option('nsquared_options', $opts);
		// deletes old page if necessary HACKY
		if(!($new_page_id==$page_id)){
			wp_delete_post($page_id, true);
			// makes sure id gets updated with right variable
			update_option('nsquared_page_id', $new_page_id);
		}
	}
}

function nsquared_deactivate() {
	global $wpdb;

	$page_id = get_option('nsquared_page_id');
	wp_delete_post( $page_id ); // this will trash, not delete
}


function nsquared_uninstall(){
	global $wpdb;

	delete_option('nsquared_options');
	$page_id = get_option('nsquared_page_id');
	wp_delete_post( $page_id , true); // this will delte, not just trash
	delete_option('nsquared_page_id');
	delete_option('nsquared_exc_cats');
	delete_option('nsquared_exc_tags');

}

/**
* adds nSquared plugin css
*/
function nsquared_add_css_js(){
	global $nsquared_js_config;
	global $wpdb;

	$options = get_option('nsquared_options');
	$page_id = get_option('nsquared_page_id');
	if(is_page($page_id)){
		// load styles
		wp_enqueue_style('colorpicker', NSQUARED_CSS_DIR. 'colorpicker.css');
		wp_enqueue_style('bootstrap', NSQUARED_CSS_DIR. 'bootstrap.css');
		wp_enqueue_style('bootstrap-responsive', NSQUARED_CSS_DIR. 'bootstrap-responsive.css');
		wp_enqueue_style('nrelate', NSQUARED_CSS_DIR. 'nrelate.css');
		wp_enqueue_style('nsquared-style', NSQUARED_CSS_DIR. 'nsquared-style.css');
		wp_enqueue_style('nsquared', NSQUARED_CSS_DIR. 'nsquared.css');
		$thumbsize = $options['nsq_thumbsize'];
		if(empty($thumbsize)){
			$thumbsize='150';
		}
		$thumbcss = 'nsq-thumb-'.$thumbsize;
		wp_enqueue_style($thumbcss, NSQUARED_CSS_DIR. $thumbcss.'.css');

		nsquared_tax_getter(); // gets categories and tags
		// load scripts
		wp_enqueue_script('jQuery', 'https://ajax.googleapis.com/ajax/libs/jquery/1.7.2/jquery.min.js');
		wp_enqueue_script('jqueryconflict', NSQUARED_JS_DIR.'jqueryconflict.js');
		wp_enqueue_script('colorpickernsq', NSQUARED_LIB_DIR.'colorpickernsq.js');
		wp_enqueue_script('angular', NSQUARED_LIB_DIR.'angular/angular.js');
		// passes categories and tags data to nsq-retriever
		// wp_enqueue_script('nsq-retriever', NSQUARED_JS_DIR.'nsq-retriever.js');
		// passes plugin directory to app.js
		wp_enqueue_script('app', NSQUARED_JS_DIR.'app.js');
		wp_localize_script('app', 'nsq', $nsquared_js_config);
		// wp_localize_script('app', 'nsqPath', $nsquared_js_config);
		wp_enqueue_script('services', NSQUARED_JS_DIR.'services.js');
		// wp_localize_script('services', 'nsqDomain', $nsquared_js_config);
		wp_enqueue_script('controllers', NSQUARED_JS_DIR.'controllers.js');
		wp_enqueue_script('filters', NSQUARED_JS_DIR.'filters.js');
		wp_enqueue_script('directives', NSQUARED_JS_DIR.'directives.js');
		wp_enqueue_script('bootstrap', NSQUARED_LIB_DIR.'bootstrap.js');
		wp_enqueue_script('spin', NSQUARED_LIB_DIR.'spin.min.js');
	}
}
add_action('get_header', 'nsquared_add_css_js');

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
	$exc_cats = get_option('nsquared_exc_cats');
	if(!empty($exc_cats)){
		foreach($categories as $category){
			if(in_array(($category->cat_ID), $exc_cats)){
				$categories = array_diff($categories, array($category));
			}
		}
	}
	$cat_json = json_encode($categories);

	$tags = get_tags($args);
	$exc_tags = get_option('nsquared_exc_tags');
	if(!empty($exc_tags)){
		foreach($tags as $tag){
			if(in_array(($tag->term_id), $exc_tags)){
				$tags = array_diff($tags, array($tag));
			}
		}
	}
	$tag_json = json_encode($tags);

	$nsquared_js_config['categories'] = $cat_json;
	$nsquared_js_config['tags'] = $tag_json;
}

function nsquared_add_div($content){
	$options = get_option('nsquared_options');
	$page_id = get_option('nsquared_page_id');
	if(is_page($page_id)){
		$content = '';
		$content .= '<div ng-app="nSquared"><div class="container-fluid">
    <div class="row-fluid" ng-view ></div>
  </div></div>

'; //end 
	}
	return $content;
}
add_filter('the_content', 'nsquared_add_div'); // edits content of the plugin specified page

// register hooks
register_activation_hook(__FILE__,'nsquared_activate'); 
register_deactivation_hook( __FILE__, 'nsquared_deactivate' );
register_uninstall_hook(__FILE__, 'nsquared_uninstall')

?>
