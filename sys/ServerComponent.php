<?php
/*************************************************************
 *  @author   Tamas Manhertz
 *  @version  V0.99.20150201
 *************************************************************/

namespace sys;

interface ServerComponentInterface {

	public function getComponentName();

}

/*
 * Class Component
 */
class ServerComponent extends \sys\ServerObject implements \sys\ServerComponentInterface {
	/***********************************************
	 *   PROPERTIES
	 */
	protected
		/**
		 *  The central system object
		 * @var \sys\System
		 */
		$oSystem = null;

	/***********************************************
	 *   CONSTRUCT
	 ***********************************************/
	/**
     */
	public function __construct(\sys\System $oSystem) {
		if (!is_object($oSystem)) {
			throw new \Exception(
				$this->getComponentName() . " can't be created, no System has been given."
			);
		}

		$this->oSystem = $oSystem;
		parent::__construct();
	}

	/***********************************************
	 *   PROTECTED METHODS
	 ***********************************************/
	final protected function createComponent($sComponentName, $sComponentType = null, $bNULLComponent = true) {
		return $this->oSystem->createComponent($sComponentName, $sComponentType, $bNULLComponent);
	}
	final protected function getComponent($sComponentName) {
		return $this->oSystem->getComponent($sComponentName);
	}
	final protected function isComponent($sComponentName) {
		return $this->oSystem->isComponent($sComponentName);
	}

	protected function onCreate() {}
	protected function onDestroy() {}
	protected function onInitialize() {}
	protected function onFinalize() {}

	/***********************************************
	 *   PUBLIC METHODS
	 ***********************************************/

	public function getComponentName() {
		return "*ServerComponent*";
	}
}