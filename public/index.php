<?php declare(strict_types=1);

use function Http\Response\send;


/**
 * Front controller
 *
 * PHP version 7.1
 */


/**
 *  constants
 */
define('BASE',     dirname(__DIR__).'/');
define('CONFIG',   BASE.'config/');
define('STORAGE',  BASE.'storage/');

/**
 * Bootstrap file to load the composer autoloader
 */
require BASE.'bootstrap.php';

$request = $container->get('Request');
$response = $container->get('Response');

$dispatcher = FastRoute\simpleDispatcher(function(FastRoute\RouteCollector $r) {
    $r->get('/', 'PagesController@homepage');
});


$httpMethod = $request->getMethod();
$uri = rawurldecode($request->getUri()->getPath());

$routeInfo = $dispatcher->dispatch($httpMethod, $uri);
switch ($routeInfo[0]) {
    case FastRoute\Dispatcher::NOT_FOUND:
        $response->withStatus(404);
        $response->getBody()->write('Not Found :(');
        break;
    case FastRoute\Dispatcher::METHOD_NOT_ALLOWED:
        $response->withStatus(405);
        $response->withHeader('Access-Control-Allow-Methods', trim(implode(', ', $routeInfo[1]), ','));
        $response->getBody()->write('Method Not Allowed ^^');
        break;
    case FastRoute\Dispatcher::FOUND:
        [$controller, $action] = explode('@', $routeInfo[1]);
        // Provisional body (soon based on: BaseController)
        $body = '<!DOCTYPE html><html><head></head><body><h1>homepage</h1></body></html>';
        // $body = (new $controller($container))->$action($routeInfo[2]);
        $response->getBody()->write($body);
        break;
}

send($response);

?>