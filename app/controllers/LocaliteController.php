<?php
namespace app\controllers;

use Flight;
use app\models\LocaliteModel;
use Throwable;
use Exception;

class LocaliteController
{
    public static function index()
    {
        if (empty($_SESSION['user']['id'])) {
            Flight::redirect('/login');
            return;
        }

        $pdo = Flight::db();
        $model = new LocaliteModel($pdo);

        $regions = $model->getRegions();

        Flight::render('localites', [
            'regions' => $regions,
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
        $model = new LocaliteModel($pdo);

        $regionNom = trim($_POST['region_nom'] ?? '');
        $villeNom  = trim($_POST['ville_nom'] ?? '');
        $regionId  = (int)($_POST['region_id'] ?? 0);

        try {
            if ($regionNom === '' && $villeNom === '') {
                throw new Exception("Veuillez saisir au moins une region ou une ville.");
            }

            $newRegionId = 0;
            if ($regionNom !== '') {
                $newRegionId = $model->createRegionIfNotExists($regionNom);
            }

            if ($villeNom !== '') {
                $finalRegionId = $newRegionId > 0 ? $newRegionId : $regionId;
                if ($finalRegionId <= 0) {
                    throw new Exception("Pour creer une ville, selectionnez une region ou creez-en une.");
                }
                $model->createVille($villeNom, $finalRegionId);
            }

            $regions = $model->getRegions();
            Flight::render('localites', [
                'regions' => $regions,
                'error' => null,
                'success' => "Enregistrement effectue avec succès.",
            ]);
        } catch (Throwable $e) {
            $regions = $model->getRegions();
            Flight::render('localites', [
                'regions' => $regions,
                'error' => $e->getMessage(),
                'success' => null,
            ]);
        }
    }
}
