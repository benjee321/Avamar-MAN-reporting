<?php

require_once 'Logger.php';

/*
 * Cookie container
 * 
 */


class Cookies
{
	/**
	 * @var Singleton The reference to *Singleton* instance of this class
	 */
	private static $instance;
	
	/*
	 * Cookie storage struct
	 * 
	 * array("server"=>"hostname",array("cookiename"=>"cookievalue"))
	 */
	
	private $_cookie_storage = array();
	private $_totalcount = 0;
	
	
	/**
	 * Returns the *Singleton* instance of this class.
	 *
	 * @return Singleton The *Singleton* instance.
	 */
	public static function getInstance()
	{
		if (null === static::$instance) {
			static::$instance = new static();
		}

		return static::$instance;
	}

	/**
	 * Protected constructor to prevent creating a new instance of the
	 * *Singleton* via the `new` operator from outside of this class.
	 */
	protected function __construct()
	{
		Logger::getInstance()->Log(LOG_SEVERITY_INFO, "Cookie storage initalized");
	}

	/**
	 * Private clone method to prevent cloning of the instance of the
	 * *Singleton* instance.
	 *
	 * @return void
	 */
	private function __clone()
	{
	}
	
	public function __destruct()
	{
		// This is just here to remind you that the
		// destructor must be public even in the case
		// of a singleton.
		unset ($this->_cookie_storage);
		Logger::getInstance()->Log(LOG_SEVERITY_INFO, "Cookie storage cleaned - Item count: ".$this->_totalcount);
		unset($this->_totalcount);
	}
	/**
	 * Private unserialize method to prevent unserializing of the *Singleton*
	 * instance.
	 *
	 * @return void
	 */
	private function __wakeup()
	{
		
	}
	
	public function getTotalCount(){
		
		return $this->_totalcount;
	}
	
	public function addCookie($server,$name,$value){
		
		
		array_push($this->_cookie_storage,array("server"=>$server,array($name=>$value)));
		$this->_totalcount++;
	}
	
	public function DeletebyName($server,$name){
		foreach ($this->_cookie_storage AS $key => $value) {
			if ($value["server"] == $server){
				unset($this->_cookie_storage[$key]);
				$this->_totalcount--;
			}
			$counter++;
		}	
	}
	
	public function DeletebyId($id){
		//echo $id;
		//var_dump($this->_cookie_storage);
		
		$counter = 1;
		foreach ($this->_cookie_storage AS $Key => $Value) {
			if ($counter == $id){
				unset($this->_cookie_storage[$Key][0]);
				$this->_totalcount--;
			}
			$counter++;
		}
		
	}

	public function getCookiebyName($server,$name){
		foreach ($this->_cookie_storage AS $key => $value) {
			if ($value == $server){
				return $this->_cookie_storage[$key][$name][1];
			}
		}
	}
	public function getCookiesbyServer($server){
	foreach ($this->_cookie_storage AS $key => $value) {
			if ($this->_cookie_storage[$key]["server"] == $server){
				return $this->_cookie_storage[$key];
			}
		}
		
	}
	
	public function getCookiebyId($id){
		$counter = 1;
		foreach ($this->_cookie_storage AS $Key => $Value) {
			if ($counter == $id){
				 return $this->_cookie_storage[$Key];
				$this->_totalcount--;
			}
			$counter++;
		}
	}
	
	public function listCookies(){
		return $this->_cookie_storage;
	}
	
}