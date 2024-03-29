<?php
error_reporting(E_ALL ^ E_NOTICE);
ini_set('display_errors', 1);

/**
 * This makes our life easier when dealing with paths. Everything is relative
 * to the application root now.
 */
chdir(dirname(__DIR__));

// Setup autoloading
require 'init_autoloader.php';

ini_set('display_errors', true);

// Run the application!
Zend\Mvc\Application::init(require 'config/application.config.php')->run();
