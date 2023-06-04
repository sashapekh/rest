<?php

use Sashapekh\SimpleRest\Controllers\AuthController;
use Sashapekh\SimpleRest\Controllers\IndexController;
use Sashapekh\SimpleRest\Controllers\UserController;
use Sashapekh\SimpleRest\Core\Router\Router;
use Sashapekh\SimpleRest\Middlewares\AuthUserMiddleware;

Router::get('/', [IndexController::class, 'index']);
Router::get('/users', [UserController::class, 'all']);
Router::get('/users/{id}', [UserController::class, 'findById'], [AuthUserMiddleware::class]);
Router::post('/login', [AuthController::class, 'login']);
