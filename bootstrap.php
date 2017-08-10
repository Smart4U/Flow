<?php


require BASE . '/vendor/autoload.php';

/**
 * The Dotenv Component parses .env files to make environment
 * variables stored in them accessible via getenv()
 */
$env = new \Symfony\Component\Dotenv\Dotenv();
$env->load(BASE.'/.env');