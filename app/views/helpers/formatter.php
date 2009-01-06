<?php

class FormatterHelper extends Helper {
	
  function tagsToString($tag_array) {
    if (!empty($tag_array)) {
      $tags = array();
      foreach ($tag_array as $tag) {
        $tags[] = $tag['term'];
      }
      sort($tags);
      return $this->output(implode(", ", $tags));
	  }
	  return $this->output("<em>none</em>");
  }
}
