<?php
/**
 * Created by PhpStorm.
 * User: Pylon
 * Date: 2015-06-13
 * Time: 23:49
 */
namespace sys\com;

interface UserInterface {

	public function getUserid();
	public function getUsername();

	public function addRight( $right );
	public function hasRight( $right );
	public function removeRight( $right );

	public function addGroup( $group );
	public function hasGroup( $group );
	public function removeGroup( $group );

}

/**
 * Class NULL
 * @package sys\com
 */
class User extends \sys\ServerComponent implements UserInterface {

	/***********************************************
	 *   PROPERTIES
	 ***********************************************/

	/***********************************************
	 *   CONSTRUCT
	 ***********************************************
	public function __construct() {
	}
	 */
	/***********************************************
	 *   PROTECTED METHODS
	 ***********************************************/

	/***********************************************
	 *   PUBLIC METHODS
	 ***********************************************/

	public function getUserid() {
		return false;
	}
	public function getUsername() {
		return false;
	}

	public function addRight( $right ) {
		return false;
	}
	public function hasRight( $right ) {
		return false;
	}
	public function removeRight( $right ) {
		return false;
	}

	public function addGroup( $group ) {
		return false;
	}
	public function hasGroup( $group ) {
		return false;
	}
	public function removeGroup( $group ) {
		return false;
	}

}