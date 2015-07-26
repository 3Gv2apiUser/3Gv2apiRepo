<?php
/**
 * Created by PhpStorm.
 * User: Pylon
 * Date: 2015-07-26
 * Time: 22:58
 */

namespace sys\collections;


/**
 * Class Collection
 * @package sys\collections
 */
class Collection {

	/***********************************************
	 *   PROPERTIES
	 ***********************************************/
	/**
	 * @var \sys\SystemBase
	 */
	protected $system = null;
	protected $router = null;

	protected $requestData = null;
	/***********************************************
	 *   CONSTRUCT
	 ***********************************************/
	/**
	 * @param \sys\SystemBase $system
	 */
	public function __construct(\sys\SystemBase $system, \sys\com\api\Router $router) {
		$this->system = $system;
		$this->router = $router;
	}

	/***********************************************
	 *   PROTECTED METHODS
	 ***********************************************/

	protected function setResultError($statusCode, $errorCode, $errorMessage) {
		$this->router->setResultError($statusCode, $errorCode, $errorMessage);
	}

	protected function setResultData($data) {
		$this->router->setResultData($data);
	}

	protected function setResponseCode($responseCode){
		$this->router->setResponseCode($responseCode);
	}

	/***********************************************
	 *   PUBLIC METHODS
	 ***********************************************/

	public function isMethodAuthRequired( $method ) {
		return true;
	}

	public function setRequestData($requestData){
		$this->requestData = $requestData;
	}

	public function doGET() {
		$this->setResponseCode(400);
	}

	public function doPOST() {
		$this->setResponseCode(400);
	}

	public function doPUT() {
		$this->setResponseCode(400);
	}

	public function doPATCH() {
		$this->setResponseCode(400);
	}

	public function doDELETE() {
		$this->setResponseCode(400);
	}

}