<?php

/**
 * Contains the required dependencies of the app.
 */
use function DI\get;
use function DI\object;

return [

    // Dotfiles
    Symfony\Component\Dotenv\Dotenv::class => object(),

    // Request
    GuzzleHttp\Psr7\ServerRequest::class => object(),
    Psr\Http\Message\RequestInterface::class => get(GuzzleHttp\Psr7\ServerRequest::class),
    'Request' => function () {
        return GuzzleHttp\Psr7\ServerRequest::fromGlobals();
    },

    // Response
    GuzzleHttp\Psr7\Response::class => object(),
    \Psr\Http\Message\ResponseInterface::class => get(\GuzzleHttp\Psr7\Response::class),
    'Response' => function () {
        return new \GuzzleHttp\Psr7\Response();
    }

];