<?php

use App\Controllers\AdminController;
use App\Controllers\MainController;
use App\Controllers\UserController;

return [
    'admin' => [AdminController::class, 'index', true],

    'login' => [UserController::class, 'login', false],
    'logout' => [UserController::class, 'logout', true],

    'profile/info' => [UserController::class, 'info', true],
    'profile/security' => [UserController::class, 'security', true],

    'index' => [MainController::class, 'index', false],
    '' => [MainController::class, 'index', false]
];
