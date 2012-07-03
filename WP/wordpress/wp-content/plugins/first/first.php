<?php
/*
Plugin Name: My first plugin
Plugin URI: http://www.columbia.edu/~jk3316
Description: lalalala
Author: Jane Kim
Version: 1.0
Author URI: http://www.columbia.edu/~jk3316
*/

//init an array of all plugins
$plugins = array();
//init an array of active plugins
$active = array();

function active_plugins(){
	//function called whenever dashboard is loaded
	global $plugins, $active;

	$plugins = get_plugins();
	
	foreach($plugins as $file => $data){
		if(is_plugin_active($file)){
			$active[$file] = get_plugin_data(WP_PLUGIN_DIR."/$file");
		}
		//for loop goes through each plugin
	}

	wp_add_dashboard_widget('active_plugins', 'Active Plugins', 'active_plugins_dashboard_widget');
}

function active_plugins_dashboard_widget(){
	global $plugins, $active;

	print("<ul>");
	foreach ($active as $plugin) {
		print("<li>{$plugin['Title']} by {$plugin['Author']}</li>");
	}
	print("</ul>");
}

add_action('wp_dashboard_setup', 'active_plugins');

?>
