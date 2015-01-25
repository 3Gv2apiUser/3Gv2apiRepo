<?php
//============================================================+
// File name   : index.php
//
// Description : startup
//
//============================================================+
/**
 * User: Pylon
 * Date: 2014.05.05.
 *
 * base index.php - startup for controllers and views
 */

define('ROOT', dirname(dirname(__FILE__)) . DIRECTORY_SEPARATOR);

echo "rrr";

/**
 * starting bootstrapping
 */
require_once (ROOT . DIRECTORY_SEPARATOR . 'sys' . DIRECTORY_SEPARATOR . 'bootstrap.php');