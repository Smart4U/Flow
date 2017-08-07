<?php

/**
 * Contains the required dependencies of the app.
 */
use function DI\get;
use function DI\object;

return [

    // Dotfiles
    Symfony\Component\Dotenv\Dotenv::class => object(),

];