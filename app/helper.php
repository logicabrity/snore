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
  require_once 'app/' . $class_name . '.php';
}

/**
 * @brief shortcut for calls to the template engine
 */
function show_index() {
    $template = new mySmarty();
    $cache_id = LANG;

    if( !$template->is_cached('index.tpl', $cache_id) ) {
        $template->assign('version', '1362');
        $template->assign('t', UserInterface::get('index'));
        $template->assign('races', Races::list_all());
    }
    $template->display('index.tpl', $cache_id);
}

/**
 * @brief shortcut for calls to the template engine
 */
function show_roster($race_id, $loaded_team=NULL) {
    $template = new mySmarty();
    $cache_id = LANG . $race_id;

    if ( $loaded_team != NULL ) {
      $template->caching = 0;
      $template->assign('team', $loaded_team);
    // to prevent showing a cached (empty!) roster:
      $cache_id = NULL;
    }

    if( !$template->is_cached('roster.tpl', $cache_id) ) {
        $template->assign('t', UserInterface::get('roster'));
        $template->assign('skills', Skills::all_nested());
        $template->assign('race', Races::get($race_id));
    }
    $template->display('roster.tpl', $cache_id);
}

/**
 * @brief template function to display an error message
 */
function insert_getError_race($params) {
    global $getError_race;
    if ( $getError_race ) {
        return $params['message'];
    }
}

/**
 * @brief template function to display an error message
 */
function insert_getError_upload($params) {
    global $getError_upload;
    if ( $getError_upload ) {
        return $params['message'];
    }
}

?>
