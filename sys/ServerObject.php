<?php
/*************************************************************�
 *  @package  Manhertz
 *  @author   Tamas Manhertz
 *  @version  V0.99.20140505
 *************************************************************/
namespace sys;

//============================================================+
// ServerObject
//
// Description : base abstract class for any server objects
//
//  Definition: ServerObject
//    Such an object that encapsulates special services/functions
//   and it provides some standard common methods
//
//============================================================+
interface ServerObjectInterface
{
	public function initialize();
	public function isToInitializing();
	public function finalize();
}


/*
 * Class ServerObject
 */
abstract class ServerObject implements \sys\ServerObjectInterface {
	/***********************************************
	 *   PROPERTIES
	 ***********************************************/
    protected
        /**
         *  whether the component still has to be initialized
         * @var boolean
         */
        $bToInitializing = true;

	/***********************************************
	 *   CONSTRUCT
	 ***********************************************/
	public function __construct() {
		$this->onCreate();
	}

	public function __destroy() {
		$this->onDestroy();
	}

	/***********************************************
	 *   PROTECTED METHODS
	 ***********************************************/
	final protected function unsetToInitializing() {
		$this->bToInitializing = false;
	}

    abstract protected function onCreate();
    abstract protected function onInitialize();
    abstract protected function onFinalize();
    abstract protected function onDestroy();

	/***********************************************
	 *   PUBLIC METHODS
	 ***********************************************/
    final public function initialize() {
	    $this->unsetToInitializing();
        $this->onInitialize();
    }
	final public function finalize() {
        $this->onFinalize();
    }
    final public function isToInitializing() {
        return $this->bToInitializing;
    }

}
