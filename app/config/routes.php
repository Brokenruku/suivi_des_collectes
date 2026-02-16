<?php

use app\controllers\LoginController;
use app\controllers\AccueilController;
use app\controllers\DonController;
use app\controllers\AchatController;
use app\controllers\RecapController;

Flight::route('GET /recap', [RecapController::class, 'index']);
Flight::route('GET /recap/data', [RecapController::class, 'data']);

Flight::route('GET /achats', [AchatController::class, 'index']);
Flight::route('POST /achats', [AchatController::class, 'create']);
Flight::route('GET /achats/@id:[0-9]+', [AchatController::class, 'show']);

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
