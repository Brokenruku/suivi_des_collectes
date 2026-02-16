<?php

use flight\Engine;
use flight\debug\tracy\TracyExtensionLoader;
use Tracy\Debugger;

Debugger::enable();
Debugger::$logDirectory = __DIR__ . $ds . '..' . $ds . 'log';
Debugger::$strictMode = true;

if (Debugger::$showBar === true && php_sapi_name() !== 'cli') {
  (new TracyExtensionLoader($app));
}

if (session_status() === PHP_SESSION_NONE) {
  session_start();
}

$app->map('db', function () {

  $host = 'localhost';
  $port = '3306';
  $dbname = '4064_4078_4107';
  $user = 'root';
  $pass = '';
  
  $dsn = "mysql:host=$host;port=$port;dbname=$dbname;charset=utf8mb4";
  $pdo = new PDO($dsn, $user, $pass);

  $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
  $pdo->setAttribute(PDO::ATTR_DEFAULT_FETCH_MODE, PDO::FETCH_ASSOC);

  return $pdo;
});




