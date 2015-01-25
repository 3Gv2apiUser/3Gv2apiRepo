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

/********************************************************************************
 *
 * a tiny PSR-0 compliant autoloader - the most lightweight I've ever seen
 * but we use SplLoadClass too for specific subsystems, libraries
 */
spl_autoload_register(function($c){@include preg_replace('#\\\|_(?!.+\\\)#','/',$c).'.php';});
require_once('libs/SplClassLoader.php');

$v2ApiAutoloader = new SplClassLoader('Sys', '/');
$v2ApiAutoloader->register();

$psrAutoloader = new SplClassLoader('Psr', 'libs');
$psrAutoloader->register();
$monologAutoloader = new SplClassLoader('Monolog', 'libs');
$monologAutoloader->register();

$adodbAutoloader = new SplClassLoader('adodb', 'libs/adodb5');
$adodbAutoloader->register();
require_once("libs/adodb5/adodb.inc.php");

$smartyAutoloader = new SplClassLoader('smarty', 'libs/smarty');
$smartyAutoloader->register();

/********************************************************************************
 *  Monologger
 */
$logger = new \Monolog\Logger("logging");
$streamHandler = new \Monolog\Handler\StreamHandler("log/mono.log");
$dateFormat = "Y n j, g:i a";
$output = "%datetime% > %level_name% > %message% %context% %extra%\n";
$streamHandler->setFormatter(new \Monolog\Formatter\LineFormatter($output, $dateFormat));
$logger->pushHandler($streamHandler);


$oSystem = new \Sys\System();
$logger->log(100, "=============== STARTING: ".$_SERVER['REQUEST_URI']);

//  starting the system
$oSystem->initialize();
$logger->log(100, '{index} SYSTEM has been initialized.');

$db = NewADOConnection("mysql://wpv2api:3gv2api@192.168.14.1/mp_manhertz_api");

echo "proba...";

//  finishing the work...
$oSystem->finalize();
$logger->log(100, '{index} exiting.');


?>