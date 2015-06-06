<?php
/*************************************************************�
 *  @package  Manhertz
 *  @author   Tamas Manhertz
 *  @version  V0.99.20150305
 *************************************************************/

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

	}

	/***********************************************
	 *   PUBLIC METHODS
	 ***********************************************/
}