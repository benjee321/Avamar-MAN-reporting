<?php
namespace AERR;
require_once './include/Collector.php';
require_once './include/Cookies.php';


class Application
{
        /**
         * @var Singleton The reference to *Singleton* instance of this class
        */
        private static $instance;
        private $_servers = array();
        private $_authenticated;
        private $_sendmail;
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
		Logger::getInstance()->Log(LOG_SEVERITY_INFO, "Application started");
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

		Logger::getInstance()->Log(LOG_SEVERITY_INFO, "Application stoped");
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

	public function CheckServers(){

		foreach ($GLOBALS['servers'] as $key =>$value) {

			$connected = @fsockopen($value["server"], 7443,$errno,$errstr,10);
			 
			if ($connected){
				array_push($this->_servers, $value["server"]);
				Logger::getInstance()->Log(LOG_SEVERITY_CRITICAL, "Connection to ".$value["server"]." is OK.");
			}else {
				Logger::getInstance()->Log(LOG_SEVERITY_CRITICAL, "Cannot connect to ".$value["server"]." host. - Err#: ".$errno." - Errmsg: ".$errstr);
			}

		}
	}

	public function Run(){
		
		//Check date to run export job collector if date is last day of the month
		
		
		//Check if connection is on to the servers
		$this->CheckServers();
		
		
		//Itterate over the servers
		
		
		foreach ($this->_servers as $key =>$servername){
			
		//Authenticate
		$this->_authenticated =  Collector::getInstance()->Authenticate($servername);
		
		//Start data collecting for evenst for daily run
		Collector::getInstance()->CollectEvents($servername);
		//End of datacollecting
		//Collector::getInstance()->getEvent
		
		//Send email with Events to addresses
		$this->_sendmail = new sendEmail();
		//var_dump(array("data" => Collector::getInstance()->getEvents()));
		
		$this->_sendmail->setContent(array("data" => Collector::getInstance()->getEvents(),"eventcounts" => Collector::getInstance()->getEventCounts()));
		$eventcounts = Collector::getInstance()->getEventCounts();
		$this->_sendmail->Send('events',"Avamar MAN ".$this->_servers[0]. " Daily report - Critical Events: ".($eventcounts["numCriticalEvents"]+$eventcounts["numSevereEvents"]));
		
		//Check if the last day of the month is today

		if ( date("Y-m-d") == date("Y-m-t", strtotime((date("Y-m-d"))))   ){
		//Collector::getInstance()->CollectTasks($this->_servers[0]);
			
		}
		
		
		Collector::getInstance()->CollectTasks($servername);
		
		$this->_sendmail->setContent(array("data" => Collector::getInstance()->getTasks(),"taskcount" => Collector::getInstance()->getTaskCount()));
		$this->_sendmail->Send('tasks',"Avamar MAN ".$servername. " Monthly Export report - Task counts: ". Collector::getInstance()->getTaskCount());
		}
	}
}
