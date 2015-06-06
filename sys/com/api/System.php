<?php
/*************************************************************�
 *  @package  Manhertz
 *  @author   Tamas Manhertz
 *  @version  V0.99.20150305
 *************************************************************/

namespace sys\com\api;

interface apiSystemInterface {

}

/**
 * Class System
 * @package sys\entry\api
 */
class System extends \sys\SystemBase implements apiSystemInterface {

	protected function onInitialize() {

		parent::onInitialize();

//		$this->createComponent("Session");
//		$this->createComponent("Router");
//		$this->createComponent("Buffer");

	}

}