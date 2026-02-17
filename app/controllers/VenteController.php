<?php
namespace app\controllers;

use Flight;
use app\models\VenteModel;
use Throwable;

class VenteController
{
    public static function form()
    {
        if (empty($_SESSION['user']['id'])) { Flight::redirect('/login'); return; }

        $pdo = Flight::db();
        $m = new VenteModel($pdo);

        Flight::render('vendre', [
            'error' => null,
            'success' => null,
            'natures' => $m->getObjetsNature(),
            'mats' => $m->getObjetsMateriaux(),
            'reduction' => $m->getReductionPourcentage(),
        ]);
    }

    public static function store()
    {
        if (empty($_SESSION['user']['id'])) { Flight::redirect('/login'); return; }

        $pdo = Flight::db();
        $m = new VenteModel($pdo);

        $idUser = (int)$_SESSION['user']['id'];

        $natureIds = $_POST['nature_id'] ?? [];
        $natureQtes = $_POST['nature_qte'] ?? [];
        $matIds = $_POST['mat_id'] ?? [];
        $matQtes = $_POST['mat_qte'] ?? [];

        try {
            $m->vendreMixte($idUser, $natureIds, $natureQtes, $matIds, $matQtes);

            Flight::render('vendre', [
                'error' => null,
                'success' => "Vente enregistree. L'argent a ete ajoute aux dons en argent.",
                'natures' => $m->getObjetsNature(),
                'mats' => $m->getObjetsMateriaux(),
                'reduction' => $m->getReductionPourcentage(),
            ]);
        } catch (Throwable $e) {
            Flight::render('vendre', [
                'error' => $e->getMessage(),
                'success' => null,
                'natures' => $m->getObjetsNature(),
                'mats' => $m->getObjetsMateriaux(),
                'reduction' => $m->getReductionPourcentage(),
            ]);
        }
    }
}
