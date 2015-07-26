<?php
/**
 * Created by PhpStorm.
 * User: Pylon
 * Date: 2015-07-26
 * Time: 23:24
 */
/*************************************************************�
 *  @package  Manhertz
 *  @author   Tamas Manhertz
 *  @version  V0.99.20150726
 *************************************************************/

namespace sys\com;
use \sys\mod\auth;
use \sys\mod\auth\drivers;

interface AuthMgrComponentInterface {
	public function isAuth();
}

/**
 * Class AuthMgr
 * @package sys\com
 */
class AuthMgr extends \sys\ServerComponent {

	const AUTH_DRIVER_NAME = '\sys\mod\auth\drivers\AuthDriver_False';

	/***********************************************
	 *   PROPERTIES
	 ***********************************************/

	/**
	 * @var \sys\mod\auth\AuthManager
	 */
	protected $authManager = null;

	protected $authDriverName = self::AUTH_DRIVER_NAME;

	/***********************************************
	 *   CONSTRUCT
	 ***********************************************/

	/***********************************************
	 *   PROTECTED METHODS
	 ***********************************************/
	protected function onCreate() {
		$this->authManager = new auth\AuthManager($this->oSystem);
	}

	protected function setAuth($userid) {
		$_SESSION['userid'] = $userid;
	}

	/***********************************************
	 *   PUBLIC METHODS
	 ***********************************************/
	/**
	 * @return string
	 */
	public function getAuthDriverName()
	{
		return $this->authDriverName;
	}

	/**
	 * @param string $authDriverName
	 */
	public function setAuthDriverName($authDriverName)
	{
		$this->authDriverName = $authDriverName;
//		$authDriverName = $this->getAuthDriverName();
		$authDriverName = "\\sys\\mod\\auth\\drivers\\AuthDriver_".ucfirst($authDriverName);
		$this->authManager->addDriver(new $authDriverName($this->oSystem));
	}


	/**
	 * @param \sys\mod\auth\drivers\AuthDriver $driver
	 */
	public function addDriver(\sys\mod\auth\drivers\AuthDriver $driver) {
		$this->authManager->addDriver($driver);
	}

	public function isAuth() {
		// @todo  ---> USER component
		return isset($_SESSION['userid']);
	}

	public function doAuth(\sys\mod\auth\AuthCredentials $credentials) {
		return $this->authManager->doAuth($credentials);
	}



}