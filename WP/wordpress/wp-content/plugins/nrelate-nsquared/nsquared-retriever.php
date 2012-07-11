<?php
/*
* Gets all the categories and tags (objects) and converts them to json to send to a Javascript function
*/
$nsq_json = $cat_json = $tag_json = $inject = '';

function nrelate_get_cats_tags(){
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

  $cats_tags = array('nsquared_categories' => $cat_json, 'nsquared_tags' => $tag_json);
  $nsq_json = json_encode($cats_tags);
  $inject = <<<EOT
  <script type = "text/javascript" id="nsq-retriever"> $nsq_json </script>
EOT;
  echo $inject;
}

add_action('get_header', 'nrelate_get_cats_tags');
// add path to enqueue to connect variable to cat/tag filter
// wp_enqueue_script( 'nsquared_retriever' , );
// wp_localize_script( 'nsquared_retriever', 'NsquaredRetriever', $nsq_json );
?>
