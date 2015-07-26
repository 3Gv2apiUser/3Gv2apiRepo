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
		$sql = "SELECT PKkunde_id iU_ID, kunde_vorname sU_FIRSTNAME, kunde_name sU_LASTNAME, kunde_mail sEMAIL, '-' dLASTLOGIN, '-' sIPADDR, FROM_UNIXTIME(1318460873) tCREATED
				FROM refox_kundenneu, refox_kundentogruppen
				WHERE refox_kundentogruppen.FKkundetogruppe_kgroup_id=8
					AND refox_kundentogruppen.FKkundetogruppe_kunde_id=refox_kundenneu.PKkunde_id
					AND kunde_user=? AND kunde_pass=?";

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