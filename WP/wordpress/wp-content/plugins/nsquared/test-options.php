<?php

add_action('admin_menu', 'j_make_options_page');

function j_make_options_page() {

	//create new top-level menu
	add_options_page('Jane Options', 'Jane options', 'manage_options', __FILE__, 'jane_settings_page');

	//call register settings function
	add_action( 'admin_init', 'jane_register_settings' );
}

function jane_register_settings(){
	add_settings_section('jane_main', 'Main Settings', 'jane_main_text', 'jane');
	add_settings_field('jane_intro', 'Intro Text', 'jane_intro_text', 'jane', 'jane_main');

	register_setting('jane_option','jane_introText');
}

function jane_settings_page(){
	?><div class="wrap">
		<h2>Jane Options Page</h2>

		<form method="post" action="options.php">
			<?php settings_fields('jane_option'); ?>
			<?php do_settings_sections('jane'); ?>
			<p class="submit"><input type="submit" class="button-primary" value="<?php _e('Save Changes') ?>"  /></p>
		</form>
	</div>
	<?php
}

function jane_main_text() {
?><p>Description of the Main Settings Area</p><?php
}

function jane_intro_text() {
?><input type="text" name="jane_introText" value="<?php get_option('jane_introText');?>" /><?php
}


?>