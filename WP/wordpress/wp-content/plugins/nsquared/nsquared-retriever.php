<?php
/*
* Gets all the categories and tags (objects) and converts them to json to send to a Javascript function
*/
$nsq_json = $cat_json = $tag_json = $inject = '';

function nrelate_get_cats_tags($content){
  $cat_args=array(
    'orderby' => 'name',
    'order' => 'ASC');
  $categories=get_categories($cat_args);
  $cat_json = json_encode($categories);

  $tag_args=array(
      'orderby' => 'name',
      'order' => 'ASC');
  $tags=get_tags($tag_args);
  $tag_json = json_encode($tags);

  $inject = <<<EOT
  <script type = "text/javascript" id="nsq-retriever"> var nsqCats = $cat_json; var nsqTags = $tag_json; </script>
EOT;
  return $content.$inject;
}

add_action('get_header', 'nrelate_get_cats_tags');
?>
