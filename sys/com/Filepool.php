<?php
/*************************************************************�
 *  @package  Manhertz
 *  @author   Tamas Manhertz
 *  @version  V0.99.20150305
 *************************************************************/


namespace sys\com;

interface FilepoolInterface {

	/**
	 *  This method looks for the first existing file in the order of filepool level hierarchy in the subdirectories.
	 * The result is an object that describes the file.
	 *
	 * @param $sFilename string
	 *
	 * @return FilepoolResult
	 */
	public function getPath($sFilename);

}

/**
 * Interface FilepoolResultInterface
 * 
 * The full filepath looks like this:
 *  ROOT . $filepoolPath . $filename
 * 
 * @package sys\com
 */
interface FilepoolResultInterface {

	public function setFileFound($fileFound);
	public function getFileFound();
	public function setFilePath($filePath);
	public function getFilePath();
	public function setFilename($sFilename);
	public function getFilename();
	public function setFilepoolCategory($filepoolCategory);
	public function getFilepoolCategory();
	public function setFilepoolLevel($filepoolLevel);
	public function getFilepoolLevel();
	public function setFilepoolPath($filepoolPath);
	public function getFilepoolPath();
	public function setFullPath($fullPath);
	public function getFullPath();
}

/**
 * Class Filepool
 * @package sys\com
 */
class Filepool extends \sys\ServerComponent implements FilepoolInterface {

	CONST FILEPOOL_LEVEL_FORCE = 0;
	CONST FILEPOOL_LEVEL_USER = 1;
	CONST FILEPOOL_LEVEL_GROUP = 2;
	CONST FILEPOOL_LEVEL_ENTRY = 3;
	CONST FILEPOOL_LEVEL_GLOBAL = 9;

	CONST FILEPOOL_CATEGORY_PHP = 'php';
	CONST FILEPOOL_CATEGORY_HTM = 'htm';
	CONST FILEPOOL_CATEGORY_GFX = 'gfx';
	CONST FILEPOOL_CATEGORY_CSS = 'css';
	CONST FILEPOOL_CATEGORY_JS = 'js';
	CONST FILEPOOL_CATEGORY_ANY = 'other';

	/***********************************************
	 *   PROPERTIES
	 ***********************************************/

	/**
	 * Maps the file extensions (got from the filename) to filepool categories.
	 *
	 * @var array
	 */
	protected $extensionsToCategories = array(
		'php' => self::FILEPOOL_CATEGORY_PHP,
		'htm' => self::FILEPOOL_CATEGORY_HTM,
		'html' => self::FILEPOOL_CATEGORY_HTM,
		'css' => self::FILEPOOL_CATEGORY_CSS,
		'js' => self::FILEPOOL_CATEGORY_JS,
		'jpg' => self::FILEPOOL_CATEGORY_GFX,
		'gif' => self::FILEPOOL_CATEGORY_GFX,
		'ico' => self::FILEPOOL_CATEGORY_GFX,
		'bmp' => self::FILEPOOL_CATEGORY_GFX,
		'tif' => self::FILEPOOL_CATEGORY_GFX,
		'any' => self::FILEPOOL_CATEGORY_ANY
	);

	/**
	 * This maps all filepool categories to a base directory. This is the "FORCE" level where we look for the
	 * file first and then we switch to "/pool" subdirectory where the other subdirectories exist for all different levels.
	 * The only exception is the "GLOBAL" level where no more subdirectory exists.
	 * See $filepoolLevelsToSubdirectories.
	 *
	 * @var array of strings
	 */
	protected $categoriesToBaseDirectories = array(
		self::FILEPOOL_CATEGORY_PHP => 'sys/',
		self::FILEPOOL_CATEGORY_HTM => 'pub/',
		self::FILEPOOL_CATEGORY_CSS => 'pub/',
		self::FILEPOOL_CATEGORY_JS  => 'pub/',
		self::FILEPOOL_CATEGORY_ANY => 'pub/',
	);


	protected $filepoolLevelHierarchy = array(
		self::FILEPOOL_LEVEL_FORCE,
		self::FILEPOOL_LEVEL_USER,
		self::FILEPOOL_LEVEL_GROUP,
		self::FILEPOOL_LEVEL_ENTRY,
		self::FILEPOOL_LEVEL_GLOBAL
	);

	/**
	 * This maps the filepool levels to specific subdirectories in the filepool relative to the
	 * array $categoriesToBaseDirectories.
	 *
	 * @var array of strings
	 */
	protected $filepoolLevelsToSubdirectories = array(
		self::FILEPOOL_LEVEL_FORCE => '',
		self::FILEPOOL_LEVEL_USER => 'pool/user/',
		self::FILEPOOL_LEVEL_GROUP => 'pool/group/',
		self::FILEPOOL_LEVEL_ENTRY => 'pool/entry/',
		self::FILEPOOL_LEVEL_GLOBAL => 'pool/',
	);

	/***********************************************
	 *   CONSTRUCT
	 ***********************************************/

	/**
	 * this is special for System component, it stores itself, no parameters
	 */
	public function __construct($system) {
		if (!defined('SYSTEM_ENTRY_POINT')) {
			throw new \Exception(
				"FATAL ERROR: No system entry point has been defined."
			);
		}
		parent::__construct($system);
	}

	/***********************************************
	 *   PROTECTED METHODS
	 ***********************************************/

	/**
	 * Returns the filepool category that includes the file extension.
	 *
	 * @param $sFilename
	 *
	 * @return mixed
	 */
	protected function getFilepoolCategory($sFilename) {
		$path_info = pathinfo($sFilename);
		if (array_key_exists('extension', $path_info)) {
			$fileExtension = $path_info['extension'];
		} else {
			$fileExtension = 'any';
		}
		if (array_key_exists($fileExtension, $this->extensionsToCategories)) {
			return $this->extensionsToCategories[$fileExtension];
		}
		return $this->extensionsToCategories['any'];
	}
	/***********************************************
	 *   PUBLIC METHODS
	 ***********************************************/
	/**
	 *  This method looks for the first existing file in the order of filepool level hierarchy in the subdirectories.
	 * The result is an object that describes the file.
	 *
	 * @param $sFilename string
	 *
	 * @return FilepoolResult
	 */
	public function getPath($sFilename) {

		$oFilepoolResult = new FilepoolResult();

		$oFilepoolResult->setFilepoolCategory($this->getFilepoolCategory($sFilename));
		$oFilepoolResult->setFilename($sFilename);

		foreach( $this->filepoolLevelHierarchy as $filepoolLevel) {
			$oFilepoolResult->setFilepoolPath(
				$this->categoriesToBaseDirectories[$oFilepoolResult->getFilepoolCategory()] .
				$this->filepoolLevelsToSubdirectories[$filepoolLevel]
			);
			$oFilepoolResult->setFilePath(ROOT . $oFilepoolResult->getFilepoolPath());
			if (file_exists($oFilepoolResult->getFilePath() . $sFilename)) {
				$oFilepoolResult->setFilepoolLevel($filepoolLevel);
				$oFilepoolResult->setFullPath( ROOT . $oFilepoolResult->getFilepoolPath() . $sFilename );
				return $oFilepoolResult;
			}
		}
		$oFilepoolResult->setFileFound(false);
		return $oFilepoolResult;
	}
}




class FilepoolResult implements FilepoolResultInterface {
	/***********************************************
	 *   PROPERTIES
	 ***********************************************/
	protected $fileFound = true;
	protected $filepoolLevel;
	protected $filepoolCategory;
	protected $filepoolPath;
	protected $filePath;
	protected $filename;
	protected $fullPath;

	/**
	 * @param mixed $fileFound
	 */
	public function setFileFound($fileFound) {
		$this->fileFound = $fileFound;
	}
	/**
	 * @return mixed
	 */
	public function getFileFound() {
		return $this->fileFound;
	}
	/**
	 * @param mixed $filePath
	 */
	public function setFilePath($filePath) {
		$this->filePath = $filePath;
	}
	/**
	 * @return mixed
	 */
	public function getFilePath() {
		return $this->filePath;
	}

	/**
	 * @param mixed $sFilename
	 */
	public function setFilename($sFilename) {
		$this->filename = $sFilename;
	}

	/**
	 * @return mixed
	 */
	public function getFilename() {
		return $this->filename;
	}

	/**
	 * @param mixed $filepoolCategory
	 */
	public function setFilepoolCategory($filepoolCategory) {
		$this->filepoolCategory = $filepoolCategory;
	}

	/**
	 * @return mixed
	 */
	public function getFilepoolCategory() {
		return $this->filepoolCategory;
	}

	/**
	 * @param mixed $filepoolLevel
	 */
	public function setFilepoolLevel($filepoolLevel) {
		$this->filepoolLevel = $filepoolLevel;
	}

	/**
	 * @return mixed
	 */
	public function getFilepoolLevel() {
		return $this->filepoolLevel;
	}

	/**
	 * @param mixed $filepoolPath
	 */
	public function setFilepoolPath($filepoolPath) {
		$this->filepoolPath = $filepoolPath;
	}

	/**
	 * @return mixed
	 */
	public function getFilepoolPath() {
		return $this->filepoolPath;
	}

	/**
	 * @param mixed $fullPath
	 */
	public function setFullPath($fullPath) {
		$this->fullPath = $fullPath;
	}

	/**
	 * @return mixed
	 */
	public function getFullPath() {
		return $this->fullPath;
	}

}
