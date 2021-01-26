<?php

use App\Components\Router;

ini_set('display_errors', 1);
error_reporting(E_ALL);

define('ROOT', __DIR__ . '/..');
define('UPLOAD_PATH', ROOT . '/public/upload');

if (!isset($_SESSION)) {
    session_start();
}

// date_default_timezone_set('Europe/Ulyanovsk');

require_once ROOT . '/vendor/autoload.php';

$router = new Router();
$router->run();
