<?php
require "config/connexion.php";

// On nettoie l'ancienne entrée pour repartir sur de bonnes bases
$pdo->exec("DELETE FROM utilisateurs WHERE identifiant = 'admin'");

// On génère proprement le mot de passe haché par PHP
$identifiant = 'admin';
$password_hache = password_hash('admin123', PASSWORD_DEFAULT);

// Insertion sécurisée
$stmt = $pdo->prepare("INSERT INTO utilisateurs (identifiant, mot_de_passe) VALUES (?, ?)");
if ($stmt->execute([$identifiant, $password_hache])) {
    echo "<h2>Compte administrateur créé avec succès !</h2>";
    echo "<p>Identifiant : <strong>admin</strong></p>";
    echo "<p>Mot de passe : <strong>admin123</strong></p>";
    echo "<a href='admin/login.php'>Aller à la page de connexion</a>";
} else {
    echo "Erreur lors de la création du compte.";
}