<?php

use app\middlewares\SecurityHeadersMiddleware;
use app\controllers\ObjectController;
use flight\Engine;
use flight\net\Router;

/** 
 * @var Router $router 
 * @var Engine $app
 */

$router->group('', function (Router $router) {

  $router->get('/', function () {
    Flight::redirect('/login');
  });

  $router->get('/login', function () {
    Flight::render('login');
  });

}, [SecurityHeadersMiddleware::class]);
