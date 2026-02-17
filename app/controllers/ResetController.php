<?php

namespace app\controllers;

use Flight;

class ResetController
{
    public static function reset()
    {
        if (empty($_SESSION['user'])) {
            Flight::redirect('/login');
            return;
        }

        $pdo = Flight::db();

        $root = dirname(__DIR__, 2);

        $candidates = [
            $root . '/insertion.sql',
            $root . '/app/database/insertion.sql',
            $root . '/app/sql/insertion.sql',
        ];

        $sqlFile = null;
        foreach ($candidates as $p) {
            if (file_exists($p)) {
                $sqlFile = $p;
                break;
            }
        }

        if ($sqlFile === null) {
            Flight::halt(500, 'insertion.sql introuvable. Mets-le dans: ' . $root . '/insertion.sql');
            return;
        }

        $sql = file_get_contents($sqlFile);

        try {
            $pdo->exec('SET FOREIGN_KEY_CHECKS=0');
            
            $pdo->exec('TRUNCATE TABLE vente_lignes');
            $pdo->exec('TRUNCATE TABLE ventes');

            $pdo->exec('TRUNCATE TABLE achat_lignes');
            $pdo->exec('TRUNCATE TABLE achats');

            $pdo->exec('TRUNCATE TABLE dons_argent');
            $pdo->exec('TRUNCATE TABLE dons_materiaux');
            $pdo->exec('TRUNCATE TABLE dons_nature');
            $pdo->exec('TRUNCATE TABLE dons');

            $pdo->exec('TRUNCATE TABLE besoin_argent');
            $pdo->exec('TRUNCATE TABLE besoin_materiaux');
            $pdo->exec('TRUNCATE TABLE besoin_nature');

            $pdo->exec('TRUNCATE TABLE objet_materiaux');
            $pdo->exec('TRUNCATE TABLE objet_nature');

            $pdo->exec('TRUNCATE TABLE ville');
            $pdo->exec('TRUNCATE TABLE region');

            $pdo->exec('TRUNCATE TABLE reduction_vente');

            $pdo->exec('SET FOREIGN_KEY_CHECKS=1');

            $parts = preg_split('/;\s*[\r\n]+/', $sql);
            foreach ($parts as $p) {
                $p = trim($p);
                if ($p !== '') {
                    $pdo->exec($p);
                }
            }

            Flight::redirect('/accueil');
        } catch (\Throwable $e) {
            try { $pdo->exec('SET FOREIGN_KEY_CHECKS=1'); } catch (\Throwable $x) {}
            Flight::halt(500, $e->getMessage());
        }
    }
}
