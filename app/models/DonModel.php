<?php

namespace app\models;

use PDO;
use Throwable;
use Exception;

class DonModel
{
    private PDO $pdo;

    public function __construct(PDO $pdo)
    {
        $this->pdo = $pdo;
    }

    public function restantArgent(): float
    {
        $sql = "
            SELECT GREATEST(
              COALESCE((SELECT SUM(vola) FROM besoin_argent), 0)
              - COALESCE((SELECT SUM(vola) FROM dons_argent), 0),
            0) AS restant
        ";
        return (float)$this->pdo->query($sql)->fetchColumn();
    }

    public function restantNature(int $idObjet): int
    {
        $sql = "
            SELECT GREATEST(
              COALESCE((SELECT SUM(qte) FROM besoin_nature WHERE id_objet_nature = :id), 0)
              - COALESCE((SELECT SUM(qte) FROM dons_nature WHERE id_objet_nature = :id), 0),
            0) AS restant
        ";
        $stmt = $this->pdo->prepare($sql);
        $stmt->execute([':id' => $idObjet]);
        return (int)$stmt->fetchColumn();
    }

    public function restantMateriaux(int $idObjet): int
    {
        $sql = "
            SELECT GREATEST(
              COALESCE((SELECT SUM(qte) FROM besoin_materiaux WHERE id_objet_materiaux = :id), 0)
              - COALESCE((SELECT SUM(qte) FROM dons_materiaux WHERE id_objet_materiaux = :id), 0),
            0) AS restant
        ";
        $stmt = $this->pdo->prepare($sql);
        $stmt->execute([':id' => $idObjet]);
        return (int)$stmt->fetchColumn();
    }

    public function creerDon(int $idUser): int
    {
        $stmt = $this->pdo->prepare("INSERT INTO dons (id_users) VALUES (:u)");
        $stmt->execute([':u' => $idUser]);
        return (int)$this->pdo->lastInsertId();
    }

    public function donnerArgent(int $idUser, float $montant): void
    {
        $reste = $this->restantArgent();
        if ($montant <= 0) throw new Exception("Montant invalide.");
        if ($montant > $reste) throw new Exception("Montant trop grand. Il ne reste que {$reste} Ar à couvrir.");

        $this->pdo->beginTransaction();
        try {
            $idDon = $this->creerDon($idUser);

            $stmt = $this->pdo->prepare("INSERT INTO dons_argent (vola, id_dons) VALUES (:v, :d)");
            $stmt->execute([':v' => $montant, ':d' => $idDon]);

            $this->pdo->commit();
        } catch (Throwable $e) {
            $this->pdo->rollBack();
            throw $e;
        }
    }

    public function donnerNature(int $idUser, int $idObjet, int $qte): void
    {
        $reste = $this->restantNature($idObjet);
        if ($qte <= 0) throw new Exception("Quantité invalide.");
        if ($qte > $reste) throw new Exception("Quantité trop grande. Il ne reste que {$reste} à couvrir pour cet objet.");

        $this->pdo->beginTransaction();
        try {
            $idDon = $this->creerDon($idUser);

            $stmt = $this->pdo->prepare("
              INSERT INTO dons_nature (id_objet_nature, id_dons, qte)
              VALUES (:o, :d, :q)
            ");
            $stmt->execute([':o' => $idObjet, ':d' => $idDon, ':q' => $qte]);

            $this->pdo->commit();
        } catch (Throwable $e) {
            $this->pdo->rollBack();
            throw $e;
        }
    }

    public function donnerMateriaux(int $idUser, int $idObjet, int $qte): void
    {
        $reste = $this->restantMateriaux($idObjet);
        if ($qte <= 0) throw new Exception("Quantité invalide.");
        if ($qte > $reste) throw new Exception("Quantité trop grande. Il ne reste que {$reste} à couvrir pour cet objet.");

        $this->pdo->beginTransaction();
        try {
            $idDon = $this->creerDon($idUser);

            $stmt = $this->pdo->prepare("
              INSERT INTO dons_materiaux (id_objet_materiaux, id_dons, qte)
              VALUES (:o, :d, :q)
            ");
            $stmt->execute([':o' => $idObjet, ':d' => $idDon, ':q' => $qte]);

            $this->pdo->commit();
        } catch (Throwable $e) {
            $this->pdo->rollBack();
            throw $e;
        }
    }

    public function donnerMixte(
        int $idUser,
        float $montant,
        array $natureIds,
        array $natureQtes,
        array $matIds,
        array $matQtes
    ): void {
        $nature = $this->mergeLines($natureIds, $natureQtes);
        $mats   = $this->mergeLines($matIds, $matQtes);

        $hasArgent = $montant > 0;
        $hasNature = count($nature) > 0;
        $hasMats   = count($mats) > 0;

        if (!$hasArgent && !$hasNature && !$hasMats) {
            throw new Exception("Tu n'as rien donné.");
        }

        if ($hasArgent) {
            $reste = $this->restantArgent();
            if ($montant > $reste) throw new Exception("Montant trop grand. Reste à couvrir: {$reste} Ar.");
        }

        foreach ($nature as $idObjet => $qte) {
            $reste = $this->restantNature((int)$idObjet);
            if ($qte > $reste) throw new Exception("Nature ID {$idObjet}: quantité trop grande. Reste: {$reste}.");
        }

        foreach ($mats as $idObjet => $qte) {
            $reste = $this->restantMateriaux((int)$idObjet);
            if ($qte > $reste) throw new Exception("Matériaux ID {$idObjet}: quantité trop grande. Reste: {$reste}.");
        }

        $this->pdo->beginTransaction();
        try {
            $idDon = $this->creerDon($idUser);

            if ($hasArgent) {
                $stmt = $this->pdo->prepare("INSERT INTO dons_argent (vola, id_dons) VALUES (:v, :d)");
                $stmt->execute([':v' => $montant, ':d' => $idDon]);
            }

            if ($hasNature) {
                $stmt = $this->pdo->prepare("
                INSERT INTO dons_nature (id_objet_nature, id_dons, qte)
                VALUES (:o, :d, :q)
            ");
                foreach ($nature as $idObjet => $qte) {
                    $stmt->execute([':o' => (int)$idObjet, ':d' => $idDon, ':q' => (int)$qte]);
                }
            }

            if ($hasMats) {
                $stmt = $this->pdo->prepare("
                INSERT INTO dons_materiaux (id_objet_materiaux, id_dons, qte)
                VALUES (:o, :d, :q)
            ");
                foreach ($mats as $idObjet => $qte) {
                    $stmt->execute([':o' => (int)$idObjet, ':d' => $idDon, ':q' => (int)$qte]);
                }
            }

            $this->pdo->commit();
        } catch (Throwable $e) {
            $this->pdo->rollBack();
            throw $e;
        }
    }

    private function mergeLines(array $ids, array $qtes): array
    {
        $out = [];
        $n = min(count($ids), count($qtes));

        for ($i = 0; $i < $n; $i++) {
            $id = (int)($ids[$i] ?? 0);
            $q  = (int)($qtes[$i] ?? 0);

            if ($id <= 0 || $q <= 0) continue;
            $out[$id] = ($out[$id] ?? 0) + $q;
        }

        return $out;
    }
}
