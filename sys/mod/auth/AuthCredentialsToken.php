<?php
/**
 * Created by PhpStorm.
 * User: Pylon
 * Date: 2015-06-19
 * Time: 23:38
 */
namespace sys\mod\auth;

interface AuthCredentialsTokenInterface {

	public function setToken($Token);
	public function getToken();

}

/**
 * Class AuthCredentials
 * @package sys\mod\auth
 */
class AuthCredentialsToken extends AuthCredentials implements AuthCredentialsTokenInterface {

	/**
	 * @var string
	 */
	protected $Token = '';

	/**
	 * @param string $Token
	 */
	public function setToken($Token)
	{
		$this->Token = $Token;
	}

	/**
	 * @return string
	 */
	public function getToken()
	{
		return $this->Token;
	}

}