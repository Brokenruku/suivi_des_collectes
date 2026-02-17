<?php
namespace app\controllers;

use Flight;
use app\models\BesoinModel;
use Throwable;

class BesoinController
{
    public static function form()
    {
        if (empty($_SESSION['user']['id'])) {
            Flight::redirect('/login');
            return;
        }

        $pdo = Flight::db();
        $model = new BesoinModel($pdo);

        Flight::render('besoins_form', [
            'villes' => $model->getVilles(),
            'natures' => $model->getObjetsNature(),
            'mats' => $model->getObjetsMateriaux(),
            'error' => null,
            'success' => null,
        ]);
    }

    public static function store()
    {
        if (empty($_SESSION['user']['id'])) {
            Flight::redirect('/login');
            return;
        }

        $pdo = Flight::db();
        $model = new BesoinModel($pdo);

        $idVille = (int)($_POST['id_ville'] ?? 0);

        $argent = (float)($_POST['montant'] ?? 0);

        $natureIds = $_POST['nature_id'] ?? [];
        $natureQtes = $_POST['nature_qte'] ?? [];

        $matIds = $_POST['mat_id'] ?? [];
        $matQtes = $_POST['mat_qte'] ?? [];

        try {
            $model->ajouterBesoins($idVille, $argent, $natureIds, $natureQtes, $matIds, $matQtes);

            Flight::render('besoins_form', [
                'villes' => $model->getVilles(),
                'natures' => $model->getObjetsNature(),
                'mats' => $model->getObjetsMateriaux(),
                'error' => null,
                'success' => "Besoins ajoutés avec succès.",
            ]);
        } catch (Throwable $e) {
            Flight::render('besoins_form', [
                'villes' => $model->getVilles(),
                'natures' => $model->getObjetsNature(),
                'mats' => $model->getObjetsMateriaux(),
                'error' => $e->getMessage(),
                'success' => null,
            ]);
        }
    }
}
