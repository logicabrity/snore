<?php
/**
 * @brief returns all skills in english grouped by categories
 * @param[out] $list two-dimensional array of skills grouped by categories
 *
 * This is used when the roster is generated. Ultimately, the template
 * produces javascript-arrays, which are very important
 * to the skill-handling in the javascript.
 */
function getSkillsNested() {
  $yaml = file("data/skills.yml", FILE_IGNORE_NEW_LINES);
  $last_opened_node = '';

  $list = array();
  foreach ( $yaml as $line ) {
    if ( substr($line,0,3) == " - " ) {
      $list[$last_opened_node][] = substr($line,3);
    }
    else {
      $last_opened_node = substr($line,0,-1); // because last char is ':'
    }
  }
  return $list;
}

?>