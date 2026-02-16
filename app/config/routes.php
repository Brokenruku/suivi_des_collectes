<?php

use app\controllers\LoginController;
use app\controllers\AccueilController;
use app\controllers\DonController;

Flight::route('GET /', function () {
    Flight::redirect('/login');
});

Flight::route('GET /login', [LoginController::class, 'showLogin']);
Flight::route('POST /login', [LoginController::class, 'login']);

Flight::route('GET /don', [DonController::class, 'form']);
Flight::route('POST /don', [DonController::class, 'submit']);

Flight::route('GET /accueil', [AccueilController::class, 'index']);

Flight::route('GET /logout', function () {
    session_unset();
    session_destroy();
    Flight::redirect('/login');
});
