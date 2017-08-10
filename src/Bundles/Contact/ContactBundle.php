<?php

namespace Flow\Bundles\Contact;

use Flow\Core\Bundle\BaseBundle;

class ContactBundle extends BaseBundle {

    public $routes;

    public function __construct()
    {
        $routesFile = __DIR__ . '/_routes.php';
        if( !file_exists($routesFile) && !is_writable(dirname($routesFile))){
            throw new \AssertionError('Bundle error ('.get_class($this).') cannot load routing in this path : ('.$routesFile.').');
        }
        $routes = require $routesFile;
        foreach ( $routes as $path => [$method, $action]) {
            $this->routes[$path] = [$method, __NAMESPACE__ . '\\Controllers\\' . $action];
        }
    }

}