<?php

/*************************************************************�
 *  @package  Manhertz
 *  @author   Tamas Manhertz
 *  @version  V0.99.20150305
 *************************************************************/


/*
 *  This is just a very basic and very simple time measurement.
 */
$iTime_start = microtime(true);

/********************************************************************************
 * a tiny PSR-0 compliant autoloader
 * but we use SplLoadClass too for specific subsystems, libraries
 */
spl_autoload_register(function($c) {
	$sFilename = ROOT . preg_replace('#\\\|_(?!.+\\\)#','/',$c).'.php';
	if (file_exists($sFilename)) {
		require_once $sFilename;
	}
});

// Second we load our batch autoloader that contains the autoloading configurations for the used libraries
require_once( ROOT . 'sys/Autoloader.php' );

// creating autoload class to create all necessery spl autoloader
$autoloader = new \sys\Autoloader();

/********************************************************************************
 *  starting the system
 */
$oSystem = new \sys\System();

//  starting the system
$oSystem->initialize();

/********************************************************************************
 *  Monologger
 */
$logger = new \Monolog\Logger("logging");
$streamHandler = new \Monolog\Handler\StreamHandler( ROOT . "log/mono.log" );
$dateFormat = "Y n j, g:i a";
$output = "%datetime% > %level_name% > %message% %context% %extra%\n";
$streamHandler->setFormatter(new \Monolog\Formatter\LineFormatter($output, $dateFormat));
$logger->pushHandler($streamHandler);

$logger->log(100, "=============== STARTING: ".$_SERVER['REQUEST_URI']);

$logger->log(100, '{index} SYSTEM has been initialized.');

//$db = NewADOConnection("mysql://wpv2api:3gv2api@192.168.14.1/mp_manhertz_api");

echo "proba...";

//  finishing the work...
$oSystem->finalize();
$logger->log(100, '{index} exiting.');

