<?php

/*
 * some  defines
 */
define ('SYSTEM_MAINDIR', dirname(__FILE__) . DIRECTORY_SEPARATOR );

/********************************************************************************
 *
 * a tiny PSR-0 compliant autoloader - the most lightweight I've ever seen
 * but we use SplLoadClass too for specific subsystems, libraries
 */
spl_autoload_register(function($c){@include preg_replace('#\\\|_(?!.+\\\)#','/',$c).'.php';});
// First we load the SplClassLoader
require_once( ROOT . 'libs/SplClassLoader.php' );
// Second we load our batch autoloader that contains the autoloading configurations for the used libraries
require_once( ROOT . 'sys/Autoloader.php' );

// creating autoload class to create all necessery spl autoloader
$autoloader = new \sys\Autoloader();

// some libraries needs extra initializations
require_once( ROOT . "libs/adodb5/adodb.inc.php" );


/********************************************************************************
 *  Monologger
 */
$logger = new \Monolog\Logger("logging");
$streamHandler = new \Monolog\Handler\StreamHandler( ROOT . "log/mono.log" );
$dateFormat = "Y n j, g:i a";
$output = "%datetime% > %level_name% > %message% %context% %extra%\n";
$streamHandler->setFormatter(new \Monolog\Formatter\LineFormatter($output, $dateFormat));
$logger->pushHandler($streamHandler);

$oSystem = new \sys\System();
$logger->log(100, "=============== STARTING: ".$_SERVER['REQUEST_URI']);

//  starting the system
$oSystem->initialize();
$logger->log(100, '{index} SYSTEM has been initialized.');

//$db = NewADOConnection("mysql://wpv2api:3gv2api@192.168.14.1/mp_manhertz_api");

echo "proba...";

//  finishing the work...
$oSystem->finalize();
$logger->log(100, '{index} exiting.');

