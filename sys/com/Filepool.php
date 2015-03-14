<?php
/**
 * Created by PhpStorm.
 * User: Pylon
 * Date: 2015.03.14.
 * Time: 16:15
 */

namespace sys\com;

interface FilepoolInterface {

	public function getPath();

}
/**
 * Class Filepool
 * @package sys\com
 */
class Filepool extends \sys\ServerComponent implements FilepoolInterface {

	public function getPath() {
		return "";
	}
} 