<?php
/**
 * Created by PhpStorm.
 * User: Pylon
 * Date: 2015.06.01.
 * Time: 21:46
 */

namespace sys\mod\xml;


/**
 * Class node_Var
 * @package sys\mod\xml
 */
class nodeVar extends node {

	/***********************************************
	 *   PUBLIC METHODS
	 ***********************************************/
	/**
	 * @param \sys\SystemBase   $system
	 * @param \sys\ServerObject $parentComponent
	 *
	 * @return bool
	 */
	public function process(
		\sys\SystemBase $system,
		\sys\ServerObject $parentComponent = null) {

		$_sVarName = $this->DOMElement->getAttribute( "name" );
		if (!preg_match('/^([0-9a-zA-Z]|\_|\-)+$/', $_sVarName))
		{
			syLog( "XML node <var>: no or illegal name attributes, skipping..." );
			return false;
		}

		/*
         *   There are two ways how to determine the type of the value.
		 *  The given value will be converted to a specified type depends on the vartype.
		 *      1. type="<vartype>" node attribute
		 *      2. hungarian notation in the var name
		 * 
		 *  The varname can be in the following structure:
         *
         *          [_]x{.}*
         *
         *  [_] : optional underline (it has no meanings in type definition - private or public)
         *   x  :  "b" : boolean
         *         "s" : string
         *         "i" : integer
         *         otherwise : string
		 */
		$_sLogText = " XML data name: '".$_sVarName."' type: ";
		$_sVarTypeName = strtolower($this->DOMElement->getAttribute( "type" ));
		switch ( (strlen($_sVarTypeName)>0 ? $_sVarTypeName : ($_sVarName[ ( $_sVarName[0] == "_" ? 1 : 0 ) ]) ))
		{
			case "b":
			case "bool":
			case "boolean":
				$_sVarType = "bool";
				break;
			case "c":
			case "comp":
			case "component":
				$_sVarType = "component";
				break;
			case "i":
			case "int":
			case "integer":
				$_sVarType = "int";
				break;
			case "d":
			case "date":
			case "datetime":
				$_sVarType = "datetime";
				break;
			case "s":
			case "str":
			case "string":
			default:
				$_sVarType = "string";
				break;
		}

		//  inspecting and convert the value
		$_mValue = '';
		if ($_sVarType == "bool") {
			/*
			 * Checking whether the value is a boolean (0 or 1, true of false)...
			 *  (otherwise it will be checked as an integer)
			 */
			switch (strtolower($this->DOMElement->nodeValue)) {
				case "1":
				case "y":
				case "yes":
				case "true":
					$_mValue = true;
					$_sLogText .= "boolean data: true";
					break;
				case "0":
				case "n":
				case "no":
				case "false":
				default:
					$_mValue = false;
					$_sLogText .= "boolean data: false";
					break;
			}
		} else {

			if ($_sVarType == "component") {
				/*
				 * query the component from system - if no component, of course it results NULL component
				 *  - so there will always be a component available, just it will do nothing at all
				 */
				$_mValue = $this->system->getComponent($this->DOMElement->nodeValue);
			}
			if ($_sVarType == "datetime") {
				/*
				 * Checking whether the value contains really a number...
				 *  (otherwise it will be a string)
				 */
				$_mValue = strtotime($this->DOMElement->nodeValue);
				if ($_mValue === false) {
					$_sVarType = "string";
				}
			}
			if ($_sVarType == "int") {
				/*
				 * Checking whether the value contains really a number...
				 *  (otherwise it will be a string)
				 */
				if (preg_match("/^((\+|\-){0,1})([0-9])+(\.[0-9]+){0,1}$/", $this->DOMElement->nodeValue))
				{
					$_mValue = $this->DOMElement->nodeValue * 1;
					$_sLogText .= "integer data: ".$_mValue;
				} else {
					$_sVarType = "string";
				}
			}
			if ($_sVarType == "string") {
				/*
				 * It makes the value as a string value
				 */
				$_mValue = $this->DOMElement->nodeValue."";
				$_sLogText .= "string data (first 40char): ".substr($_mValue,0,40);
			}
		}
		sylog($_sLogText, 0);

		// if the parent is a component and a setter method exists for the var parameter
		if (
			($parentComponent instanceof \sys\ServerComponent) &&
			(
				(($setterMethodName = 'set_'.$_sVarName) && method_exists($parentComponent, $setterMethodName))
				|| (($setterMethodName = 'set'.ucfirst($_sVarName)) && method_exists($parentComponent, $setterMethodName))
			)
		) {
			$parentComponent->$setterMethodName($_mValue);
			return false;
		}

		//  if the parent is an object (or component without setter)
		if (is_object($parentComponent)) {
			$parentComponent->$_sVarName = $_mValue;
		}
		//  or the parent is an array
		elseif (is_array($parentComponent)) {
			$parentComponent[$_sVarName] = $_mValue;
		}
		else {
			syLog( "XML node <var>:  - parent is not an object or array to set var into" );
		}

		return false;
	}

}