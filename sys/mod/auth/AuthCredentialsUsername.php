<?php
/**
 * Created by PhpStorm.
 * User: Pylon
 * Date: 2015-06-19
 * Time: 23:36
 */
namespace sys\mod\auth;

interface AuthCredentialsUsernameInterface {

	public function setPassword($password);
	public function getPassword();
	public function setUsername($username);
	public function getUsername();

}

/**
 * Class AuthCredentials
 * @package sys\mod\auth
 */
class AuthCredentialsUsername extends AuthCredentials implements AuthCredentialsUsernameInterface {

	/**
	 * @var string
	 */
	protected $username = '';
	/**
	 * @var string
	 */
	protected $password = '';

	/**
	 * @param string $password
	 */
	public function setPassword($password)
	{
		$this->password = $password;
	}

	/**
	 * @return string
	 */
	public function getPassword()
	{
		return $this->password;
	}

	/**
	 * @param string $username
	 */
	public function setUsername($username)
	{
		$this->username = $username;
	}

	/**
	 * @return string
	 */
	public function getUsername()
	{
		return $this->username;
	}

}