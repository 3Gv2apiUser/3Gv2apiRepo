<?php
/*************************************************************�
 *  @package  Manhertz
 *  @author   Tamas Manhertz
 *  @version  V0.99.20150305
 *************************************************************/
namespace sys;

/*
 * Class System
 */
interface SystemBaseInterface
{

	public static function getInstance();

	//  component handling
	public function createComponent($sComponentName, $sComponentType = null, $bNULLComponent = true);
	public function getComponent($sComponentName, $bReturnNULLComponent = true);
	public function isComponent($sComponentName);

}

class SystemBase extends \sys\ServerObject implements \sys\SystemBaseInterface
{
	/***********************************************
	 *   PROPERTIES
	 ***********************************************/
	protected static
		/**
		 * Singleton construction.
		 * @var SystemBase
		 */
		$_oInstance;

	protected
		/**
		 *  Component storage, to store all created component objects
		 * @var array (of strings)
		 */
		$aComponents = array();

	/***********************************************
	 *   CONSTRUCT
	 ***********************************************/

	/**
	 * this is special for System component, it stores itself, no parameters
	 */
	public function __construct() {
		// storing the object as singleton
		if (!isset(self::$_oInstance))  self::$_oInstance = $this;

		parent::__construct();
	}

	/***********************************************
	 *   PROTECTED METHODS
	 ***********************************************/
	protected function onCreate() {
		// this is the absolute first component in the system
		$this->aComponents["SYSTEM"] = $this;
	}

	protected function onDestroy() {
	}

	/**
	 * the initialization of the system will create the necessery components
	 */
	protected function onInitialize() {
		$this->createComponent("NULL");
//		$this->createComponent("LOG");
//		$this->createComponent("Config");
		$this->createComponent("Filepool");
		$this->createComponent("Database");

		$this->initializeComponents();
	}


	protected function onFinalize() {
		unset($this->aComponents["SYSTEM"]);
		$this->finalizeComponents();
	}

	/**
	 *  Initializes all non-initialized components recursively. (If an initialized
	 * component creates a new component, it will be initialized too.)
	 */
	protected function initializeComponents() {
		$_bInit = true;
		while ($_bInit) {
			$_bInit = false;
			/** @var $_oComponent \sys\ServerComponent */
			foreach( $this->aComponents as $_oComponent ) {
				if ($_oComponent->isToInitializing()) {
					$_oComponent->initialize();
					$_bInit = true;
				}
			}
		}
	}

	/**
	 *  Finalize all existing components.
	 */
	protected function finalizeComponents() {
		/** @var $_oComponent \sys\ServerComponent */
		foreach( $this->aComponents as $_oComponent ) {
			if (!$_oComponent->isToInitializing())
				$_oComponent->finalize();
		}
	}

	/***********************************************
	 *   PUBLIC METHODS
	 ***********************************************/

	final public static function getInstance() {
		return self::$_oInstance;
	}
	/**
	 * Creates a component. The $sComponentName will be a unique name (identifier) in the system. If the
	 * type has not been given, it will use the same as the given component name. The optional NULLComponent
	 * parameter specifies whether a NULL component will be created if the specified component type is not
	 * defined in the system (like the program code is missing).
	 *
	 * @param string $sComponentName
	 * @param null $sComponentType
	 * @param bool $bNULLComponent
	 *
	 * @return mixed
	 */
	final public function createComponent($sComponentName, $sComponentType = null, $bNULLComponent = true) {

		//  if a component with the same name has already been created earlier, it will be returned
		if (array_key_exists($sComponentName, $this->aComponents)) {
			return $this->aComponents[$sComponentName];
		}

		$sClassname = "\\sys\\com\\" . $sComponentName;
		if (!class_exists($sClassname)) {
			// if autoloader couldn't load the component class file, warning will be triggered and NULL component will be generated
			return $this->getComponent("NULL");
		}

		// stores the component
		$_oComponent = new $sClassname($this);
		$this->aComponents[$sComponentName] = $_oComponent;
		return $_oComponent;
	}

	/**
	 *  Whether a component exists with the specified name.
	 *
	 * @param $sComponentName
	 *
	 * @return bool
	 */
	final public function isComponent($sComponentName) {
		return array_key_exists( $sComponentName, $this->aComponents );
	}

	/**
	 * Returns the component from the system that is called like '$sComponentName'.
	 * If $bCreateNULLComponent is true (by default) then the NULL component will be returned if a component
	 * with the given name does not exist in the system.
	 *
	 * @param string $sComponentName
	 * @param bool $bCreateNULLComponent
	 *
	 * @return \sys\ServerComponent
	 */
	final public function getComponent($sComponentName, $bReturnNULLComponent = true) {
		if (array_key_exists($sComponentName, $this->aComponents)) {
			return $this->aComponents[$sComponentName];
		} else {
			if (!array_key_exists("NULL", $this->aComponents)) {
				$this->createComponent("NULL");
			}
			if ($bReturnNULLComponent) {
				return $this->aComponents["NULL"];
			}
		}
		return null;
	}

}
