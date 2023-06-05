<?php

use Sashapekh\SimpleRest\Controllers\AuthController;
use Sashapekh\SimpleRest\Controllers\IndexController;
use Sashapekh\SimpleRest\Controllers\UserController;
use Sashapekh\SimpleRest\Core\Route\Route;
use Sashapekh\SimpleRest\Middlewares\AuthUserMiddleware;

Route::get('/', [IndexController::class, 'index']);
Route::get('/users', [UserController::class, 'all']);
Route::get('/users/{id}', [UserController::class, 'findById'], [AuthUserMiddleware::class]);
Route::post('/login', [AuthController::class, 'login']);
