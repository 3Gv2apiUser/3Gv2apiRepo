<?php
/**
 * Created by PhpStorm.
 * User: Pylon
 * Date: 2015.06.01.
 * Time: 2:04
 */

namespace sys\mod\xml;

interface XMLprocInterface {
	public function process();
}

/**
 * Class XMLproc
 * @package sys\mod\xml
 */
class XMLproc implements XMLprocInterface {

	/***********************************************
	 *   PROPERTIES
	 ***********************************************/
	/**
	 * @var \sys\SystemBase
	 */
	protected $system = null;
	/**
	 * @var \DOMDocument
	 */
	protected $DOMDocument = null;
	/**
	 * list of allowed nodes in xml config
	 *
	 * @var array of strings
	 */
	protected $allowedNodeTypes = array(
		"component",
		"var",
	);
	/***********************************************
	 *   CONSTRUCT
	 ***********************************************/
	/**
	 * @param \sys\SystemBase   $system
	 * @param \DOMDocument $DOMDocument
	 */
	public function __construct(\sys\SystemBase $system, \DOMDocument $DOMDocument) {
		$this->system = $system;
		$this->DOMDocument = $DOMDocument;
	}

	/***********************************************
	 *   PROTECTED METHODS
	 ***********************************************/
	/**
	 * @param \DOMNode          $node
	 * @param \sys\ServerObject $oParentComponent
	 *
	 * @return bool
	 */
	public function _processXML(\DOMNode $node,
	                            \sys\ServerObject $oParentComponent = null) {
		if (isset($node)) {

			$_bProcessChildren = true;
			$_oComponentObject = $oParentComponent;

			$_sNodeName = strtolower($node->nodeName);
			if (in_array($_sNodeName, $this->allowedNodeTypes)) {
				syLog( "XML:  node: ".$node->nodeName." processing, value: ".substr($node->nodeValue,0,20) );

				$_sNodeClassname = '\\sys\\mod\\xml\\XMLnode'.ucfirst($_sNodeName);
				if (class_exists($_sNodeClassname)) {
					/** @var \sys\mod\xml\XMLnode $_oNodeObject */
					$_oNodeObject = new $_sNodeClassname( $this->system, $node );
					$_oComponentObject = $_oNodeObject->process($this->system, $_oComponentObject);
					if ($_oComponentObject === false) {
						$_bProcessChildren = false;
					}
				}
			} else {
				syLog( "XML:  node: ".$node->nodeName." node is not allowed here, continue with children." );
			}

			if ($_bProcessChildren) {
				$_oChildNode = $node->firstChild;
				while($_oChildNode) {
					$this->_processXML($_oChildNode, $_oComponentObject);
					$_oChildNode = $_oChildNode->nextSibling;
				}
			}
		}
		return true;
	}

	/***********************************************
	 *   PUBLIC METHODS
	 ***********************************************/
	public function process() {
		if ($this->DOMDocument && $this->DOMDocument->firstChild && $this->DOMDocument->firstChild->nodeName == 'root') {
			return $this->_processXML($this->DOMDocument->firstChild, null);
		}
		syLog("XML: no root has been found");
		return false;
	}
}