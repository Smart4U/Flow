<?php

namespace Flow\Core\Routing;


use function FastRoute\cachedDispatcher;

use RuntimeException;
use InvalidArgumentException;
use FastRoute\Dispatcher;
use FastRoute\RouteCollector;
use Psr\Container\ContainerInterface;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;

/**
 * Class Router
 * @package Flow\Core
 */
class Router
{

    /**
     * @var ContainerInterface $container
     */
    protected $container;


    /**
     * @var callable $dispatcher
     */
    protected $dispatcher;


    /**
     * @var string/bool $cacheFile
     */
    protected $cacheFile = false;


    /**
     * Router constructor.
     * @param ContainerInterface $container
     */
    public function __construct(ContainerInterface $container)
    {
        $this->container = $container;
        $routes = $container->get('routes');

        $this->setDispatcher(
            $dispatcher = cachedDispatcher(function(RouteCollector $r) use($routes) {
                foreach ($routes as $path => [$method, $action]){
                    $r->addRoute($method, $path, $action);
                }
            }, [
                'cacheFile' => $this->container->get('config')['route.cacheFile'],
                'cacheDisabled' => $this->container->get('config')['route.cacheDisable'],
            ])
        );
    }


    /**
     * @param ServerRequestInterface $request
     * @return mixed
     */
    public function dispatch(ServerRequestInterface $request)
    {
        $uri = '/' . ltrim($request->getUri()->getPath(), '/');
        return $this->createDispatcher()->dispatch( $request->getMethod(), $uri );
    }


    /**
     * @return callable
     */
    protected function createDispatcher()
    {
        return $this->dispatcher;
    }


    /**
     * @param Dispatcher $dispatcher
     */
    public function setDispatcher(Dispatcher $dispatcher)
    {
        $this->dispatcher = $dispatcher;
    }


    /**
     * @param $cacheFile
     * @return $this
     */
    public function setCacheFile($cacheFile)
    {
        if (!is_string($cacheFile) && $cacheFile !== false) {
            throw new InvalidArgumentException('Router cacheFile must be a string or false');
        }

        $this->cacheFile = $cacheFile;

        if ($cacheFile !== false && !is_writable(dirname($cacheFile))) {
            throw new RuntimeException('Router cacheFile directory must be writable');
        }

        return $this;
    }


    /**
     * @param ServerRequestInterface $request
     * @param ResponseInterface $response
     * @return ResponseInterface
     */
    public function getResponse(ServerRequestInterface $request, ResponseInterface $response) :ResponseInterface{
        $routeInfo = $this->dispatch($request);
        switch ($routeInfo[0]) {
            case Dispatcher::NOT_FOUND:
                $response = $response->withStatus(404);
                $response->getBody()->write('Not Found :(');
                break;
            case Dispatcher::METHOD_NOT_ALLOWED:
                $response = $response->withStatus(405);
                $response->withHeader('Access-Control-Allow-Methods', trim(implode(', ', $routeInfo[1]), ','));
                $response->getBody()->write('Method Not Allowed.');
                break;
            case Dispatcher::FOUND:
                [$controller, $action] = explode('@', $routeInfo[1]);
                $response = $response->withStatus(200);
                $body = $this->container->get($controller)->$action($routeInfo[2]);
                $response->getBody()->write($body);
                break;
        }

        return $response;
    }

}
