<?php
/**
 * @file
 * @brief configuration file.
 *
 * Define global constants used in the whole application,
 * and set up the template engine too.
 */

error_reporting(E_ALL);

/**
 * @brief Base directory of the application.
 *
 * Used for includes, like in \link mySmarty \endlink.
 */
define('PROJECT_DIR', getcwd());

/* require smarty template engine and spyc yaml library */
require_once PROJECT_DIR . '/libs/smarty/Smarty.class.php';
require_once PROJECT_DIR . '/libs/spyc.php';

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

/**
 * @brief Set up the template engine Smarty.
 *
 * A flexible way to set up Smarty is to extend the base class
 * and initialize the Smarty environment. So instead of repeatedly
 * setting directory paths, assigning the same vars, etc., it is
 * done in one place.
 *
 * Described in http://www.smarty.net/manual/en/installing.smarty.extended.php
 *
 * SNORE uses own smarty-plugins which are stored in the directory
 * which gets appended to the plugins_dir array of the base class.
 * Described in http://www.smarty.net/manual/en/variable.plugins.dir.php
 */
class mySmarty extends Smarty {
	/**
	 * @brief This is the name of the default template directory.
	 *
	 * Described in http://www.smarty.net/manual/en/api.variables.php#variable.template.dir
	 */
	public $template_dir;

	/**
	 * @brief Directory in which Smarty stores the compiled templates.
	 *
	 * Directory must be writable by the web server!
	 * Described in http://www.smarty.net/manual/en/variable.compile.dir.php
	 */
	public $compile_dir;

	/**
	 * @brief Directory used to store template-configuration files.
	 *
	 * SNORE does not use any of those for now, but they may be useful.
	 * Described in http://www.smarty.net/manual/en/variable.config.dir.php
	 */
	public $config_dir;

	/**
	 * @brief Directory in which Smarty stores the cache.
	 *
	 * Must be writable by the web server!
	 * Described in http://www.smarty.net/manual/en/variable.cache.dir.php
	 */
	public $cache_dir;

	/**
	 * @brief Toggles an useful debubbing console.
	 *
	 * I really recommend to set this to TRUE when working on the templates,
	 * especially during refactoring.
	 * Described in http://www.smarty.net/manual/en/variable.debugging.php
	 */
	public	$debugging;

	/**
	 * @brief Toggles caching of rendered templates.
	 *
	 * It is wise to disable caching during the development phase, but remember
	 * to enable it (set to 1) for production, because it REALLY increases the
	 * speed of the application.
	 * Described in http://www.smarty.net/manual/en/variable.caching.php
	 */
	public $caching;

	/**
	 * @brief Check to see if a template has to be recompiled.
	 *
	 * Slight overhead, can be set to FALSE if in production mode, but don't
	 * be surprised if you don't see the changes you have made in the templates
	 * after their first compilation. You have to delete the cache AND the
	 * compiled templates then.
	 * Described in http://www.smarty.net/manual/en/variable.compile.check.php
	 */
	public $compile_check;

	/**
	 * @brief Time until cache expires in seconds.
	 *
	 * The cache never expires when cache_lifetime is set to -1. This is
	 * the right thing to do in SNORE, because of the static nature of it's content.
	 * Again, remember to delete the cache and the compiled templates if you
	 * tinkered with the templates in production.
	 * Described in http://www.smarty.net/manual/en/variable.cache.lifetime.php
	 */
	public $cache_lifetime;

	/**
	 * @brief Use the constructor to set all desired variables.
	 */
	public function __construct() {
        $this->template_dir = PROJECT_DIR.'/views/templates';
        $this->compile_dir  = PROJECT_DIR.'/views/tmp/templates_c';
        $this->config_dir   = PROJECT_DIR.'/views/configs';
        $this->cache_dir    = PROJECT_DIR.'/views/tmp/cache';
        $this->plugins_dir[]= PROJECT_DIR.'/views/helpers';

        $this->debugging        = FALSE;
        $this->caching          = 1;
        $this->compile_check    = FALSE;
        $this->cache_lifetime   = -1; // cache never expires
    }
}
?>
