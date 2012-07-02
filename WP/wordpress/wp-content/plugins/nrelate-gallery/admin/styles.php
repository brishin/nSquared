<?php
/**
* nrelate gallery styles
* 
* @package nrelate
* @subpackage Functions
*/

/* Thumbnail styles
* currently just a default style called Gallery. more will be added, probz
*/

$nrelate_thumbnail_styles = array(
	'gallery' => array(
		"stylesheet" => "nrelate-panels-gallery",
		"styleclass" => "gallery",
		"layout" => "1col",
		"name" => __('Gallery', 'nrelate'),
		"features" => __('<ul>
			<li>Hover effects.</li>
			<li>No borders.</li>
			<li>Full title revealed on hover.</li>
		</ul>' , 'nrelate'),
		"info" => __('Simple yet interactive style for the best gallery viewing experience.' , 'nrelate')
	)
);

/* Thumbnail / Ad Styles */

$nrelate_thumbnail_styles_separate = array(
	'gallery-2col' => array(
		"stylesheet" => "nrelate-panels-gallery",
		"styleclass" => "gallery",
		"layout" => "2col", 
		"name" => __('Gallery: <br><em>2 Columns</em>', 'nrelate'),
		"features" => __('<ul>
			<li>Hover effects.</li>
			<li>No borders.</li>
			<li>Full title revealed on hover.</li>
		</ul>' , 'nrelate'),
		"info" => __('Simple yet interactive style for the best gallery viewing experience.' , 'nrelate')
	),
	'default-2row' => array(
					"stylesheet" => "nrelate-panels-default",
					"styleclass" => "default",
					"layout" => "2row",
					"name"=>__('Gallery:<br/><em>2 Rows</em>','nrelate'),
					"features"=>__('<ul>
										<li>Hover effects.</li>
										<li>No borders.</li>
										<li>Full title revealed on hover.</li>
									</ul>' , 'nrelate'),
					"info" => __('Simple yet interactive style for the best gallery viewing experience.','nrelate')
	)
);

/* Text Styles */

$nrelate_text_styles = array(
	'gallery' => array(
		"stylesheet" => "nrelate-text-gallery",
		"styleclass" => "gallery", 
		"layout" => "1col",
		"name" => __('Gallery', 'nrelate'),
	)
);

/* Text/Ad styles */
$nrelate_text_styles_separate = array(
	'gallery-text-2col' => array(
		"stylesheet" => "nrelate-text-gallery",
		"styleclass" => "gallery",
		"layout" => "2col",
		"name"=>__('Gallery:<br/><em>2 Columns</em>','nrelate'),
		"features"=>__('<ul>
							<li>Hover effects.</li>
							<li>No borders.</li>
							<li>Full title revealed on hover.</li>
						</ul>' , 'nrelate'),
		"info" => __('Simple yet interactive style for the best gallery viewing experience.','nrelate')
	),
	'gallery-text-2col' => array(
		"stylesheet" => "nrelate-text-gallery",
		"styleclass" => "gallery",
		"layout" => "2row",
		"name"=>__('Gallery:<br/><em>2 Rows</em>','nrelate'),
		"features"=>__('<ul>
							<li>Hover effects.</li>
							<li>No borders.</li>
							<li>Full title revealed on hover.</li>
						</ul>' , 'nrelate'),
		"info" => __('Simple yet interactive style for the best gallery viewing experience.','nrelate')
	)
);

?>
