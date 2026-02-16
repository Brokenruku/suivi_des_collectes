<?php

use app\controllers\LoginController;
use app\controllers\AccueilController;
use app\middlewares\SecurityHeadersMiddleware;

$router = Flight::router();
$base = Flight::request()->base;

$router->group('', function () use ($base) {

    Flight::route('GET /', function () use ($base) {
        Flight::redirect($base . '/login');
    });

    Flight::route('GET /login', [LoginController::class, 'showLogin']);

    Flight::route('POST /login', [LoginController::class, 'login']);

    Flight::route('GET /accueil', [AccueilController::class, 'index']);

    Flight::route('GET /logout', function () use ($base) {
        session_destroy();
        Flight::redirect($base . '/login');
    });

}, [SecurityHeadersMiddleware::class]);
