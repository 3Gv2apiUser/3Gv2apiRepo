<?php
/**
 * Created by PhpStorm.
 * User: Pylon
 * Date: 2015-06-13
 * Time: 23:22
 */

namespace sys\mod\xml\check;

/**
 * Class onuser
 * @package sys\mod\xml\check
 */
class onuser extends onAttribute {

	/***********************************************
	 *   PUBLIC METHODS
	 ***********************************************/
	/*
	 *  "onuser" attribute gives a creation condition about the logged on (or not logged on) user(s).
	 * Separator is comma ",". We can just simple list the users but we can specifiy a right too:
	 *		- ALL, NONE
	 *		- LOGGEDON, LOGGEDOUT
	 *		- <username>
	 *		- group:<groupname>
	 *		- right:<rightname>
	 * IMPORTANT: the USER component must be specified - mostly the SESSION component has to recreate
	 *			 it from the database, so it must already be initialized! (i.e. with system-init)
	 */

	public function check() {

		$_sOnUserAttribute = trim($this->DOMElement->getAttribute( "onuser" ).'');
		if (strlen($_sOnUserAttribute)>0)
		{
			/** @var \sys\com\User $_oUserComponent */
			$_oUserComponent = $this->system->getComponent( "USER", false );
			switch (strtoupper($_sOnUserAttribute)) {

				case "MEMBER":
				case "LOGGEDON":
				case "LOGGEDIN":
				case "SIGNEDIN":
					return isset($_oUserComponent);

				case "GUEST":
				case "LOGGEDOUT":
				case "NOTLOGGEDON":
				case "NOTLOGGEDIN":
					return !isset($_oUserComponent);

				case "ALL":
					return true;

				case "NONE":
					return false;

				default:
					if (!isset($_oUserComponent)) {
						return false;
					}

					$aAttributes = explode(',', $_sOnUserAttribute);
					foreach ($aAttributes as $sAttribute) {
						if (strtolower(substr($sAttribute, 0, 6)) == "group:") {
							if ($_oUserComponent->hasGroup(substr($sAttribute, 6))) {
								return true;
							}
						} elseif (strtolower(substr($sAttribute, 0, 6)) == "right:") {
							if ($_oUserComponent->hasRight(substr($sAttribute, 6))) {
								return true;
							}
						} else {
							if ($_oUserComponent->getUsername() == $sAttribute) {
								return true;
							}
						}
					}
					return false;

			}
		}
		return true;
	}

}