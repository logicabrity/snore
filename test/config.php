<?php

/**
 * basedir of the application
 */
define('PROJECT_DIR', realpath('../'));

require_once 'PHPUnit/Framework.php';

function __autoload($class_name) {
  require_once PROJECT_DIR.'/app/' . $class_name . '.php';
}

?>
