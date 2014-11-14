<?php
/**
 * Configuration
 */

return array(
    'slim' => array(
        'templates.path' => APP_DIR.'/views',
        'view' => new \Slim\Views\Twig()
    ),
    'twig' => array(
        'auto_reload' => true,
        'cache' => APP_DIR.'/storage/view'
    ),
    'app' => array(
        'cache.path' => APP_DIR.'/storage/cache',
        'cache.time' => 3600,
        'api.site' => 'https://pp.sfanytime.com',
        'cookie.file' => APP_DIR.'/storage/cookie/cookie',
    )
);
