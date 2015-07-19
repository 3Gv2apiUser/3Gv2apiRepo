<?php
/**
 * Created by PhpStorm.
 * User: Pylon
 * Date: 2015-07-19
 * Time: 23:33
 */

namespace sys\mod\auth\drivers;
use sys\mod\auth\AuthCredentials;
use sys\mod\auth\AuthCredentialsUsername;

/**
 * Class AuthDriver_Manhertzhu
 * @package sys\mod\auth\drivers
 */
class AuthDriver_Fuchs extends AuthDriver {

	/**
	 * @param AuthCredentialsUsername $credentials
	 */
	protected function authorize(AuthCredentials $credentials) {

		/** @var \sys\com\Database $db */
		$db = $this->system->getComponent( "SYSDB" );
		$db->connect();
		$sql = "SELECT * FROM addresses WHERE addr_username=? AND addr_password=?";

		$selectResult = $db->getRow($sql, array(
			$credentials->getUsername(),
			$credentials->getPassword()
		));
		$db->closeConnection();

		if (is_array($selectResult)) {
			$credentials->setAuthId($selectResult['addr_id']*1);
			$credentials->setUserData($selectResult);
		} else {
			$credentials->setAuthId(null);
		}

	}

}