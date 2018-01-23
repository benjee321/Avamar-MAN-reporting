<?php

define("LOG_SEVERITY_CRITICAL", 1001);
define("LOG_SEVERITY_SEVERE", 1002);
define("LOG_SEVERITY_WARNING", 103);
define("LOG_SEVERITY_INFO", 1004);
define("LOG_SEVERITY_DEBUG", 1005);
define("DB_DIR", "./");

$lgdate = round(microtime(true) * 1000);



$from_email = "<email>";

$users = array(
		"<email>","<email2>",
		);		
		

ini_set("SMTP", "smtp.server.com");

$options = array(
		'useragent'      => "Firefox (+http://www.firefox.org)", // who am i
		'connecttimeout' => 120, // timeout on connect
		'timeout'          => 120, // timeout on response
		'redirect'          => 0, // stop after 0 redirects
		'referer'           => "https://<fqdn/ip>:7443/imf.action",
		'verifypeer'	=> false,
		'verifyhost' => false,
);

//monitoring / Monit*r1ng
$servers = array(
			array("server"=>"fqd","user"=>"","pass"=>""),
			array("server"=>"fqd","user"=>"","pass"=>""),
		);


?>