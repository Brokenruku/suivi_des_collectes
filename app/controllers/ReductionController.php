<?php
namespace app\controllers;

use Flight;
use app\models\ReductionModel;

class ReductionController
{
    public static function ChangeReduction()
    {
        if (empty($_SESSION['user']['id'])) {
            Flight::redirect('/accueil');
            return;
        }

        $pdo = Flight::db();
        $model = new ReductionModel($pdo);

        $newReduction = $_POST['reduction'] ?? null;
        if ($newReduction === null || !is_numeric($newReduction) || $newReduction < 0 || $newReduction > 100) {
            Flight::halt(400, 'Doit être un nombre entre 0 et 100.');
            return;
        }

        $model->setReductionPourcentage((float)$newReduction);

        Flight::redirect('/vendre');
    }
}