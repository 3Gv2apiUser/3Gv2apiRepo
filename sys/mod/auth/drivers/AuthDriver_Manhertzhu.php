<?php
/**
 * Created by PhpStorm.
 * User: Pylon
 * Date: 2015.05.31.
 * Time: 17:01
 */

namespace sys\mod\auth\drivers;
use sys\mod\auth\AuthCredentials;
use sys\mod\auth\AuthCredentialsUsername;

/**
 * Class AuthDriver_Manhertzhu
 * @package sys\mod\auth\drivers
 */
class AuthDriver_Manhertzhu extends AuthDriver {

	/**
	 * @param AuthCredentialsUsername $credentials
	 */
	protected function authorize(AuthCredentials $credentials) {

		/** @var \sys\com\Database $db */
		$db = $this->system->getComponent( "SYSDB" );
		$db->connect();
		$sql = "SELECT * FROM sUSERS WHERE sU_USERNAME=? AND sU_PASSWORD=?";

		$selectResult = $db->getRow($sql, array(
			$credentials->getUsername(),
			$credentials->getPassword()
		));
		$db->closeConnection();

		if (is_array($selectResult)) {
			$credentials->setAuthId($selectResult['iU_ID']*1);
			$credentials->setUserData($selectResult);
		} else {
			$credentials->setAuthId(null);
		}

	}

}