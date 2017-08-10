<?php

namespace Flow\Core;

use DI\ContainerBuilder;
use Psr\Container\ContainerInterface;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;


/**
 * Class App
 * @package Flow\Core
 */
class App {

    /**
     * @var int $selector
     */
    private $selector = -1;


    /**
     * @var \DI\Container|ContainerInterface $container
     */
    private $container;


    /**
     * @var array $middlewares
     */
    private $middlewares = [];


    /**
     * App constructor creates the container.
     * @param array $container
     */
    public function __construct(array $container = [])
    {
        $containerBuilder = new ContainerBuilder();
        $containerBuilder->addDefinitions($container);
        $container = $containerBuilder->build();

        if (!$container instanceof ContainerInterface) {
            throw new \InvalidArgumentException('Expected a ContainerInterface');
        }
        $this->container = $container;
    }


    /**
     * @param callable $middleware
     */
    public function bindMiddleware(callable $middleware) :void {
        $this->middlewares[] = $middleware;
    }


    /**
     * @return callable|null
     */
    private function getMiddleware() :?callable {
        $this->selector++;
        if(isset($this->middlewares[$this->selector])){
            return $this->middlewares[$this->selector];
        }
        return null;
    }


    /**
     * @param ServerRequestInterface $request
     * @param ResponseInterface $response
     * @return ResponseInterface
     */
    public function callMiddleware(ServerRequestInterface $request, ResponseInterface $response) :ResponseInterface {
        $middleware = $this->getMiddleware();
        if( !is_null($middleware)){
            return $middleware($request, $response, [$this, 'callMiddleware']);
        }
        return $response;
    }


    /**
     * @return ResponseInterface
     */
    public function run() :ResponseInterface {
        $response = $this->container->get('response');
        $request = $this->container->get('request');
        $router = $this->container->get('router');

        try {
            $response = $this->callMiddleware($request, $response);
            $response = $router->getResponse($request, $response);
        } catch (\InvalidArgumentException $e) {
            $response = $response->getBody()->write($e->getMessage());
        }

        return $response;

    }

}