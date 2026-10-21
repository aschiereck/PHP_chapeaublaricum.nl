<?php

use Zend\ServiceManager\ServiceManager;

ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

date_default_timezone_set('Europe/Amsterdam');

// Load configuration
$config = require 'config.php';
$appConfig = new \App\Config\AppConfig($config);

// Build container
$container = new ServiceManager($config['dependencies']);
$container->setService(\App\Config\AppConfig::class, $appConfig);

// Inject config
$container->setService('config', $config);

\LosMiddleware\LosLog\ErrorLogger::registerHandlers();
\LosMiddleware\LosLog\ExceptionLogger::registerHandlers();

return $container;
