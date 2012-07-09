<?php
/**
 * nrelate nSquared Settings
 *
 * @package nrelate
 * @subpackage Functions
 */


function options_init_nr_sq(){
	register_setting('nrelate_nsquared_options', 'nrelate_nsquared_options', 'nsquared_options_validate' );
	
	$options = get_option('nrelate_nsquared_options');
	
	// Display preview image

	if($options['nsquared_thumbnail']=="Thumbnails"){
		$divstyle = 'style="display:block;"';
	}else{
		$divstyle = 'style="display:none;"';
	}
	if(isset($options['nsquared_show_post_title']) && $options['nsquared_show_post_title']=='on'){
		$showpost_divstyle = 'style="display:block;"';
	}else{
		$showpost_divstyle = 'style="display:none;"';
	}
	if(isset($options['nsquared_show_post_excerpt']) && $options['nsquared_show_post_excerpt']=='on'){
		$showexcerpt_divstyle = 'style="display:block;"';
	}else{
		$showexcerpt_divstyle = 'style="display:none;"';
	}
	
	// Main Section
	// SQ
	add_settings_section('main_section', __('Main Settings','nrelate'), 'section_text_nr_sq' , __FILE__);
	add_settings_field('nsquared_save_preview_top','', 'nrelate_save_preview', __FILE__, 'main_section');
	// add_settings_field('nsquared_thumbnail', __('Would you like to display thumbnails with text, or text only','nrelate') . nrelate_tooltip('_thumbnail'), 'setting_thumbnail',__FILE__,'main_section');
	add_settings_field('nsquared_thumbnail_size', __('<div class="nr_image_option" '.$divstyle.'>Please choose a thumbnail size','nrelate') . nrelate_tooltip('_thumbnail_size') . '</div>', 'setting_thumbnail_size',__FILE__,'main_section');
	add_settings_field('nsquared_default_image', __('<div class="nr_image_option" '.$divstyle.'>Please provide a link to your default image: (This will show up when a nsquared post does not have a picture in it)<br/><i>For best results image should be as large (or larger) than the thumbnail size you chose above.</i>','nrelate'). nrelate_tooltip('_default_image')."</div>", 'setting_nsquared_default_image',__FILE__,'main_section');
	add_settings_field('nsquared_custom_field', __('<div class="nr_image_option" '.$divstyle.'>If you use <b>Custom Fields</b> for your images, nrelate can show them.</div>','nrelate'), 'setting_nsquared_custom_field',__FILE__,'main_section');
	add_settings_field('nsquared_title', __('Please enter a title for the nsquared content box','nrelate') . nrelate_tooltip('_title'), 'setting_string_nr_sq', __FILE__, 'main_section');
	// add_settings_field('nsquared_number_of_posts', __('<b>Maximum</b> number of posts to display from this site</br><em>To display multiple rows of thumbnails, choose more than will fit in one row.</em>','nrelate') . nrelate_tooltip('_number_of_posts'), 'setting_nsquared_number_of_posts_nr_sq', __FILE__, 'main_section');
	// add_settings_field('nsquared_bar', __('How relevant do you want the results to be?<br/><i>Based on the amount/type of content on your website, medium and high relevancy settings may return little or no posts.</i>','nrelate'), 'setting_nsquared_bar_nr_sq', __FILE__, 'main_section');
	// add_settings_field('nsquared_max_age', __('How deep into your archive would you like to go for nsquared posts?','nrelate') . nrelate_tooltip('_max_age'), 'setting_nsquared_max_age', __FILE__, 'main_section');
	add_settings_field('nsquared_exclude_cats', __('Exclude Categories from your nsquared content.','nrelate') . nrelate_tooltip('_exclude_cats'), 'nrelate_text_exclude_categories',__FILE__,'main_section');
	add_settings_field('nsquared_exclude_tags', __('Exclude Tags from your nsquared content.','nrelate') . nrelate_tooltip('_exclude_tags'), 'nrelate_text_exclude_tags',__FILE__,'main_section');
	// add_settings_field('nsquared_show_post_title', '<a name="nrelate_show_post_title"></a>'.__('Show Post Title?','nrelate') . nrelate_tooltip('_show_post_title'), 'setting_nsquared_show_post_title', __FILE__, 'main_section');
	add_settings_field('nsquared_max_chars_per_line', __('<div class="nr_showpost_option" '.$showpost_divstyle.'>Maximum number of characters for title?','nrelate') . nrelate_tooltip('_max_chars_per_line').'</div>', 'setting_nsquared_max_chars_per_line', __FILE__, 'main_section');
	add_settings_field('nsquared_show_post_excerpt', '<a name="nrelate_show_post_excerpt"></a>'.__('Show Post Excerpt?','nrelate') . nrelate_tooltip('_show_post_excerpt'), 'setting_nsquared_show_post_excerpt', __FILE__, 'main_section');
	add_settings_field('nsquared_max_chars_post_excerpt', __('<div class="nr_showexcerpt_option" '.$showexcerpt_divstyle.'>Maximum number of words for post excerpt?','nrelate') . nrelate_tooltip('_max_chars_post_excerpt').'</div>', 'setting_nsquared_max_chars_post_excerpt', __FILE__, 'main_section');
	add_settings_field('nrelate_save_preview','', 'nrelate_save_preview', __FILE__, 'main_section');
	
	//Partner Section
	// SQ not needed?
	// add_settings_section('partner_section',__('Partner Settings','nrelate'),'section_text_nr_sq_partner',__FILE__);
	// add_settings_field('nsquared_blogoption',__('Would you like to display nsquared content from any of your blogroll categories?','nrelate'), 'setting_nsquared_blogoption',__FILE__,'partner_section');
	// add_settings_field('nsquared_number_of_posts_ext',__('<b>Maximum</b> number of nsquared posts to display from this site\'s blogroll','nrelate'), 'setting_nsquared_number_of_posts_nr_sq_ext', __FILE__, 'partner_section');
	// add_settings_field('nrelate_save_preview','', 'nrelate_save_preview', __FILE__, 'partner_section');
	
	// Layout Section
	// SQ not needed? 
	// add_settings_section('layout_section',__('Layout Settings','nrelate'), 'section_text_nr_sq_layout', __FILE__);
	// add_settings_field('nsquared_where_to_show',__('Which pages should display nsquared content?'  . nrelate_tooltip('_where_to_show') . '<p>You can read about these options at the <a href="http://codex.wordpress.org/Conditional_Tags">WordPress Codex</a>','nrelate'), 'setting_nsquared_where_to_show', __FILE__, 'layout_section');
	// add_settings_field('nsquared_loc_top',__('Top of post <em>(Automatic)</em>' . nrelate_tooltip('_loc_top'),'nrelate'), 'setting_nsquared_loc_top', __FILE__, 'layout_section');
	// add_settings_field('nsquared_loc_bottom',__('Bottom of post <em>(Automatic)</em>' . nrelate_tooltip('_loc_bottom'),'nrelate'), 'setting_nsquared_loc_bottom', __FILE__, 'layout_section');
	// add_settings_field('nsquared_loc_widget',__('Widget area or Sidebar <em>(Automatic)</em>','nrelate'), 'nrelate_text_widget_page', __FILE__, 'layout_section');
	// add_settings_field('nsquared_loc_manual',__('<span id="loc_manual">Add to Theme <em>(Manual)</em><span>','nrelate'), 'setting_nsquared_manual', __FILE__, 'layout_section');
	// add_settings_field('nsquared_css_link',__('Change the Style','nrelate','nrelate'), 'setting_nsquared_css_link', __FILE__, 'layout_section');
	// add_settings_field('nsquared_display_logo',__('Would you like to support nrelate by displaying our logo?','nrelate'), 'setting_nsquared_display_logo', __FILE__, 'layout_section');
	add_settings_field('nrelate_save_preview','', 'nrelate_save_preview', __FILE__, 'layout_section');

	// Labs Section
	// add_settings_section('labs_section',__('nrelate Labs','nrelate'), 'nrelate_text_labs', __FILE__);
	// add_settings_field('nsquared_nonjs', __('Which nrelate version would you like to use?','nrelate'), 'setting_nsquared_nonjs', __FILE__, 'labs_section');
	
	
	// Reset Setting
	add_settings_section('reset_section',__('Reset Settings to Default','nrelate'), 'nrelate_text_reset', __FILE__);
	add_settings_field('nsquared_reset',__('Would you like to restore to defaults upon reactivation?','nrelate'), 'setting_reset_nr_sq', __FILE__, 'reset_section');
	add_settings_field('nrelate_save_preview','', 'nrelate_save_preview', __FILE__, 'reset_section');
	
}
add_action('admin_init', 'options_init_nr_sq' );


/****************************************************************
 ************************** Admin Sections ********************** 
*****************************************************************/

///////////////////////////
//   Main Settings
//////////////////////////
 
// Section description
function section_text_nr_sq() { nrelate_text_main(NRELATE_NSQUARED_NAME); }


// DROP-DOWN-BOX - Name: nrelate_nsquared_options[nsquared_number_of_posts]
// SQ fix this thing...
function setting_nsquared_number_of_posts_nr_sq() {
	$options = get_option('nrelate_nsquared_options');
	$items = array("0","1", "2", "3", "4", "5", "6", "7", "8", "9", "10");
	echo "<select id='nsquared_number_of_posts' name='nrelate_nsquared_options[nsquared_number_of_posts]'>";
	foreach($items as $item) {
		$selected = ($options['nsquared_number_of_posts']==$item) ? 'selected="selected"' : '';
		echo "<option value='$item' $selected>$item</option>";
	}
	echo "</select>";
}

// DROP-DOWN-BOX - Name: nrelate_nsquared_options[nsquared_bar]
// function  setting_nsquared_bar_nr_sq() {
// 	$options = get_option('nrelate_nsquared_options');
// 	$items = array ("Low", "Medium", "High");
// 	$itemval = array ("Low" => __("Low (recommended)",'nrelate'), "Medium" => __("Medium",'nrelate'), "High" => __("High",'nrelate'));
// 	echo "<select id='nsquared_bar' name='nrelate_nsquared_options[nsquared_bar]'>";
// 	foreach($items as $item) {
// 		$selected = ($options['nsquared_bar']==$item) ? 'selected="selected"' : '';
// 		echo "<option value='$item' $selected>$itemval[$item]</option>";
// 	}
// 	echo "</select>";
// }

// TEXTBOX - Name: nrelate_nsquared_options[nsquared_title]
function setting_string_nr_sq() {
	$options = get_option('nrelate_nsquared_options');
	$r_title = stripslashes(stripslashes($options['nsquared_title']));
	$r_title = htmlspecialchars($r_title);
	echo '<input id="nsquared_title" name="nrelate_nsquared_options[nsquared_title]" size="40" type="text" value="'.$r_title.'" />';
}


// TEXTBOX / DROPDOWN - Name: nrelate_nsquared_options[nsquared_max_age]
// function setting_nsquared_max_age() {
// 	$options_num = get_option('nrelate_nsquared_options');
// 	$options_frame = get_option('nrelate_nsquared_options');
// 	$items = array(
// 		"Hour(s)" => __("Hour(s)","nrelate"),
// 		"Day(s)" => __("Day(s)","nrelate"),
// 		"Week(s)" => __("Week(s)","nrelate"),
// 		"Month(s)" => __("Month(s)","nrelate"),
// 		"Year(s)" => __("Year(s)","nrelate")
// 	);
// 	echo "<input id='nsquared_max_age_num' name='nrelate_nsquared_options[nsquared_max_age_num]' size='4' type='text' value='{$options_num['nsquared_max_age_num']}' />";
	
// 	echo "<select id='nsquared_max_age_frame' name='nrelate_nsquared_options[nsquared_max_age_frame]'>";
// 	foreach($items as $type => $item) {
// 		$selected = ($options_frame['nsquared_max_age_frame']==$item) ? 'selected="selected"' : '';
// 		echo "<option value='$type' $selected>$item</option>";
// 	}
// 		echo "</select>";
// }

// // CHECKBOX - Show Post Title
// function setting_nsquared_show_post_title(){
// 	$options = get_option('nrelate_nsquared_options');
// 	$checked = (isset($options['nsquared_show_post_title']) && $options['nsquared_show_post_title']=='on') ? ' checked="checked" ' : '';
// 	echo "<input ".$checked." id='nsquared_show_post_title' name='nrelate_nsquared_options[nsquared_show_post_title]' type='checkbox' onclick=\"if(this.checked){jQuery('.nr_showpost_option').show('slow');}else{jQuery('.nr_showpost_option').hide('slow');}\"/>";
// }

// TEXTBOX - Characters for Post Title
function setting_nsquared_max_chars_per_line() {
	$options = get_option('nrelate_nsquared_options');
	if(isset($options['nsquared_show_post_title']) && $options['nsquared_show_post_title']=='on'){
		$showpost_divstyle = 'style="display:block;"';
	}else{
		$showpost_divstyle = 'style="display:none;"';
	}
	echo "<div class='nr_showpost_option' ".$showpost_divstyle."><input class='nr_showpost_option' id='nsquared_max_chars_per_line' name='nrelate_nsquared_options[nsquared_max_chars_per_line]' size='4' type='text' value='{$options['nsquared_max_chars_per_line']}' /></div>";
}

// CHECKBOX - Show Post Excerpt
function setting_nsquared_show_post_excerpt(){
	$options = get_option('nrelate_nsquared_options');
	$checked = (isset($options['nsquared_show_post_excerpt']) && $options['nsquared_show_post_excerpt']=='on') ? ' checked="checked" ' : '';
	echo "<input ".$checked." id='nsquared_show_post_excerpt' name='nrelate_nsquared_options[nsquared_show_post_excerpt]' type='checkbox' onclick=\"if(this.checked){jQuery('.nr_showexcerpt_option').show('slow');}else{jQuery('.nr_showexcerpt_option').hide('slow');}\"/>";
}


// TEXTBOX - Characters for Post Excerpt
function setting_nsquared_max_chars_post_excerpt() {
	$options = get_option('nrelate_nsquared_options');
	if(isset($options['nsquared_show_post_excerpt']) && $options['nsquared_show_post_excerpt']=='on'){
		$showexcerpt_divstyle = 'style="display:block;"';
	}else{
		$showexcerpt_divstyle = 'style="display:none;"';
	}
	echo "<div class='nr_showexcerpt_option' ".$showexcerpt_divstyle."><input class='nr_showexcerpt_option' id='nsquared_max_chars_post_excerpt' name='nrelate_nsquared_options[nsquared_max_chars_post_excerpt]' size='4' type='text' value='{$options['nsquared_max_chars_post_excerpt']}' /></div>";
}


// CHECKBOX - Name: nrelate_nsquared_options[nsquared_reset]
function setting_reset_nr_sq() {
	$options = get_option('nrelate_nsquared_options');
	$checked = (isset($options['nsquared_reset']) && $options['nsquared_reset'] == 'on') ? ' checked="checked" ' : '';
	echo "<input ".$checked." id='plugin_nsquared_reset' name='nrelate_nsquared_options[nsquared_reset]' type='checkbox' />";
}

///////////////////////////
//   Partner Settings
//////////////////////////

// // Section description
// function section_text_nr_sq_partner() { nrelate_text_partner(NRELATE_NSQUARED_NAME); }

// // CHECKBOX-LIST - Name: nrelate_nsquared_options[nsquared_blogoption]
// function setting_nsquared_blogoption() {
// 	$options = get_option('nrelate_nsquared_options');
	
// 	$taxonomy = 'link_category';
// 	$tax = get_taxonomy($taxonomy);
// 	$link_categories = (array) get_terms($taxonomy, array('get' => 'all'));

// 	$args = array(
// 		'taxonomy' => $taxonomy,
// 		'selected_cats' => is_array($options['nsquared_blogoption']) ? $options['nsquared_blogoption'] : array(), 
// 		'name' => "nrelate_nsquared_options[nsquared_blogoption]"
// 	);
// 	$walker = new nrelate_Walker_Category_Checklist();
	
// 	echo "<div id='nrelate-blogroll-categories' class='categorydiv'><ul id='blogroll-categorychecklist' class='list:category categorychecklist form-no-clear'>";
// 	echo call_user_func_array(array(&$walker, 'walk'), array($link_categories, 0, $args));
// 	echo "</ul></div>";
	
// 	// Ajax calls to contact nrelate servers and update as necessary
// 	echo "<div id='bloglinks'></div>";
// 	echo '<script type="text/javascript"> checkblog(\''.NRELATE_NSQUARED_SETTINGS_URL.'\',\''.NRELATE_API_URL.'\',\''.NRELATE_BLOG_ROOT.'\',\''.NRELATE_NSQUARED_ADMIN_VERSION.'\'); </script>';

// }

// // DROP-DOWN-BOX - Name: nrelate_nsquared_options[nsquared_number_of_posts_ext]
// // Number of posts from external sites
// function setting_nsquared_number_of_posts_nr_sq_ext(){
// 	$options = get_option('nrelate_nsquared_options');
// 	$items = array("1", "2", "3", "4", "5", "6", "7", "8", "9", "10");
// 	echo "<div id='blogrollnumber'><select id='nsquared_number_of_posts_ext' name='nrelate_nsquared_options[nsquared_number_of_posts_ext]'>";
// 	foreach($items as $item) {
// 		$selected = ($options['nsquared_number_of_posts_ext']==$item) ? 'selected="selected"' : '';
// 		echo "<option value='$item' $selected>$item</option>";
// 	}
// 	echo "</select></div>";
// }

///////////////////////////
//   Layout Settings
//////////////////////////

// // Section description
// function section_text_nr_sq_layout() { nrelate_text_layout(NRELATE_NSQUARED_NAME); }

// // CHECKBOX LIST - Where to show nsquared content
// function setting_nsquared_where_to_show(){
// 	global $nrelate_cond_tags;
// 	$options = get_option('nrelate_nsquared_options');
	
// 	$args = array('taxonomy' => 'category', 'value_field' => 'check_val');
// 	$args['selected_cats'] = is_array(isset($options['nsquared_where_to_show']) ? $options['nsquared_where_to_show'] : null) ? $options['nsquared_where_to_show'] : array();
// 	$args['name'] = 'nrelate_nsquared_options[nsquared_where_to_show]';
	
// 	echo '<div id="nrelate-where-to-show" class="categorydiv"><ul id="categorychecklist" class="list:category categorychecklist form-no-clear">';
// 	$walker = new nrelate_Walker_Category_Checklist();
// 	echo call_user_func_array(array(&$walker, 'walk'), array($nrelate_cond_tags, 0, $args));
	
// 	echo '</ul></div>';
	
// 	nrelate_where_to_show_check();
// }

// // CHECKBOX - Location Post Top
// function setting_nsquared_loc_top(){
// 	$options = get_option('nrelate_nsquared_options');
// 	$checked = (isset($options['nsquared_loc_top']) && $options['nsquared_loc_top']=='on') ? ' checked="checked" ' : '';
// 	echo "<input ".$checked." id='nsquared_loc_top' name='nrelate_nsquared_options[nsquared_loc_top]' type='checkbox'/>";
// }

// // CHECKBOX - Location Post Bottom
// function setting_nsquared_loc_bottom(){
// 	$options = get_option('nrelate_nsquared_options');
// 	$checked = (isset($options['nsquared_loc_bottom']) && $options['nsquared_loc_bottom']=='on') ? ' checked="checked" ' : '';
// 	echo "<input ".$checked." id='nsquared_loc_bottom' name='nrelate_nsquared_options[nsquared_loc_bottom]' type='checkbox' />";
// }

// // TEXT ONLY - no options
// function setting_nsquared_manual(){
// 	_e("Add this code anywhere in your theme to show nsquared content:","nrelate"); echo"<br><b>&lt;?php if (function_exists('nrelate_nsquared')) nrelate_nsquared(); ?&gt;</b>";
// }

// // TEXT ONLY - no options
// function setting_nsquared_css_link(){
// 	echo '<a href="admin.php?page=nrelate-nsquared&tab=styles">';	
// 	_e("Choose a style from our Style Gallery","nrelate");
// 	echo '</a>';
// }

// // CHECKBOX - Show nrelate logo
// function setting_nsquared_display_logo(){
// 	$options = get_option('nrelate_nsquared_options');
// 	$checked = (isset($options['nsquared_display_logo']) && $options['nsquared_display_logo']=='on') ? ' checked="checked" ' : '';
// 	echo "<input ".$checked." id='show_logo' name='nrelate_nsquared_options[nsquared_display_logo]' type='checkbox' />";
// }

// DROPDOWN - Name: nrelate_nsquared_options[nsquared_thumbnail]
// function setting_thumbnail() {
// 	$options = get_option('nrelate_nsquared_options');
// 	$items = array('Thumbnails'=>__("Thumbnails","nrelate"), 'Text'=>__("Text","nrelate"));
// 	echo "<select id='nsquared_thumbnail' name='nrelate_nsquared_options[nsquared_thumbnail]' onChange='nrelate_showhide_thumbnail(\"nsquared_thumbnail\");'>";
	/*?><select id='nsquared_thumbnail' name='nrelate_nsquared_options[nsquared_thumbnail]'>;
	<?php*/
// 	foreach($items as $type => $item) {
// 		$selected = ($options['nsquared_thumbnail']==$type) ? 'selected="selected"' : '';
// 		echo "<option value='".$type."' ".$selected.">".$item."</option>";
// 	}
// 	echo "</select>";
// }

// RADIO - Name: nrelate_nsquared_options[nsquared_thumbnail_size]
function setting_thumbnail_size(){
	$options = get_option('nrelate_nsquared_options');
	
	// if($options['nsquared_thumbnail']=="Thumbnails"){
		$divstyle = "style='display:block;'";
	// }
	// else{
	// 	$divstyle = "style='display:none;'";
	// }
	
	echo "<div id='imagesizepreview' class='nr_image_option' ".$divstyle.">";
	$sizes = array(140,150,200);
	// SQ add 200 - 250
	echo "<select id='nsquared_thumbnail_size' name='nrelate_nsquared_options[nsquared_thumbnail_size]' onChange='document.getElementById(\"nsquared_thumbnail_image\").src=\"". NRELATE_ADMIN_IMAGES ."/thumbnails/preview_cloud_\"+this.value+\".jpeg\";'>";
	foreach ($sizes as $size){
		$selected = ($options['nsquared_thumbnail_size']==$size) ? 'selected="selected"' : '';
		echo "<option value='".$size."' ".$selected.">".$size."</option>";
	}
	echo "</select><div class='thumbnail_wrapper' style='height:160px;'><img id='nsquared_thumbnail_image' src='" . NRELATE_ADMIN_IMAGES . "/thumbnails/preview_cloud_" .$options['nsquared_thumbnail_size'].".jpeg' /></div>";
}

// TEXTBOX - Name: nrelate_nsquared_options[nsquared_thumbnail]
//show picture and give ability to change picture
function setting_nsquared_default_image(){
	
	$options = get_option('nrelate_nsquared_options');
	// Display preview image
	// if($options['nsquared_thumbnail']=="Thumbnails"){
		$divstyle = "style='display:block;'";
	// }
	// else{
	// 	$divstyle = "style='display:none;'";
	// }
	echo "<div class='nr_image_option' ".$divstyle.">";
	$imageurl = stripslashes(stripslashes($options['nsquared_default_image']));
	$imageurl = htmlspecialchars($imageurl);
	
	// Check if $imageurl is an empty string
	if($imageurl==""){
		_e("No default image chosen, until you provide your default image, nrelate will use <a class=\"thickbox\" href='http://img.nrelate.com/nsq_wp/".NRELATE_NSQUARED_PLUGIN_VERSION."/defaultImages.html?KeepThis=true&TB_iframe=true&height=400&width=600' target='_blank'>these images</a>.<BR>","nrelate");
	}
	else{
		
		$body=array(
			'link'=>$imageurl,
			'domain'=>NRELATE_BLOG_ROOT
		);
		$url = 'http://api.nrelate.com/common_wp/'.NRELATE_NSQUARED_ADMIN_VERSION.'/thumbimagecheck.php';
		
		$result = wp_remote_post($url, array('body'=>$body, 'timeout'=>10));


		$imageurl_cached=!is_wp_error($result) ? $result['body'] : null;
		if ($imageurl_cached) {
			echo "Current default image: &nbsp &nbsp";
			//$imageurl = htmlspecialchars(stripslashes($imageurl));
			$imagecall = '<img id="imgupload" style="outline: 1px solid #DDDDDD;  width:'.$options['nsquared_thumbnail_size'].'px; height:'.$options['nsquared_thumbnail_size'].'px;" src="'.$imageurl_cached.'" alt="No default image chosen" /><br><br>';
			echo $imagecall;
		}
	}
	// User can input an image url
	_e("Enter the link to your default image (include http://): <br>");
	echo '<input type="text" size="60" id="nsquared_default_image" name="nrelate_nsquared_options[nsquared_default_image]" value="'.$imageurl.'"></div>';
}


// TEXTBOX - Name: nrelate_nsquared_options[nsquared_custom_field]
function setting_nsquared_custom_field() {
	$options = get_option('nrelate_nsquared_options');
	// Display preview image
	// if($options['nsquared_thumbnail']=="Thumbnails"){
		$divstyle = "style='display:block;'";
	// }
	// else{
	// 	$divstyle = "style='display:none;'";
	// }
		
	nrelate_text_custom_fields( $divstyle );
	echo "<script type='text/javascript'> nrelate_showhide_thumbnail('nsquared_thumbnail');</script>";
}

// ///////////////////////////
// //   nrelate Labs
// //////////////////////////

// // Radio - Use Non js: nonjs=1, js=0
// function setting_nsquared_nonjs(){
// 	$options = get_option('nrelate_nsquared_options');
// 	$values=array("js","nonjs");
// 	$valuedescription = array ("js" => __("<strong>Javascript:</strong> Stable and fast",'nrelate'), "nonjs" => __("<strong>No Javascript:</strong> BETA VERSION: Allows search engines to index our plugin and may help your SEO.",'nrelate')); 
// 	$i=0;
// 	foreach($values as $value){
// 		$checked = (isset($options['nsquared_nonjs']) && $options['nsquared_nonjs']==$i) ? ' checked="checked" ' : '';
// 		echo "<label for='nsquared_nonjs_".$i."'><input ".$checked." id='nsquared_nonjs_".$i."' name='nrelate_nsquared_options[nsquared_nonjs]' value='$i' type='radio'/>  ".$valuedescription[$value]."</label><br/>";
// 		$i+=1;
// 	}
// }

/****************************************************************
 ******************** Build the Admin Page ********************** 
*****************************************************************/

function nrelate_nsquared_do_page() {

	// nnsquared option loaded from wp db
	$options = get_option('nrelate_nsquared_options');
	// $ad_options = get_option('nrelate_nsquared_options_ads');
	$style_options = get_option('nrelate_nsquared_options_styles');
?>
	
	<?php nrelate_nsquared_settings_header(); ?>
    <script type="text/javascript">
		//<![CDATA[
		var nr_plugin_settings_url = '<?php echo NRELATE_NSQUARED_SETTINGS_URL; ?>';
		var nr_plugin_domain = '<?php echo NRELATE_BLOG_ROOT ?>';
		var nr_plugin_version = '<?php echo NRELATE_NSQUARED_PLUGIN_VERSION ?>';
		//]]>
    </script>
		<form name="settings" action="options.php" method="post" enctype="multipart/form-action">
      <div class="nrelate-hidden">
<!--       <input type="checkbox" id="show_ad" <?php echo empty($ad_options['nsquared_display_ad']) ? '' : 'checked="checked"'; ?> value="on" />
      <input type="hidden" id="nsquared_number_of_ads" value="<?php echo isset($ad_options['nsquared_number_of_ads']) ? $ad_options['nsquared_number_of_ads'] : ''; ?>" />
      <input type="hidden" id="nsquared_ad_placement" value="<?php echo isset($ad_options['nsquared_ad_placement']) ? $ad_options['nsquared_ad_placement'] : ''; ?>" />
      <input type="hidden" id="nsquared_ad_title" value="<?php echo isset($ad_options['nsquared_ad_title']) ? $ad_options['nsquared_ad_title'] : ''; ?>" />
      <input type="checkbox" id="ad_animation" value="on" <?php echo empty($ad_options['nsquared_ad_animation']) ? '' : ' checked="checked" '; ?> />
 -->      <input type="hidden" id="nsquared_imagestyle" value="<?php echo $style_options['nsquared_thumbnails_style']; ?>" />
      <input type="hidden" id="nsquared_textstyle" value="<?php echo $style_options['nsquared_text_style']; ?>" />
	  <!-- <input type="hidden" id="nsquared_blogoption" value="<?php echo ( is_array($options['nsquared_blogoption']) && count($options['nsquared_blogoption'] > 0) ) ? 1 : 0; ?>" /> -->
      </div>
     
			<?php settings_fields('nrelate_nsquared_options'); ?>
			<?php do_settings_sections(__FILE__);?>
		</form>
    <script type="text/javascript">
		//<![CDATA[
		jQuery(document).ready(function($){
			$('.nrelate_preview_button').click(function(event){
				event.preventDefault();
				$(this).parents('form:first').find('.nrelate_disabled_preview span').hide();
				if ($('#nsquared_thumbnail').val()=='Thumbnails') {
					if ($('#nsquared_imagestyle').val()=='none') { $(this).parents('td:first').find('.thumbnails_message:first').show(); return; }
				} else {
					if ($('#nsquared_textstyle').val()=='none') { $(this).parents('td:first').find('.text_message:first').show(); return; }
				}
				
				if ($('#nsquared_thumbnail').val()=='Text') {
					if (!$('#nsquared_show_post_title').is(':checked') && !$('#nsquared_show_post_excerpt').is(':checked')) {
						$(this).parents('td:first').find('.text-warning-message:first').show();
						setTimeout('tb_remove()', 50);
						return;
					}
				}
			});
			
			$('#nsquared_thumbnail').change(function(){
				$(this).parents('form:first').find('.nrelate_disabled_preview span').hide();
			});
			
			$('input.button-primary[name="Submit"]').click(function(event){
				$(this).parents('form:first').find('.nrelate_disabled_preview span').hide();
				
				if ($('#nsquared_thumbnail').val()=='Thumbnails') return;
				if ($('#nsquared_show_post_title').is(':checked')) return;
				if ($('#nsquared_show_post_excerpt').is(':checked')) return;
				event.preventDefault();
				event.stopPropagation();
				$(this).parents('td:first').find('.text-warning-message:first').show();
			});
		});
		//]]>
    </script>
	</div>
<?php
	
	update_nrelate_data_sq();
}

// Loads all of the nrelate_nsquared_options from wp database
// Makes necessary conversion for some parameters.
// Sends nrelate_nsquared_options entries, rss feed mode, and wordpress home url to the nrelate server
// Returns Success if connection status is "200". Returns error if not "200"
function update_nrelate_data_sq(){
	
	// Get nrelate_nsquared options from wordpress database
	$option = get_option('nrelate_nsquared_options');
	$number = urlencode($option['nsquared_number_of_posts']);
	// $r_bar = $option['nsquared_bar'];
	$r_title = urlencode($option['nsquared_title']);
	// $r_max_age = $option['nsquared_max_age_num'];
	// $r_max_frame = $option['nsquared_max_age_frame'];
	$r_show_post_title = empty($option['nsquared_show_post_title']) ? false : true;
	// $r_max_char_per_line = $option['nsquared_max_chars_per_line'];
	// $r_show_post_excerpt = empty($option['nsquared_show_post_excerpt']) ? false : true;
	// $r_max_char_post_excerpt = $option['nsquared_max_chars_post_excerpt'];
	// $r_display_logo = empty($option['nsquared_display_logo']) ? false : true;
	$r_nsquared_reset = $option['nsquared_reset'];
	// $nsquared_blogoption = $option['nsquared_blogoption'];
	$nsquared_thumbnail = $option['nsquared_thumbnail'];
	$backfill = $option['nsquared_default_image'];
	// $number_ext = $option['nsquared_number_of_posts_ext'];
	$nsquared_thumbnail_size = $option['nsquared_thumbnail_size'];
	// $nsquared_loc_top = isset($option['nsquared_loc_top']) ? $option['nsquared_loc_top'] : null;
	// $nsquared_loc_bot = isset($option['nsquared_loc_bottom']) ? $option['nsquared_loc_bottom'] : null;
	// $nsquared_nonjs = isset($option['nsquared_nonjs']) ? $option['nsquared_nonjs'] : null;
	
	// $nsquared_layout= '';
	// if ($nsquared_loc_top=='on') {
	// 	$nsquared_layout.='(TOP)';
	// }
	// if ($nsquared_loc_bot=='on') {
	// 	$nsquared_layout.='(BOT)';
	// }
	
	// // Convert max age time frame to minutes
	// switch ($r_max_frame){
	// case 'Hour(s)':
	// 	$maxageposts = $r_max_age * 60;
	// 	break;
	// case 'Day(s)':
	// 	$maxageposts = $r_max_age * 1440;
	// 	break;
	// case 'Week(s)':
	// 	$maxageposts = $r_max_age * 10080;
	// 	break;
	// case 'Month(s)':
	// 	$maxageposts = $r_max_age * 44640;
	// 	break;
	// case 'Year(s)':
	// 	$maxageposts = $r_max_age * 525600;
	// 	break;
	// }
	
	// // Convert show post title parameter
	// $r_show_post_title=($r_show_post_title)?1:0;

	// // Convert show post excerpt parametet
	// $r_show_post_excerpt=($r_show_post_excerpt)?1:0;
	
	// // Convert logo parameter
	// $logo=($r_display_logo)?1:0;
	
	// // Convert blogroll option parameter
	// $blogroll=( is_array($nsquared_blogoption) && count($nsquared_blogoption) > 0 )?1:0;
	
	
	// Convert thumbnail option parameter
	switch ($nsquared_thumbnail){
	case 'Thumbnails':
		$thumb = 1;
	  break;
	default:
		$thumb = 0;
	}
	
	// // Get the wordpress root url and the wordpress rss url.
	// $bloglist = nrelate_get_blogroll();

	// Write the parameters to be sent
	$body=array(
		'DOMAIN'=>NRELATE_BLOG_ROOT,
		'VERSION'=>NRELATE_NSQUARED_PLUGIN_VERSION,
		'KEY'=>	get_option('nrelate_key'),
		'NUM'=>$number,
		// 'NUMEXT'=>$number_ext,
		// 'R_BAR'=>$r_bar,
		'HDR'=>$r_title,
		// 'BLOGOPT'=>$blogroll,
		// 'BLOGLI'=>$bloglist,
		// 'MAXPOST'=>$maxageposts,
		'SHOWPOSTTITLE'=>$r_show_post_title,
		// 'MAXCHAR'=>$r_max_char_per_line,
		// 'SHOWEXCERPT'=>$r_show_post_excerpt,
		// 'MAXCHAREXCERPT'=>$r_max_char_post_excerpt,
		'THUMB'=>$thumb,
		// 'LOGO'=>$logo,
		'IMAGEURL'=>$backfill,
		'THUMBSIZE'=>$nsquared_thumbnail_size,
		// 'LAYOUT'=>$nsquared_layout,
		// 'NONJS'=>$nsquared_nonjs
	);
	$url = 'http://api.nrelate.com/nsq_wp/'.NRELATE_NSQUARED_PLUGIN_VERSION.'/processWPnsquared.php';
	
	$result = wp_remote_post( $url, array('body'=>$body,'blocking'=>false,'timeout'=>15));

}


// Validate user data for some/all of our input fields
function nsquared_options_validate($input) {
	// Check our textbox option field contains no HTML tags - if so strip them out
	$input['nsquared_title'] =  wp_filter_nohtml_kses($input['nsquared_title']);
	// if(!is_numeric($input['nsquared_max_chars_per_line'])){
	// 	$input['nsquared_max_chars_per_line']=100;
	// }
	// if(!is_numeric($input['nsquared_max_age_num'])){
	// 	$input['nsquared_max_age_num']=2;
	// }
	
	// Like escape all text fields
	$input['nsquared_default_image'] = like_escape($input['nsquared_default_image']);
	$input['nsquared_title'] = like_escape($input['nsquared_title']);
	// Add slashes to all text fields
	$input['nsquared_default_image'] = esc_sql($input['nsquared_default_image']);
	$input['nsquared_title'] = esc_sql($input['nsquared_title']);
	
	$input['nsquared_version'] = NRELATE_NSQUARED_PLUGIN_VERSION;
	
	// Make sure that unchecked checkboxes are stored as empty strings
	global $nr_sq_std_options;
	$options = array_keys($nr_sq_std_options);
	$values = array_fill(0, count($options), '');
	$empty_settings_array = array_combine($options, $values);
	
	$input = wp_parse_args( $input, $empty_settings_array );
	
	return $input; // return validated input
}
?>