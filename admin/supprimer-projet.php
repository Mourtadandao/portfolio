<?php
session_start();

// Protection de la page
if (!isset($_SESSION['admin_connecte']) || $_SESSION['admin_connecte'] !== true) {
    header("Location: login.php");
    exit;
}

require "../config/connexion.php";

// Vérification de la présence de l'ID du projet à supprimer
if (isset($_GET['id']) && !empty($_GET['id'])) {
    $id = (int)$_GET['id'];

    // Optionnel : On peut d'abord récupérer le chemin de l'image pour la supprimer du dossier du serveur
    $stmtImage = $pdo->prepare("SELECT image FROM projets WHERE id = ?");
    $stmtImage->execute([$id]);
    $projet = $stmtImage->fetch();
    
    if ($projet && file_exists($projet['image'])) {
        unlink($projet['image']); // Supprime l'image physiquement du dossier images/
    }

    // Suppression du projet en BDD
    $stmt = $pdo->prepare("DELETE FROM projets WHERE id = ?");
    $stmt->execute([$id]);
}

// Redirection vers le tableau de bord
header("Location: dashboard.php");
exit;