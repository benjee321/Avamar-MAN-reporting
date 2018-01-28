<?php
require_once('./include/base.php');
require_once('./include/Application.php');
require_once('./include/Logger.php');
require_once('./include/sendEmail.php');

//Start Application
Application::getInstance()->Run();