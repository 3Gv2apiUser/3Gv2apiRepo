<?php
/**
 * Created by PhpStorm.
 * User: Pylon
 * Date: 2015.05.31.
 * Time: 17:07
 */

namespace sys\mod\auth;

interface AuthCredentialsInterface {
	public function setAuthenticated($isAuthenticated);
	public function isAuthenticated();
}

/**
 * Class AuthCredentials
 * @package sys\mod\auth
 */
class AuthCredentials implements AuthCredentialsInterface {

	/**
	 * @var bool
	 */
	protected $isAuthenticated = false;
	/**
	 * @var mixed
	 */
	protected $userData = null;

	/**
	 * @param boolean $isAuthenticated
	 */
	public function setAuthenticated($isAuthenticated)
	{
		$this->isAuthenticated = $isAuthenticated;
	}

	/**
	 * @return boolean
	 */
	public function isAuthenticated()
	{
		return $this->isAuthenticated;
	}

	/**
	 * @return mixed
	 */
	public function getUserData()
	{
		return $this->userData;
	}

	/**
	 * @param mixed $userData
	 */
	public function setUserData($userData)
	{
		$this->userData = $userData;
	}

}