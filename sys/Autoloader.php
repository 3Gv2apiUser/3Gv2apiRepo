<?php
/*************************************************************�
 *  @package  Manhertz
 *  @author   Tamas Manhertz
 *  @version  V0.99.20150305
 *************************************************************/
namespace sys;

// First we load the SplClassLoader
require_once( ROOT . 'libs/SplClassLoader.php' );

/**
 * Class Autoloader
 * @package sys
 */
class Autoloader {

    const AUTOLOADER_DIRECTORY_PATH_DOES_NOT_EXIST = "The specified autoload %s path for %s does not exist. Please check the configuration.";
    /**
     * List of all autoload paths to register in bootstrap
     * <name> => <directory_path_from_root>
     * where the directory path should not contain a leading "/"
     *
     * @var array of strings
     */
    protected $paths = array(
        "sys"       =>  "",
        "Psr"       =>  "libs",
        "Monolog"   =>  "libs",
        "adodb"     =>  "libs/adodb5",
        "smarty"    =>  "libs/smarty",
    );

    public function __construct() {
        foreach( $this->paths as $name => $path ) {
            if (is_dir( ROOT . $path)) {
	            $oAutoloader = new \SplClassLoader( $name, ROOT . $path );
                $this->paths[$name] = $oAutoloader;
	            $oAutoloader->register();
            } else {
                throw new \Exception(sprintf(self::AUTOLOADER_DIRECTORY_PATH_DOES_NOT_EXIST, $path, $name ));
            }
        }
    }

    public function __destruct() {
	    /** @var \SplClassLoader $autoloaderObject */
        foreach( $this->paths as $autoloaderObject ) {
            if (get_class( $autoloaderObject ) == "SplClassLoader" ) {
                $autoloaderObject->unregister();
            }
        }
    }
}