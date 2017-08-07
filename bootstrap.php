<?php

/**
 * The bootstrap file creates and returns the container.
 */
use DI\ContainerBuilder;

require BASE . '/vendor/autoload.php';

$containerBuilder = new ContainerBuilder;
$containerBuilder->addDefinitions(CONFIG . '/container.php');
$container = $containerBuilder->build();

/**
 * The Dotenv Component parses .env files to make environment
 * variables stored in them accessible via getenv()
 */
$container->get(\Symfony\Component\Dotenv\Dotenv::class)->load(BASE.'/.env');

return $container;