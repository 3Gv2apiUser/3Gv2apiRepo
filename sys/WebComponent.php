<?php
/**
 * Created by PhpStorm.
 * User: Pylon
 * Date: 2015.03.14.
 * Time: 12:15
 */

namespace sys;

interface WebComponentInterface {

}

/**
 * Class WebComponent
 * @package sys
 */
class WebComponent extends \sys\ServerComponent implements WebComponentInterface {

	/***********************************************
	 *   PROPERTIES
	 ***********************************************/

	/***********************************************
	 *   CONSTRUCT
	 ***********************************************/

	/***********************************************
	 *   PROTECTED METHODS
	 ***********************************************/

	/**
	 * the initialization of the system will create the necessery components
	 */
	protected function onInitialize() {

		parent::onInitialize();

		$this->createComponent("URL");
//		$this->createComponent("Session");
//		$this->createComponent("Router");
//		$this->createComponent("Buffer");

	}

	/***********************************************
	 *   PUBLIC METHODS
	 ***********************************************/
}