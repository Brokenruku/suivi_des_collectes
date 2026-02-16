<?php

use app\controllers\LoginController;
use app\controllers\AccueilController;

Flight::route('GET /', function () {
    Flight::redirect('/login');
});

Flight::route('GET /login', [LoginController::class, 'showLogin']);
Flight::route('POST /login', [LoginController::class, 'login']);

Flight::route('GET /accueil', [AccueilController::class, 'index']);

