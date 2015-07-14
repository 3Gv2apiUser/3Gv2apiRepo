<?php
/**
 * Created by PhpStorm.
 * User: Pylon
 * Date: 2015-06-14
 * Time: 12:32
 */
/*************************************************************�
 *  @package  Manhertz
 *  @author   Tamas Manhertz
 *  @version  V0.99.20150614
 *************************************************************/

namespace sys\com;


/**
 * Class Session
 * @package sys\com
 */
class Session extends \sys\ServerComponent {

	/***********************************************
	 *   PROPERTIES
	 ***********************************************/
	/**
	 * @var string
	 */
	protected $session_id;
	/**
	 * @var string
	 */
	protected $session_name;
	/***********************************************
	 *   CONSTRUCT
	 ***********************************************
	public function __construct() {
	}
	 */
	/***********************************************
	 *   PROTECTED METHODS
	 ***********************************************/
	protected function onInitialize() {
		if ($this->session_name) {
			session_name($this->session_name);
		}
		if ($this->session_id) {
			session_id($this->session_id);
		}
		session_start();
	}
	protected function onFinalize() {
		session_write_close();
		session_unset();
	}
	/***********************************************
	 *   PUBLIC METHODS
	 ***********************************************/
	/**
	 * @param string $session_id
	 */
	public function set_session_id( $session_id ) {
		$this->session_id = $session_id;
	}
}