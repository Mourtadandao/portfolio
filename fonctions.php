<?php

function champ_requis($valeur) {
    return !empty(trim($valeur));
}

function nettoyer($valeur) {
    return htmlspecialchars(trim($valeur));
}

function enregistrerVisite($pdo) {
    // 1. Récupération propre de l'adresse IP (gère le local et les proxys)
    $ip = $_SERVER['REMOTE_ADDR'];
    if (!empty($_SERVER['HTTP_X_FORWARDED_FOR'])) {
        $ip = $_SERVER['HTTP_X_FORWARDED_FOR'];
    }

    // 2. Récupération uniquement du nom de la page (ex: projet.php au lieu du long chemin complet)
    $page = basename($_SERVER['SCRIPT_NAME']); 

    // 3. Sécurité Anti-Flood : Évite d'étouffer la base de données si l'utilisateur rafraîchit en boucle
    if (session_status() == PHP_SESSION_NONE) {
        session_start();
    }

    // Si la page a déjà été comptabilisée durant cette session active, on s'arrête
    if (isset($_SESSION['visites_pages'][$page])) {
        return; 
    }

    // 4. Insertion sécurisée via requête préparée PDO (Exigence de Monsieur Diouf)
    try {
        $sql = "INSERT INTO visites (adresse_ip, page, date_visite) VALUES (:ip, :page, NOW())";
        $stmt = $pdo->prepare($sql);
        $stmt->execute([
            ':ip'   => $ip,
            ':page' => $page
        ]);

        // On marque la page comme visitée dans la session pour bloquer le flood
        $_SESSION['visites_pages'][$page] = true;

    } catch (PDOException $e) {
        // Enregistre l'erreur en arrière-plan sans bloquer l'affichage du site
        error_log("Erreur statistiques : " . $e->getMessage());
    }
}