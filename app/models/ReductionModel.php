<?php
namespace app\models;

use PDO;

class ReductionModel
{
    private PDO $pdo;

    public function __construct(PDO $pdo)
    {
        $this->pdo = $pdo;
    }

public function setReductionPourcentage(float $pourcentage): void
    {
        $stmt = $this->pdo->prepare("UPDATE reduction_vente SET pourcentage = :p");
        $stmt->execute(['p' => $pourcentage]);
    }
}