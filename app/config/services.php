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

// ---- PDO PostgreSQL connection registered into Flight as "db" ----
$app->map('db', function () {

  $host = 'localhost';
  $port = '5432';
  $dbname = 'final';
  $user = 'postgres';
  $pass = '';

  $dsn = "pgsql:host=$host;port=$port;dbname=$dbname;";

  $pdo = new PDO($dsn, $user, $pass);

  $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
  $pdo->setAttribute(PDO::ATTR_DEFAULT_FETCH_MODE, PDO::FETCH_ASSOC);

  return $pdo;
});

