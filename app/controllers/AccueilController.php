<?php

namespace app\controllers;

use Flight;
use app\models\VilleModel;

class AccueilController
{
    public static function index()
    {
        if (!isset($_SESSION['user'])) {
            Flight::redirect('/login');
            return;
        }

        $pdo = Flight::db();
        $villes = VilleModel::getVilleAvecBesoin($pdo);

        Flight::render('accueil', [
            'villes' => $villes
        ]);
    }
}
