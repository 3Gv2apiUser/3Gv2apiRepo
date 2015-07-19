<?php
/**
 * Created by PhpStorm.
 * User: Pylon
 * Date: 2015.05.31.
 * Time: 17:07
 */

namespace sys\mod\auth;

interface AuthCredentialsInterface {
	public function setAuthId($authId);
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
	protected $auth_id = null;
	/**
	 * @var mixed
	 */
	protected $userData = null;

	/**
	 * @param int $authId
	 */
	public function setAuthId($authId)
	{
		$this->auth_id = $authId;
	}

	/**
	 * @return boolean
	 */
	public function isAuthenticated()
	{
		return !is_null($this->auth_id);
	}

	/**
	 * @return int|null
	 */
	public function getAuthId()
	{
		return $this->auth_id;
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