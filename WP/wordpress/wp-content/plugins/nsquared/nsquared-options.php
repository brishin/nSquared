<?php

function nsquared_init(){
	add_settings_section('nsquared_plugin_options', 'nsquared opts', 'nsquard_render_form', __FILE__);
	add_settings_field('nsquared_options', 'All nSquared Options', 'nsquard_render_form', 'plugin', 'nsquared_plugin_options');
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

				<!-- exclude categories // Checkbox Buttons -->
				<tr valign="top">
					<th scope="row">Exclude Categories</th>
					<td>
						<?php $args=array(
	'orderby' => 'id',
	'order' => 'ASC');

$categories = get_categories($args);
foreach($categories as $category) { 
	$title = $category->name;
	$id = $category->cat_ID;
	echo '<label><input name="nsquared_options[chk_button1]" type="checkbox" value="1"';
	if (isset($options['chk_button1'])) { 
		checked('1', $options['chk_button1']); 
	}
	echo "/>" . $title . "</label><br />";


}
?> 
					</td>
				</tr>


				<!-- exclude tags // Checkbox Buttons -->
				<tr valign="top">
					<th scope="row">Exclude Tags</th>
					<td>
						<?php $args=array(
	'orderby' => 'id',
	'order' => 'ASC');

$tags = get_tags($args);
foreach($tags as $tag) { 
	$title = $tag->name;
	$id = $tag->term_id;
	echo '<label><input name="nsquared_options[chk_button1]" type="checkbox" value="1"';
	if (isset($options['chk_button1'])) { 
		checked('1', $options['chk_button1']); 
	}
	echo "/>" . $title . "</label><br />";


}
?> 
					</td>
				</tr>


				<!-- chk_default_options // checkbox -->
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
	global $wpdb;

	// strip html from textboxes
	$input['nsq_title'] =  wp_filter_nohtml_kses($input['nsq_title']);
	$input['nsq_slug'] =  wp_filter_nohtml_kses($input['nsq_slug']);

	$opts = get_option('nsquared_options');
	$page_id = get_option('nsquared_page_id');
	$updated_page['post_title'] = $input['nsq_title'];
	$updated_page['post_name'] = $input['nsq_slug'];
	$updated_page['ID'] = $page_id;
	
	$new_page_id = wp_update_post($updated_page);
	update_option('nsquared_page_id', $new_page_id);
	if(!($new_page_id==$page_id)){
		wp_delete_post($page_id, true);
		// makes sure id gets updated with right variable
		update_option('nsquared_page_id', $new_page_id);
	}
	return $input;
}

add_action('admin_init', 'nsquared_init' );
add_action('admin_menu', 'nsquared_add_options_page');


?>