<?php
//============================================================+
// File name   : index.php
//
// Description : startup
//
//============================================================+
/**
 * User: Pylon
 * Date: 2015.03.05.
 *
 * base batch.php - startup for controllers and views
 */
error_reporting(E_ALL);

/*
 * constant name: ROOT
 *   description: the root directory of the system WITH ending separator (the sys directory must be here)
 */
define('ROOT', dirname(dirname(__FILE__)) . DIRECTORY_SEPARATOR);
/*
 * constant name: SYSTEM_ENTRY_POINT
 *   description: it defines where the php system has started.
 *      - api
 *      - www
 *      - batch
 */
define('SYSTEM_ENTRY_POINT', 'api');

/**
 * starting bootstrap
 */
/** @noinspection PhpIncludeInspection */
require (ROOT . 'sys' . DIRECTORY_SEPARATOR . 'bootstrap.php');