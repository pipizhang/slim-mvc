<?php
/**
 * System setting
 */

define('APP_DIR',  dirname(__DIR__));
define('ROOT_DIR', dirname(APP_DIR));
require_once ROOT_DIR.'/vendor/autoload.php';

/* autoload */
set_include_path(get_include_path().PATH_SEPARATOR.
    APP_DIR.'/controllers'.PATH_SEPARATOR.
    APP_DIR.'/models'.PATH_SEPARATOR.
    APP_DIR.'/helpers'.PATH_SEPARATOR.
    APP_DIR.'/services'
);
spl_autoload_register(function ($className) {
    $className = (string) str_replace('\\', DIRECTORY_SEPARATOR, $className);
    require_once($className . '.php');
});

/* configuration */
Util::config(include __DIR__.'/define.php');
