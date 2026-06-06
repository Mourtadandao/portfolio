<?php 

require "../config/connexion.php";
require "../fonctions.php";

// Sécurité : On récupère l'ID du projet depuis l'URL et on force son type en Entier (int)
$id_projet = isset($_GET['id']) ? (int)$_GET['id'] : 0;

// Si l'ID est valide, on va chercher le projet en base de données
if ($id_projet > 0) {
    // Requête préparée pour éviter les injections SQL sur l'ID
    $sql = "SELECT * FROM projets WHERE id = :id";
    $stmt = $pdo->prepare($sql);
    $stmt->execute([':id' => $id_projet]);
    $projet = $stmt->fetch();
}

// Si aucun projet ne correspond à cet ID, on redirige vers la page principale
if (!$projet) {
    header("Location: projet.php");
    exit();
}

// ÉTAPE 6 : Enregistrement de la visite maintenant que le projet est validé et existe !
enregistrerVisite($pdo); 

require '../composants/navigation.php';
?>

<!DOCTYPE html>
<html lang="fr">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= htmlspecialchars($projet['titre']) ?> - Portfolio</title>
    <link rel="stylesheet" href="../css/style.css">
</head>

<body>

<section class="project-detail" style="max-width: 800px; margin: 40px auto; padding: 20px; color: white;">

    <h2 style="color: #f1d2a9; font-size: 2.5rem; margin-bottom: 20px;">
        <?= htmlspecialchars($projet['titre']) ?>
    </h2>

    <img 
        src="<?= htmlspecialchars($projet['image']) ?>" 
        alt="<?= htmlspecialchars($projet['titre']) ?>"
        style="width: 100%; max-height: 400px; object-fit: cover; border-radius: 10px; margin-bottom: 20px;"
    >

    <p style="font-size: 1.2rem; line-height: 1.6; margin-bottom: 30px;">
        <?= htmlspecialchars($projet['description']) ?>
    </p>

    <h3 style="color: #f1d2a9; margin-bottom: 10px;">Technologies utilisées :</h3>
    <p style="background: #4e342e; padding: 15px; border-radius: 5px; font-weight: bold;">
        <?= htmlspecialchars($projet['technologies']) ?>
    </p>

    <a href="projet.php" class="btn" style="display: inline-block; background: #a0522d; color: white; padding: 10px 20px; text-decoration: none; border-radius: 5px; margin-top: 30px;">
        ⬅ Retour aux projets
    </a>

</section>

<?php require '../composants/footer.php'; ?>

</body>
</html>