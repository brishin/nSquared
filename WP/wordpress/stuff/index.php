<?php
/*
Plugin Name: Category and Tag retriever
Plugin URI: http://www.columbia.edu/~jk3316
Description: lalalala
Author: Jane Kim
Version: 1.0
Author URI: http://www.columbia.edu/~jk3316
*/


$mtype = "text/javascript";
header("Content-Type: $mtype");

function nrelate_get_cats($stuff){
    $cat_args=array(
        'orderby' => 'name',
        'order' => 'ASC');
    $categories=get_categories($cat_args);
    $cat_json = json_encode($categories);
    $stuff = $cat_json;
    return $stuff;

}

if(nrelate_get_cats()=='')
    echo 'Jane';


