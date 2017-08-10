<?php

namespace Flow\Middlewares;

use Psr\Container\ContainerInterface;

/**
 * Class BaseMidddleware
 * @package Flow\Middlewares
 */
class BaseMiddleware
{

    private $container;

    public function __construct(ContainerInterface $container)
    {
        $this->container = $container;
    }
}