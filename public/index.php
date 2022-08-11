<?php

use App\Components\Router;

define('ROOT', __DIR__ . '/..');
define('UPLOAD_PATH', ROOT . '/public/upload');

require_once ROOT . '/vendor/autoload.php';

$dotenv = Dotenv\Dotenv::createImmutable(ROOT);
$dotenv->load();

error_reporting(E_ALL);
intval($_ENV['DEBUG']) ? ini_set('display_errors', 1) : ini_set('display_errors', 0);

if (!isset($_SESSION)) {
    session_start();
}

date_default_timezone_set($_ENV['TIMEZONE']);

$routes = include_once ROOT . '/config/routes.php';
$router = new Router($routes);
$router->run();
