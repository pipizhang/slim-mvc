<?php
/**
 * SlimPHP for test
 */
define('APP_START', microtime(true));
require_once __DIR__.'/../app/config/app.php';
$app = new \Slim\Slim(Util::config('slim'));
$app->view()->parserOptions = Util::config('twig');
require __DIR__.'/../app/routes.php';
$app->run();
