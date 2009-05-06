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

/**
 * @brief List stats and injury abbrevations.
 * @param[in] $lang desired language of the results
 * @param[out] $stats numbered array where values are the abbrevations
 */
function stats($lang=LANG) {
  $file = 'data/' . $lang . '/roster.yml';
  $data = Spyc::YAMLLoad($file);

  $stats = array();
  $stats[] = $data['stats_ma'];
  $stats[] = $data['stats_st'];
  $stats[] = $data['stats_ag'];
  $stats[] = $data['stats_av'];
  $stats[] = $data['injuries_m'];
  $stats[] = $data['injuries_n'];
  return $stats;
}
?>