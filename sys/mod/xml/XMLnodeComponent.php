<?php
/**
 * Created by PhpStorm.
 * User: Pylon
 * Date: 2015.06.01.
 * Time: 21:46
 */

namespace sys\mod\xml;


/**
 * Class XMLnode_Component
 * @package sys\mod\xml
 */
class XMLnodeComponent extends XMLnode {

	/***********************************************
	 *   PUBLIC METHODS
	 ***********************************************/
	/**
	 * @param \sys\SystemBase   $system
	 * @param \sys\ServerObject $parentComponent
	 *
	 * @return bool|mixed|\sys\ServerComponent
	 */
	public function process(
		\sys\SystemBase $system,
	                        \sys\ServerObject $parentComponent = null) {

		$_sComponentName = $this->DOMNode->getAttribute( "name" );
		if (strlen($_sComponentName) == 0)
		{
			syLog( 'XML node <component>: no name attributes, skipping...' );
			return false;
		}

		if ($this->_checkNodeOptions($system))
		{
			//  getting type attribute
			$_sComponentType = $this->DOMNode->getAttribute( "type" );

			if ($_oComponent = $system->getComponent($_sComponentName, false)) {
				syLog( 'XML node <component>: found component '.$_sComponentName.( strlen($_sComponentType)>0 ? ", type ".$_sComponentType." has been ignored." : "" ) );
				return $_oComponent;
			}

			// is a type given?
			if (strlen($_sComponentType)==0) {
				$_sComponentType = $_sComponentName;
			}

			// created component
			//   - if the type does not exists, NULL component will be created
			$_oComponent = $system->createComponent( $_sComponentName, $_sComponentType );
			syLog( "XML node <component>: created component ".$_sComponentName.( strlen($_sComponentType)>0 ? " with type ".$_sComponentType : "" ) );

			//  *******************************
			//  second step: component initialization
			//    whether the component should be initialized immediately after the creation
			//      - given by the "init" attribute (yes, true, now values)
			$_sComponentInitialization = strtolower($this->DOMNode->getAttribute( "init" ));
			if ($this->_getBoolean($_sComponentInitialization) || ($_sComponentInitialization == "now") ) {
				$_oComponent->initialize();
				syLog( "XML node <component>:   - initialized" );
			}

			/*  Continue the processing with childen and this object  */
			return $_oComponent;
		} else {
			syLog( "XML node <component>: attributes are not allowing this component, skipping..." );
		}
		return false;
	}

}