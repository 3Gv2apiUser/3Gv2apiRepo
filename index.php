<?php
//============================================================+
// File name   : index.php
//
// Description : startup
//
//============================================================+
/**
 * Created by PhpStorm.
 * User: Pylon
 * Date: 2014.05.05.
 *
 * base index.php - startup for controllers and views
 */

/*
 * some  defines
 */
define ('SYSTEM_MAINDIR', dirname(__FILE__) . DIRECTORY_SEPARATOR );
define ('SYSTEM_CLASSES', SYSTEM_MAINDIR . 'classes' . DIRECTORY_SEPARATOR );
define ('SMARTY_MAINDIR', dirname(__FILE__) . DIRECTORY_SEPARATOR  );
define ('SMARTY_DIR',  SMARTY_MAINDIR . 'libs'. DIRECTORY_SEPARATOR);

/*
 * a tiny PSR-0 compliant autoloader - the most lightweight I've ever seen
 * but we use SplLoadClass too for specific subsystems, libraries
 */
spl_autoload_register(function($c){@include preg_replace('#\\\|_(?!.+\\\)#','/',$c).'.php';});
require_once('libs/SplClassLoader.php');

$v2ApiAutoloader = new SplClassLoader('Sys', '/');
$v2ApiAutoloader->register();

$oSystem = new \Sys\System();

//  starting the system
$oSystem->initialize();

echo "proba...";

//  finishing the work...
$oSystem->finalize();


?>