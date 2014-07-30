<?php

// Define application environment
defined('APPLICATION_ENV') || define('APPLICATION_ENV', (getenv('APPLICATION_ENV') ? getenv('APPLICATION_ENV') : 'testing'));

/* Create composer loader */
$loader = include(realpath(dirname(__FILE__) . '/..') . '/init_autoloader.php');

