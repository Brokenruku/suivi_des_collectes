<?php
namespace app\controllers;

use Flight;
use app\models\VilleModel;

class AccueilController
{
  public static function index()
  {
    $pdo = Flight::db();
    $villes = VilleModel::getVilleAvecBesoin($pdo);

    Flight::render('accueil', [
      'villes' => $villes
    ]);
  }
}
