<?php
/*
Plugin Name: My second plugin
Plugin URI: http://www.columbia.edu/~jk3316
Description: lalalala
Author: Jane Kim
Version: 2.0
Author URI: http://www.columbia.edu/~jk3316
*/

function Jane_Formatting($content){
	$some_string = <<<EOT
	<h3>omg this is the shit</h3>
	<p>OMG LOOK AT THIS AWESOME STRING. I HOPE IT GETS Y'ALL EXCITED AND SHIT. THIS IS A TEST. TESTICLES.</p> 
EOT;
	return $content.$some_string;
}

add_filter('comment_text', 'Jane_Formatting');

?>
