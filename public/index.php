<?php

use App\Components\Router;

define('ROOT', __DIR__ . '/..');
define('UPLOAD_PATH', ROOT . '/public/upload');

require_once ROOT . '/vendor/autoload.php';

$env = include_once ROOT . '/config/.env.php';
$routes = include_once ROOT . '/config/routes.php';

error_reporting(E_ALL);

$env['debug'] ? ini_set('display_errors', 1) : ini_set('display_errors', 0);

if (!isset($_SESSION)) {
    session_start();
}

date_default_timezone_set($env['timezone']);

$router = new Router($routes);
$router->run();
