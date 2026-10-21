<?php

define('AREA', 'frontend');

// Delegate static file requests back to the PHP built-in webserver
if (php_sapi_name() === 'cli-server'
    && is_file(__DIR__ . parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH))
) {
    return false;
}

chdir(dirname(__DIR__));
require 'vendor/autoload.php';

/**
 * Self-called anonymous function that creates its own scope and keep the global namespace clean.
 */
call_user_func(function () {
    /** @var \Interop\Container\ContainerInterface $container */
    $container = require 'config/container.php';

    /** @var \Zend\Expressive\Application $app */
    $app = $container->get(\Zend\Expressive\Application::class);

    //Pipeline
    $app->pipe(\Zend\Stratigility\Middleware\ErrorHandler::class);
    $app->pipe(\Zend\Expressive\Helper\ServerUrlMiddleware::class);

    $app->pipeRoutingMiddleware();

    $app->pipe(\Zend\Expressive\Middleware\ImplicitHeadMiddleware::class);
    $app->pipe(\Zend\Expressive\Middleware\ImplicitOptionsMiddleware::class);
    $app->pipe(\Zend\Expressive\Helper\UrlHelperMiddleware::class);

    $app->pipe(\App\Session\StartSessionMiddleware::class);
    $app->pipe(\App\Middleware\Frontend\SiteMiddleware::class);
    $app->pipe(\App\Middleware\Frontend\PrepareLayoutMiddleware::class);
    $app->pipe(\App\Middleware\Frontend\FormValidatorMiddleWare::class);

    $app->pipeDispatchMiddleware();
    $app->pipe(\Zend\Expressive\Middleware\NotFoundHandler::class);

    // Import routing from config
    require 'config/routes.php';

    $app->run();
});