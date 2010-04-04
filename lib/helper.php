<?php
/**
 * @file
 * @brief Miscellaneous helper funtions.
 *
 * Functions that don't fit into any particular class or group
 * are declared here.
 *
 */

function show_index( $errorCode=NULL ) {
  $version    = '1540';
  $t          = getTextOfPage("index", LANG);
  $races      = translateRacesList(getRaceList(), LANG);
  require_once "views/index.php";
}

function show_roster( $raceId, $lt=NULL ) {
  $t          = getTextOfPage("roster", LANG);
  $s          = translateSkillsList(getSkillsNested(), LANG);
  $r          = translateRaceInfo(getRaceInfo($raceId), LANG);
  require_once "views/roster.php";
}

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

function ak($arr, $key) {
  if ( isset($arr) && array_key_exists($key, $arr) )
    return $arr[$key];
  if ( $key == 'skill' or $key=='inj' )
    return array();
  return "";
}

?>