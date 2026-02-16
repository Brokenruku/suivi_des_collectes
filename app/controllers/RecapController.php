<?php
namespace app\controllers;

use Flight;
use app\models\RecapModel;

class RecapController
{
    public static function index()
    {
        if (empty($_SESSION['user']['id'])) {
            Flight::redirect('/login');
            return;
        }

        Flight::render('recap');
    }

    public static function data()
    {
        if (empty($_SESSION['user']['id'])) {
            Flight::json(['error' => 'Unauthorized'], 401);
            return;
        }

        $pdo = Flight::db();
        $model = new RecapModel($pdo);

        Flight::json($model->getStats());
    }
}
