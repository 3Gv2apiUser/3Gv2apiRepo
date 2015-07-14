<?php
/**
 * Created by PhpStorm.
 * User: Pylon
 * Date: 2015.05.31.
 * Time: 16:57
 */

namespace sys\mod\auth\drivers;

use sys\mod\auth\AuthCredentials;

interface AuthDriverInterface {
	public function doAuth(AuthCredentials $credentials);
}


/**
 * Class AuthDriver
 * @package sys\mod\auth\drivers
 */
abstract class AuthDriver implements AuthDriverInterface {

	/***********************************************
	 *   PROPERTIES
	 ***********************************************/

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
	 * @param AuthCredentials $credentials
	 */
	abstract protected function authorize(AuthCredentials $credentials);

	/***********************************************
	 *   PUBLIC METHODS
	 ***********************************************/

	/**
	 * @param AuthCredentials $credentials
	 * @return AuthCredentials
	 */
	public function doAuth(AuthCredentials $credentials) {
		$this->authorize($credentials);
		return $credentials;
	}
}