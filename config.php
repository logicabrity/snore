<?php
/**
 * @file
 * @brief configuration file.
 *
 * Define global constants used in the whole application.
 */

error_reporting(E_ALL);

/**
 * @brief Base directory of the application.
 *
 * Used for includes.
 */
define('PROJECT_DIR', getcwd());

/* require spyc yaml library */
require_once PROJECT_DIR . '/lib/spyc.php';

require_once PROJECT_DIR . '/lib/helper.php';
require_once PROJECT_DIR . '/lib/Translation.php';
require_once PROJECT_DIR . '/lib/Races.php';
require_once PROJECT_DIR . '/lib/Skills.php';
require_once PROJECT_DIR . '/lib/UserInterface.php';

/**
 * @brief Languages that are safe to use for SNORE.
 * 
 * This is to control and restrict the possible directories
 * in which the application may want to open files. Remember:
 * Never trust user input!
 */
$allowed_languages = array('en','de','fr');

/**
 * @brief The language the user has set.
 *
 * Important global variable, that controls which
 * localisation-files are parsed, and thus the
 * language of the user-interface.
 */
define('LANG', check_lang($allowed_languages));

?>