<?php


add_action('admin_init', 'nsquared_init' );
add_action('admin_menu', 'nsquared_add_options_page');


function nsquared_init(){
	register_setting( 'nsquared_plugin_options', 'nsquared_options', 'nsquared_validate_options' );
}

function nsquared_add_options_page(){
	add_options_page('nSquared Options', 'nSquared', 'manage_options', __FILE__, 'nsquared_render_form');
}

function nsquared_render_form(){	?>
	<div class="wrap">
		
		<!-- Display Plugin Icon, Header, and Description -->
		<!-- <div class="icon32" id="icon-options-general"><br></div> -->
		<h2>nSquared Options</h2>
		<p>ohai! this is Jane from nRelate. this is our new plugin, nSquared.</p>

		<!-- Beginning of the Plugin Options Form -->
		<form method="post" action="options.php">
			<?php settings_fields('nsquared_plugin_options'); ?>
			<?php $options = get_option('nsquared_options'); ?>

			<table class="form-table">
				<!-- nsq_title // textbox -->
				<tr>
					<th scope="row">Title</th>
					<td>
						<input type="text" size="57" name="nsquared_options[nsq_title]" value="<?php echo $options['nsq_title']; ?>" /><br /><br /><span style="color:#666666;margin-left:2px;">What do you want to call your nSquared page? e.g. "Gallery", "Inspiration", "Film Roll"</span>
					</td>
				</tr>
				<!-- nsq_slug // textbox -->
				<tr>
					<th scope="row">Slug</th>
					<td>
						<input type="text" size="57" name="nsquared_options[nsq_slug]" value="<?php echo $options['nsq_slug']; ?>" /><br /><span style="color:#666666;margin-left:2px;">The slug is the URL. example.com/your-slug</span>
					</td>
				</tr>

				<!-- nsq_thumbsize // dropdown -->
				<tr>
					<th scope="row">Thumbnail Size</th>
					<td>
						<select name='nsquared_options[nsq_thumbsize]'>
							<option value='150' <?php selected('150', $options['nsq_thumbsize']); ?>>150</option>
							<option value='175' <?php selected('175', $options['nsq_thumbsize']); ?>>175</option>
							<option value='200' <?php selected('200', $options['nsq_thumbsize']); ?>>200</option>
						</select>
						<span style="color:#666666;margin-left:2px;">Choose the thumbnail size for the plugin.</span>
					</td>
				</tr>

				<tr><td colspan="2"><div style="margin-top:10px;"></div></td></tr>
				<tr valign="top" style="border-top:#dddddd 1px solid;">
					<th scope="row">Database Options</th>
					<td>
						<label><input name="nsquared_options[chk_default_options_db]" type="checkbox" value="1" <?php if (isset($options['chk_default_options_db'])) { checked('1', $options['chk_default_options_db']); } ?> /> Restore defaults upon plugin deactivation/reactivation</label>
						<br /><span style="color:#666666;margin-left:2px;">Only check this if you want to reset plugin settings upon Plugin reactivation</span>
					</td>
				</tr>
			</table>
			<p class="submit">
			<input type="submit" class="button-primary" value="<?php _e('Save Changes') ?>" />
			</p>
		</form>

	<!-- include support information here -->

	</div>
	<?php	
}

function nsquared_validate_options($input) {
	 // strip html from textboxes
	$input['nsq_title'] =  wp_filter_nohtml_kses($input['nsq_title']);
	$input['nsq_slug'] =  wp_filter_nohtml_kses($input['nsq_slug']);
	return $input;
}



// add_action('admin_init', 'nquared_set_defaults' );
// add_action('admin_menu', 'nsquared_menu');

// // Menu
// function nsquared_menu(){
// 	add_options_page('nSquared Options', 'nSquared', 'manage_options', 'nsquared-options-menu', 'nsquared_options');

// }

// // Options
// function nsquared_options(){
// 	if(!current_user_can('manage_options') )  {
// 			wp_die( __( 'You do not have sufficient permissions to access this page.' ) );
// 	}
// }

// function nquared_set_defaults() {
// 	$tmp = get_option('nsquared_options');
//     if(($tmp['chk_default_options_db']=='1')||(!is_array($tmp))) {
// 		delete_option(_options'); // so we don't have to reset all the 'off' checkboxes too! (don't think this is needed but leave for now)
// 		$arr = array(	"chk_button1" => "1",
// 						"chk_button3" => "1",
// 						"nsq_title" => "This type of control allows a large amount of information to be entered all at once. Set the 'rows' and 'cols' attributes to set the width and height.",
// 						"textarea_two" => "This text area control uses the TinyMCE editor to make it super easy to add formatted content.",
// 						"textarea_three" => "Another TinyMCE editor! It is really easy now in WordPress 3.3 to add one or more instances of the built-in WP editor.",
// 						"txt_one" => "Enter whatever you like here..",
// 						"nsq_thumbsize" => "four",
// 						"chk_default_options_db" => "",
// 						"rdo_group_one" => "one",
// 						"rdo_group_two" => "two"
// 		);
// 		update_option('nsquared_options', $arr);
// 	}
// }


?>