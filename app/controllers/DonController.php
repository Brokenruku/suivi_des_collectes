<?php

namespace app\controllers;

use Flight;
use app\models\DonModel;
use Throwable;
use Exception;

class DonController
{
    public static function form()
    {
        $pdo = Flight::db();

        $natures = $pdo->query("SELECT id, nom FROM objet_nature ORDER BY nom")->fetchAll();
        $mats    = $pdo->query("SELECT id, nom FROM objet_materiaux ORDER BY nom")->fetchAll();

        Flight::render('faireDon', [
            'natures' => $natures,
            'mats' => $mats,
            'error' => null
        ]);
    }

    public static function submit()
    {
        if (empty($_SESSION['user']['id'])) {
            Flight::redirect('/login');
            return;
        }

        $pdo = Flight::db();
        $donModel = new \app\models\DonModel($pdo);

        $idUser = (int)$_SESSION['user']['id'];

        $montant = (float)($_POST['montant'] ?? 0);

        $natureIds = $_POST['nature_id'] ?? [];
        $natureQtes = $_POST['nature_qte'] ?? [];

        $matIds = $_POST['mat_id'] ?? [];
        $matQtes = $_POST['mat_qte'] ?? [];

        try {
            $res = $donModel->donnerMixte($idUser, $montant, $natureIds, $natureQtes, $matIds, $matQtes);

            $natures = $pdo->query("SELECT id, nom FROM objet_nature ORDER BY nom")->fetchAll();
            $mats    = $pdo->query("SELECT id, nom FROM objet_materiaux ORDER BY nom")->fetchAll();

            Flight::render('faireDon', [
                'natures' => $natures,
                'mats' => $mats,
                'error' => null,
                'success' => 'Don enregistré avec succès.',
                'warnings' => $res['warnings'] ?? []
            ]);
        } catch (\Throwable $e) {
            $natures = $pdo->query("SELECT id, nom FROM objet_nature ORDER BY nom")->fetchAll();
            $mats    = $pdo->query("SELECT id, nom FROM objet_materiaux ORDER BY nom")->fetchAll();

            Flight::render('faireDon', [
                'natures' => $natures,
                'mats' => $mats,
                'error' => $e->getMessage(),
                'success' => null,
                'warnings' => []
            ]);
        }
    }
}
