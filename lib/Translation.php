<?php

/**
 * @brief translate the post data from $lang to english before saving
 */
function translateTeamBeforeSaving($data, $lang) {
  if ( $lang == "en" )
    return $data;

  $races = array_flip(buildRaceNamesTranslation($lang));
  $data['RACE'] = $races[$data['RACE']];

  $stats  = array_combine(buildStatsAndInjuriesTranslation($lang),
                          buildStatsAndInjuriesTranslation('en'));
  $skills = array_flip(buildSkillsTranslation($lang));
  for ( $i=0; $i<15; $i++ ) {

    if ( $data['SKILLS'][$i] != "" ) {
      $s = explode(", ", $data['SKILLS'][$i]);
      for ( $j=0; $j<count($s); $j++ ) {
        if ( substr($s[$j], 0, 1) == '+' ) {
          $s[$j] = '+' . $stats[substr($s[$j], 1)];
        }
        else {
          $s[$j] = $skills[$s[$j]];
        }
      }
      $data['SKILLS'][$i] = implode(", ", $s);
    }

    if ( $data['INJURIES'][$i] != "" ) {
      $s = explode(", ", $data['INJURIES'][$i]);
      for ( $j=0; $j<count($s); $j++ ) {
        if ( substr($s[$j], 0, 1) == '-' ) {
          $s[$j] = '-' . $stats[substr($s[$j], 1)];
        }
        else {
          $s[$j] = $stats[$s[$j]];
        }
      }
      $data['INJURIES'][$i] = implode(", ", $s);
    }
  }

  return $data;
}

/**
 * @brief translate team info to $lang after the roster was loaded/parsed
 */
function translateTeamAfterLoading($data, $lang) {
  if ( $lang == "en" )
    return $data;

  $races = buildRaceNamesTranslation($lang);
  $data['race'] = $races[$data['race']];

  $positions = buildPositionNamesTranslation($lang);
  $stats     = array_combine(buildStatsAndInjuriesTranslation("en"),
                             buildStatsAndInjuriesTranslation($lang));
  $skills    = buildSkillsTranslation($lang);

  foreach ( $data['player'] as &$player ) {
    $player['position'] = $positions[$player['position']];

    if ( array_key_exists("skill", $player) ) {
      foreach ( $player['skill'] as &$skill ) {
        if ( substr($skill, 0, 1) == "+" ) {
          $skill = "+" . $stats[substr($skill, 1)];
        }
        else {
          $skill = $skills[$skill];
        }
      }
    }

    if ( array_key_exists("inj", $player) ) {
      foreach ( $player['inj'] as &$inj ) {
        if ( substr($inj, 0, 1) == "-" ) {
          $inj = "-" . $stats[substr($inj, 1)];
        }
        else {
          $inj = $stats[$inj];
        }
      }
    }
  }

  return $data;
}

/**
 * @brief translates an english list of races into another language
 * @param[in] $lang language the list should be translated into
 *
 */
function translateRacesList($list, $lang) {
  if ( $lang == 'en' )
    return $list;
  $translation = buildRaceNamesTranslation($lang);
  foreach ( $list as &$race ) {
    if ( isset($translation[$race]) )
      $race = $translation[$race];
  }
  return $list;
}

/**
 * @brief translates an english list of skills into another language
 * @param[in] $lang language the list should be translated into
 *
 */
function translateSkillsList($list, $lang) {
  if ( $lang == 'en' )
    return $list;
  $translation = buildSkillsTranslation($lang);
  foreach ( $list as &$v ) {
    if ( is_array($v) ) {
      foreach ( $v as &$w ) {
        if ( isset($translation[$w]) )
          $w = $translation[$w];
      }
    }
    elseif ( isset($translation[$v]) )
      $v = $translation[$v];
  }
  return $list;
}

/**
 * @brief translate the parsed information about a race to another language
 * @param[in] $raceInfo result of function getRaceInfo($raceID, $lang)
 * @param[in] $lang the language to which the information shall be translated
 * @param[out] $raceInfo the race info, but now translated
 */
function translateRaceInfo($raceInfo, $lang) {
  if ( $lang == 'en' )
    return $raceInfo;
  $raceInfo['race'] = _translateRaceName($raceInfo['race'], $lang);
  _translatePositionNamesInRaceInfo($raceInfo, $lang);
  _translateSkillsInRaceInfo($raceInfo, $lang);
  return $raceInfo;
}

/**
 * @brief translate the name of a race to another language
 * @param[in] $raceName the name of a race
 * @param[in] $lang the language to which the name shall be translated
 * @param[out] $raceName the translated name of the race
 */
function _translateRaceName( $raceName, $lang ) {
  $translation = buildRaceNamesTranslation($lang);
  if ( array_key_exists($raceName, $translation) )
    return $translation[$raceName];
  return $raceName;
}

function _translatePositionNamesInRaceInfo( &$raceInfo, $lang ) {
  $posTranslation = buildPositionNamesTranslation($lang);
  for ( $i=0; $i<sizeof($raceInfo['positions']); $i++) {
    $pos = &$raceInfo['positions'][$i];
    if ( array_key_exists($pos['title'], $posTranslation) )
      $pos['title'] = $posTranslation[$pos['title']];
  }
}

function _translateSkillsInRaceInfo( &$raceInfo, $lang ) {
  $skillTrans = buildSkillsTranslation($lang);
  /* couldn't get it to work with
   * $skills = &$raceInfo->xPath("positions/position/skills/skill");
   * and a for loop through skills. it would not change the original.
   */
  for ( $i=0; $i<sizeof($raceInfo['positions']); $i++ ) {
    $pos = &$raceInfo['positions'][$i];
    for ( $j=0; $j<sizeof($pos['skills']); $j++ ) {
      if ( array_key_exists((string) $pos['skills'][$j], $skillTrans) )
        $pos['skills'][$j] = $skillTrans[$pos['skills'][$j]];
    }
  }
}

/**
 * @brief build the translation mapping from english to given language
 * @param[in] $lang target language
 *
 */
function buildRaceNamesTranslation($lang) {
  $t = Spyc::YAMLLoad("data/$lang/races.yml");
  return $t;
}

/**
 * @brief build the translation mapping from english to given language
 * @param[in] $lang target language
 *
 */
function buildSkillsTranslation($lang) {
  $t = Spyc::YAMLLoad("data/$lang/skills.yml");
  return $t;
}

/**
 * @brief build the translation mapping for position names
 * @param[in] $lang target language, source lang is english
 *
 */
function buildPositionNamesTranslation($lang) {
  $t = Spyc::YAMLLoad("data/$lang/positions.yml");
  return $t;
}

/**
 * @brief List stats and injury abbrevations.
 * @param[in] $lang desired language of the results
 * @param[out] $stats numbered array where values are the abbrevations
 */
function buildStatsAndInjuriesTranslation($lang) {
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