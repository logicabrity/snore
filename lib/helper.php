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
  $races      = translateRacesList(getRacesList(), LANG);
  require_once "views/index.php";
}

function show_roster( $raceId, $loadedTeam=NULL ) {
  $t          = getTextOfPage("roster", LANG);
  $skills     = translateSkillsList(getSkillsNested(), LANG);
  $race       = translateRaceInfo(getRaceInfo($raceId), LANG);
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

?>