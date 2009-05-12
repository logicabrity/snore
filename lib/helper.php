<?php
/**
 * @file
 * @brief Miscellaneous helper funtions.
 *
 * Functions that are used on several occasions, but
 * don't fit into any particular class or group are declared here.
 *
 */

 /**
  * @brief Returns the language the user set.
  *
  * Performs a check to see if the language is allowed in the config.
  */
function check_lang($allowed) {
  if ( isset($_POST['lang']) && in_array($_POST['lang'], $allowed) ) {
    setcookie('lang', $_POST['lang'], time()+60*60*24*364, '/', $_SERVER['HTTP_HOST'], 0);
    header("Location: {$_SERVER['PHP_SELF']}");
  }
  elseif ( isset($_COOKIE['lang']) && in_array($_COOKIE['lang'], $allowed) ) {
    return $_COOKIE['lang'];
  }
  else {
    return 'en';
  }
}

/**
 * @brief autoload magic as seen in http://de2.php.net/autoload
 */
function __autoload($class_name) {
  require_once 'lib/' . $class_name . '.php';
}

/**
 * @brief shortcut for calls to the template engine
 */
function show_index( $errorCode=NULL ) {
  $version    = '1529';
  $t          = getTextOfPage("index", LANG);
  $races      = translateRacesList(getRacesList(), LANG);
  require_once "views/index.php";
}

/**
 * @brief shortcut for calls to the template engine
 */
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