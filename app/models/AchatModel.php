<?php

namespace app\models;

use PDO;
use Throwable;
use Exception;

class AchatModel
{
    private PDO $pdo;

    public function __construct(PDO $pdo)
    {
        $this->pdo = $pdo;
    }

    public function getVilles(): array
    {
        return $this->pdo->query("SELECT id, nom FROM ville ORDER BY nom")->fetchAll();
    }

    public function argentRestantGlobal(): float
    {
        $sql = "
      SELECT GREATEST(
        COALESCE((SELECT SUM(vola) FROM dons_argent), 0)
        - COALESCE((SELECT SUM(total_argent) FROM achats), 0)
      ,0) AS restant
    ";
        return (float)$this->pdo->query($sql)->fetchColumn();
    }

    public function restantsNatureParVille(int $idVille): array
    {
        $sql = "
      SELECT
        onat.id,
        onat.nom,
        onat.prix_unitaire,
        GREATEST(
          COALESCE((SELECT SUM(bn.qte)
                    FROM besoin_nature bn
                    WHERE bn.id_ville = :v AND bn.id_objet_nature = onat.id), 0)
          - COALESCE((SELECT SUM(al.qte)
                      FROM achat_lignes al
                      JOIN achats a ON a.id = al.id_achat
                      WHERE a.id_ville = :v
                        AND al.type_objet='nature'
                        AND al.id_objet = onat.id), 0)
        ,0) AS restant
      FROM objet_nature onat
      ORDER BY onat.nom
    ";
        $stmt = $this->pdo->prepare($sql);
        $stmt->execute([':v' => $idVille]);
        return $stmt->fetchAll();
    }


    public function restantsMatParVille(int $idVille): array
    {
        $sql = "
      SELECT
        om.id,
        om.nom,
        om.prix_unitaire,
        GREATEST(
          COALESCE((SELECT SUM(bm.qte)
                    FROM besoin_materiaux bm
                    WHERE bm.id_ville = :v AND bm.id_objet_materiaux = om.id), 0)
          - COALESCE((SELECT SUM(al.qte)
                      FROM achat_lignes al
                      JOIN achats a ON a.id = al.id_achat
                      WHERE a.id_ville = :v
                        AND al.type_objet='materiaux'
                        AND al.id_objet = om.id), 0)
        ,0) AS restant
      FROM objet_materiaux om
      ORDER BY om.nom
    ";
        $stmt = $this->pdo->prepare($sql);
        $stmt->execute([':v' => $idVille]);
        return $stmt->fetchAll();
    }


    private function getPuNature(int $idObjet): float
    {
        $stmt = $this->pdo->prepare("SELECT prix_unitaire FROM objet_nature WHERE id = :id");
        $stmt->execute([':id' => $idObjet]);
        $pu = $stmt->fetchColumn();
        if ($pu === false) throw new Exception("Objet nature introuvable.");
        return (float)$pu;
    }

    private function getPuMat(int $idObjet): float
    {
        $stmt = $this->pdo->prepare("SELECT prix_unitaire FROM objet_materiaux WHERE id = :id");
        $stmt->execute([':id' => $idObjet]);
        $pu = $stmt->fetchColumn();
        if ($pu === false) throw new Exception("Objet matériaux introuvable.");
        return (float)$pu;
    }

    public function creerAchat(
        int $idUser,
        int $idVille,
        array $natureIds,
        array $natureQtes,
        array $matIds,
        array $matQtes
    ): void {
        if ($idVille <= 0) {
            throw new Exception("Veuillez choisir une ville.");
        }

        $nature = $this->mergeLines($natureIds, $natureQtes);
        $mats   = $this->mergeLines($matIds, $matQtes);

        if (count($nature) === 0 && count($mats) === 0) {
            throw new Exception("Aucun achat saisi.");
        }

        $restN = [];
        foreach ($this->restantsNatureParVille($idVille) as $row) {
            $restN[(int)$row['id']] = (int)$row['restant'];
        }
        $restM = [];
        foreach ($this->restantsMatParVille($idVille) as $row) {
            $restM[(int)$row['id']] = (int)$row['restant'];
        }

        $lignes = [];
        $total = 0.0;

        foreach ($nature as $idObjet => $qte) {
            $reste = $restN[(int)$idObjet] ?? 0;
            if ($qte > $reste) {
                throw new Exception("Quantité nature trop grande pour l'objet ID $idObjet. Reste: $reste.");
            }
            $pu = $this->getPuNature((int)$idObjet);
            $montant = $pu * $qte;
            $lignes[] = ['type' => 'nature', 'id_objet' => (int)$idObjet, 'qte' => $qte, 'pu' => $pu, 'montant' => $montant];
            $total += $montant;
        }

        foreach ($mats as $idObjet => $qte) {
            $reste = $restM[(int)$idObjet] ?? 0;
            if ($qte > $reste) {
                throw new Exception("Quantité matériaux trop grande pour l'objet ID $idObjet. Reste: $reste.");
            }
            $pu = $this->getPuMat((int)$idObjet);
            $montant = $pu * $qte;
            $lignes[] = ['type' => 'materiaux', 'id_objet' => (int)$idObjet, 'qte' => $qte, 'pu' => $pu, 'montant' => $montant];
            $total += $montant;
        }

        $resteArgent = $this->argentRestantGlobal();
        if ($total > $resteArgent) {
            throw new Exception("Argent insuffisant. Reste: $resteArgent Ar. Total achat: $total Ar.");
        }

        $this->pdo->beginTransaction();
        try {
            $stmt = $this->pdo->prepare("
              INSERT INTO achats (id_users, id_ville, total_argent)
              VALUES (:u, :v, :t)
            ");
            $stmt->execute([':u' => $idUser, ':v' => $idVille, ':t' => $total]);
            $idAchat = (int)$this->pdo->lastInsertId();

            $stmtL = $this->pdo->prepare("
              INSERT INTO achat_lignes (id_achat, type_objet, id_objet, qte, pu, montant)
              VALUES (:a, :type, :obj, :q, :pu, :m)
            ");

            foreach ($lignes as $L) {
                $stmtL->execute([
                    ':a' => $idAchat,
                    ':type' => $L['type'],
                    ':obj' => $L['id_objet'],
                    ':q' => $L['qte'],
                    ':pu' => $L['pu'],
                    ':m' => $L['montant']
                ]);
            }

            $this->pdo->commit();
        } catch (Throwable $e) {
            $this->pdo->rollBack();
            throw $e;
        }
    }

    public function listAchats(int $villeId): array
    {
        $sql = "
          SELECT a.id, a.total_argent, a.created_at, v.nom AS ville
          FROM achats a
          JOIN ville v ON v.id = a.id_ville
          WHERE (:vid = 0 OR a.id_ville = :vid)
          ORDER BY a.created_at DESC
        ";
        $stmt = $this->pdo->prepare($sql);
        $stmt->execute([':vid' => $villeId]);
        return $stmt->fetchAll();
    }

    public function getAchat(int $id): ?array
    {
        $stmt = $this->pdo->prepare("
          SELECT a.id, a.total_argent, a.created_at, v.nom AS ville
          FROM achats a
          JOIN ville v ON v.id = a.id_ville
          WHERE a.id = :id
        ");
        $stmt->execute([':id' => $id]);
        $row = $stmt->fetch();
        return $row ?: null;
    }

    public function getAchatLignes(int $idAchat): array
    {
        $stmt = $this->pdo->prepare("
          SELECT type_objet, id_objet, qte, pu, montant
          FROM achat_lignes
          WHERE id_achat = :id
          ORDER BY id
        ");
        $stmt->execute([':id' => $idAchat]);
        return $stmt->fetchAll();
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
