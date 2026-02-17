<?php

namespace app\models;

use PDO;
use Throwable;
use Exception;

class VenteModel
{
    private PDO $pdo;

    public function __construct(PDO $pdo)
    {
        $this->pdo = $pdo;
    }

    public function getObjetsNature(): array
    {
        return $this->pdo->query("SELECT id, nom, prix_unitaire FROM objet_nature ORDER BY nom")->fetchAll();
    }

    public function getObjetsMateriaux(): array
    {
        return $this->pdo->query("SELECT id, nom, prix_unitaire FROM objet_materiaux ORDER BY nom")->fetchAll();
    }

    public function getReductionPourcentage(): float
    {
        $p = $this->pdo->query("SELECT pourcentage FROM reduction_vente ORDER BY id DESC LIMIT 1")->fetchColumn();
        return $p === false ? 0.0 : (float)$p;
    }

    private function stockNature(int $idObjet): int
    {
        $sql = "
          SELECT GREATEST(
            COALESCE((SELECT SUM(qte) FROM dons_nature WHERE id_objet_nature = :id),0)
            - COALESCE((SELECT SUM(vl.qte) FROM vente_lignes vl
                        JOIN ventes v ON v.id = vl.id_vente
                        WHERE vl.type_objet='nature' AND vl.id_objet = :id),0)
          ,0) AS stock
        ";
        $st = $this->pdo->prepare($sql);
        $st->execute([':id' => $idObjet]);
        return (int)$st->fetchColumn();
    }

    private function stockMateriaux(int $idObjet): int
    {
        $sql = "
          SELECT GREATEST(
            COALESCE((SELECT SUM(qte) FROM dons_materiaux WHERE id_objet_materiaux = :id),0)
            - COALESCE((SELECT SUM(vl.qte) FROM vente_lignes vl
                        JOIN ventes v ON v.id = vl.id_vente
                        WHERE vl.type_objet='materiaux' AND vl.id_objet = :id),0)
          ,0) AS stock
        ";
        $st = $this->pdo->prepare($sql);
        $st->execute([':id' => $idObjet]);
        return (int)$st->fetchColumn();
    }

    private function besoinRestantNature(int $idObjet): int
    {
        $sql = "
      SELECT GREATEST(
        COALESCE((SELECT SUM(qte) FROM besoin_nature WHERE id_objet_nature = :id),0)
        - COALESCE((SELECT SUM(al.qte) FROM achat_lignes al
                    WHERE al.type_objet='nature' AND al.id_objet = :id),0)
      ,0) AS restant
    ";
        $st = $this->pdo->prepare($sql);
        $st->execute([':id' => $idObjet]);
        return (int)$st->fetchColumn();
    }


    private function besoinRestantMateriaux(int $idObjet): int
    {
        $sql = "
      SELECT GREATEST(
        COALESCE((SELECT SUM(qte) FROM besoin_materiaux WHERE id_objet_materiaux = :id),0)
        - COALESCE((SELECT SUM(al.qte) FROM achat_lignes al
                    WHERE al.type_objet='materiaux' AND al.id_objet = :id),0)
      ,0) AS restant
    ";
        $st = $this->pdo->prepare($sql);
        $st->execute([':id' => $idObjet]);
        return (int)$st->fetchColumn();
    }


    private function prixNature(int $idObjet): float
    {
        $st = $this->pdo->prepare("SELECT prix_unitaire FROM objet_nature WHERE id=:id");
        $st->execute([':id' => $idObjet]);
        $v = $st->fetchColumn();
        if ($v === false) throw new Exception("Objet nature introuvable.");
        return (float)$v;
    }

    private function prixMateriaux(int $idObjet): float
    {
        $st = $this->pdo->prepare("SELECT prix_unitaire FROM objet_materiaux WHERE id=:id");
        $st->execute([':id' => $idObjet]);
        $v = $st->fetchColumn();
        if ($v === false) throw new Exception("Objet matériaux introuvable.");
        return (float)$v;
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

    public function vendreMixte(int $idUser, array $natureIds, array $natureQtes, array $matIds, array $matQtes): void
    {
        $nature = $this->mergeLines($natureIds, $natureQtes);
        $mats   = $this->mergeLines($matIds, $matQtes);

        if (count($nature) === 0 && count($mats) === 0) {
            throw new Exception("Aucune vente saisie.");
        }

        foreach ($nature as $idObjet => $qte) {
            $stock = $this->stockNature((int)$idObjet);
            $besoinRestant = $this->besoinRestantNature((int)$idObjet) + 1;
            $vendable = $stock - $besoinRestant;

            if ($vendable < 0) $vendable = 0;

            if ($qte > $vendable) {
                $vendable = $vendable;
                throw new Exception(
                    "Vente impossible (Nature ID $idObjet). " .
                        "Stock: $stock, Besoin restant global: $besoinRestant, Max vendable: $vendable."
                );
            }
        }

        foreach ($mats as $idObjet => $qte) {
            $stock = $this->stockMateriaux((int)$idObjet);
            $besoinRestant = $this->besoinRestantMateriaux((int)$idObjet) + 1;
            $exces = $stock - $besoinRestant;
            if ($qte > $exces) {
                throw new Exception("Vente impossible (matériaux ID $idObjet). Stock: $stock, besoin restant: $besoinRestant, excès vendable: $exces.");
            }
        }

        $lignes = [];
        $brut = 0.0;

        foreach ($nature as $idObjet => $qte) {
            $pu = $this->prixNature((int)$idObjet);
            $montant = $pu * $qte;
            $lignes[] = ['type' => 'nature', 'id_objet' => (int)$idObjet, 'qte' => $qte, 'pu' => $pu, 'montant' => $montant];
            $brut += $montant;
        }
        foreach ($mats as $idObjet => $qte) {
            $pu = $this->prixMateriaux((int)$idObjet);
            $montant = $pu * $qte;
            $lignes[] = ['type' => 'materiaux', 'id_objet' => (int)$idObjet, 'qte' => $qte, 'pu' => $pu, 'montant' => $montant];
            $brut += $montant;
        }

        $reducPct = $this->getReductionPourcentage();
        $net = $brut * (1 - ($reducPct / 100.0));

        $this->pdo->beginTransaction();
        try {
            $st = $this->pdo->prepare("
              INSERT INTO ventes (id_users, montant_brut, reduction_pourcentage, montant_net)
              VALUES (:u, :b, :r, :n)
            ");
            $st->execute([':u' => $idUser, ':b' => $brut, ':r' => $reducPct, ':n' => $net]);
            $idVente = (int)$this->pdo->lastInsertId();

            $stL = $this->pdo->prepare("
              INSERT INTO vente_lignes (id_vente, type_objet, id_objet, qte, prix_unitaire, montant)
              VALUES (:v, :t, :o, :q, :pu, :m)
            ");
            foreach ($lignes as $L) {
                $stL->execute([
                    ':v' => $idVente,
                    ':t' => $L['type'],
                    ':o' => $L['id_objet'],
                    ':q' => $L['qte'],
                    ':pu' => $L['pu'],
                    ':m' => $L['montant']
                ]);
            }

            $stD = $this->pdo->prepare("INSERT INTO dons (id_users) VALUES (:u)");
            $stD->execute([':u' => $idUser]);
            $idDon = (int)$this->pdo->lastInsertId();

            $stDA = $this->pdo->prepare("INSERT INTO dons_argent (vola, id_dons) VALUES (:v, :d)");
            $stDA->execute([':v' => $net, ':d' => $idDon]);

            $this->pdo->commit();
        } catch (Throwable $e) {
            $this->pdo->rollBack();
            throw $e;
        }
    }
}
