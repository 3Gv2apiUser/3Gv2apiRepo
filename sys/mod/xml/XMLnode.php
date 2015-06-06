<?php
/**
 * Created by PhpStorm.
 * User: Pylon
 * Date: 2015.06.01.
 * Time: 21:44
 */

namespace sys\mod\xml;

interface XMLnodeInterface {
	public function process(\sys\SystemBase $system, \sys\ServerObject $parentComponent = null);
}

/**
 * Class XMLnodeBase
 * @package sys\mod\xml
 */
class XMLnode implements XMLnodeInterface {

	/***********************************************
	 *   PROPERTIES
	 ***********************************************/
	/**
	 * @var \sys\SystemBase
	 */
	protected $system = null;
	/**
	 * @var \DOMElement
	 */
	protected $DOMNode = null;
	/**
	 * @var \DOMElement
	 */
	protected $ParentNode = null;
	/**
	 * @var \sys\ServerComponent
	 */
	protected $component = null;
	/***********************************************
	 *   CONSTRUCT
	 ***********************************************/
	/**
	 * @param \sys\SystemBase $system
	 * @param \DOMNode        $node
	 */
	public function __construct(
		\sys\SystemBase $system, \DOMNode $node) {
		$this->system = $system;
		$this->DOMNode = $node;
	}

	/***********************************************
	 *   PROTECTED METHODS
	 ***********************************************/
	/**
	 *  Converts a string to boolean.
	 * @param $sBoolean string	- the string containing the word that can be converted to boolean
	 * @return boolean			- true: if $sBoolen is in (true, yes, t, y, 1 - case insensitive), false: otherwise
	 */
	protected function _getBoolean($sBoolean)
	{
		return in_array( strtolower($sBoolean), array( 'true', 't', 'yes', 'y', '1' ) );
	}

	protected function _checkNodeOptions() {

		/*  default is true: node creation is allowed  */
		$_bCreateIt = true;
		return $_bCreateIt;
		/*
		 *  "onuser" attribute gives a creation condition about the logged on (or not logged on) user(s).
		 * Separator is comma ",". We can just simple list the users but we can specifiy a right too:
		 *		- ALL
		 *		- LOGGEDON
		 *		- <username>
		 *		- right:<rightname>
		 * IMPORTANT: the USER component must be specified - mostly the SESSION component has to recreate
		 *			 it from the database, so it must already be initialized! (i.e. with system-init)
		 */

	}
	public function __felretett() {
		$_sOnUser = trim($this->DOMNode->getAttribute( "onuser" ));
		$_bCreateIt = true;
		if (strlen($_sOnUser)>0)
		{
			$_oUserComponent = $this->system->getComponent( "USER" );
			if (!(
				($_sOnUser == "ALL") ||
				(($_sOnUser == "LOGGEDON") && (isset($_oUserComponent))) ||
				(($_sOnUser == "NOTLOGGEDON") && (!isset($_oUserComponent)))
//				||(isset($_oUserComponent) && ($_aUsers = explode( ",", $_sOnUser)) && (in_array( $_oUserComponent->getUsername(), $_aUsers ))) ||
//				(isset($_oUserComponent) && (preg_match_all( '/right:([^,.]+),/i', $_sOnUser.",", $_aMatches)) && $_oUserComponent->hasRight($_aMatched[1]))
			))
				$_bCreateIt = false;
		}
/*
		// *  "onlang" attribute: which language(s) - a list, separated by commas
		$_sOnLang = $this->DOMNode->getAttribute( "onlang" );
		// *  "onpage" attribute: which page - the exact name (not prefix!)
		$_sOnPage = $this->DOMNode->getAttribute( "onpage" );
		// *  "notonpage" attribute: which page NOT - the exact name (not prefix!)
		$_sNotOnPage = $this->DOMNode->getAttribute( "notonpage" );
		// *  "onurlpath" attribute: which url folder - with "/" at the end of the path!
		$_sOnURLPath = $this->DOMNode->getAttribute( "onurlpath" );
		// *  "notonurlpath" attribute: which url folder is NOT - with "/" at the end of the path!
		$_sNotOnURLPath = $this->DOMNode->getAttribute( "notonurlpath" );
		// *  "onfiletype" attribute: which file type (group) - based on FType component
		$_sOnFileType = $this->DOMNode->getAttribute( "onfiletype" );
		// *  "oninterface" attribute: which interface
		$_sOnInterface = $this->DOMNode->getAttribute( "oninterface" );
		// *  "onserverid" attribute: which serverid
		$_sOnServerID = $this->DOMNode->getAttribute( "onserverid" );
		// *  "onservertype" attribute: which server type? "WIN" or "LINUX"
		$_sOnServerType = $this->DOMNode->getAttribute( "onservertype" );
*/
		/*  if the conditions above are not specified, then it is equal with true, otherwise it must meet the current state/conditions/facts  */
		if  (!(
//			((strlen($_sOnLang)==0) || (stripos( $_sOnLang, $this->system->getComponent("SYSTEM")->getLang() ) !== false)) &&
//			((strlen($_sOnPage)==0) || ( in_array( $_oClientProperties->getFileBaseName(), explode( ",", str_replace( ' ', '', $_sOnPage ) ) ) )) &&
//			((strlen($_sNotOnPage)==0) || (!( in_array( $_oClientProperties->getFileBaseName(), explode( ",", str_replace( ' ', '', $_sOnPage ) ) ) ))) &&
//			((strlen($_sOnURLPath)==0) || ( in_array( $_oClientProperties->getPath(), explode( ",", str_replace( ' ', '', $_sOnURLPath ) ) ) )) &&
//			((strlen($_sNotOnURLPath)==0) || (!( in_array( $_oClientProperties->getPath(), explode( ",", str_replace( ' ', '', $_sNotOnURLPath ) ) ) ))) &&
//			((strlen($_sOnFileType)==0) || (stripos( $_sOnFileType, $this->system->getComponent("FILETYPE")->getFType() ) !== false)) &&
//			((strlen($_sOnInterface)==0) || (stripos( $_sOnInterface, sy_SERVER_NAME ) !== false)) &&
//			((strlen($_sOnServerID)==0) || (stripos( $_sOnServerID, sy_SERVER_ID."" ) !== false)) &&
//			((strlen($_sOnServerType)==0) || (stripos( $_sOnServerType, sy_SERVER_OS_TYPE ) !== false)) &&
			1
		))
			$_bCreateIt = false;
		/*
		 *  "ondemand" attribute (true or false, 1 or 0) will store the parameters of the component and the
		 *  system will put them into the component upon creation
		 *		THIS IS NOT IMPLEMENTED YET!!!!!!!!
		$_bComponentOnDemand = ( in_array( strlower($this->DOMNode->getAttribute( "ondemand" )), array( "1", "true", "y", "yes" )) ? true : false );
		 */
		return $_bCreateIt;
	}

	protected function _createComponent( $componentName, $componentType ) {
		$this->component = $this->system->createComponent( $componentName, $componentType );
	}

	/***********************************************
	 *   PUBLIC METHODS
	 ***********************************************/
	/**
	 * @param XMLnode $parentNode
	 */
	public function setParent(XMLnode $parentNode) {
		$this->ParentNode = $parentNode;
	}

	public function getComponent() {
		return $this->component;
	}

	public function process(
		\sys\SystemBase $system,
		\sys\ServerObject $parentComponent = null) {
		if (!isset($this->DOMNode)) {
			return false;
		}
		return false;
	}

}