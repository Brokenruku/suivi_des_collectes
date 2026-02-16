<?php
namespace app\controllers;

use Flight;
use app\models\AchatModel;
use Throwable;

class AchatController
{
    public static function index()
    {
        if (empty($_SESSION['user']['id'])) {
            Flight::redirect('/login');
            return;
        }

        $pdo = Flight::db();
        $model = new AchatModel($pdo);

        $villeId = (int)($_GET['ville'] ?? 0);

        $villes = $model->getVilles();
        $natureRestants = $villeId > 0 ? $model->restantsNatureParVille($villeId) : [];
        $matRestants = $villeId > 0 ? $model->restantsMatParVille($villeId) : [];

        $achats = $model->listAchats($villeId);
        $argentRestant = $model->argentRestantGlobal();

        Flight::render('achats', [
            'error' => null,
            'villeId' => $villeId,
            'villes' => $villes,
            'natureRestants' => $natureRestants,
            'matRestants' => $matRestants,
            'achats' => $achats,
            'argentRestant' => $argentRestant,
        ]);
    }

    public static function create()
    {
        if (empty($_SESSION['user']['id'])) {
            Flight::redirect('/login');
            return;
        }

        $pdo = Flight::db();
        $model = new AchatModel($pdo);

        $idUser = (int)$_SESSION['user']['id'];

        $idVille = (int)($_POST['id_ville'] ?? 0);

        $natureIds = $_POST['nature_id'] ?? [];
        $natureQtes = $_POST['nature_qte'] ?? [];

        $matIds = $_POST['mat_id'] ?? [];
        $matQtes = $_POST['mat_qte'] ?? [];

        try {
            $model->creerAchat($idUser, $idVille, $natureIds, $natureQtes, $matIds, $matQtes);
            Flight::redirect('/achats?ville=' . $idVille);
        } catch (Throwable $e) {

            $villeId = $idVille;
            $villes = $model->getVilles();
            $natureRestants = $villeId > 0 ? $model->restantsNatureParVille($villeId) : [];
            $matRestants = $villeId > 0 ? $model->restantsMatParVille($villeId) : [];
            $achats = $model->listAchats($villeId);
            $argentRestant = $model->argentRestantGlobal();

            Flight::render('achats', [
                'error' => $e->getMessage(),
                'villeId' => $villeId,
                'villes' => $villes,
                'natureRestants' => $natureRestants,
                'matRestants' => $matRestants,
                'achats' => $achats,
                'argentRestant' => $argentRestant,
            ]);
        }
    }

    public static function show($id)
    {
        if (empty($_SESSION['user']['id'])) {
            Flight::redirect('/login');
            return;
        }

        $pdo = Flight::db();
        $model = new AchatModel($pdo);

        $achat = $model->getAchat((int)$id);
        $lignes = $model->getAchatLignes((int)$id);

        if (!$achat) {
            Flight::halt(404, 'Achat introuvable');
            return;
        }

        Flight::render('achat_show', [
            'achat' => $achat,
            'lignes' => $lignes
        ]);
    }
}
