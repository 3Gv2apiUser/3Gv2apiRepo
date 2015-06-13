<?php
/**
 * Created by PhpStorm.
 * User: Pylon
 * Date: 2015-06-13
 * Time: 23:20
 */

namespace sys\mod\xml\check;
use sys\mod\xml;

interface onAttributeInterface {

	public function check();

}

/**
 * Class node_Component
 * @package sys\mod\xml\check
 */
class onAttribute implements onAttributeInterface {

	protected $system = null;
	protected $DOMElement = null;

	/***********************************************
	 *   PUBLIC METHODS
	 ***********************************************/
	public function __construct(\sys\SystemBase $system, \DOMElement $DOMElement) {
		$this->system = $system;
		$this->DOMElement = $DOMElement;
	}

	public function check() {
		return true;
	}

}