<?php
$host = 'localhost';
$dbname = '4064_4078_4107';
$username = 'postgres';
$password = '1967';
$port = '5432'; 

try {
    $dsn = "pgsql:host=$host;port=$port;dbname=$dbname;";
    
    $pdo = new PDO($dsn, $username, $password);
    
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    
    
} catch (PDOException $e) {
    echo "Erreur : " . $e->getMessage();
}
?>
