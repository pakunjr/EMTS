<?php session_start(); date_default_timezone_set('Asia/Manila');

/**
 * Turn on error reporting.
 */
error_reporting(E_ALL);

/**
 * Define important CONSTANT VARIABLES.
 */
define('DS', DIRECTORY_SEPARATOR);
define('ROOT', dirname(dirname(__FILE__)));
define('PLUGINS_DIR', ROOT.DS.'plugins');
define('LIBRARY_DIR', ROOT.DS.'library');
define('APPLICATION_DIR', ROOT.DS.'application');
define('CONTROLLERS_DIR', APPLICATION_DIR.DS.'controllers');
define('MODELS_DIR', APPLICATION_DIR.DS.'models');
define('VIEWS_DIR', APPLICATION_DIR.DS.'views');
define('PUBLIC_DIR', ROOT.DS.'public');

/**
 * Automagically load classes.
 */
function autoloadClasses ($classname) {
    $paths = array(
            CONTROLLERS_DIR.DS.$classname.'.php'
            ,MODELS_DIR.DS.$classname.'.php'
            ,VIEWS_DIR.DS.$classname.'.php'
            ,LIBRARY_DIR.DS.$classname.'.class.php'
        );
    foreach ( $paths as $a ) {
        if ( file_exists($a) ) require_once($a);
    }
} //End function autoloadClasses
spl_autoload_register('autoloadClasses');

/**
 * Get configuration file.
 */
$f = LIBRARY_DIR.DS.'config.php';
if ( file_exists($f) ) require_once($f);
else echo '<div>Error: Your main configuration file is missing.</div>';

define('TEMPLATE_DIR', PUBLIC_DIR.DS.'template'.DS.SYSTEM_TEMPLATE);
define('FORMS_DIR', TEMPLATE_DIR.DS.'forms');
define('ERRORS_DIR', TEMPLATE_DIR.DS.'errors');
