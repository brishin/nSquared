<?php
/*
Plugin Name: nSquared example
Plugin URI: http://www.nrelate.com
Description: Present all your posts in an easy to browse and filterable interface. 
Author: <a href="http://www.nrelate.com">nrelate</a> and <a href="http://www.slipfire.com">SlipFire</a>
Version: 0.10.1
Author URI: http://nrelate.com/
*/

function nsquared_install() {

	global $wpdb;

	$page_title = 'nSquared';
	//set title option
	$page_name = 'nsquared';
	//set option

	$page = get_page_by_title($page_title);
	if (!$page) {
		// Create post object
		$new_post = array(
			'post_title' => $page_title,
			'post_name' => $page_name,
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
		// the plugin may have been previously active and the page may just be trashed...
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

	$page_title = get_option( "nsquared_page_title" );
	$page_name = get_option( "nsquared_page_name" );

	$page_id = get_option( 'nsquared_page_id' );
	if( $page_id ){
		wp_delete_post( $page_id ); // this will trash, not delete
	}

	delete_option("nsquared_page_title");
	delete_option("nsquared_page_name");
	delete_option("nsquared_page_id");

}

function add_js($content){
	$path = 'wp-content/plugins/nsquared';
	$somejs = <<<EOD
<script src="https://ajax.googleapis.com/ajax/libs/jquery/1.7.2/jquery.min.js"></script>
<script src="$path/lib/angular/angular.js"></script>
<script src="$path/js/app.js"></script>
<script src="$path/js/services.js"></script>
<script src="$path/js/controllers.js"></script>
<script src="$path/js/filters.js"></script>
<script src="$path/js/directives.js"></script>
<script src="$path/lib/bootstrap.js"></script>
<script src="$path/lib/spin.min.js"></script>
<div data-ng-app="myApp"></div>
EOD;

	$page_id = get_option( 'nsquared_page_id' );
	if(is_page($page_id)){
		$content = $somejs;
	}
	return $content;
}
add_filter('the_content', 'add_js');register_activation_hook(__FILE__,'nsquared_install'); 
register_deactivation_hook( __FILE__, 'nsquared_uninstall' );


?>
