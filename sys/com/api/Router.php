<?php
/**
 * Created by PhpStorm.
 * User: Pylon
 * Date: 2015-07-18
 * Time: 16:34
 */
namespace sys\com\api;

/**
 *
 *
 * Interface RouterComponentInterface
 * @package sys\com
 */
interface RouterComponentInterface {


}

/**
 * Class Database
 * @package sys\com
 */

class Router extends \sys\ServerComponent {

	const AUTH_DRIVER_NAME = '\sys\mod\auth\drivers\AuthDriver_False';

	protected $requestData = null;

	protected $result = array();

	protected $statusCode = 400;

	protected $authDriverName = self::AUTH_DRIVER_NAME;

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
	}




	public function onInitialize() {
		/** @var \sys\com\api\HTTP $HTTP */
		$HTTP = $this->getComponent("HTTP");

		//  @todo ---> api http
		if ($HTTP->getMethod() == "POST") {
			$entityBody = file_get_contents('php://input');
			if (!$decodedBody = json_decode($entityBody)) {
				$this->setResultError(403, "R001", "Request body is not a proper JSON string.");
				return;
			}
			$this->requestData = $decodedBody;
		}



		if (!$this->isAuth()) {
			if ($HTTP->getMethod() == "POST") {

				if ((is_array($this->requestData) && isset($this->requestData['user']) && isset($this->requestData['password'])) ||
					(is_object($this->requestData) && isset($this->requestData->user) && isset($this->requestData->password))) {
					$collections = $HTTP->getCollections();
					$firstCollection = array_shift($collections);
					if ($firstCollection['collection'] == "auth") {
						$authmgr = new \sys\mod\auth\AuthManager($this->oSystem);
						$authDriverName = $this->getAuthDriverName();
						$authmgr->addDriver(new $authDriverName($this->oSystem));
						$credentials = new \sys\mod\auth\AuthCredentialsUsername();
						$credentials->setUsername(is_array($this->requestData) ? $this->requestData['user'] : $this->requestData->user);
						$credentials->setPassword(is_array($this->requestData) ? $this->requestData['password'] : $this->requestData->password);
						$r = $authmgr->doAuth($credentials);
						if ($r->isAuthenticated()) {
							$this->setAuth($r->getAuthId());
							$this->setResultData([
								"userid" => $r->getAuthId(),
								"firstname" => $r->getUserData()['sU_FIRSTNAME'],
								"lastname" => $r->getUserData()['sU_LASTNAME'],
							]);
						} else {
							$this->setResultError(401, "A004", "Invalid username or password.");
						}
						return;
					} else {
						$this->setResultError(401, "A003", "You must be authenticated user to use the collection.");
						return;
					}
				} else {
					$this->setResultError(400, "A002", "Illegal request body.");
					return;
				}
			} else {
				$this->setResultError(400, "A001", "You must use the proper authentication method.");
				return;
			}
		} else {

			$this->setResultData("Super!!!");

		}
		// checking access control

		//

		$this->process($HTTP->getCollections(), $HTTP->getCommand(), $HTTP);
		$this->setResponseCode(200);
	}

	// @todo
	protected function isAuth() {
		return isset($_SESSION['userid']);
	}

	protected function setAuth($userid) {
		$_SESSION['userid'] = $userid;
	}
	protected function setResultError($statusCode, $errorCode, $errorMessage) {
		$this->statusCode = $statusCode;
		$this->result["error"] = true;
		$this->result[$errorCode] = $errorMessage;
	}

	protected function setResultData($data) {
		$this->result['data'] = $data;
	}

	protected function setResponseCode($responseCode){
		$this->statusCode = $responseCode;
	}

	public function process() {

	}

	public function onFinalize() {

		/** @var Session $session */
		$session = $this->getComponent("SESSION");
		ob_clean();
		header('Token: '.$session->getSessionId());

		//  @todo  ---> apiBuffer
		$jsonResult = json_encode($this->result);
		http_response_code ($this->statusCode);
		echo $jsonResult;
	}

}

