<?php


require BASE . '/vendor/autoload.php';

/**
 * whoops is an error handler framework for PHP
 */
$whoops = new \Whoops\Run;
$whoops->pushHandler(new \Whoops\Handler\PrettyPageHandler);
$whoops->register();


/**
 * The Dotenv Component parses .env files to make environment
 * variables stored in them accessible via getenv()
 */
$env = new \Symfony\Component\Dotenv\Dotenv();
$env->load(BASE.'/.env');
