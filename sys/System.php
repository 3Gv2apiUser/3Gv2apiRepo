<?php
/*************************************************************
 *  @author   Tamas Manhertz
 *  @version  V0.99.20140505
 *************************************************************/
namespace sys;

/*
 * Class System
 */
class System extends \sys\ServerObject
{
	protected
		/**
		 *  Component storage, to store all created component objects
		 * @var array (of strings)
		 */
		$aComponents = array();

	/**
	 * this is special for System component, it stores itself
	 */
	public function __construct() {
		$this->oSystem = $this;
		$this->onCreate();
	}
	/**
	 */
	public function __destruct() {
		$this->onDestroy();
	}


	public function onCreate() {
		// this is the absolute first component in the system
		$this->aComponents["SYSTEM"] = $this;
	}

	public function onDestroy() {
	}

	public function createComponent($sComponentName) {
		if (array_key_exists($sComponentName, $this->aComponents))
			return $this->aComponents[$sComponentName];

		if (!class_exists("\\sys\\com\\".$sComponentName, true))
		{
			// if autoloader couldnt load the component class file, warning will be triggered and NULL component will be generated
			$sComponentName = "NULL";
		}

		// stores the component
		$sComponentName = "\\sys\\com\\".$sComponentName;
		$_oComponent = new $sComponentName($this);
		$this->aComponents[$sComponentName] = $_oComponent;
		return $_oComponent;
	}

	public function isComponent($sComponentName) {
		return array_key_exists( $sComponentName, $this->aComponents );
	}

	public function getComponent($sComponentName) {
		if (array_key_exists($sComponentName, $this->aComponents))
			return $this->aComponents[$sComponentName];
		else
		{
			if (!array_key_exists("NULL", $this->aComponents))
			{
				$this->createComponent("NULL");
			}
			return $this->aComponents["NULL"];
		}
	}

// the initialization of the system will create the necessery components
// for the the modell (its like an MVC - with some extensions and generalizations)
	public function onInitialize() {

		$this->createComponent("NULL");
//		$this->createComponent("ClientProperties");
//		$this->createComponent("Config");
//		$this->createComponent("Database");
//		$this->createComponent("Session");
//		$this->createComponent("Router");
//		$this->createComponent("Buffer");

		$this->initializeComponents();
	}


	public function onFinalize() {

		unset($this->aComponents["SYSTEM"]);
		$this->finalizeComponents();

	}


//  Component methods
	public function initializeComponents() {

		$_bInit = true;
		// this is now a very simple initialization too but all components must be
		// initialized - that ones too that an another component creates under
		// initialization (like a possible config component)
		while ($_bInit)
		{
			$_bInit = false;
			foreach( $this->aComponents as $_sComponentName => $_oComponent )
				if (!$_oComponent->isInitialized())
				{
					$_oComponent->initialize();
					$_bInit = true;
				}
		}

	}

// finalization is simple, no new components....
	public function finalizeComponents() {

		foreach( $this->aComponents as $_sComponentName => $_oComponent )
			if ($_oComponent->isInitialized())
				$_oComponent->finalize();

	}


}
