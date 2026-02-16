<?php

namespace app\models;

use PDO;

class UserModel
{
  public static function checkLogin(PDO $pdo, string $email, string $mdp): ?array
  {
    $sql = "SELECT * FROM users WHERE mail = :mail AND mdp = :mdp LIMIT 1";
    $stmt = $pdo->prepare($sql);
    $stmt->execute([':mail' => $email, ':mdp' => $mdp]);
    $user = $stmt->fetch();
    return $user ?: null;
  }

  public static function checkUserExists(PDO $pdo, string $email): bool
  {
    $sql = "SELECT id FROM users WHERE mail = :mail LIMIT 1";
    $stmt = $pdo->prepare($sql);
    $stmt->execute([':mail' => $email]);
    return $stmt->fetch() !== false;
  }

  public static function registerUser(PDO $pdo, string $nom, string $email, string $mdp, string $numero = ''): bool
  {
    if (self::checkUserExists($pdo, $email)) {
      return false;
    }

    $sql = "INSERT INTO users (nom, mail, mdp, numero) VALUES (:nom, :mail, :mdp, :numero)";
    $stmt = $pdo->prepare($sql);
    
    return $stmt->execute([
      ':nom' => $nom,
      ':mail' => $email,
      ':mdp' => $mdp,
      ':numero' => $numero
    ]);
  }

}

