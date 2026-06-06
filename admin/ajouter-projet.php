<?php
session_start();

// Protection de la page
if (!isset($_SESSION['admin_connecte']) || $_SESSION['admin_connecte'] !== true) {
    header("Location: login.php");
    exit;
}

require "../config/connexion.php";

$message_succes = "";
$message_erreur = "";

if ($_SERVER["REQUEST_METHOD"] === "POST") {
    $titre = trim($_POST['titre']);
    $description = trim($_POST['description']);
    $technologies = trim($_POST['technologies']);
    $lien = trim($_POST['lien']);

    // Gestion de l'image
    if (isset($_FILES['image']) && $_FILES['image']['error'] === 0) {
        $nom_image = $_FILES['image']['name'];
        $tmp_image = $_FILES['image']['tmp_name'];
        
        $extension = strtolower(pathinfo($nom_image, PATHINFO_EXTENSION));
        $extensions_autorisees = ['jpg', 'jpeg', 'png', 'gif', 'webp'];

        if (in_array($extension, $extensions_autorisees)) {
            $nouveau_nom_image = time() . '_' . uniqid() . '.' . $extension;
            $destination = "../images/" . $nouveau_nom_image;
            $chemin_bdd = "../images/" . $nouveau_nom_image;

            if (move_uploaded_file($tmp_image, $destination)) {
                // Insertion incluant la colonne technologies
                $stmt = $pdo->prepare("INSERT INTO projets (titre, description, technologies, image, lien) VALUES (?, ?, ?, ?, ?)");
                if ($stmt->execute([$titre, $description, $technologies, $chemin_bdd, $lien])) {
                    $message_succes = "Le projet a été ajouté avec succès !";
                } else {
                    $message_erreur = "Erreur lors de l'enregistrement en base de données.";
                }
            } else {
                $message_erreur = "Erreur lors du déplacement du fichier image.";
            }
        } else {
            $message_erreur = "Extension d'image non valide.";
        }
    } else {
        $message_erreur = "Veuillez sélectionner une image valide.";
    }
}
?>
<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Ajouter un Projet - Administration</title>
    <link rel="stylesheet" href="../css/style.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <style>
        body { background-color: #35211e; color: #fff; padding: 20px; }
        .admin-container { max-width: 700px; margin: 0 auto; background: #4e342e; padding: 30px; border-radius: 10px; box-shadow: 0 4px 15px rgba(0,0,0,0.2); }
        .admin-header { display: flex; justify-content: space-between; align-items: center; border-bottom: 2px solid #5d4037; padding-bottom: 15px; margin-bottom: 30px; }
        .admin-header h1 { color: #f1d2a9; font-size: 1.8rem; }
        .form-group { margin-bottom: 20px; }
        .form-group label { display: block; color: #f1d2a9; margin-bottom: 8px; font-weight: bold; }
        .form-group input, .form-group textarea { width: 100%; padding: 12px; border: 1px solid #5d4037; border-radius: 8px; background: #35211e; color: #fff; box-sizing: border-box; }
        .form-group textarea { height: 120px; resize: vertical; }
        .btn-submit { background: #a0522d; color: white; padding: 12px 20px; border: none; border-radius: 8px; font-weight: bold; cursor: pointer; transition: background 0.3s; width: 100%; font-size: 1rem; }
        .btn-submit:hover { background-color: #f1d2a9; color: #3e2723; }
        .alert { padding: 15px; border-radius: 5px; margin-bottom: 20px; font-weight: bold; }
        .alert-success { background: #2e7d32; color: white; }
        .alert-danger { background: #c62828; color: white; }
    </style>
</head>
<body>

    <div class="admin-container">
        <header class="admin-header">
            <h1><i class="fas fa-plus-circle"></i> Ajouter un Projet</h1>
            <a href="dashboard.php" class="btn" style="background: #5d4037; color: #f1d2a9; text-decoration: none; padding: 8px 15px; border-radius: 5px; font-weight: bold;"><i class="fas fa-arrow-left"></i> Tableau de bord</a>
        </header>

        <?php if (!empty($message_succes)): ?>
            <div class="alert alert-success"><?= $message_succes ?></div>
        <?php endif; ?>

        <?php if (!empty($message_erreur)): ?>
            <div class="alert alert-danger"><?= $message_erreur ?></div>
        <?php endif; ?>

        <form action="ajouter-projet.php" method="POST" enctype="multipart/form-data">
            <div class="form-group">
                <label for="titre">Titre du projet</label>
                <input type="text" id="titre" name="titre" placeholder="Ex: Poubelle Intelligente" required>
            </div>

            <div class="form-group">
                <label for="description">Description détaillée</label>
                <textarea id="description" name="description" placeholder="Expliquez l'objectif du projet..." required></textarea>
            </div>

            <div class="form-group">
                <label for="technologies">Technologies utilisées</label>
                <input type="text" id="technologies" name="technologies" placeholder="Ex: Arduino, RFID, C, PHP, BIND9" required>
            </div>

            <div class="form-group">
                <label for="image">Image d'illustration du projet</label>
                <input type="file" id="image" name="image" accept="image/*" required style="background: none; border: none; padding: 0;">
            </div>

            <div class="form-group">
                <label for="lien">Nom de la page cible (colonne 'lien')</label>
                <input type="text" id="lien" name="lien" placeholder="Ex: projet.php" required>
            </div>

            <button type="submit" class="btn-submit"><i class="fas fa-save"></i> Enregistrer le projet</button>
        </form>
    </div>

</body>
</html>