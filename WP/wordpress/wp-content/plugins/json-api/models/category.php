<?php

class JSON_API_Category {
  
  var $id;          // Integer
  var $title;       // String
  
  function nsquared_retrieve_categories($wp_category = null) {
    if ($wp_category) {
      $this->import_wp_object($wp_category);
    }
  }
  
  function import_wp_object($wp_category) {
    $this->id = (int) $wp_category->term_id;
    $this->title = $wp_category->name;
  }
  
}

?>
