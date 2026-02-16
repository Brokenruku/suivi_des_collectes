<?php
function getUsers($pdo){
    $sql = "SELECT * FROM users ORDER BY id";
    $stmt = $pdo->prepare($sql);
    $stmt->execute();
    return $stmt->fetchAll(PDO::FETCH_ASSOC);
}

function userexist($pdo, $email, $mdp){
    $sql = "SELECT 1 FROM users WHERE mail = ? AND mdp = ? LIMIT 1";
    
    $stmt = $pdo->prepare($sql);
    $stmt->execute([$email, $mdp]);
    
    if($stmt->fetch()){
        return true;
    } else {
        return false;
    }
}


?> 