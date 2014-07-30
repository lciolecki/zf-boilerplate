<?php

/* Create composer loader */
$loader = include(realpath(dirname(__FILE__) . '/..') . '/init_autoloader.php');

//Create and run Zend Framework CLI
$console = new Zend_Tool_Framework_Client_Console(array(
    'classesToLoad' => 'Zend_Tool_Project_Provider_Manifest'
));

$console->dispatch();