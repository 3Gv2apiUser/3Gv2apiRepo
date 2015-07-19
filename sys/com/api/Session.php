<?php
/**
 * Created by PhpStorm.
 * User: Pylon
 * Date: 2015-06-18
 * Time: 0:19
 */

namespace sys\com\api;


/**
 * Class HTTP
 * @package sys\api\com
 */
class Session extends \sys\com\Session {
	/***********************************************
	 *   PROPERTIES
	 ***********************************************/
	protected function onInitialize() {
		/** @var \sys\com\api\HTTP $HTTP */
		if ($HTTP = $this->getComponent("HTTP")) {
			if ($session_id = $HTTP->getHeader("Token")) {
				$this->setSessionId($session_id);
			}
		}
		ini_set('session.use_cookies', '0');
		parent::onInitialize();
	}


	/***********************************************
	 *   PROTECTED METHODS
	 ***********************************************/


}