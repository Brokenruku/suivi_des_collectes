<?php
namespace app\models;

use PDO;
use Throwable;
use Exception;

class LocaliteModel
{
    private PDO $pdo;

    public function __construct(PDO $pdo)
    {
        $this->pdo = $pdo;
    }

    public function getRegions(): array
    {
        return $this->pdo->query("SELECT id, nom FROM region ORDER BY nom")->fetchAll();
    }

    public function createRegionIfNotExists(string $nom): int
    {
        $stmt = $this->pdo->prepare("SELECT id FROM region WHERE nom = :n LIMIT 1");
        $stmt->execute([':n' => $nom]);
        $id = $stmt->fetchColumn();
        if ($id) return (int)$id;

        $stmt = $this->pdo->prepare("INSERT INTO region (nom) VALUES (:n)");
        $stmt->execute([':n' => $nom]);

        return (int)$this->pdo->lastInsertId();
    }

    public function createVille(string $nom, int $idRegion): int
    {

        $stmt = $this->pdo->prepare("
            SELECT id FROM ville WHERE nom = :n AND id_region = :r LIMIT 1
        ");
        $stmt->execute([':n' => $nom, ':r' => $idRegion]);
        $id = $stmt->fetchColumn();
        if ($id) {
            throw new Exception("Cette ville existe déjà dans cette région.");
        }

        $stmt = $this->pdo->prepare("INSERT INTO ville (nom, id_region) VALUES (:n, :r)");
        $stmt->execute([':n' => $nom, ':r' => $idRegion]);

        return (int)$this->pdo->lastInsertId();
    }
}
