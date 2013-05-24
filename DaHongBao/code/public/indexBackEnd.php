<?php
/**
 * This makes our life easier when dealing with paths. Everything is relative
 * to the application root now.
 */
chdir(dirname(__DIR__));

define('APPLICATION_MODULE_PATH', dirname(__DIR__) . '/module/BackEnd/');
define('APPLICATION_PATH', dirname(__DIR__));

// Setup autoloading
require 'config/init_autoloader.php';

// set global config
$globalConfig = require 'config/application.config.php';
$globalConfig['modules'] = array('BackEnd');

// auto create write dir
$writeDir = '/mezi/sites/apache/writable/'. $_SERVER['SERVER_NAME'] . '/files/';
if (!file_exists($writeDir)) {
    mkdir($writeDir, 0777, true);
}

// Run the application!
Zend\Mvc\Application::init($globalConfig)->run();
