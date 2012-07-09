<?php

$cat_json = $tag_json = '';

function nrelate_get_cats(){
  $cat_args=array(
    'orderby' => 'name',
    'order' => 'ASC');
  $categories=get_categories($cat_args);
  $cat_json = json_encode($categories);

}

function nrelate_get_tags(){
  $tag_args=array(
      'orderby' => 'name',
      'order' => 'ASC');
  $tags=get_tags($tag_args);
  $tag_json = json_encode($tags);
}

add_action('init', 'nrelate_get_cats');
add_action('init', 'nrelate_get_tags');

//wp enqueue

?>
