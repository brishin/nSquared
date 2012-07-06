<?php
/*
Plugin Name: Category and Tag retriever
Plugin URI: http://www.columbia.edu/~jk3316
Description: lalalala
Author: Jane Kim
Version: 1.0
Author URI: http://www.columbia.edu/~jk3316
*/


function nrelate_get_cats(){
	$cat_args=array(
		'orderby' => 'name',
		'order' => 'ASC');
	$categories=get_categories($cat_args);
	foreach($categories as $category) { 
    echo 'Category: '.$category->name.'<br>';
    echo 'Category ID:'. $category->cat_ID.'<br><br>';
    }

    $cat_json = json_encode($categories);
    echo $cat_json;
    $mtype = "text/javascript";
    header("Content-Type: $mtype");

}

function nrelate_get_tags(){
    $tag_args=array(
        'orderby' => 'name',
        'order' => 'ASC');
    $tags=get_tags($tag_args);
    foreach($tags as $tag) { 
    echo 'Tag: '.$tag->name.'<br>';
    echo 'Tag ID:'. $tag->tag_ID.'<br><br>';
    }

    $tag_json = json_encode($categories);
    echo $cat_json;
    $mtype = "text/javascript";
    header("Content-Type: $mtype");

}


// add_filter('the_content', 'nrelate_get_cats');

?>
