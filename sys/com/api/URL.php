<?php
/**
 * Created by PhpStorm.
 * User: Pylon
 * Date: 2015.05.31.
 * Time: 12:34
 */

namespace sys\com\api;


/**
 * Class URL
 * @package sys\api\com
 */
class URL extends \sys\com\URL {
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
		var_dump($parts);
		while(count($parts)>1) {
			$collection = array_shift($parts);
			$resource = array_shift($parts);
			$this->collections[$collection] = $resource;
		}
		if ( count($parts)>0 )
			$this->command = array_shift($parts);

	}


}