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

// we need some system classes to continue
require_once ROOT . 'sys/ServerObject.php';
require_once ROOT . 'sys/SystemBase.php';

$sSystemClassname = "\\sys\\" . SYSTEM_ENTRY_POINT . 'System';
$sSystemClassFilename = ROOT . 'sys/pool/entry/' . SYSTEM_ENTRY_POINT . 'System.php';
if (!file_exists($sSystemClassFilename)) {
	throw new Exception(
		"SYSTEM FATAL: no system class file can be loaded for " . SYSTEM_ENTRY_POINT . " entry point."
	);
}
require_once $sSystemClassFilename;

// First we load our batch autoloader that contains the autoloading configurations for the used libraries
require_once( ROOT . 'sys/Autoloader.php' );

// creating autoload class to create all necessery spl autoloader
$autoloader = new \sys\Autoloader();

// system component autoloader
spl_autoload_register(
	function($sClassname) {
		$sFilename = preg_replace( '#\\\|_(?!.+\\\)#','/', $sClassname ) . '.php';

		$sSystemClassname = "\\sys\\" . SYSTEM_ENTRY_POINT . "System";
		/** @var $sSystemClassname \sys\SystemBase */
		if ($system = $sSystemClassname::getInstance()) {
			//  is the filepool already exists?
			/** @var $system \sys\SystemBase */
			if ($system->isComponent('Filepool')) {
				/** @var $oFilepool \sys\com\Filepool */
				$oFilepool = $system->getComponent('Filepool');
				$sFilepoolFilename = str_replace("sys/", "", $sFilename);
				$oFilepoolResult = $oFilepool->getPath($sFilepoolFilename);
				if ($oFilepoolResult->getFileFound()) {
					$sFilename = $oFilepoolResult->getFilepoolPath() . $sFilepoolFilename;
				}
			}

		}

		$sFullpath = ROOT . $sFilename;
		if (file_exists($sFullpath)) {
			require_once $sFullpath;
		}
	}
);


/********************************************************************************
 *  starting the system
 */
/** @var $oSystem \sys\SystemBase */
$oSystem = new $sSystemClassname();

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

