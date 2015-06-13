<?php
/**
 * Created by PhpStorm.
 * User: Pylon
 * Date: 2015-06-13
 * Time: 10:01
 */

namespace sys\mod\db;

interface db_mysqliInterface {

}

/**
 * Class XMLnodeBase
 * @package sys\mod\xml
 */
class db_mysqli extends dbBase implements db_mysqliInterface {

	const DATABASE_DRIVER_MYSQLI_MAX_NUMBER_OF_ROWS_IN_GETALL = 1000;
	/***********************************************
	 *   PROPERTIES
	 ***********************************************/
	/**
	 * @var \mysqli
	 */
	protected $db = null;
	/**
	 * @var \mysqli_result
	 */
	protected $queryResult = null;
	/***********************************************
	 *   CONSTRUCT
	 ***********************************************/
	/**
	 * @param \sys\SystemBase $system
	 * @param \DOMElement        $node
	 */
	public function __construct(\sys\SystemBase $system) {
		parent::__construct($system);
		$this->setMaxNumberOfRecordsInGetAll(self::DATABASE_DRIVER_MYSQLI_MAX_NUMBER_OF_ROWS_IN_GETALL);
	}

	/***********************************************
	 *   PROTECTED METHODS
	 ***********************************************/
	/***********************************************
	 *   PUBLIC METHODS
	 ***********************************************/
	public function connect() {
		if ($this->db instanceof \mysqli) {
			return true;
		}
		$oMysqli = new \mysqli(
			$this->credentials->getHost(),
			$this->credentials->getUsername(),
			$this->credentials->getPassword(),
			$this->credentials->getDatabase(),
			$this->credentials->getPort()
		);

		if ($oMysqli->connect_errno) {
			$this->setErrorMessage($oMysqli->connect_error);
			return false;
		}

		$this->db = $oMysqli;
		return true;
	}
	public function isConnected() {
		if ($this->db instanceof \mysqli) {
			return true;
		}
		return false;
	}

	public function closeConnection() {
		if ($this->db instanceof \mysqli) {
			$this->db->close();
			unset($this->db);
			return true;
		}
		return false;
	}

	public function execute( $sql, $options = null ){
		if ($this->db instanceof \mysqli) {
			if (isset($this->queryResult)) {
				$this->queryResult->close();
			}
			$queryResult = $this->db->query($sql);
			if ($queryResult === false) {
				return false;
			}
			$this->queryResult = $queryResult;
		}
		return true;
	}

	public function close() {
		if (!$this->db instanceof \mysqli) {
			return false;
		}
		if (isset($this->queryResult)) {
			$this->queryResult->close();
			unset($this->queryResult);
			return true;
		}
		return false;
	}

	public function getRow( $sql = null, $options = null ) {
		if (!$this->fetchRow()) {
			return false;
		}
		if (is_string($sql) && isset($this->queryResult)) {
			$this->close();
		}
		if (is_null($sql) && !isset($this->queryResult)) {
			return false;
		}
		if (is_string($sql)) {
			if (!$this->execute($sql, $options)) {
				return false;
			}
		}
		$row = $this->queryResult->fetch_assoc();
		$this->close();
		return $row;
	}

	public function getAll( $sql = null, $options = null ) {
		if (!$this->db instanceof \mysqli) {
			return false;
		}
		if (is_string($sql) && isset($this->queryResult)) {
			$this->close();
		}
		if (is_null($sql) && !isset($this->queryResult)) {
			return false;
		}
		if (is_string($sql)) {
			if (!$this->execute($sql, $options)) {
				return false;
			}
		}

		$i = $this->getMaxNumberOfRecordsInGetAll();
		$resultSet = array();
		while($row = $this->queryResult->fetch_assoc() && ($i-->0)) {
			array_push( $resultSet, $row );
		}

		if ($i==0) {
			syLog( "DB MYSQLI DRIVER: Max number of records in getAll() have been reached, fetching is stopped. Please inspect your code and query!" );
			$this->close();
		}

		return $resultSet;
	}

	public function fetchRow() {
		if (!$this->db instanceof \mysqli) {
			return false;
		}
		if (isset($this->queryResult)) {
			return $this->queryResult->fetch_assoc();
		}
		return false;
	}

}