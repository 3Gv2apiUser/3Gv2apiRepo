<?php
/**
 * Created by PhpStorm.
 * User: Pylon
 * Date: 2015.05.31.
 * Time: 17:12
 */

namespace sys\mod\auth\drivers;
use sys\mod\auth\AuthCredentials;

/**
 * Class AuthDriver_False
 * @package sys\mod\auth\driver
 */
class AuthDriver_False extends AuthDriver {

	protected function authorize(AuthCredentials $credentials) {
		echo "FALSE auth plugin";
		$credentials->setAuthenticated(false);
	}

} 