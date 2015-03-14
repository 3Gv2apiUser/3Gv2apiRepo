<?php
/*************************************************************�
 *  @package  Manhertz
 *  @author   Tamas Manhertz
 *  @version  V0.99.20150305
 *************************************************************/


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