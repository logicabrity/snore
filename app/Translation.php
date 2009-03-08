<?php
/**
 * @file
 * @brief implement Translation class
 */

/**
 * @brief Builds a dictionary and translates a roster with it.
 *
 * There are two different static methods for translation,
 * Translation::for_saving() and Translation::for_loading(),
 * because the data has a different form in each case.
 *
 * The dictionary is the same for both, though.
 *
 */
class Translation {
  /**
   * @brief the dictionary
   */
  private $translation;

  /**
   * @brief Prints a dictionary for testing purposes.
   * @param[in] $lang_from the 'keys' in the dictionary
   * @param[in] $lang_to the 'values' in the dictionary
   * @param[in] $race_id the id of a race
   *
   * It has the same parameters as __construct().
   *
   */
  public static function test($lang_from, $lang_to, $race_id) {
    $translation = new Translation($lang_from, $lang_to, $race_id);
    print_r($translation);
    die();
  }

  /**
   * @brief Translates data that is going to be saved in a roster.
   * @param[in] $data the data
   * @param[in] $lang_from the language of the data
   * @param[in] $lang_to the language the data is going to be translated into
   * @param[out] $data the data after translation
   *
   * In the typical scenario for SNORE, the data comes in the language
   * of the user interface (for example french for a  french user). Because
   * all rosters are stored in english, the data has to be translated to english,
   * which is thus the default for $lang_to.
   *
   */
  public static function for_saving($data, $lang_from, $lang_to='en') {
    $t = new Translation($lang_from, $lang_to, $data['RACE_ID']);
    $data['RACE'] = $t->translation[$data['RACE']];
    foreach($data['SKILLS'] as &$v) {
      if ( is_array($v) ) {
        foreach($v as &$w) {
          if ( substr($w, 0, 1) == '+' ) {
            $w = '+' . $t->translation[substr($w, 1)];
          }
          else {
            $w = $t->translation[stripslashes($w)];
          }
        }
      }
    }

    foreach($data['INJURIES'] as &$v) {
      if ( is_array($v) ) {
        foreach($v as &$w) {
          $w = $t->translation[$w];
        }
      }
    }
    return $data;
  }

  /**
   * @brief Translates data that is going to be saved in a roster.
   * @param[in] $data the data
   * @param[in] $lang_to the language the data is going to be translated into
   * @param[in] $lang_to tthe language of the data
   * @param[out] $data the data after translation
   *
   * In SNORE, the rosters are stored in english. Hence the default for
   * $lang_from is english. When loaded, the roster is translated into
   * $lang_to.
   *
   */
  public static function for_loading($data, $lang_to, $lang_from='en') {
    $t = new Translation($lang_from, $lang_to, $data['id']);
    $data['race'] = $t->translation[$data['race']];

    $positions = array_flip( $t->list_positions($data['id'], $lang_from) );
    foreach ( $data['player'] as &$player ) {
      $player['position'] = $positions[$player['position']];

      if ( array_key_exists('skill', $player) && is_array($player['skill']) ) {
        foreach($player['skill'] as &$skill) {
          if ( substr($skill, 0, 1) == '+' ) {
            $skill = '+' . $t->translation[substr($skill, 1)];
          }
          else {
            $skill = $t->translation[stripslashes($skill)];
          }
         }
      }

      if ( array_key_exists('inj', $player) && is_array($player['inj']) ) {
        foreach($player['inj'] as &$inj ) {
          if ( substr($inj, 0, 1) == '-' ) {
            $inj = '-' . $t->translation[substr($inj, 1)];
          }
          else {
            $inj = $t->translation[stripslashes($inj)];
          }
        }
      }
    }
    return $data;
  }

  /**
   * @brief Builds the dictionary used for the translations.
   * @param[in] $lang_from the 'keys' in the dictionary
   * @param[in] $lang_to the 'values' in the dictionary
   * @param[in] $race_id the id of a race
   *
   * Why do we need a $race_id? Because we need to translate
   * the players' positions (like Lineman, Thrower) too, and
   * it would be too time consuming to look up them up for each
   * race.
   *
   * Once the translation is built, it is stored in the class
   * variable $translation.
   */
  private function __construct($lang_from, $lang_to, $race_id) {
    $stats_from = UserInterface::stats($lang_from);
    $stats_to = UserInterface::stats($lang_to);

    $skills_from = Skills::all_flat($lang_from);
    $skills_to = Skills::all_flat($lang_to);

    $races_from = Races::list_all($lang_from);
    $races_to = Races::list_all($lang_to);

    $positions_from = Races::list_positions($race_id, $lang_from);
    $positions_to = Races::list_positions($race_id, $lang_to);

    $from = array_merge($skills_from, $races_from, $positions_from, $stats_from);
    $to = array_merge($skills_to, $races_to, $positions_to, $stats_to);

    $this->translation = array_combine($from, $to);
  }

  /**
   * @brief lists the positions of a certain race.
   * @param[in] $race_id a race
   * @param[in] $lang the language
   * @param[out] $positions positions of race $race_id in language $lang
   *
   */
  private function list_positions($race_id, $lang) {
    $positions = Races::list_positions($race_id, $lang);
    // The first non-empty position in the user interface
    // has the id 1 (0 is the empty player). By unshifting
    // the positions here in the translation, they connect
    // more easily to their real counterpart.
    array_unshift($positions, "");
    return $positions;
  }
}

?>
