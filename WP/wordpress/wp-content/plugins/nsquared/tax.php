<?php 

$args=array(
	'orderby' => 'id',
	'order' => 'ASC');

$categories = get_categories($args);
foreach($categories as $category) { 
	echo $category['name']."<br>";
	echo $category['cat_ID'];
	


}

$cat_json = json_encode($categories);
$tags = get_tags($args);
$tag_json = json_encode($tags);


?>
