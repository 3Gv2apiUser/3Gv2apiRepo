<?php
/**
 * Created by PhpStorm.
 * User: Pylon
 * Date: 2015-06-13
 * Time: 10:04
 */
namespace sys\mod\db;


interface dbBaseInterface {

	public function setCredentials(dbCredentials $credentials );

	public function connect();
	public function isConnected();
	public function closeConnection();
	public function getErrorMessage();

	public function execute( $sql, $options = null );
	public function getRow( $sql = null, $options = null );
	public function getAll( $sql = null, $options = null );
	public function fetchRow();
	public function close();

}

/**
 * Class
 * @package sys\mod\xml
 */
class dbBase implements dbBaseInterface {

	/***********************************************
	 *   PROPERTIES
	 ***********************************************/
	/**
	 * @var \sys\SystemBase
	 */
	protected $system = null;

	/**
	 * @var dbCredentials
	 */
	protected $credentials = null;

	/**
	 * Error message if any db operation went wrong
	 * @var string
	 */
	protected $errorMessage = null;
	/**
	 * @var integer
	 */
	protected $maxNumberOfRecordsInGetAll = 0;

	/***********************************************
	 *   CONSTRUCT
	 ***********************************************/
	/**
	 * @param \sys\SystemBase $system
	 * @param \DOMElement        $node
	 */
	public function __construct(\sys\SystemBase $system) {
		$this->system = $system;
	}

	/***********************************************
	 *   PROTECTED METHODS
	 ***********************************************/
	protected function setErrorMessage( $errorMessage ) {
		$this->errorMessage = $errorMessage;
	}
	/***********************************************
	 *   PUBLIC METHODS
	 ***********************************************/

	public function setCredentials(dbCredentials $credentials ) {
		$this->credentials = $credentials;
	}

	public function getErrorMessage() {
		return $this->errorMessage;
	}

	/**
	 * @return int
	 */
	public function getMaxNumberOfRecordsInGetAll() {
		return $this->maxNumberOfRecordsInGetAll;
	}

	/**
	 * @param int $maxNumberOfRecordsInGetAll
	 */
	public function setMaxNumberOfRecordsInGetAll($maxNumberOfRecordsInGetAll) {
		$this->maxNumberOfRecordsInGetAll = $maxNumberOfRecordsInGetAll;
	}

	public function connect() {
		return false;
	}
	public function isConnected() {
		return false;
	}
	public function closeConnection() {
		return false;
	}

	public function execute( $sql, $options = null ){
		return false;
	}
	public function getRow( $sql = null, $options = null ) {
		return false;
	}
	public function getAll( $sql = null, $options = null ) {
		return false;
	}
	public function fetchRow() {
		return false;
	}
	public function close() {
		return false;
	}

}