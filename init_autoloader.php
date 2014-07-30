<?php

// Define path to application directory
defined('APPLICATION_PATH') || define('APPLICATION_PATH', realpath(dirname(__FILE__) . '/../application'));

// Define application environment
defined('APPLICATION_ENV') || define('APPLICATION_ENV', (getenv('APPLICATION_ENV') ? getenv('APPLICATION_ENV') : 'production'));

// Define path to Your project
defined('PROJECT_PATH') || define('PROJECT_PATH', realpath(dirname(__FILE__)));

if (!file_exists(PROJECT_PATH . '/vendor/autoload.php') ) {
    throw new \RuntimeException("Composer packages is not installed. First install composer by https://getcomposer.org/download and run command: composer install -o");
}

/* Create composer loader */
return include(PROJECT_PATH . '/vendor/autoload.php');