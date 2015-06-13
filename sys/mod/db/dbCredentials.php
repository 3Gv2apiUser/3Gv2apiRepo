<?php
/**
 * Created by PhpStorm.
 * User: Pylon
 * Date: 2015-06-13
 * Time: 10:05
 */
namespace sys\mod\db;

interface dbCredentialsInterface {

	public function setHost( $hostName );
	public function setPort( $port );
	public function setUsername( $username );
	public function setPassword( $password );
	public function setDatabase( $databaseName );

	public function getHost();
	public function getPort();
	public function getUsername();
	public function getPassword();
	public function getDatabase();

}




class dbCredentials implements dbCredentialsInterface {

	protected $host;
	protected $port;
	protected $username;
	protected $password;
	protected $database;

	/**
	 * @return string
	 */
	public function getHost()
	{
		return $this->host;
	}

	/**
	 * @param string $host
	 */
	public function setHost($host)
	{
		$this->host = $host;
	}

	/**
	 * @return mixed
	 */
	public function getPort()
	{
		return $this->port;
	}

	/**
	 * @param mixed $port
	 */
	public function setPort($port)
	{
		$this->port = $port;
	}

	/**
	 * @return string
	 */
	public function getUsername()
	{
		return $this->username;
	}

	/**
	 * @param string $username
	 */
	public function setUsername($username)
	{
		$this->username = $username;
	}

	/**
	 * @return string
	 */
	public function getPassword()
	{
		return $this->password;
	}

	/**
	 * @param string $password
	 */
	public function setPassword($password)
	{
		$this->password = $password;
	}

	/**
	 * @return string
	 */
	public function getDatabase()
	{
		return $this->database;
	}

	/**
	 * @param string $database
	 */
	public function setDatabase($database)
	{
		$this->database = $database;
	}

}

