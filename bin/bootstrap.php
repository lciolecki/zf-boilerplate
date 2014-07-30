<?php

require_once(realpath(dirname(__FILE__) . '/..') . '/init_autoloader.php');

// Create application, bootstrap, and run
$application = new Zend_Application(APPLICATION_ENV, APPLICATION_PATH . '/configs/application.ini');

//Run Zend Framework application bootstrap
$application->bootstrap();