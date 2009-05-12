<?php

/**
 * @brief Get the text that composes the user interface.
 * @param[in] $page requested page, i.e. roster or index
 * @param[in] $lang desired language of the results
 * @param[out] $data associative array
 */
function getTextOfPage($page, $lang) {
  if ( $page != 'index' && $page != 'roster' ) {
    die("Page $page does not exist.");
  }
  $file = "data/$lang/$page.yml";
  $data = Spyc::YAMLLoad($file);
  return $data;
}

?>