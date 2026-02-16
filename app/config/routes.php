<?php

use app\middlewares\SecurityHeadersMiddleware;
use app\controllers\ObjectController;
use flight\Engine;
use flight\net\Router;
use app\controllers\AccueilController;


/** 
 * @var Router $router 
 * @var Engine $app
 */

$base = \Flight::get('flight.base_url');

$router->group('', function (Router $router) use ($base) {

  Flight::route('GET /accueil', [AccueilController::class, 'index']);

  Flight::route('GET /', function () use ($base) {
    Flight::redirect($base . '/login');
  });

  Flight::route('GET /login', function () {
    Flight::render('login');
  });
}, [SecurityHeadersMiddleware::class]);
