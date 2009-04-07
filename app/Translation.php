<?php

  /**
   * @brief lists the positions of a certain race.
   * @param[in] $race_id a race
   * @param[in] $lang the language
   * @param[out] $positions positions of race $race_id in language $lang
   *
   */
  function list_positions($race_id, $lang) {
    $positions = Races::list_positions($race_id, $lang);
    // The first non-empty position in the user interface
    // has the id 1 (0 is the empty player). By unshifting
    // the positions here in the translation, they connect
    // more easily to their real counterpart.
    array_unshift($positions, "");
    return $positions;
  }

/**
 * @brief translate team info after a roster was parsed when loading
 */
function translateTeam($data, $lang) {
  if ( $lang == "en" )
    return $data;

  $races = buildRacesTranslation($lang);
  $data['race'] = $races[$data['race']];

  $positions = buildPositionNamesTranslation($lang);
  $stats     = array_combine(stats("en"), stats($lang));
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
 * @brief translate the post data to english before saving
 */
function translatePostDataBeforeSaving($data, $lang) {
  if ( $lang == "en" )
    return $data;
  
  $races = array_flip(buildRacesTranslation($lang));
  $data['RACE'] = $races[$data['RACE']];

  $stats  = array_combine(stats($lang), stats('en'));
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
 * @brief translate the parsed information about a race to another language
 * @param[in] $raceInfo the race info (result of function getRaceInfo($raceID, $lang)
 * @param[in] $lang the language to which the information shall be translated
 * @param[out] $raceInfo the race info, but now translated
 */
function translateRaceInfo($raceInfo, $lang) {
  if ( $lang == 'en' )
    return $raceInfo;
  $raceInfo['name'] = translateRaceName((string)$raceInfo['name'], $lang);
  translatePositionNamesInRaceInfo($raceInfo, $lang);
  translateSkillsInRaceInfo($raceInfo, $lang);
  return $raceInfo;
}

function translateSkillsInRaceInfo( &$raceInfo, $lang ) {
  $skillTrans = buildSkillsTranslation($lang);
  /* couldn't get it to work with
   * $skills = &$raceInfo->xPath("positions/position/skills/skill");
   * and a for loop through skills. it would not change the original.
   */
  for ( $i=0; $i<sizeof($raceInfo->positions->position); $i++ ) {
    $pos = &$raceInfo->positions->position[$i];
    for ( $j=0; $j<sizeof($pos->skills->skill); $j++ ) {
      if ( array_key_exists((string) $pos->skills->skill[$j], $skillTrans) )
        $pos->skills->skill[$j] = $skillTrans[(string) $pos->skills->skill[$j]];
    }
  }
}

function translatePositionNamesInRaceInfo( &$raceInfo, $lang ) {
  $posTranslation = buildPositionNamesTranslation($lang);
  for ( $i=0; $i<sizeof($raceInfo->positions->position); $i++) {
    $pos = &$raceInfo->positions->position[$i];
    if ( array_key_exists((string) $pos->title, $posTranslation) )
      $pos->title = $posTranslation[(string) $pos->title];
  }
}

/**
 * @brief translate the name of a race to another language
 * @param[in] $raceName the name of a race
 * @param[in] $lang the language to which the name shall be translated
 * @param[out] $raceName the translated name of the race
 */
function translateRaceName( $raceName, $lang ) {
  $translation = buildRacesTranslation($lang);
  if ( array_key_exists($raceName, $translation) )
    return $translation[$raceName];
  return $raceName;
}

/**
 * @brief translates an english list of races into another language
 * @param[in] $lang language the list should be translated into
 *
 */
function translateRacesList($list, $lang) {
  if ( $lang == 'en' )
    return $list;
  $translation = buildRacesTranslation($lang);
  foreach ( $list as &$race ) {
    if ( isset($translation[$race]) )
      $race = $translation[$race];
  }
  return $list;
}

/**
 * @brief build the translation mapping from english to given language
 * @param[in] $lang target language
 *
 */
function buildRacesTranslation($lang) {
  $t = Spyc::yaml_load("data/$lang/races.yml");
  return $t;
}

/**
 * @brief translates an english list of skills into another language
 * @param[in] $lang language the list should be translated into
 *
 */
function translateSkills($list, $lang) {
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
 * @brief build the translation mapping from english to given language
 * @param[in] $lang target language
 *
 */
function buildSkillsTranslation($lang) {
  $t = Spyc::yaml_load("data/$lang/skills.yaml");
  return $t;
}

/**
 * @brief build the translation mapping for position names
 * @param[in] $lang target language, source lang is english
 *
 */
function buildPositionNamesTranslation($lang) {
  $t = Spyc::yaml_load("data/$lang/positions.yml");
  return $t;
}

?>