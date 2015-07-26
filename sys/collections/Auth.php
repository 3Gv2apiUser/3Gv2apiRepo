<?php
/**
 * Created by PhpStorm.
 * User: Pylon
 * Date: 2015-07-26
 * Time: 23:03
 */

namespace sys\collections;


/**
 * Class Auth
 * @package sys\collections
 */
class Auth extends Collection {

	/***********************************************
	 *   PROTECTED METHODS
	 ***********************************************/
	// @todo
	protected function isAuth() {
		return isset($_SESSION['userid']);
	}

	/***********************************************
	 *   PUBLIC METHODS
	 ***********************************************/

	public function isMethodAuthRequired( $method ) {
		return false;
	}

	public function doPOST() {

		if (
			!((is_array($this->requestData) && isset($this->requestData['user']) && isset($this->requestData['password']))
				|| (is_object($this->requestData) && isset($this->requestData->user) && isset($this->requestData->password)))
		) {
			$this->setResultError(400, "A002", "Illegal request body.");
			return;
		}

		/** @var \sys\com\AuthMgr $authmgr */
		$authmgr = $this->system->getComponent("AuthMgr");

		$credentials = new \sys\mod\auth\AuthCredentialsUsername();
		$credentials->setUsername(is_array($this->requestData) ? $this->requestData['user'] : $this->requestData->user);
		$credentials->setPassword(is_array($this->requestData) ? $this->requestData['password'] : $this->requestData->password);

		$r = $authmgr->doAuth($credentials);
		if ($r->isAuthenticated()) {
			$this->setResultData([
				"userid" => $r->getAuthId(),
				"firstname" => $r->getUserData()['sU_FIRSTNAME'],
				"lastname" => $r->getUserData()['sU_LASTNAME'],
			]);
			$this->setResponseCode(200);
		} else {
			$this->setResultError(401, "A004", "Invalid username or password.");
		}
	}

}