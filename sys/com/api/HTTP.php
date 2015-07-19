<?php
/**
 * Created by PhpStorm.
 * User: Pylon
 * Date: 2015.05.31.
 * Time: 12:34
 */

namespace sys\com\api;


/**
 * Class HTTP
 * @package sys\api\com
 */
class HTTP extends \sys\com\HTTP {
	/***********************************************
	 *   PROPERTIES
	 ***********************************************/

	/**
	 *  List of collections with requested resources. Sort order means the processing order.
	 * @var array
	 */
	protected $collections = array();

	/**
	 *  command in request
	 * @var string
	 */
	protected $command = 'list';

	/***********************************************
	 *   PROTECTED METHODS
	 ***********************************************/

	/**
	 * @param string $path
	 */
	protected function _analyzePath($path) {
		$parts = explode( '/', trim($path, '/') );

		while(count($parts)>1) {
			$collection = array_shift($parts);
			$resource = array_shift($parts);
			array_push( $this->collections, [
				"collection" => $collection,
				"resource" => $resource
			] );
		}

		if ( count($parts)>0 ) {
			$this->command = array_shift($parts);
		}
	}

	public function getCollections() {
		return $this->collections;
	}

	public function getCommand() {
		return $this->command;
	}

	public function getParameters() {

	}
}