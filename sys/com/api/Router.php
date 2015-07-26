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

	protected $requestData = null;

	protected $result = array();

	protected $statusCode = 400;


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


		// gets the first collection
		$collections = $HTTP->getCollections();
		$firstCollection = array_shift($collections);
		$nameOfFirstCollection = '\\sys\\collections\\'.ucfirst($firstCollection['collection']);

		/** @var \sys\collections\Collection $collectionObject */
		$collectionObject = new $nameOfFirstCollection($this->oSystem, $this);
		$collectionObject->setRequestData($this->requestData);

		// checking whether it needs authorization
		if ($collectionObject->isMethodAuthRequired($HTTP->getMethod())) {
			if (!$this->getComponent("AuthMgr")->isAuth()) {
				$this->setResultError(401, "A003", "You must be authenticated user to use the collection.");
				return;
			}
		}

		$processorMethod = 'do'.$HTTP->getMethod();
		if (method_exists($collectionObject, $processorMethod)) {
			$collectionObject->$processorMethod();
		} else {
			$this->setResultError(401, "R001", "Collection does not provide ".$processorMethod." method.");
			return;
		}

//		$this->process($HTTP->getCollections(), $HTTP->getCommand(), $HTTP);
//		$this->setResponseCode(200);
	}

	public function setResultError($statusCode, $errorCode, $errorMessage) {
		$this->statusCode = $statusCode;
		$this->result["error"] = true;
		$this->result[$errorCode] = $errorMessage;
	}

	public function setResultData($data) {
		$this->result['data'] = $data;
	}

	public function setResponseCode($responseCode){
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

