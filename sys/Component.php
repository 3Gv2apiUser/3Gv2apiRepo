<?php
/*************************************************************
 *  @author   Tamas Manhertz
 *  @version  V0.99.20140505
 *************************************************************/

namespace sys;

/*
 * Class Component
 */
class Component extends \sys\ServerObject
{
    /**
     */
    public function __construct($oSystem) {
        $this->oSystem = $oSystem;
    }


    public function onCreate() {
    }
    public function onDestroy() {
    }
    public function onInitialize() {
    }
    public function onFinalize() {
    }

    public function createComponent($sComponentName) {
        return $this->oSystem->createComponent($sComponentName);
    }
    public function getComponent($sComponentName) {
        return $this->oSystem->getComponent($sComponentName);
    }
    public function isComponent($sComponentName) {
        return $this->oSystem->isComponent($sComponentName);
    }

}