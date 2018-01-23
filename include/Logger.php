<?php


class Logger
{
	/**
	 * @var Singleton The reference to *Singleton* instance of this class
	 */
	private static $instance;
	private $_logfile = null;

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
	protected function __construct($logfile = null)
	{
		if($logfile == null){
			$this->_logfile = "./avamanlog.log";
		}
		
	}

	public function setLogPath($path){
		$this->_logfile = $path;
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
	
	public function Log($severity,$msg){
		file_put_contents($this->_logfile,"Time: ".date(DATE_RFC2822)." - Level: ".$severity." - Message: ".$msg."\n", FILE_APPEND | LOCK_EX);
	}
	
}