<?php

/**
 * Contains the required dependencies of the app.
 */
use function DI\get;
use function DI\object;

return [

    'settings.route.cacheFile' => STORAGE.'cache/routing/app_routes.cache',
    'settings.route.cacheDisable' => true,

    'routes' => require  CONFIG.'routes.php',
    'config' => require CONFIG.'config.php',

    // App
    Flow\Core\App::class => object(),
    'app' => get(Flow\Core\App::class),

    // Dotfiles
    Symfony\Component\Dotenv\Dotenv::class => object(),


    // Request
    GuzzleHttp\Psr7\ServerRequest::class => object(),
    \Psr\Http\Message\ServerRequestInterface::class => get(GuzzleHttp\Psr7\ServerRequest::class),
    'request' => function () {
        return GuzzleHttp\Psr7\ServerRequest::fromGlobals();
    },

    // Response
    GuzzleHttp\Psr7\Response::class => object(),
    Psr\Http\Message\ResponseInterface::class => get(GuzzleHttp\Psr7\Response::class),
    'response' => function () {
        return new \GuzzleHttp\Psr7\Response();
    },

    // Router
    Flow\Core\Routing\Router::class => function(\Psr\Container\ContainerInterface $c) {
        return new \Flow\Core\Routing\Router($c);
    },
    'router' => get(Flow\Core\Routing\Router::class),

];