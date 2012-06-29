<?php
/**
 * nrelate Most Popular Settings
 *
 * @package nrelate
 * @subpackage Functions
 */
 

function options_init_nr_mp(){
	register_setting('nrelate_popular_options', 'nrelate_popular_options', 'popular_options_validate' );
	
	$options = get_option('nrelate_popular_options');
	// Display preview image
	if($options['popular_thumbnail']=="Thumbnails"){
		$divstyle = 'style="display:block;"';
	}
	else{
		$divstyle = 'style="display:none;"';
	}
	if(isset($options['popular_show_post_title']) && $options['popular_show_post_title']=='on'){
		$showpost_divstyle = 'style="display:block;"';
	}else{
		$showpost_divstyle = 'style="display:none;"';
	}
	if(isset($options['popular_show_post_excerpt']) && $options['popular_show_post_excerpt']=='on'){
		$showexcerpt_divstyle = 'style="display:block;"';
	}else{
		$showexcerpt_divstyle = 'style="display:none;"';
	}
	if(isset($options['popular_showviews']) && $options['popular_showviews']=='on'){
		$showview_divstyle = 'style="display:block;"';
	}else{
		$showview_divstyle = 'style="display:none;"';
	}
	
	// Main Section
	add_settings_section('main_section', __('Main Settings','nrelate'), 'section_text_nr_mp', __FILE__);
	add_settings_field('popular_save_preview_top','', 'nrelate_save_preview', __FILE__, 'main_section');
	add_settings_field('popular_thumbnail', __('Would you like to display thumbnails with text, or text only','nrelate') . nrelate_tooltip('_thumbnail'), 'setting_popular_thumbnail',__FILE__,'main_section');
	add_settings_field('popular_thumbnail_size', __('<div class="nr_image_option" '.$divstyle.'>Please choose a thumbnail size','nrelate')  . nrelate_tooltip('_thumbnail_size') . '</div>', 'setting_popular_thumbnail_size',__FILE__,'main_section');
	add_settings_field('popular_default_image', __('<div class="nr_image_option" '.$divstyle.'>Please provide a link to your default image: (This will show up when a popular post does not have a picture in it)<br/><i>For best results image should be as large (or larger) than the thumbnail size you chose above.</i>'. nrelate_tooltip('_default_image').'</div>','nrelate'), 'setting_popular_default_image',__FILE__,'main_section');
	add_settings_field('popular_custom_field', __('<div class="nr_image_option" '.$divstyle.'>If you use <b>Custom Fields</b> for your images, nrelate can show them.</div>','nrelate'), 'setting_popular_custom_field',__FILE__,'main_section');
	add_settings_field('popular_title', __('Please enter a title for the popular content box','nrelate'). nrelate_tooltip('_title'), 'setting_string_nr_mp', __FILE__, 'main_section');
	add_settings_field('popular_number_of_posts', __('<b>Maximum</b> number of popular posts to display from this site','nrelate'). nrelate_tooltip('_number_of_posts'), 'setting_popular_number_of_posts_nr_mp', __FILE__, 'main_section');
	add_settings_field('popular_max_age', __('Show the most popular posts for the selected time period.<br/>(note: we can only begin tracking from your install date) ','nrelate'). nrelate_tooltip('_mp_max_age'), 'setting_popular_max_age', __FILE__, 'main_section');
	add_settings_field('popular_exclude_cats', __('Exclude Categories from your popular content.','nrelate') . nrelate_tooltip('_exclude_cats'), 'nrelate_text_exclude_categories',__FILE__,'main_section');
	add_settings_field('popular_show_post_title', '<a name="nrelate_show_post_title"></a>'.__('Show Post Title?','nrelate'). nrelate_tooltip('_show_post_title'), 'setting_popular_show_post_title', __FILE__, 'main_section');
	add_settings_field('popular_max_chars_per_line', __('<div class="nr_showpost_option" '.$showpost_divstyle.'>Maximum number of characters for title?','nrelate'). nrelate_tooltip('_max_chars_per_line').'</div>', 'setting_popular_max_chars_per_line', __FILE__, 'main_section');
	add_settings_field('popular_show_post_excerpt', '<a name="nrelate_show_post_excerpt"></a>'.__('Show Post Excerpt?','nrelate'). nrelate_tooltip('_show_post_excerpt'), 'setting_popular_show_post_excerpt', __FILE__, 'main_section');
	add_settings_field('popular_max_chars_post_excerpt', __('<div class="nr_showexcerpt_option" '.$showexcerpt_divstyle.'>Maximum number of words for post excerpt?','nrelate') . nrelate_tooltip('_max_chars_post_excerpt').'</div>', 'setting_popular_max_chars_post_excerpt', __FILE__, 'main_section');
	add_settings_field('popular_showviews', __('Show View Count?','nrelate'), 'setting_show_views', __FILE__, 'main_section');
	add_settings_field('popular_view', __('<div class="nr_showview_option" '.$showview_divstyle.'>Please enter a title for the "views" box','nrelate').'</div>', 'setting_views', __FILE__, 'main_section');
	add_settings_field('nrelate_save_preview','', 'nrelate_save_preview', __FILE__, 'main_section');
	
	// Layout Section
	add_settings_section('layout_section',__('Layout Settings','nrelate'), 'section_text_nr_mp_layout', __FILE__);
	add_settings_field('popular_where_to_show',__('Which pages should display popular content?' . nrelate_tooltip('_where_to_show') . '<p>You can read about these options at the <a href="http://codex.wordpress.org/Conditional_Tags">WordPress Codex</a>','nrelate'), 'setting_popular_where_to_show', __FILE__, 'layout_section');
	add_settings_field('popular_loc_top',__('Top of post <em>(Automatic)</em>' . nrelate_tooltip('_loc_top'),'nrelate'), 'setting_popular_loc_top', __FILE__, 'layout_section');
	add_settings_field('popular_loc_bottom',__('Bottom of post <em>(Automatic)</em>' . nrelate_tooltip('_loc_bottom'),'nrelate'), 'setting_popular_loc_bottom', __FILE__, 'layout_section');
    add_settings_field('popular_loc_widget',__('Widget area or Sidebar <em>(Automatic)</em>','nrelate'), 'nrelate_text_widget_page', __FILE__, 'layout_section');
	add_settings_field('popular_loc_manual',__('<span id="loc_manual">Add to Theme <em>(Manual)</em></span>','nrelate'), 'setting_popular_manual', __FILE__, 'layout_section');
	add_settings_field('popular_css_link',__('Change the Style','nrelate','nrelate'), 'setting_popular_css_link', __FILE__, 'layout_section');
	add_settings_field('popular_display_logo',__('Would you like to support nrelate by displaying our logo?','nrelate'), 'setting_popular_display_logo', __FILE__, 'layout_section');
	add_settings_field('nrelate_save_preview','', 'nrelate_save_preview', __FILE__, 'layout_section');

	// Labs Section
	add_settings_section('labs_section',__('nrelate Labs','nrelate'), 'nrelate_text_labs', __FILE__);
	add_settings_field('popular_nonjs', __('Which nrelate version would you like to use?','nrelate'), 'setting_popular_nonjs', __FILE__, 'labs_section');
	

	// Reset Setting
	add_settings_section('reset_section',__('Reset Settings to Default','nrelate'), 'nrelate_text_reset', __FILE__);
	add_settings_field('popular_reset',__('Would you like to restore to defaults upon reactivation?','nrelate'), 'setting_reset_nr_mp', __FILE__, 'reset_section');
	add_settings_field('nrelate_save_preview','', 'nrelate_save_preview', __FILE__, 'reset_section');
}
add_action('admin_init', 'options_init_nr_mp' );


/****************************************************************
 ************************** Admin Sections ********************** 
*****************************************************************/

///////////////////////////
//   Main Settings
//////////////////////////
 
// Section description
function section_text_nr_mp() { nrelate_text_main(NRELATE_POPULAR_NAME); }

// DROP-DOWN-BOX - Name: nrelate_popular_options[popular_number_of_posts]
function setting_popular_number_of_posts_nr_mp() {
	$options = get_option('nrelate_popular_options');
	$items = array("0","1", "2", "3", "4", "5", "6", "7", "8", "9", "10");
	echo "<select id='popular_number_of_posts' name='nrelate_popular_options[popular_number_of_posts]'>";
	foreach($items as $item) {
		$selected = ($options['popular_number_of_posts']==$item) ? 'selected="selected"' : '';
		echo "<option value='$item' $selected>$item</option>";
	}
	echo "</select>";
}


// TEXTBOX - Name: nrelate_popular_options[popular_title]
function setting_string_nr_mp() {
	$options = get_option('nrelate_popular_options');
	$p_title = stripslashes(stripslashes($options['popular_title']));
	$p_title = htmlspecialchars($p_title);
	echo '<input id="popular_title" name="nrelate_popular_options[popular_title]" size="40" type="text" value="'.$p_title.'" />';
}


// TEXTBOX / DROPDOWN - Name: nrelate_popular_options[popular_max_age]
function setting_popular_max_age() {
	$options_num = get_option('nrelate_popular_options');
	$options_frame = get_option('nrelate_popular_options');
	$items = array(
		"Hour(s)" => __("Hour(s)","nrelate"),
	 	"Day(s)" => __("Day(s)","nrelate"), 
		"Week(s)" => __("Week(s)","nrelate"),
		"Month(s)" => __("Month(s)","nrelate"), 
		"Year(s)" => __("Year(s)","nrelate")
	);
	echo "<input id='popular_max_age_num' name='nrelate_popular_options[popular_max_age_num]' size='4' type='text' value='{$options_num['popular_max_age_num']}' />";
	
	echo "<select id='popular_max_age_frame' name='nrelate_popular_options[popular_max_age_frame]'>";
	foreach($items as $type => $item) {
		$selected = ($options_frame['popular_max_age_frame']==$item) ? 'selected="selected"' : '';
		echo "<option value='$type' $selected>$item</option>";
	}
		echo "</select>";
}

// CHECKBOX - Show Post Title
function setting_popular_show_post_title(){
	$options = get_option('nrelate_popular_options');
	$checked = (isset($options['popular_show_post_title']) && $options['popular_show_post_title']=='on') ? ' checked="checked" ' : '';
	echo "<input ".$checked." id='popular_show_post_title' name='nrelate_popular_options[popular_show_post_title]' type='checkbox' onclick=\"if(this.checked){jQuery('.nr_showpost_option').show('slow');}else{jQuery('.nr_showpost_option').hide('slow');}\"/>";
}

// TEXTBOX - Name: nrelate_popular_options[popular_max_chars_per_line]
function setting_popular_max_chars_per_line() {
	$options = get_option('nrelate_popular_options');
	if(isset($options['popular_show_post_title']) && $options['popular_show_post_title']=='on'){
		$showpost_divstyle = 'style="display:block;"';
	}else{
		$showpost_divstyle = 'style="display:none;"';
	}
	echo "<div class='nr_showpost_option' ".$showpost_divstyle."><input id='popular_max_chars_per_line' name='nrelate_popular_options[popular_max_chars_per_line]' size='4' type='text' value='{$options['popular_max_chars_per_line']}' /></div>";
}

// CHECKBOX - Show Post Excerpt
function setting_popular_show_post_excerpt(){
	$options = get_option('nrelate_popular_options');
	$checked = (isset($options['popular_show_post_excerpt']) && $options['popular_show_post_excerpt']=='on') ? ' checked="checked" ' : '';
	echo "<input ".$checked." id='popular_show_post_excerpt' name='nrelate_popular_options[popular_show_post_excerpt]' type='checkbox'/ onclick=\"if(this.checked){jQuery('.nr_showexcerpt_option').show('slow');}else{jQuery('.nr_showexcerpt_option').hide('slow');}\">";
}


// TEXTBOX - Characters for Post Excerpt
function setting_popular_max_chars_post_excerpt() {
	$options = get_option('nrelate_popular_options');
	if(isset($options['popular_show_post_excerpt']) && $options['popular_show_post_excerpt']=='on'){
		$showexcerpt_divstyle = 'style="display:block;"';
	}else{
		$showexcerpt_divstyle = 'style="display:none;"';
	}
	echo "<div class='nr_showexcerpt_option' ".$showexcerpt_divstyle."><input id='popular_max_chars_post_excerpt' name='nrelate_popular_options[popular_max_chars_post_excerpt]' size='4' type='text' value='{$options['popular_max_chars_post_excerpt']}' /></div>";
}


// CHECKBOX - Name: nrelate_popular_options[popular_reset]
function setting_reset_nr_mp() {
	$options = get_option('nrelate_popular_options');
	$checked = (isset($options['popular_reset']) && $options['popular_reset'] == 'on') ? ' checked="checked" ' : '';
	echo "<input ".$checked." id='plugin_popular_reset' name='nrelate_popular_options[popular_reset]' type='checkbox' />";
}

// DROP-DOWN-BOX - Name: nrelate_popoular_options[popular_showviews]
function setting_show_views(){
	$options = get_option('nrelate_popular_options');
	$checked = (isset($options['popular_showviews']) && $options['popular_showviews'] == 'on') ? ' checked="checked" ' : '';
	echo "<input ".$checked." id='popular_showviews' name='nrelate_popular_options[popular_showviews]' type='checkbox' onclick=\"if(this.checked){jQuery('.nr_showview_option').show('slow');}else{jQuery('.nr_showview_option').hide('slow');}\"/>";
}

// TEXTBOX - Language for Views
function setting_views() {
	$options = get_option('nrelate_popular_options');
	if(isset($options['popular_showviews']) && $options['popular_showviews']=='on'){
		$showview_divstyle = 'style="display:block;"';
	}else{
		$showview_divstyle = 'style="display:none;"';
	}
	echo "<div class='nr_showview_option' ".$showview_divstyle."><input id='popular_view' name='nrelate_popular_options[popular_view]' size='20' type='text' value='{$options['popular_view']}' /></div>";
}
///////////////////////////
//   Layout Settings
//////////////////////////

// Section description
function section_text_nr_mp_layout() { nrelate_text_layout(NRELATE_POPULAR_NAME); }

// CHECKBOX LIST - Where to show popular content
function setting_popular_where_to_show(){
	global $nrelate_cond_tags;
	$options = get_option('nrelate_popular_options');
	
	$args = array('taxonomy' => 'category', 'value_field' => 'check_val');
	$args['selected_cats'] = is_array(isset($options['popular_where_to_show']) ? $options['popular_where_to_show'] : null) ? $options['popular_where_to_show'] : array();
	$args['name'] = 'nrelate_popular_options[popular_where_to_show]';
	
	echo '<div id="nrelate-where-to-show" class="categorydiv"><ul id="categorychecklist" class="list:category categorychecklist form-no-clear">';
	$walker = new nrelate_Walker_Category_Checklist();
	echo call_user_func_array(array(&$walker, 'walk'), array($nrelate_cond_tags, 0, $args));
	
	echo '</ul></div>';
	
	nrelate_where_to_show_check();
}

// CHECKBOX - Location Post Top
function setting_popular_loc_top(){
	$options = get_option('nrelate_popular_options');
	$checked = (isset($options['popular_loc_top']) && $options['popular_loc_top']=='on') ? ' checked="checked" ' : '';
	echo "<input ".$checked." id='popular_loc_top' name='nrelate_popular_options[popular_loc_top]' type='checkbox'/>";
}

// CHECKBOX - Location Post Bottom
function setting_popular_loc_bottom(){
	$options = get_option('nrelate_popular_options');
	$checked = @$options['popular_loc_bottom']=='on' ? ' checked="checked" ' : '';
	echo "<input ".$checked." id='popular_loc_bottom' name='nrelate_popular_options[popular_loc_bottom]' type='checkbox'/>";
}

// TEXT ONLY - no options
function setting_popular_manual(){
	_e("Add this code anywhere in your theme to show popular content:","nrelate"); echo"<br><b>&lt;?php if (function_exists('nrelate_popular')) nrelate_popular(); ?&gt;</b>";
}

// TEXT ONLY - no options
function setting_popular_css_link(){
	echo '<a href="admin.php?page=nrelate-popular&tab=styles">';	
	_e("Choose a style from our Style Gallery","nrelate");
	echo '</a>';
}

// CHECKBOX - Show nrelate logo
function setting_popular_display_logo(){
	$options = get_option('nrelate_popular_options');
	$checked = (isset($options['popular_display_logo']) && $options['popular_display_logo']=='on') ? ' checked="checked" ' : '';
	echo "<input ".$checked." id='show_logo' name='nrelate_popular_options[popular_display_logo]' type='checkbox' />";
}

// DROPDOWN - Name: nrelate_popular_options[popular_thumbnail]
function setting_popular_thumbnail() {
	$options = get_option('nrelate_popular_options');
	$items = array('Thumbnails'=>__("Thumbnails","nrelate"), 'Text'=>__("Text","nrelate"));
	echo "<select id='popular_thumbnail' name='nrelate_popular_options[popular_thumbnail]' onChange='nrelate_showhide_thumbnail(\"popular_thumbnail\");'>";
	/*?><select id='popular_thumbnail' name='nrelate_popular_options[popular_thumbnail]'>;
	<?php*/
	foreach($items as $type => $item) {
		$selected = ($options['popular_thumbnail']==$type) ? 'selected="selected"' : '';
		echo "<option value='".$type."' ".$selected.">".$item."</option>";
	}
	echo "</select>";
}

// RADIO - Name: nrelate_popular_options[popular_thumbnail_size]
function setting_popular_thumbnail_size(){
	$options = get_option('nrelate_popular_options');
	
	if($options['popular_thumbnail']=="Thumbnails"){
		$divstyle = "style='display:block;'";
	}
	else{
		$divstyle = "style='display:none;'";
	}
	
	echo "<div id='imagesizepreview' class='nr_image_option' ".$divstyle.">";
	$sizes = array(80,90,100,110,120,130,140,150);
	echo "<select id='popular_thumbnail_size' name='nrelate_popular_options[popular_thumbnail_size]' onChange='document.getElementById(\"popular_thumbnail_image\").src=\"". NRELATE_ADMIN_IMAGES ."/thumbnails/preview_cloud_\"+this.value+\".jpeg\";'>";
	foreach ($sizes as $size){
		$selected = ($options['popular_thumbnail_size']==$size) ? 'selected="selected"' : '';
		echo "<option value='".$size."' ".$selected.">".$size."</option>";
	}
	echo "</select><div class='thumbnail_wrapper' style='height:160px;'><img id='popular_thumbnail_image' src='" . NRELATE_ADMIN_IMAGES . "/thumbnails/preview_cloud_" .$options['popular_thumbnail_size'].".jpeg' /></div>";
}

// TEXTBOX - Name: nrelate_popular_options[popular_thumbnail]
//show picture and give ability to change picture
function setting_popular_default_image(){
	
	$options = get_option('nrelate_popular_options');
	if($options['popular_thumbnail']=="Thumbnails"){
		$divstyle = "style='display:block;'";
	}
	else{
		$divstyle = "style='display:none;'";
	}
	// Display preview image
	echo "<div class='nr_image_option' ".$divstyle.">";
	$imageurl = stripslashes(stripslashes($options['popular_default_image']));
	$imageurl = htmlspecialchars($imageurl);
	
	// Check if $imageurl is an empty string
	if($imageurl==""){
		_e("No default image chosen, until you provide your default image, nrelate will use <a class=\"thickbox\" href='http://img.nrelate.com/mpw_wp/".NRELATE_POPULAR_PLUGIN_VERSION."/defaultImages.html?KeepThis=true&TB_iframe=true&height=400&width=600' target='_blank'>these images</a>.<BR>","nrelate");
	}
	else{
		
		$body=array(
			'link'=>$imageurl,
			'domain'=>NRELATE_BLOG_ROOT
		);
		$url = 'http://api.nrelate.com/common_wp/'.NRELATE_POPULAR_ADMIN_VERSION.'/thumbimagecheck.php';
		
		$result = wp_remote_post($url,array('body'=>$body, 'timeout'=>10));

		$imageurl_cached=!is_wp_error($result) ? $result['body'] : null;
		if ($imageurl_cached) {
			echo "Current default image: &nbsp &nbsp";
			//$imageurl = htmlspecialchars(stripslashes($imageurl));
			$imagecall = '<img id="imgupload" style="outline: 1px solid #DDDDDD; width:'.$options['popular_thumbnail_size'].'; height:'.$options['popular_thumbnail_size'].';" src="'.$imageurl_cached.'" alt="No default image chosen"/><br><br>';
			echo $imagecall;
		}
	}
	// User can input an image url
	_e("Enter the link to your default image (include http://): <br>");
	echo '<input type="text" size="60" id="popular_default_image" name="nrelate_popular_options[popular_default_image]" value="'.$imageurl.'"></div>';
}


// TEXTBOX - Name: nrelate_popular_options[popular_custom_field]
function setting_popular_custom_field() {
	$options = get_option('nrelate_popular_options');
	// Display preview image
	if($options['popular_thumbnail']=="Thumbnails"){
		$divstyle = "style='display:block;'";
	}
	else{
		$divstyle = "style='display:none;'";
	}
	
	nrelate_text_custom_fields( $divstyle );
	echo "<script type='text/javascript'> nrelate_showhide_thumbnail('popular_thumbnail');</script>";
}


///////////////////////////
//   nrelate Labs
//////////////////////////

// Radio - Use Non js: nonjs=1, js=0
function setting_popular_nonjs(){
	$options = get_option('nrelate_popular_options');
	$values=array("js","nonjs");
	$valuedescription = array ("js" => __("<strong>Javascript:</strong> Stable and fast",'nrelate'), "nonjs" => __("<strong>No Javascript:</strong> BETA VERSION: Allows search engines to index our plugin and may help your SEO.",'nrelate')); 
	$i=0;
	foreach($values as $value){
		$checked = (isset($options['popular_nonjs']) && $options['popular_nonjs']==$i) ? ' checked="checked" ' : '';
		echo "<label for='popular_nonjs_".$i."'><input ".$checked." id='popular_nonjs_".$i."' name='nrelate_popular_options[popular_nonjs]' value='$i' type='radio'/>  ".$valuedescription[$value]."</label><br/>";
		$i+=1;
	}
}


/****************************************************************
 ******************** Build the Admin Page ********************** 
*****************************************************************/
function nrelate_popular_do_page() {

//Convert some visual option parameters for preview purposes
	$options = get_option('nrelate_popular_options');
	$ad_options = get_option('nrelate_popular_options_ads');
	$style_options = get_option('nrelate_popular_options_styles');
?>

		<?php nrelate_popular_settings_header();?>
		<script type="text/javascript">
			//<![CDATA[
			var nr_mp_plugin_settings_url = '<?php echo NRELATE_POPULAR_SETTINGS_URL; ?>';
			var nr_plugin_domain = '<?php echo NRELATE_BLOG_ROOT ?>';
			var nr_mp_plugin_version = '<?php echo NRELATE_POPULAR_PLUGIN_VERSION ?>';
			//]]>
		</script>
		<form name="settings" action="options.php" method="post" enctype="multipart/form-action">
	  <div class="nrelate-hidden">
	  <input type="checkbox" id="show_ad" <?php echo empty($ad_options['popular_display_ad']) ? '' : 'checked="checked"'; ?> value="on" />
      <input type="hidden" id="popular_number_of_ads" value="<?php echo isset($ad_options['popular_number_of_ads']) ? $ad_options['popular_number_of_ads'] : ''; ?>" />
      <input type="hidden" id="popular_ad_placement" value="<?php echo isset($ad_options['popular_ad_placement']) ? $ad_options['popular_ad_placement'] : ''; ?>" />
      <input type="hidden" id="popular_ad_title" value="<?php echo isset($ad_options['popular_ad_title']) ? $ad_options['popular_ad_title'] : ''; ?>" />
      <input type="checkbox" id="ad_animation" value="on" <?php echo empty($ad_options['popular_ad_animation']) ? '' : ' checked="checked" '; ?> />
      <input type="hidden" id="popular_imagestyle" value="<?php echo $style_options['popular_thumbnails_style']; ?>" />
      <input type="hidden" id="popular_textstyle" value="<?php echo $style_options['popular_text_style']; ?>" />
	</div>
			<?php settings_fields('nrelate_popular_options'); ?>
			<?php do_settings_sections(__FILE__);?>
		</form>
    	<script type="text/javascript">
		//<![CDATA[
		jQuery(document).ready(function($){
			$('.nrelate_mp_preview_button').click(function(event){
				event.preventDefault();
				$(this).parents('form:first').find('.nrelate_disabled_preview span').hide();
				
				if ($('#popular_thumbnail').val()=='Thumbnails') {
					if ($('#popular_imagestyle').val()=='none') { $(this).parents('td:first').find('.thumbnails_message:first').show(); return; }
				} else {
					if ($('#popular_textstyle').val()=='none') { $(this).parents('td:first').find('.text_message:first').show(); return; }
				}
				
				if ($('#popular_thumbnail').val()=='Text') {
					if (!$('#popular_show_post_title').is(':checked') && !$('#popular_show_post_excerpt').is(':checked')) {
						$(this).parents('td:first').find('.text-warning-message:first').show();
						setTimeout('tb_remove()', 50);
						return;
					}
				}
			});
			
			$('#popular_thumbnail').change(function(){
				$(this).parents('form:first').find('.nrelate_disabled_preview span').hide();
			});
			
			$('input.button-primary[name="Submit"]').click(function(event){
				$(this).parents('form:first').find('.nrelate_disabled_preview span').hide();
				
				if ($('#popular_thumbnail').val()=='Thumbnails') return;
				if ($('#popular_show_post_title').is(':checked')) return;
				if ($('#popular_show_post_excerpt').is(':checked')) return;
				event.preventDefault();
				event.stopPropagation();
				$(this).parents('td:first').find('.text-warning-message:first').show();
			});
		});
		//]]>
    </script>
	</div>
<?php
	update_nrelate_data_mp();
}

// Loads all of the nrelate_popular_options from wp database
// Makes necessary conversion for some parameters.
// Sends nrelate_popular_options entries, rss feed mode, and wordpress home url to the nrelate server
// Returns Success if connection status is "200". Returns error if not "200"
function update_nrelate_data_mp(){
	
	// Get nrelate_popular options from wordpress database
	$option = get_option('nrelate_popular_options');
	$number = urlencode($option['popular_number_of_posts']);
	$p_title = urlencode($option['popular_title']);
	$p_max_age = $option['popular_max_age_num'];
	$p_max_frame = $option['popular_max_age_frame'];
	$p_show_post_title = empty($option['popular_show_post_title']) ? false : true;
	$p_max_char_per_line = $option['popular_max_chars_per_line'];
	$p_show_post_excerpt = empty($option['popular_show_post_excerpt']) ? false : true;
	$p_max_char_post_excerpt = $option['popular_max_chars_post_excerpt'];
	$p_display_logo = $option['popular_display_logo'];
	//$p_popular_reset = $option['popular_reset'];
	$popular_thumbnail = $option['popular_thumbnail'];
	$backfill = $option['popular_default_image'];
	$popular_thumbnail_size = isset($option['popular_thumbnail_size']) ? $option['popular_thumbnail_size'] : null;
	$popular_loc_top = isset($option['popular_loc_top']) ? $option['popular_loc_top'] : null;
	$popular_loc_bot = isset($option['popular_loc_bottom']) ? $option['popular_loc_bottom'] : null;
	$popular_showviews = $option['popular_showviews'];
	$popular_nonjs = $option['popular_nonjs'];
	$popular_view = urlencode($option['popular_view']);
	
	$popular_layout= '';
	if($popular_loc_top=='on'){
		$popular_layout.='(TOP)';
	}
	if($popular_loc_bot=='on'){
		$popular_layout.='(BOT)';
	}
	$popular_layout=urlencode($popular_layout);

	// Convert max age time frame to minutes
	switch ($p_max_frame){
	case 'Hour(s)':
		$maxageposts = $p_max_age * 60;
		break;
	case 'Day(s)':
		$maxageposts = $p_max_age * 1440;
		break;
	case 'Week(s)':
		$maxageposts = $p_max_age * 10080;
		break;
	case 'Month(s)':
		$maxageposts = $p_max_age * 44640;
		break;
	case 'Year(s)':
		$maxageposts = $p_max_age * 525600;
		break;
	}

// Convert show post title parameter
	$p_show_post_title=($p_show_post_title)?1:0;

	$popular_showviews=($popular_showviews)?1:0;

	// Convert show post excerpt parametet
	$p_show_post_excerpt=($p_show_post_excerpt)?1:0;

	// Convert logo parameter
	$logo=($p_display_logo)?1:0;
	
	// Convert thumbnail option parameter
	switch ($popular_thumbnail){
	case 'Thumbnails':
		$thumb = 1;
	  break;
	default:
		$thumb = 0;
	}
	
	$body=array(
		'DOMAIN'=>NRELATE_BLOG_ROOT,
		'VERSION'=>NRELATE_POPULAR_PLUGIN_VERSION,
		'KEY'=>	get_option('nrelate_key'),
		'NUM'=>$number,
		'HDR'=>$p_title,
		'MAXPOST'=>$maxageposts,
		'SHOWPOSTTITLE'=>$p_show_post_title,
		'MAXCHAR'=>$p_max_char_per_line,
		'SHOWEXCERPT'=>$p_show_post_excerpt,
		'MAXCHAREXCERPT'=>$p_max_char_post_excerpt,
		'THUMB'=>$thumb,
		'LOGO'=>$logo,
		'IMAGEURL'=>$backfill,
		'THUMBSIZE'=>$popular_thumbnail_size,
		'LAYOUT'=>$popular_layout,
		'SHOWVIEWS'=>$popular_showviews,
		'NONJS'=>$popular_nonjs,
		'VIEW'=>$popular_view
	);
	$url = 'http://api.nrelate.com/mpw_wp/'.NRELATE_POPULAR_PLUGIN_VERSION.'/processWPpopular.php';

	$result = wp_remote_post($url,array('body'=>$body,'blocking'=>false, 'timeout'=>15));
}


// Validate user data for some/all of our input fields
function popular_options_validate($input) {
	// Check our textbox option field contains no HTML tags - if so strip them out
	$input['popular_title'] =  wp_filter_nohtml_kses($input['popular_title']);
	if(!is_numeric($input['popular_max_chars_per_line'])){
		$input['popular_max_chars_per_line']=100;
	}
	if(!is_numeric($input['popular_max_age_num'])){
		$input['popular_max_age_num']=2;
	}
	
	// Like escape all text fields
	$input['popular_default_image'] = like_escape($input['popular_default_image']);
	$input['popular_title'] = like_escape($input['popular_title']);
	// Add slashes to all text fields
	$input['popular_default_image'] = esc_sql($input['popular_default_image']);
	$input['popular_title'] = esc_sql($input['popular_title']);

	$input['popular_version'] = NRELATE_POPULAR_PLUGIN_VERSION;
		
	// Make sure that unchecked checkboxes are stored as empty strings
	global $nr_mp_std_options;
	$options = array_keys($nr_mp_std_options);
	$values = array_fill(0, count($options), '');
	$empty_settings_array = array_combine($options, $values);
	
	$input = wp_parse_args( $input, $empty_settings_array );
	
	return $input; // return validated input
}
?>