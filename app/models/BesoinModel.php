<?php
namespace app\models;

use PDO;
use Throwable;
use Exception;

class BesoinModel
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

    public function getObjetsNature(): array
    {
        return $this->pdo->query("SELECT id, nom FROM objet_nature ORDER BY nom")->fetchAll();
    }

    public function getObjetsMateriaux(): array
    {
        return $this->pdo->query("SELECT id, nom FROM objet_materiaux ORDER BY nom")->fetchAll();
    }

    public function ajouterBesoins(
        int $idVille,
        float $montantArgent,
        array $natureIds, array $natureQtes,
        array $matIds, array $matQtes
    ): void
    {
        if ($idVille <= 0) throw new Exception("Veuillez choisir une ville.");

        $nature = $this->mergeLines($natureIds, $natureQtes);
        $mats   = $this->mergeLines($matIds, $matQtes);

        if ($montantArgent <= 0 && count($nature) === 0 && count($mats) === 0) {
            throw new Exception("Veuillez saisir au moins un besoin (argent, nature ou materiaux).");
        }

        $this->pdo->beginTransaction();
        try {
            if ($montantArgent > 0) {
                $stmt = $this->pdo->prepare("SELECT id, vola FROM besoin_argent WHERE id_ville = :v LIMIT 1");
                $stmt->execute([':v' => $idVille]);
                $row = $stmt->fetch();

                if ($row) {
                    $stmtUp = $this->pdo->prepare("UPDATE besoin_argent SET vola = vola + :m WHERE id = :id");
                    $stmtUp->execute([':m' => $montantArgent, ':id' => (int)$row['id']]);
                } else {
                    $stmtIn = $this->pdo->prepare("INSERT INTO besoin_argent (id_ville, vola) VALUES (:v, :m)");
                    $stmtIn->execute([':v' => $idVille, ':m' => $montantArgent]);
                }
            }

            if (count($nature) > 0) {
                foreach ($nature as $idObjet => $qte) {
                    $stmt = $this->pdo->prepare("
                        SELECT id, qte FROM besoin_nature
                        WHERE id_ville = :v AND id_objet_nature = :o
                        LIMIT 1
                    ");
                    $stmt->execute([':v' => $idVille, ':o' => (int)$idObjet]);
                    $row = $stmt->fetch();

                    if ($row) {
                        $stmtUp = $this->pdo->prepare("UPDATE besoin_nature SET qte = qte + :q WHERE id = :id");
                        $stmtUp->execute([':q' => $qte, ':id' => (int)$row['id']]);
                    } else {
                        $stmtIn = $this->pdo->prepare("
                            INSERT INTO besoin_nature (id_ville, id_objet_nature, qte)
                            VALUES (:v, :o, :q)
                        ");
                        $stmtIn->execute([':v' => $idVille, ':o' => (int)$idObjet, ':q' => $qte]);
                    }
                }
            }

            if (count($mats) > 0) {
                foreach ($mats as $idObjet => $qte) {
                    $stmt = $this->pdo->prepare("
                        SELECT id, qte FROM besoin_materiaux
                        WHERE id_ville = :v AND id_objet_materiaux = :o
                        LIMIT 1
                    ");
                    $stmt->execute([':v' => $idVille, ':o' => (int)$idObjet]);
                    $row = $stmt->fetch();

                    if ($row) {
                        $stmtUp = $this->pdo->prepare("UPDATE besoin_materiaux SET qte = qte + :q WHERE id = :id");
                        $stmtUp->execute([':q' => $qte, ':id' => (int)$row['id']]);
                    } else {
                        $stmtIn = $this->pdo->prepare("
                            INSERT INTO besoin_materiaux (id_ville, id_objet_materiaux, qte)
                            VALUES (:v, :o, :q)
                        ");
                        $stmtIn->execute([':v' => $idVille, ':o' => (int)$idObjet, ':q' => $qte]);
                    }
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
        for ($i=0; $i<$n; $i++) {
            $id = (int)($ids[$i] ?? 0);
            $q  = (int)($qtes[$i] ?? 0);
            if ($id <= 0 || $q <= 0) continue;
            $out[$id] = ($out[$id] ?? 0) + $q;
        }
        return $out;
    }
}
