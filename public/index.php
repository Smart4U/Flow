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

$config = require CONFIG . '/container.php';
$bundles =  require CONFIG . '/bundles.php';

$app = new \Flow\Core\App($config);
$app->bindBundle($bundles);

$response = $app->run();

send($response);
?>