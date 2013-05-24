<?php
/**
 * This makes our life easier when dealing with paths. Everything is relative
 * to the application root now.
 */
chdir(dirname(__DIR__));

// Setup autoloading
require 'config/init_autoloader.php';
require 'library/Track/scripts/incoming.php';
// set global config
$globalConfig = require 'config/application.config.php';
$globalConfig['modules'] = array('US');

// Run the application!
Zend\Mvc\Application::init($globalConfig)->run();