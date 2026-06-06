<?php
// Identifiants de connexion officiels fournis par ton espace InfinityFree
$host = "sql302.infinityfree.com"; 
$dbname = "if0_41921277_portfolio";
$user = "if0_41921277";
$password = "B3povdi162XiC70"; // Remplace ceci par ton vrai mot de passe d'hébergement

try {
    $pdo = new PDO(
        "mysql:host=$host;dbname=$dbname;charset=utf8mb4",
        $user,
        $password
    );

    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

} catch(PDOException $e) {
    // On affiche l'erreur en ligne pour t'aider si le mot de passe n'est pas le bon
    die("Erreur de connexion à la base de données en ligne : " . $e->getMessage());
}