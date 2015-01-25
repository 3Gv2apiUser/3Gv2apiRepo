<?php
/*************************************************************�
 *  @package  Manhertz
 *  @author   Tamas Manhertz
 *  @version  V0.99.20140505
 *************************************************************/
namespace sys;

//============================================================+
// ServerComponent
//
// Description : abstract class for component definitions
//
//  Definition: Component
//    Such an object that encapsulates special services/functions
//   and it provides some standard common methods
//
//============================================================+
interface ServerComponentInterface
{
    // some "on" event so we don't need to overload some standard methods
    public function onCreate();
    public function onInitialize();
    public function onFinalize();
    public function onDestroy();

    //  component handling
    public function createComponent($sComponentName);
    public function getComponent($sComponentName);
    public function setInitialized();
    public function isInitialized();
}


/*
 * Class ServerComponent
 */
abstract class ServerComponent implements ServerComponentInterface
{

    protected
        /**
         *  The central system component object
         * @var object
         */
        $oSystem = null;

    protected
        /**
         *  whether the component has already been initialized
         * @var boolean
         */
        $bInitialized = false;


    abstract public function onCreate();
    abstract public function onInitialize();
    abstract public function onFinalize();
    abstract public function onDestroy();

    abstract public function createComponent($sComponentName);
    abstract public function getComponent($sComponentName);

    public function __construct() {
        $this->onCreate();
    }
    public function __destroy() {
        $this->onDestroy();
    }
    public function initialize()
    {
        $this->setInitialized();
        $this->onInitialize();
    }
    public function finalize()
    {
        $this->onFinalize();
    }
    public function setInitialized()
    {
        $this->bInitialized = true;
    }
    public function isInitialized()
    {
        return $this->bInitialized;
    }

}
