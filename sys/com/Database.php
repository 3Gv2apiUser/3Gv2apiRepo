<?php
/*************************************************************�
 *  @package  Manhertz
 *  @author   Tamas Manhertz
 *  @version  V0.99.20150305
 *************************************************************/
/**
 *  Connection String:
 *	    <username>:<password>@<server>:<port>#<database>
 *
 *	@author   Tamas Manhertz <3gcms@manhertz.net>
 *	@version  20150602
 *	@package  components
 *  @category component
 *  @link     http://manhertz.net/WPDokSys
 *  @since    PHP 5.4.0
 *  @access   public
 */

namespace sys\com;

// adodb library needs extra initializations but only once at the first time
//require_once(ROOT . "libs/adodb5/adodb.inc.php");

use sys\mod\db\dbCredentials;

/**
 *
 *  For traditional reasons we call this component as "Database" but it wrappes only
 * the SQL drivers. For NoSQL, document based databases please use the specified
 * wrapper components (like Mongo)
 *
 * Interface DatabaseComponentInterface
 * @package sys\com
 */
interface DatabaseComponentInterface {

	public function set_sConnectionString( $connectionString );

	public function connect();
	public function closeConnection();

	public function execute( $sql, $options = null );
	public function getRow( $sql = null, $options = null );
	public function getAll( $sql = null, $options = null );
	public function fetchRow();
	public function close();

}

/**
 * Class Database
 * @package sys\com
 */

class Database extends \sys\ServerComponent {

	const DATABASE_DEFAULT_DRIVER = 'mysqli';
	const DATABASE_DEFAULT_HOSTNAME = 'localhost';
	const DATABASE_DEFAULT_PORTNUMBER = '3306';


	const DATABASE_MASTER_ONLY = 1;
	const DATABASE_SLAVE_ONLY = 2;

	/***********************************************
	 *   PROPERTIES
	 ***********************************************/
	/**
	 * @var \sys\mod\db\dbBase
	 */
	protected $driver = null;
	/**
	 * @var string
	 */
	protected $connectionString = null;
	/***********************************************
	 *   CONSTRUCT
	 ***********************************************/

	/***********************************************
	 *   PROTECTED METHODS
	 ***********************************************/
	/**
	 *  Decompose the connection string to separate parts
	 *
	 * @param string $connectionString
	 * @return bool|array
	 */
	protected function _decomposeConnectionString($connectionString)
	{
		if (preg_match( '/(?:(?<driver>.+):\/\/){0,1}(?<username>.+):(?<password>.+)@(?<host>.+):(?<port>[0-9]+)#(?<database>.+)/', $connectionString, $_aMatches)) {
			if (!isset($_aMatches['driver'])) {
				$_aMatches['driver'] = self::DATABASE_DEFAULT_DRIVER;
			}
			return $_aMatches;
		}
		return false;
	}

	/**
	 *  Set db driver object
	 *
	 * @param \sys\mod\db\dbBase $driverObject
	 */
	protected function setDriverObject( \sys\mod\db\dbBase $driverObject ) {
		$this->driver = $driverObject;
	}
	/***********************************************
	 *   PUBLIC METHODS SETTERS
	 ***********************************************/

	/**
	 *  Sets connection string
	 *
	 * @param string $connectionString
	 */
	public function set_sConnectionString( $connectionString ) {
		$this->connectionString = $connectionString;
	}

	/***********************************************
	 *   PUBLIC METHODS
	 ***********************************************/
	public function connect() {
		if (!isset($this->connectionString)) {
			syLog( "DB ".$this->componentName.": no connection string has been given." );
			return false;
		}

		if (!($dbParameters = $this->_decomposeConnectionString( $this->connectionString ))) {
			syLog( "DB ".$this->componentName.": connection string is not valid." );
			return false;
		}

		$_sDriverClassName = '\sys\mod\db\db_'.$dbParameters['driver'];
		if (!class_exists($_sDriverClassName)) {
			syLog( "DB ".$this->componentName.": driver ".$dbParameters['driver']." is not available." );
			return false;
		}

		//  setting up db credentials
		$_oDbCredentials = new dbCredentials();
		$_oDbCredentials->setHost( (isset($dbParameters['host']) ? $dbParameters['host'] : self::DATABASE_DEFAULT_HOSTNAME));
		$_oDbCredentials->setPort( (isset($dbParameters['port']) ? $dbParameters['port'] : self::DATABASE_DEFAULT_PORTNUMBER));
		if (!isset($dbParameters['username']) || !isset($dbParameters['password']) || !isset($dbParameters['database'])) {
			syLog( "DB ".$this->componentName.": db credentials must be specified." );
			return false;
		}
		$_oDbCredentials->setUsername($dbParameters['username']);
		$_oDbCredentials->setPassword($dbParameters['password']);
		$_oDbCredentials->setDatabase($dbParameters['database']);

		/** @var \sys\mod\db\dbBase $_oDbDriverObject */
		$_oDbDriverObject = new $_sDriverClassName($this->oSystem);
		$_oDbDriverObject->setCredentials($_oDbCredentials);

		if (!$_oDbDriverObject->connect()) {
			syLog( "DB ".$this->componentName.": could not established the db connection. Error: ".$_oDbDriverObject->getErrorMessage() );
			return false;
		}

		$this->setDriverObject($_oDbDriverObject);
		return true;
	}

	public function closeConnection() {
		if ($this->driver && $this->driver->isConnected()) {
			return $this->driver->closeConnection();
		}
		return true;
	}

	public function execute( $sql, $options = null ){
		if (!$this->driver || !$this->driver->isConnected()) {
			return false;
		}
		return $this->driver->execute($sql, $options);
	}

	public function getRow( $sql = null, $options = null ) {
		if (!$this->driver || !$this->driver->isConnected()) {
			return false;
		}
		return $this->driver->getRow($sql, $options);
	}

	public function getAll( $sql = null, $options = null ) {
		if (!$this->driver || !$this->driver->isConnected()) {
			return false;
		}
		return $this->driver->getAll($sql, $options);
	}

	public function fetchRow() {
		if (!$this->driver || !$this->driver->isConnected()) {
			return false;
		}
		return $this->driver->fetchRow();
	}

	public function close() {
		if (!$this->driver || !$this->driver->isConnected()) {
			return false;
		}
		return $this->driver->close();
	}

}