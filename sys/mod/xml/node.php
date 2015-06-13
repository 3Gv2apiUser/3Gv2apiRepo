<?php
/**
 * Created by PhpStorm.
 * User: Pylon
 * Date: 2015.06.01.
 * Time: 21:44
 */

namespace sys\mod\xml;

interface nodeInterface {
	public function process(\sys\SystemBase $system, \sys\ServerObject $parentComponent = null);
}

/**
 * Class nodeBase
 * @package sys\mod\xml
 */
class node implements nodeInterface {

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
	protected $DOMElement = null;
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
	 * @param \DOMElement        $node
	 */
	public function __construct(\sys\SystemBase $system, \DOMElement $node) {
		$this->system = $system;
		$this->DOMElement = $node;
	}

	/***********************************************
	 *   PROTECTED METHODS
	 ***********************************************/
	/**
	 *  Converts a string to boolean.
	 * @param $sBoolean string	- the string containing the word that can be converted to boolean
	 * @return boolean			- true: if $sBoolen is in (true, yes, t, y, 1 - case insensitive), false: otherwise
	 */
	protected function _getBoolean($sBoolean) {
		return in_array( strtolower($sBoolean), array( 'true', 't', 'yes', 'y', '1' ) );
	}

	protected $conditionalAttributes = array(
		"onuser",
	);
	protected function _checkNodeOptions() {

		/*  default is true: node creation is allowed  */
		foreach($this->conditionalAttributes as $attribute) {
			$attributeCheckClassname = '\\sys\\mod\\xml\\check\\'.$attribute;
			if (class_exists($attributeCheckClassname)) {
				/** @var check\onAttribute $checkerObject */
				$checkerObject = new $attributeCheckClassname($this->system, $this->DOMElement);
				if (!$checkerObject->check()) {
					return false;
				}
			}
		}
		return true;
	}

	public function __felretett() {
/*
		// *  "onlang" attribute: which language(s) - a list, separated by commas
		$_sOnLang = $this->DOMElement->getAttribute( "onlang" );
		// *  "onpage" attribute: which page - the exact name (not prefix!)
		$_sOnPage = $this->DOMElement->getAttribute( "onpage" );
		// *  "notonpage" attribute: which page NOT - the exact name (not prefix!)
		$_sNotOnPage = $this->DOMElement->getAttribute( "notonpage" );
		// *  "onurlpath" attribute: which url folder - with "/" at the end of the path!
		$_sOnURLPath = $this->DOMElement->getAttribute( "onurlpath" );
		// *  "notonurlpath" attribute: which url folder is NOT - with "/" at the end of the path!
		$_sNotOnURLPath = $this->DOMElement->getAttribute( "notonurlpath" );
		// *  "onfiletype" attribute: which file type (group) - based on FType component
		$_sOnFileType = $this->DOMElement->getAttribute( "onfiletype" );
		// *  "oninterface" attribute: which interface
		$_sOnInterface = $this->DOMElement->getAttribute( "oninterface" );
		// *  "onserverid" attribute: which serverid
		$_sOnServerID = $this->DOMElement->getAttribute( "onserverid" );
		// *  "onservertype" attribute: which server type? "WIN" or "LINUX"
		$_sOnServerType = $this->DOMElement->getAttribute( "onservertype" );
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
		$_bComponentOnDemand = ( in_array( strlower($this->DOMElement->getAttribute( "ondemand" )), array( "1", "true", "y", "yes" )) ? true : false );
		 */
		return true;
	}

	/***********************************************
	 *   PUBLIC METHODS
	 ***********************************************/
	/**
	 * @param node $parentNode
	 */
	public function setParent(node $parentNode) {
		$this->ParentNode = $parentNode;
	}

	public function process(\sys\SystemBase $system, \sys\ServerObject $parentComponent = null) {
		if (!isset($this->DOMElement)) {
			return false;
		}
		return false;
	}

}