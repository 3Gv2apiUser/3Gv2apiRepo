<?php
/**
 * Created by PhpStorm.
 * User: Pylon
 * Date: 2015.05.31.
 * Time: 11:43
 */

namespace sys\mod\auth;
use sys\mod\auth\drivers\AuthDriver;

interface AuthManagerInterface {

	public function addDriver(AuthDriver $driver);
	public function doAuth(AuthCredentials $credentials);
	public function isAuthenticated();

}


/**
 * Class AuthManager
 * @package sys\auth
 */
class AuthManager implements AuthManagerInterface {


	/***********************************************
	 *   PROPERTIES
	 ***********************************************/
	/**
	 * @var array of drivers\AuthDriver
	 */
	protected $drivers = [];
	/**
	 * @var \sys\SystemBase
	 */
	protected $system = null;

	/***********************************************
	 *   CONSTRUCT
	 ***********************************************/
	/**
	 * @param \sys\SystemBase $system
	 */
	public function __construct(\sys\SystemBase $system) {
		$this->system = $system;
	}

	/***********************************************
	 *   PROTECTED METHODS
	 ***********************************************/
	/**
	 * @param AuthDriver $driver
	 */
	public function addDriver(AuthDriver $driver) {
		if (is_object($driver)) {
			$this->drivers[] = $driver;

		}
	}

	public function doAuth(AuthCredentials $credentials) {

		/** @var AuthDriver $driver */
		foreach($this->drivers as $driver) {
			if (!$credentials->isAuthenticated()) {
				$credentials = $driver->doAuth($credentials);
			}
		}
		return $credentials;

	}

	public function isAuthenticated() {

	}

	/***********************************************
	 *   PUBLIC METHODS
	 ***********************************************/
}