<?php

/**
 * The bootstrap file creates and returns the container.
 */
use DI\ContainerBuilder;

require BASE . '/vendor/autoload.php';

$containerBuilder = new ContainerBuilder;
$containerBuilder->addDefinitions(CONFIG . '/container.php');
$container = $containerBuilder->build();
return $container;