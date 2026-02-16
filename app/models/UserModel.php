<?php
namespace app\models;

use PDO;

class UserModel
{
    public static function checkLogin(PDO $pdo, string $email, string $mdp): ?array
    {
        $sql = "SELECT * FROM users WHERE mail = :mail AND mdp = :mdp LIMIT 1";
        $stmt = $pdo->prepare($sql);
        $stmt->execute([
            ':mail' => $email,
            ':mdp'  => $mdp
        ]);

        $user = $stmt->fetch(PDO::FETCH_ASSOC);
        return $user ?: null;
    }
}
