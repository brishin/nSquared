<?php
/**
 * nrelate Most Popular Advertising Settings
 *
 * @package nrelate
 * @subpackage Functions
 */
 

function options_init_nr_mp_ads(){
	register_setting('nrelate_popular_options_ads', 'nrelate_popular_options_ads', 'popular_adv_options_validate' );
	
	$options = get_option('nrelate_popular_options_ads'); 
	// Div style on initial load for showing ad_title 
	$divstyle=($options['popular_ad_placement']=="Separate")?'style="display:block;"':'style="display:none;"'; 
	

	// Ad Section
	add_settings_section('ad_section',__('Advertising Settings','nrelate'), 'nrelate_text_advertising', __FILE__);
	add_settings_field('popular_display_ad_image','', 'popular_display_ad_money', __FILE__, 'ad_section');
	add_settings_field('popular_display_ad',__('Would you like to display ads?','nrelate'), 'setting_adv_display_ad_mp', __FILE__, 'ad_section');
	add_settings_field('popular_ad_number',__('How many ad spaces do you wish to show?','nrelate'), 'setting_adv_ad_number_mp', __FILE__, 'ad_section');
	add_settings_field('popular_ad_placement',__('Where would you like to place the ads?','nrelate') . nrelate_tooltip('_adplacement'), 'setting_adv_ad_placement_mp', __FILE__, 'ad_section');
	add_settings_field('popular_ad_title', __('<div class="nr_separate_ad_opt" '.$divstyle.'>Please enter a title for advertising section</div>','nrelate'), 'setting_adv_ad_title_mp', __FILE__, 'ad_section');
	add_settings_field('popular_ad_animation',__('Would you like to show animated "sponsored" text in ads?','nrelate'), 'setting_adv_ad_animation_mp', __FILE__, 'ad_section');
	add_settings_field('nrelate_save_preview','', 'nrelate_save_preview', __FILE__, 'ad_section');
	
}
add_action('admin_init', 'options_init_nr_mp_ads' );


/****************************************************************
 ************************** Admin Sections ********************** 
*****************************************************************/


///////////////////////////
//   Advertising Settings
//////////////////////////

// Show "Wanna make some money?" image
function popular_display_ad_money(){
	
	 // Get Advertising options
	$ad_options = get_option('nrelate_popular_options_ads');
	
	// get ad show option
	$ad_show = isset($ad_options['popular_display_ad']) ? $ad_options['popular_display_ad'] : null;
	
	// If not showing ads, display image
	if ($ad_show == null) { nrelate_wanna_make_money(); }
}

// CHECKBOX - Display ads
function setting_adv_display_ad_mp() {
	$options = get_option('nrelate_popular_options_ads');
	$checked = (isset($options['popular_display_ad']) && $options['popular_display_ad']=='on') ? ' checked="checked" ' : '';
	echo "<input ".$checked." id='show_ad' name='nrelate_popular_options_ads[popular_display_ad]' type='checkbox' />";
}

// DROPDOWN - number of ads to show
function setting_adv_ad_number_mp(){
	$options = get_option('nrelate_popular_options_ads');
	$items = array("1", "2", "3", "4", "5", "6", "7", "8", "9", "10");
	echo "<div id='adnumber'><select id='popular_number_of_ads' name='nrelate_popular_options_ads[popular_number_of_ads]'>";
	foreach($items as $item) {
		$selected = (isset($options['popular_number_of_ads']) && $options['popular_number_of_ads']==$item) ? 'selected="selected"' : '';
		echo "<option value='$item' $selected>$item</option>";
	}
	echo "</select></div>";
}

// DROPDOWN - ad placement
function setting_adv_ad_placement_mp(){	
	$options = get_option('nrelate_popular_options_ads');
	$items = array("Mixed","First","Last","Separate");
	echo "<div id='adplacement'><select id='popular_ad_placement' name='nrelate_popular_options_ads[popular_ad_placement]' onChange='if(this.value==\"Separate\"){jQuery(\".nr_separate_ad_opt\").show(\"slow\");}else{jQuery(\".nr_separate_ad_opt\").hide(\"slow\");}'>";
	foreach($items as $item) {
		$selected = (isset($options['popular_ad_placement']) && $options['popular_ad_placement']==$item) ? 'selected="selected"' : '';
		echo "<option value='$item' $selected>$item</option>";
	}
	echo "</select></div>";
}

// TEXTBOX - Name: nrelate_popular_options_ads[popular_ad_title]
function setting_adv_ad_title_mp() {
	$options = get_option('nrelate_popular_options_ads');
	
	// Div style on initial load for showing ad_title 
	$divstyle=($options['popular_ad_placement']=="Separate")?'style="display:block;"':'style="display:none;"';
  
	$mp_ad_title = stripslashes(stripslashes($options['popular_ad_title']));
	$mp_ad_title = htmlspecialchars($mp_ad_title);
	echo '<input id="popular_ad_title" class="nr_separate_ad_opt" name="nrelate_popular_options_ads[popular_ad_title]" size="40" type="text" value="'.$mp_ad_title.'" '.$divstyle.'/>';
}

// CHECKBOX - Animated "sponsored" text in ads
function setting_adv_ad_animation_mp(){
	$options = get_option('nrelate_popular_options_ads');
	$checked = !empty($options['popular_ad_animation']) ? ' checked="checked" ' : '';
	echo "<input ".$checked." id='ad_animation' name='nrelate_popular_options_ads[popular_ad_animation]' type='checkbox' />";
}



/****************************************************************
 ******************** Build the Admin Page ********************** 
*****************************************************************/

function nrelate_popular_ads_do_page() {
	$options = get_option('nrelate_popular_options');
	$style_options = get_option('nrelate_popular_options_styles');
?>
	
	<?php nrelate_popular_settings_header(); ?>
    <script type="text/javascript">
		//<![CDATA[
		var nr_mp_plugin_settings_url = '<?php echo NRELATE_POPULAR_SETTINGS_URL; ?>';
		var nr_plugin_domain = '<?php echo NRELATE_BLOG_ROOT ?>';
		var nr_mp_plugin_version = '<?php echo NRELATE_POPULAR_PLUGIN_VERSION ?>';
		//]]>
    </script>
		<form name="settings" action="options.php" method="post" enctype="multipart/form-action">
			<?php settings_fields('nrelate_popular_options_ads'); ?>
			<?php do_settings_sections(__FILE__);?>
			
			<div class="nrelate-hidden">
		      <input type="hidden" id="popular_number_of_posts" value="<?php echo isset($options['popular_number_of_posts']) ? $options['popular_number_of_posts'] : ''; ?>" />
		      <input type="hidden" id="popular_number_of_posts_ext" value="<?php echo isset($options['popular_number_of_posts_ext']) ? $options['popular_number_of_posts_ext'] : ''; ?>" />
		      <input type="hidden" id="popular_title" value="<?php echo isset($options['popular_title']) ? $options['popular_title'] : ''; ?>" />
		      <input type="checkbox" id="popular_show_post_title" <?php echo empty($options['popular_show_post_title']) ? '' : 'checked="checked"'; ?> value="on" />
		      <input type="hidden" id="popular_max_chars_per_line" value="<?php echo isset($options['popular_max_chars_per_line']) ? $options['popular_max_chars_per_line'] : ''; ?>" />
		      <input type="checkbox" id="popular_show_post_excerpt" <?php echo empty($options['popular_show_post_excerpt']) ? '' : 'checked="checked"'; ?> value="on" />
		      <input type="hidden" id="popular_max_chars_post_excerpt" value="<?php echo isset($options['popular_max_chars_post_excerpt']) ? $options['popular_max_chars_post_excerpt'] : ''; ?>" />
		      <input type="checkbox" id="show_logo" <?php echo empty($options['popular_display_logo']) ? '' : 'checked="checked"'; ?> value="on" />
		      <input type="hidden" id="popular_thumbnail" value="<?php echo $options['popular_thumbnail']; ?>" />
		      <input type="hidden" id="popular_textstyle" value="<?php echo empty($style_options['popular_text_style']) ? 'default' : $style_options['popular_text_style']; ?>" />
		      <input type="hidden" id="popular_imagestyle" value="<?php echo empty($style_options['popular_thumbnails_style']) ? 'default' : $style_options['popular_thumbnails_style']; ?>" />
		      <input type="hidden" id="popular_default_image" value="<?php echo $options['popular_default_image']; ?>" />
		      <input type="hidden" id="popular_max_age_num" value="<?php echo $options['popular_max_age_num']; ?>" />
		      <input type="hidden" id="popular_max_age_frame" value="<?php echo $options['popular_max_age_frame']; ?>" />
		      <input type="hidden" id="popular_thumbnail_size" value="<?php echo $options['popular_thumbnail_size']; ?>" />
		      <input type="hidden" id="popular_imagestyle" value="<?php echo $style_options['popular_thumbnails_style']; ?>" />
		      <input type="hidden" id="popular_textstyle" value="<?php echo $style_options['popular_text_style']; ?>" />
		      <input type="hidden" id="popular_view" value="<?php echo $options['popular_view']; ?>" />
		       <input type="checkbox" id="popular_showviews" value="on" <?php echo empty($options['popular_showviews']) ? '' : ' checked="checked" '; ?> />
		       <input type="checkbox" id="ad_animation" value="on" <?php echo empty($options['popular_ad_animation']) ? '' : ' checked="checked" '; ?> />
		    </div>
		</form>

	</div>
<?php
	
	update_nrelate_data_mp_adv();
}

// Loads all of the nrelate_popular_options from wp database
// Makes necessary conversion for some parameters.
// Sends nrelate_popular_options entries, rss feed mode, and wordpress home url to the nrelate server
// Returns Success if connection status is "200". Returns error if not "200"
function update_nrelate_data_mp_adv(){
	
	// Get nrelate_popular options from wordpress database
	$ad_option = get_option('nrelate_popular_options_ads');
	
	$p_display_ad = empty($ad_option['popular_display_ad']) ? false : true;
	$popular_ad_num = isset($ad_option['popular_number_of_ads']) ? $ad_option['popular_number_of_ads'] : null;
	$popular_ad_place = isset($ad_option['popular_ad_placement']) ? $ad_option['popular_ad_placement'] : null;
	$popular_ad_title = $ad_option['popular_ad_title'];

	$ad = ($p_display_ad) ? 1:0;

	$body=array(
		'DOMAIN'=>NRELATE_BLOG_ROOT,
		'ADOPT'=>$ad,
		'ADNUM'=>$popular_ad_num,
		'ADPLACE'=>$popular_ad_place,
		'ADTITLE'=>$popular_ad_title,
		'VERSION'=>NRELATE_POPULAR_PLUGIN_VERSION,
		'KEY'=>get_option('nrelate_key')
	);
	$url = 'http://api.nrelate.com/mpw_wp/'.NRELATE_POPULAR_PLUGIN_VERSION.'/processWPpopular_ad.php';

	$result = wp_remote_post($url,array('body'=>$body,'blocking'=>false, 'timeout'=>15));
}



// Validate user data for some/all of our input fields
function popular_adv_options_validate($input) {
	// Make sure that unchecked checkboxes are stored as empty strings
	global $nr_mp_ad_options;
	$options = array_keys($nr_mp_ad_options);
	$values = array_fill(0, count($options), '');
	$empty_settings_array = array_combine($options, $values);
	
	$input = wp_parse_args( $input, $empty_settings_array );
	
	return $input; // return validated input
}
?>