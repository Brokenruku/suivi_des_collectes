<?php

use app\middlewares\SecurityHeadersMiddleware;
use app\controllers\ObjectController;
use flight\Engine;
use flight\net\Router;

/** 
 * @var Router $router 
 * @var Engine $app
 */

$base = Flight::request()->base;

$router->group('', function (Router $router) use ($base) {

  Flight::route('GET /', function () use ($base) {
    Flight::redirect($base . '/login');
  });

  Flight::route('GET /login', function () {
    Flight::render('login'); 
  });
}, [SecurityHeadersMiddleware::class]);
