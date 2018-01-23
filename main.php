<?php
require_once ('./include/base.php');
require_once ('./include/Application.php');
require_once './include/Logger.php';
require_once './include/sendEmail.php';



//Start Application


Application::getInstance()->Run();
/*
 filters.endTime
2015-07-31 00:00:00 +02:00
filters.startTime
2015-06-03 00:00:00 +02:00
*/


//last day of a month
//echo date("Y-m-t h:m:s P")."\n";

//first day of the month
//echo date("Y-m-01 h:m:s P")."\n";

//echo microtime(true);

//echo strtotime("s","2015-07-31 00:00:00 +02:00");