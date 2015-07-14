<?php
/*************************************************************�
 *  @package  Manhertz
 *  @author   Tamas Manhertz
 *  @version  V0.99.20150305
 *************************************************************/

namespace sys\com;

interface HTTPInterface {

	public function getHeader($headerName);

}

define ('UTF32_BIG_ENDIAN_BOM',		chr(0x00).chr(0x00).chr(0xFE).chr(0xFF));
define ('UTF32_LITTLE_ENDIAN_BOM',	chr(0xFF).chr(0xFE).chr(0x00).chr(0x00));
define ('UTF16_BIG_ENDIAN_BOM',		chr(0xFE).chr(0xFF));
define ('UTF16_LITTLE_ENDIAN_BOM',	chr(0xFF).chr(0xFE));
define ('UTF8_BOM',					chr(0xEF).chr(0xBB).chr(0xBF));

/**
 * Class ClientProperties
 * @package sys\com
 */
class HTTP extends \sys\ServerComponent implements HTTPInterface {

	/***********************************************
	 *   PROPERTIES
	 ***********************************************/
		/**
		 * true: request is https
		 * @var bool
		 */
	protected $_bHTTPS = false;
		/**
		 *  Server name - which address has been called from the client.
		 * @var string
		 */
	protected $_sSERVER_NAME = 'LOCALHOST';
		/**
		 *  Server port number - which port address has been called from the client.
		 * @var string
		 */
	protected $_sSERVER_PORT = '';
		/**
		 *  HostName - at the first, it is equivalent with SERVER_NAME but if the script
		 *            wants to modify the hostname, this object will modify this property.
		 * @var string
		 */
	protected $_sHostName = '';
		/**
		 *  request method from client
		 * @var string
		 */
	protected $_sREQUEST_METHOD = '-';
		/**
		 *  The query string parameters in the URI without the "?" character at the beginning
		 * @var string
		 */
	protected $_sQUERY_PARAMS = '';
		/**
		 *  User Agent text from the browser.
		 * @var string
		 */
	protected $_sUserAgent = '';
		/**
		 *  IP address of the client.
		 * @var string
		 */
	protected $_sREMOTE_ADDR = '';

//   "http://".$DNS_SessionID.".".$HostName."/".$PATH."/".$PAGE_NAME.".".$PAGE_EXT."?".$sQUERY_PARAMS

	protected $_sFOLDER_NAME = "";
	protected $_sFILE_NAME = "";
	protected $_sFILE_BASENAME = "";
	protected $_sFILE_EXTENSION = "";

	/**
	 *  client request timestamp with msecs
	 * @var float
	 */
	protected $_tRequestTime = 0;

	/**
	 * HTTP request headers
	 * @var array
	 */
	protected $_aRequestHeaders = null;
	/**
	 * client accept type string
	 * @var string
	 */
	protected $_sAcceptTypes = '';
	/***********************************************
	 *   PROTECTED METHODS
	 ***********************************************/
	/**
	 * the initialization of the system will create the necessery components
	 */
	protected function onInitialize() {
		$this->_bHTTPS = (!empty($_SERVER['HTTPS']) && $_SERVER['HTTPS'] !== 'off');
		$this->_sSERVER_NAME = (array_key_exists('SERVER_NAME', $_SERVER) ?  $_SERVER['SERVER_NAME'] : $this->_sSERVER_NAME );
		$this->_sHostName = $this->_sSERVER_NAME;
		$this->_sSERVER_PORT = $_SERVER['SERVER_PORT'];
		$this->_sREQUEST_METHOD = (array_key_exists('REQUEST_METHOD', $_SERVER) ? $_SERVER['REQUEST_METHOD'] : $this->_sREQUEST_METHOD );
		$this->_sREMOTE_ADDR = (array_key_exists('REMOTE_ADDR', $_SERVER) ? $_SERVER['REMOTE_ADDR'] : 'N/A' );

		$this->_sAcceptTypes = $_SERVER['HTTP_ACCEPT'];
		$this->_sUserAgent= (array_key_exists('HTTP_USER_AGENT', $_SERVER) ? str_replace( "'", "''", $_SERVER['HTTP_USER_AGENT'] ) : "*No_user_agent_string_has_been_received*" );
		$this->_tRequestTime = $_SERVER['REQUEST_TIME_FLOAT'];

		// $_SERVER['HTTP_COOKIE']

		// ***********************************
		//  analyzing the url to get rest parameters
		//
		if (!($_aURLInfo = parse_url( $_SERVER['REQUEST_URI'] ))) {
			$_aURLInfo = array( 'path' => '/' );
		}
		//
		//  QUERY_PARAMS:  the query string parameters in URI without "?"
		//
		$this->_sQUERY_PARAMS = (array_key_exists( "query", $_aURLInfo ) ? $_aURLInfo['query'] : "" );
		//
		//  path, filename, extension
		//

		$this->_analyzePath($_aURLInfo['path']);
	}

	protected function _analyzePath($path) {

		if (substr($path,-1) == "/")
			//  if the filename is not specified then we need to extend the path filename to get the correct path info
			$_aPathInfo = pathinfo ( $path."-" );
		else
			$_aPathInfo = pathinfo ( $path );
		//
		//  Path, folder name
		//
		$this->_sFOLDER_NAME = str_replace( '\\', '/', substr( $_aPathInfo['dirname'], 1, strlen($_aPathInfo['dirname'])-1 ) );
		$this->_sFOLDER_NAME .= ( (substr($this->_sFOLDER_NAME,-1) != '/') ? "/" : "" );
		//
		//  The whole filename or empty string if not specified
		//
		$this->_sFILE_NAME = ( $_aPathInfo['basename'] != "-" ? $_aPathInfo['basename'] : "" );
		//
		//  The name part of the whole filename (without - the last - extension)
		//
		$this->_sFILE_BASENAME = ( $_aPathInfo['filename'] != "-" ? $_aPathInfo['filename'] : "" );
		//
		//  The last file extension (after the last ".")
		//
		$this->_sFILE_EXTENSION = ( array_key_exists( "extension", $_aPathInfo) ? strtolower($_aPathInfo['extension']) : "" );
		if (!preg_match( '/^[a-zA-Z0-9\_\-]*$/', $this->_sFILE_EXTENSION ))
			$this->_sFILE_EXTENSION = "";
	}

	/**
	 *  get all HTTP request headers into a property array
	 */
	protected function _fetchRequestHeaders() {
		$this->_aRequestHeaders = getallheaders();
	}
	/***********************************************
	 *   PUBLIC METHODS
	 ***********************************************/
	/**
	 * @param string $headerName
	 *
	 * @return bool|string
	 */
	public function getHeader( $headerName ) {
		if (!isset($this->_aRequestHeaders)) {
			$this->_fetchRequestHeaders();
		}
		if (array_key_exists($headerName, $this->_aRequestHeaders)) {
			return trim($this->_aRequestHeaders[$headerName]);
		}
		return false;
	}

}