<?php
/**
 * Created by PhpStorm.
 * User: Pylon
 * Date: 2015.06.01.
 * Time: 0:45
 */

namespace sys\com;
use sys\ServerComponent;
use sys\mod\xml\processor;

interface ConfigInterface {

	public function processConfigXML($configFilename);

}

/**
 * Class Config
 * @package sys\com
 */
class Config extends ServerComponent {

	const CONFIG_BASE_PATH = 'cfg/';
	/**
	 *  List of config files that should be all processed.
	 * @var array
	 */
	protected $configFiles = array(
		"config_base.xml",
		"api/api_config.xml"
	);

	/***********************************************
	 *   PROPERTIES
	 ***********************************************/

	/***********************************************
	 *   PROTECTED METHODS
	 ***********************************************/
	protected function onCreate() {
	}

	protected function onInitialize() {
		foreach($this->configFiles as $configFile) {
			if ($this->oSystem->isComponent('Filepool')) {
				/** @var $oFilepool \sys\com\Filepool */
				$oFilepool = $this->oSystem->getComponent('Filepool');
				$oFilepoolResult = $oFilepool->getPath($configFile);
				if ($oFilepoolResult->isFileFound()) {
					$configFile = ROOT . self::CONFIG_BASE_PATH . $oFilepoolResult->getFilepoolPath() . $configFile;
				} else {
					$configFile = ROOT . self::CONFIG_BASE_PATH . $configFile;
				}
			} else {
				$configFile = ROOT . self::CONFIG_BASE_PATH . $configFile;
			}
			if ($this->processConfigXML($configFile)) {
				$this->setConfigFileProcessed($configFile);
			}
		}
	}

	protected function isConfigFileProcessed($configFile) {
		return ( array_key_exists($configFile, $this->configFiles) && ($this->configFiles[$configFile] == true) ? true : false );
	}

	protected function setConfigFileProcessed($configFile) {
		$this->configFiles[$configFile] = true;
	}

	/***********************************************
	 *   PUBLIC METHODS
	 ***********************************************/
	/**
	 * @param string $configFilename
	 *
	 * @return bool
	 */
	public function processConfigXML($configFilename){

		if ($configFilename[0] != "/") {
			$configFilename = ROOT . self::CONFIG_BASE_PATH . $configFilename;
		}

		if (!file_exists($configFilename)) {
			syLog( "Config: ".$configFilename." does not exist", E_WARNING);
			return false;
		}

		if ($this->isConfigFileProcessed($configFilename)) {
			syLog( "Config: ".$configFilename." does has already been processed.");
			return false;
		}

		syLog( "Config: processing '".$configFilename."' file." );

		/*  Measuring the time elapsed  */
		$_iTimeStart = microtime(true);

		/*  Reading the XML config file  */
		$_sConfig = file_get_contents( $configFilename );
		syLog( "Config: file length is ".strlen($_sConfig) );

		/*  Processing the string as an XML document  */
		$_oXMLTree = new \DOMDocument( "1.0" );
		if (!$_oXMLTree->loadXML( $_sConfig )) {
			syLog("Config: XML ERROR. '".$configFilename."' file has not been processed." );
			return false;
		}

		$xmlProcessor = new processor($this->oSystem, $_oXMLTree);

		//  Processing the XML Tree
		$xmlProcessor->process();

		/*  how long has it taken?  */
		$_iTimeDiff =  microtime(true) - $_iTimeStart;
		syLog( "Config: XML config '".$configFilename."' has been read, processing time: ".$_iTimeDiff );

		$this->setConfigFileProcessed($configFilename);
		return true;
	}
}