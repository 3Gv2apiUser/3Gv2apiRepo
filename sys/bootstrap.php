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

$sSystemClassname = "\\sys\\com\\" . SYSTEM_ENTRY_POINT . '\System';
$sSystemClassFilename = ROOT . 'sys' . DIRECTORY_SEPARATOR. 'com' . DIRECTORY_SEPARATOR. SYSTEM_ENTRY_POINT . DIRECTORY_SEPARATOR. 'System.php';
if (!file_exists($sSystemClassFilename)) {
	throw new Exception(
		"SYSTEM FATAL: no system class file can be loaded for " . SYSTEM_ENTRY_POINT . " entry point."
	);
}
/** @noinspection PhpIncludeInspection */
require_once $sSystemClassFilename;

// First we load our batch autoloader that contains the autoloading configurations for the used libraries
require_once( ROOT . 'sys/Autoloader.php' );

// creating autoload class to create all necessery spl autoloader
$autoloader = new \sys\Autoloader();

// system component autoloader
spl_autoload_register(
	function($sClassname) {
//		$sFilename = preg_replace( '#\\\|_(?!.+\\\)#','/', $sClassname ) . '.php';
		$sFilename = str_replace( '\\','/', $sClassname ) . '.php';

		$sSystemClassname = "\\sys\\com\\" . SYSTEM_ENTRY_POINT . "\\System";
		/** @var $sSystemClassname \sys\SystemBase */
		if ($system = $sSystemClassname::getInstance()) {
			//  is the filepool already exists?
			/** @var $system \sys\SystemBase */
			if ($system->isComponent('Filepool')) {
				/** @var $oFilepool \sys\com\Filepool */
				$oFilepool = $system->getComponent('Filepool');
				$sFilepoolFilename = str_replace("sys/", "", $sFilename);
				$oFilepoolResult = $oFilepool->getPath($sFilepoolFilename);
				if ($oFilepoolResult->isFileFound()) {
					$sFilename = $oFilepoolResult->getFilepoolPath() . $sFilepoolFilename;
				}
			}

		}

		$sFullpath = ROOT . $sFilename;
		if (file_exists($sFullpath)) {
			/** @noinspection PhpIncludeInspection */
			require_once $sFullpath;
		}
	}
);


/********************************************************************************
 *  starting the system
 */
/** @var $oSystem \sys\SystemBase */
$oSystem = new $sSystemClassname();

$logger = new \Monolog\Logger("logging");
$streamHandler = new \Monolog\Handler\StreamHandler( ROOT . "log/mono.log" );
$dateFormat = "Y n j, g:i a";
$output = "%datetime% > %level_name% > %message% %context% %extra%\n";
$streamHandler->setFormatter(new \Monolog\Formatter\LineFormatter($output, $dateFormat));
$logger->pushHandler($streamHandler);

$logger->log(100, "=============== STARTING: ".(array_key_exists('REQUEST_URI', $_SERVER) ? $_SERVER['REQUEST_URI'] : '') );

$logger->log(100, '{index} SYSTEM has been initialized.');

function syLog( $sLogLine, $iType = 100 )
{
	global $logger;

	//  for now...
	if ($iType != 100) $iType = 100;
	/*  checks whether the LOG component is already loaded  */
	if (isset($logger))
	{
		$logger->log( $iType, $sLogLine );

		/* checks whether this site needs to store every log records immediately - main for DEBUG reason  */
	}
}


//  starting the system
$oSystem->initialize();

/********************************************************************************
 *  router for api
 */
/*
$authmgr = new \sys\mod\auth\AuthManager($oSystem);
$r = $authmgr->doAuth($credentials);
var_dump($r);
*/

//echo "proba...";










//  finishing the work...
$oSystem->finalize();
$logger->log(100, '{index} exiting.');

