<?php
/*
Plugin Name: nSquared
Plugin URI: http://www.nrelate.com
Description: Present all your posts in a beautiful, easy to browse, and filterable interface. 
Author: <a href="http://www.nrelate.com">nrelate</a>
Version: 0.1
Author URI: http://nrelate.com/
*/


/* Define path constants
CSS and JS files are hosted at http://nsquared.nrelate.com/static/
*/

define( 'NSQUARED_PLUGIN_BASENAME', plugin_basename( __FILE__ ) );
define( 'NSQUARED_PLUGIN_NAME', trim( dirname( NSQUARED_PLUGIN_BASENAME )) );
define( 'NSQUARED_PLUGIN_DIR', plugin_dir_url(__FILE__) );
define( 'NSQUARED_PLUGIN_URL', plugins_url(__FILE__) );
define( 'NSQUARED_HOST', 'http://nsquared.nrelate.com/static/');
define( 'NSQUARED_JS_DIR', NSQUARED_HOST .'js/');
define( 'NSQUARED_LIB_DIR', NSQUARED_HOST .'lib/');
define( 'NSQUARED_CSS_DIR', NSQUARED_HOST .'css/');
// define( 'NSQUARED_PART_DIR', NSQUARED_HOST .'partials/'); // partials are the templates for the angular MVC framework
// // for development, partials path changed
// define( 'NSQUARED_PART_DIR', NSQUARED_PLUGIN_DIR .'app/partials/');

if( !defined( 'NRELATE_BLOG_ROOT' )) { define( 'NRELATE_BLOG_ROOT', urlencode(str_replace(array('http://','https://'), '', get_bloginfo( 'url' )))); }


/* nsquared_js_config 
contains all the variables that are used by JS files 
*/

$nsquared_js_config = array(
	'pluginDIR' => NSQUARED_PLUGIN_DIR,
	// 'partialsDIR' => NSQUARED_PART_DIR,
	'domain' => get_option('home')
	); 
// ? Bad practice to have this global variable

/* Load options menu */

if (is_admin()) { require_once('nsquared-options.php' ); }


/* Activate the plugin */

function nsquared_activate() {

    // sets defaults
	$tmp = get_option('nsquared_options');
    if(($tmp['chk_default_options_db']=='1') || (!is_array($tmp))){
		delete_option('nsquared_options'); 
		delete_option('nsquared_page_id');
		$arr = array(	"nsq_title" => "nSquared", // title of the page that houses the nSquared plugin
						"nsq_slug" => "nsquared", // slug for the page
						"nsq_thumbsize" => "150", // thumbnail size
						"nsq_style" => "nsquared", // thumbnail style
						"chk_default_options_db" => "",
		);
		$emp = '';
		$emp_array = array('0');
		update_option('nsquared_options', $arr);
		update_option('nsquared_page_id', $emp);
	}

	$opts = get_option('nsquared_options');
	$page_id = get_option('nsquared_page_id');
	$title = $opts['nsq_title'];
	$slug = $opts['nsq_slug'];

	$page = get_page($page_id);
	if (!$page) {
		// Create post object if the page with page_id doesn't exist
		$nsq_page = array(
			'post_title' => $title,
			'post_name' => $slug,
			'post_content' => "This is new content", // dummy text
			'post_status' => 'publish',
			'post_type' => 'page',
			'comment_status' => 'closed',
			'ping_status' => 'closed',
			'post_parent' => '0',
		);
		// Insert the post into the database
		$new_page_id = wp_insert_post($nsq_page); // this is the NEW page_id. Plugin material will always be on this page
		update_option('nsquared_page_id', $new_page_id);
	} 
	else {
		// Takes out the pre-existing nSquared page from trash
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
		if(!( $new_page_id==$page_id )){
			wp_delete_post($page_id, true);
			// makes sure id gets updated with right variable
			update_option('nsquared_page_id', $new_page_id);
		}
	}
}

/* Deactivate the plugin */

function nsquared_deactivate() {
	$page_id = get_option('nsquared_page_id');
	wp_delete_post( $page_id ); // this will trash, not delete
}

/* Uninstall the plugin 
Deletes options arrays and deletes the page the plugin was on 
*/

function nsquared_uninstall(){
	delete_option('nsquared_options'); 
	$page_id = get_option('nsquared_page_id');
	wp_delete_post( $page_id , true); // this will delete completely, not just trash
	delete_option('nsquared_page_id');
}


/* Add nSquared plugin CSS and JS */
function nsquared_add_css_js(){
	global $nsquared_js_config;

	$options = get_option('nsquared_options');
	$page_id = get_option('nsquared_page_id');
	if(is_page($page_id)){
		// load styles
		wp_enqueue_style('colorpicker', NSQUARED_CSS_DIR. 'colorpicker.css');
		wp_enqueue_style('nsquared-bootstrap', NSQUARED_CSS_DIR. 'nsquared-bootstrap.css');
		wp_enqueue_style('bootstrap-responsive', NSQUARED_CSS_DIR. 'bootstrap-responsive.css');
		wp_enqueue_style('nrelate', NSQUARED_CSS_DIR. 'nrelate.css');
		wp_enqueue_style('nsquared', NSQUARED_CSS_DIR. 'nsquared.css');
		$style = $options['nsq_style'];
		if(empty($style)){ $style='nsquared'; }
		wp_enqueue_style('nrelate-nsq-'.$style, NSQUARED_CSS_DIR. 'nrelate-nsq-'.$style.'.css');
		nsquared_info_getter(); // gets categories and tags
		// load scripts
		wp_enqueue_script('colorpickernsq', NSQUARED_LIB_DIR.'colorpickernsq.js');
		wp_enqueue_script('angular', 'http://code.angularjs.org/1.0.1/angular-1.0.1.min.js');
		// passes JS variables to app.js
		wp_enqueue_script('app', NSQUARED_JS_DIR.'app.js');
		wp_localize_script('app', 'nsq', $nsquared_js_config);
		wp_enqueue_script('bootstrap', NSQUARED_LIB_DIR.'bootstrap.js');
		wp_enqueue_script('spin', NSQUARED_LIB_DIR.'spin.min.js');
		wp_enqueue_script('pinit', 'http://assets.pinterest.com/js/pinit.js');
	}
}
add_action('wp_head', 'nsquared_add_css_js');


/* Load jQuery */
function nsquared_load_jquery(){
	wp_enqueue_script('jQuery', 'https://ajax.googleapis.com/ajax/libs/jquery/1.7.2/jquery.min.js');
}
add_action('get_header', 'nsquared_load_jquery');


/* Get the site's categories and tags to pass to toolbar partial */
function nsquared_info_getter(){
	global $nsquared_js_config;

	$slimcats = $slimtags = array();
	$cat_json = $tag_json = '';
	//orders results by name
	$args=array(
		'orderby' => 'name',
		'order' => 'ASC');
	$categories = get_categories($args);
	foreach ($categories as $category){
		$id = $category->term_id;
		$name = $category->name;
		$arr = array(
			'id' => $id,
			'name' => $name);
		array_push($slimcats, $arr);
	}
	$cat_json = json_encode($slimcats);

	$tags = get_tags($args);
	foreach ($tags as $tag){
		$id = $tag->term_id;
		$name = $tag->name;
		$arr = array(
			'id' => $id,
			'name' => $name);
		array_push($slimtags, $arr);
	}
	$tag_json = json_encode($slimtags);

	$options = get_option('nsquared_options');
	$style = $options['nsq_style'];
	$thumbsize = $options['nsq_thumbsize'];

	$nsquared_js_config['categories'] = $cat_json;
	$nsquared_js_config['tags'] = $tag_json;
	$nsquared_js_config['domain'] = addslashes(NRELATE_BLOG_ROOT);
	$nsquared_js_config['style'] = 'nrelate_'.$style;
	$nsquared_js_config['thumbsize'] = 'nr_'.$thumbsize;
}


/* Add the plugin div */

function nsquared_add_div($content){
	$options = get_option('nsquared_options');
	$page_id = get_option('nsquared_page_id');
	if(is_page($page_id)){
		$content = '';
		$content .= '<div ng-app="nSquared"><div class="container-fluid">
    <div class="row-fluid" ng-view ></div>
  </div></div>'; // content end 
	}
	return $content;
}
add_filter('the_content', 'nsquared_add_div'); // edits content of the plugin specified page

/* Register hooks */
register_activation_hook(__FILE__,'nsquared_activate'); 
register_deactivation_hook( __FILE__, 'nsquared_deactivate' );
register_uninstall_hook(__FILE__, 'nsquared_uninstall')

?>
