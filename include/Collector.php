<?php


class Collector
{
	/**
	 * @var Singleton The reference to *Singleton* instance of this class
	 */
	private static $instance;
	
	private $_HttpRequest;
	
	//default settings for httprequest
	private $options = array(
			'useragent'      => "Firefox (+http://www.firefox.org)", // who am i
			'connecttimeout' => 120, // timeout on connect
			'timeout'          => 120, // timeout on response
			'redirect'          => 0, // stop after 0 redirects
			'referer'           => "https://hikaava01man01.ad.harman.com:7443/imf.action",
			'verifypeer'	=> false,
			'verifyhost' => false,
	);
	
	private $_events;
	private $_tasks;
	private $_eventcounts = array();
	private $_taskcount;
	
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
		//Createa HttpRequest object
		$this->_HttpRequest = new HttpRequest();
		$this->_HttpRequest->setOptions($this->options);
		//Log class start
		Logger::getInstance()->Log(LOG_SEVERITY_INFO, "Collector started");
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

		Logger::getInstance()->Log(LOG_SEVERITY_INFO, "Collector stoped");
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

	public function CollectEvents($server){
				
		
		$this->_HttpRequest->setMethod(HTTP_METH_GET);
		
		$cookies = Cookies::getInstance()->getCookiesbyServer($server);
		
		
		
		$this->_HttpRequest->setCookies($cookies[0]);
		
		
	
		$this->_HttpRequest->setUrl("https://".$server.":7443/rest/event.json?filters.unacknowledged=true&filters.severity=INFO&filters.severity=CRITICAL&filters.severity=SEVERE&filters.severity=WARN&sort=timestamp&dir=ASC");
		try {
			$events = json_decode($this->_HttpRequest->send()->getBody(),true);
			$this->_events = $events['events'];
			$this->_eventcounts = $events['summary'];
			unset ($events);
		}catch (HttpException $ex){
			var_dump($ex);
		}
		
	}
	
	//--------------TASKS START--------------------------------------
	
	public function CollectTasks($server){
		
		$this->_HttpRequest->setMethod(HTTP_METH_GET);
		
		$cookies = Cookies::getInstance()->getCookiesbyServer($server);
		
		$this->_HttpRequest->setCookies($cookies[0]);
		
		$this->_HttpRequest->setUrl("https://".$server.":7443/rest/task.json?filters.dateType=timeRange&filters.state=ACTIVE&filters.state=SCHEDULED&filters.state=COMPLETED&filters.state=CANCELLED&filters.state=FAILED&sort=startTime".
				"&filters.endTime=2015-07-05%2023:45:00%20%2B02:00&filters.startTime=2015-06-01%2000:00:00%20%2B02:00");
		
	try {
			$tasks = json_decode($this->_HttpRequest->send()->getBody(),true);
			$this->_tasks = $tasks['tasks'];
			$this->_taskcount = $tasks['totalRecords'];
			unset ($tasks);
		}catch (HttpException $ex){
			var_dump($ex);
		}
		
				
	}
	
	//------------------------------------------------------
	
	
	public function CollectPoolTapes($server){
		$this->_HttpRequest->setMethod(HTTP_METH_GET);
	}
	
	//----------------------------------------------------------
	
	
	public function Authenticate($server){
		$this->_HttpRequest->setMethod(HTTP_METH_POST);
		
		$this->_HttpRequest->setUrl("https://".$server.":7443/login.action");
		
		foreach ($GLOBALS['servers'] as $key =>$value) {
			if($value["server"] == $server){
				$postdata = array(
						"name" => $value["user"],
						"pass" => $value["pass"],
						"date" => $GLOBALS['lgdate'],
				);
				
			}
		}
		
		
		$this->_HttpRequest->setPostFields($postdata);
		
		$response = $this->_HttpRequest->send()->getBody();
		//Check if authentication was successfull
		if ($response == "Authentication Failed"){
			Logger::getInstance()->Log(LOG_SEVERITY_INFO, "Authentication failed against - ".$server);
			return false;
		}else if ($response =="locked out"){
			Logger::getInstance()->Log(LOG_SEVERITY_INFO, "User is locked out from the server - ".$server);
			return false;
		}else{
			
			Logger::getInstance()->Log(LOG_SEVERITY_INFO, "Authentication successeded - ".$server);
			
			//save response cookies to further work
			$responseCookies = $this->_HttpRequest->getResponseCookies();
			
		foreach ($responseCookies[0]->cookies as $key =>$value) {
			Cookies::getInstance()->addCookie($server,$key,$responseCookies[0]->cookies[$key]);
		}
			return true;
		}
		
	}
	
	public function getEvents(){
		return $this->_events;
	}
	
	public function getEventCounts(){
		return $this->_eventcounts;
	}
	
	public function getTasks(){
		return $this->_tasks;
	}
	
	public function getTaskCount(){
		return $this->_taskcount;
	}
	
	
}
