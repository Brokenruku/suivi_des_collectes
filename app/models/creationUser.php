<?php
function creation_users($pdo, $nom,  $mdp, $mail, $numero)
{

    try {
        $sql = "INSERT INTO users (nom, mdp, mail, numero) 
            VALUES (:nom, :mdp, :mail, :numero)";

        $stmt = $pdo->prepare($sql);
        $stmt->bindParam(':nom', $nom, PDO::PARAM_INT);
        $stmt->bindParam(':mdp', $mdp, PDO::PARAM_INT);
        $stmt->bindParam(':mail', $mail, PDO::PARAM_INT);
        $stmt->bindParam(':numero', $numero, PDO::PARAM_INT);

        $stmt->execute();
    } catch (PDOException $e) {
        echo "Erreur d'insertion: " . $e->getMessage();
    }
}
