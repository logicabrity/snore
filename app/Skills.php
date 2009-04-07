<?php
/**
 * @brief returns all skills grouped by categories
 * @param[out] $list two-dimensional array of skills grouped by categories
 *
 * This is used when the roster is generated. Ultimately, the template
 * produces javascript-arrays, which are very important
 * to the skill-handling in the javascript.
 */
function getSkillsNested() {
  $yaml = file("data/en/skills.yaml", FILE_IGNORE_NEW_LINES);
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

/**
 * @brief returns all skills in a single list
 * @param[out] $list simple numbered array of all skills
 *
 * Used twice (once for every language) everytime a
 * translation is built. The arrays are later combined.
 */
function getSkillsFlat() {
  $yaml = file("data/en/skills.yaml", FILE_IGNORE_NEW_LINES);

  $list = array();
  foreach ( $yaml as $line ) {
    if ( substr($line,0,3) == " - " ) {
      $list[] = substr($line,3);
    }
  }
  return $list;
}

?>