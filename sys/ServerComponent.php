<?php
/*************************************************************�
 *  @package  Manhertz
 *  @author   Tamas Manhertz
 *  @version  V0.99.20150305
 *************************************************************/

namespace sys;

interface ServerComponentInterface {

	public function setComponentName($componentName);
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
		 * @var \sys\SystemBase
		 */
		$oSystem = null;

	/**
	 * Name of this component
	 *
	 * @var string
	 */
	protected $componentName = '[COMPONENT_NOT_INITIALIZED]';
	/***********************************************
	 *   CONSTRUCT
	 ***********************************************/
	/**
	 * @param SystemBase $oSystem
	 *
	 * @throws \Exception
	 */
	public function __construct(\sys\SystemBase $oSystem) {
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
	/**
	 * @param      $sComponentName
	 * @param null $sComponentType
	 * @param bool $bNULLComponent
	 *
	 * @return mixed
	 */
	final protected function createComponent($sComponentName, $sComponentType = null, $bNULLComponent = true) {
		return $this->oSystem->createComponent($sComponentName, $sComponentType, $bNULLComponent);
	}

	/**
	 * @param      $sComponentName
	 * @param bool $bReturnNULLComponent
	 *
	 * @return ServerComponent
	 */
	final protected function getComponent($sComponentName, $bReturnNULLComponent = true) {
		return $this->oSystem->getComponent($sComponentName, $bReturnNULLComponent);
	}

	/**
	 * @param $sComponentName
	 *
	 * @return bool
	 */
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

	/**
	 * Sets the component name
	 *
	 * @param string $componentName
	 */
	final public function setComponentName($componentName) {
		$this->componentName = $componentName;
	}

	/**
	 *  Tells what the component name is
	 *
	 * @return string
	 */
	final public function getComponentName() {
		return $this->componentName;
	}

	/**
	 *  Magic method to handle all non-existing method request.
	 *
	 * @param $methodName
	 * @param $methodParams
	 *
	 * @return bool|mixed|null
	 */
	function __call( $methodName, $methodParams ) {

		if ((!in_array($methodName, array("_set", "_unset", "_call", "_isset", "_unset"))) &&
			(method_exists($this, "_".$methodName)))
		{
/*			//  Checking the necessery rights if the component specifies it/them as a precondition of the method
			if (in_array($methodName, array_keys($this->aRights)))
			{
				if ($_oUserComponent = $this->getComponent( "USER" ))
				{
					if (!$_oUserComponent->hasRight($this->aRights[$methodName]))
					{
						syLog( ( in_array($methodName, $this->aNoRightErrorMessages) ? $this->aNoRightErrorMessages[$methodName] : MSG_ERROR_USER_NO_RIGHTS ) );
						return null;
					}
				}
				else
				{
					syLog( "Component ".$this->componentName." method ".$methodName." need a user to be logged in with the necessery rights." );
					return null;
				}
			}
*/
			/*  Method can be executed  */
			return call_user_func_array(array(&$this, "_".$methodName), ( $methodParams )  );

		} elseif ((substr($methodName,0,4)=='set_') && (strlen($methodName)>4) && count($methodParams)>0 ) {

			$propertyName = substr($methodName, strlen($methodName)-4);
			syLog( "SysComp: setting not defined component property (".$this->componentName."/".$propertyName.")." );
			$this->$propertyName = $methodParams[0];
			return null;

		} elseif ((substr($methodName,0,4)=='get_') && (strlen($methodName)>4) ) {

			$propertyName = substr($methodName, strlen($methodName)-4);
			syLog( "SysComp: getting not defined component property (".$this->componentName."/".$propertyName.")." );
			return $this->$propertyName;

		} else {

			//  if no method exists but a pipe is defined with the same name, start it!
			if (isset($this->$methodName) /*&& is_pipe($this->$methodName)*/)
			{
/*				syLog( "Component calling: ".$this->."/".$sMname.", starting pipe", LOG_LEVEL_DEBUG_INFO );

				//  gets the pipe object
				$_oPipe = $this->$methodName;

				//  the method parameters interpreted as an input record array
				$_aInputRecord = ( is_array($mMparams[0]) ? $mMparams[0] : (is_object($mMparams[0]) ? (array) $mMparams[0] : $mMparams ) );
				syLog(serialize($_aInputRecord), LOG_LEVEL_DEBUG_INFO );

				//  starts the pipe
				$_eState = $_oPipe->start( $_aInputRecord );

				//  examine the result state
				if ($_eState == PIPE_FINISHED)
				{
					syLog( "Component PIPE finished ".$this->sName."/".$sMname, LOG_LEVEL_DEBUG_INFO );
					//  the pipe result will be the return value
					return $_oPipe->getOutput();
				}
				else
					syLog( "Pipe is not finished, code: ".$_eState, LOG_LEVEL_DEBUG_INFO );
*/
				return false;
			}
//			syLog( "Component ".$this->componentName." method ".$methodName." does not exist.", LOG_LEVEL_DEBUG_INFO );
			return false;
		}
	}
}