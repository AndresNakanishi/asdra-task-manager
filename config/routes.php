<?php
use Cake\Http\Middleware\CsrfProtectionMiddleware;
use Cake\Routing\RouteBuilder;
use Cake\Routing\Router;
use Cake\Routing\Route\DashedRoute;

Router::defaultRouteClass(DashedRoute::class);

Router::scope('/', function (RouteBuilder $routes) {
    // Register Middleware

    $routes->registerMiddleware('csrf', new CsrfProtectionMiddleware([
        'httpOnly' => true
    ]));

    // Use Middleware

    $routes->applyMiddleware('csrf');

    // Routes

    $routes->connect('/', ['controller' => 'Users', 'action' => 'login']);
    $routes->connect('/dashboard', ['controller' => 'Users', 'action' => 'dashboard']);

    $routes->fallbacks(DashedRoute::class);
});
