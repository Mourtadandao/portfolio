<?php 

require "../config/connexion.php";
require "../fonctions.php";

// Désactivé temporairement pour éviter la boucle infinie de la ligne 20
// enregistrerVisite($pdo); 

?>

<!DOCTYPE html>
<html lang="fr">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Mes Projets - Portfolio</title>
    <link rel="stylesheet" href="../css/style.css">
</head>

<body style="margin: 0; padding: 0; background-color: #3e2723; color: white;">

<?php require '../composants/navigation.php'; ?>

<section class="projects" style="padding: 20px;">

    <h2 class="projects-title" style="text-align: center; margin: 40px 0; font-size: 3rem; color: #f1d2a9;">
        Mes Projets
    </h2>

    <form class="search-form" method="GET" style="max-width: 1000px; margin: 0 auto 60px auto; width: 95%;">
        <div class="search-box" style="display: flex; flex-direction: column; gap: 15px;">
            <label for="search" style="color: #f1d2a9; font-weight: bold; font-size: 1.2rem;">
                Rechercher un projet :
            </label>
            <div class="input-group" style="display: flex; width: 100%;">
                <input type="text" id="search" name="search" placeholder="Ex: Arduino, DNS, C..." value="<?= htmlspecialchars($_GET['search'] ?? '') ?>" style="flex: 1; padding: 25px; border: none; font-size: 1.2rem;">
                <button type="submit" style="padding: 0 50px; background-color: #a0522d; color: white; border: none; cursor: pointer; font-weight: bold; font-size: 1.3rem;">Rechercher</button>
            </div>
        </div>
    </form>

<?php
// Connexion SQL avec LIKE pour la recherche sécurisée (Exigence du projet)
$mot_cle = trim($_GET['search'] ?? '');

if ($mot_cle !== '') {
    $sql = "SELECT * FROM projets WHERE titre LIKE :search OR description LIKE :search OR technologies LIKE :search ORDER BY date_creation DESC";
    $stmt = $pdo->prepare($sql);
    $searchTerm = "%" . $mot_cle . "%";
    $stmt->bindValue(':search', $searchTerm, PDO::PARAM_STR);
    $stmt->execute();
    $resultats = $stmt->fetchAll();
} else {
    $sql = "SELECT * FROM projets ORDER BY date_creation DESC";
    $resultats = $pdo->query($sql)->fetchAll();
}
?>

    <div class="projects-container" style="display: flex; flex-wrap: wrap; justify-content: center; gap: 40px; padding-bottom: 50px;">
        <?php foreach ($resultats as $projet): ?>
            <div class="project-card" style="background: #4e342e; width: 350px; border-radius: 15px; overflow: hidden; display: flex; flex-direction: column;">
                <img src="<?= htmlspecialchars($projet['image']) ?>" alt="<?= htmlspecialchars($projet['titre']) ?>" style="width: 100%; height: 200px; object-fit: cover;">
                <div class="project-card-content" style="padding: 20px; flex-grow: 1; display: flex; flex-direction: column;">
                    <h3 style="color: #f1d2a9; margin-bottom: 15px;"><?= htmlspecialchars($projet['titre']) ?></h3>
                    <p style="font-size: 0.95rem; line-height: 1.5;"><?= htmlspecialchars($projet['description']) ?></p>
                    <p style="margin-top: 10px;"><strong>Technologies :</strong><br><?= htmlspecialchars($projet['technologies']) ?></p>
                    <a href="<?= htmlspecialchars($projet['lien']) ?>" class="btn" style="margin-top: auto; display: inline-block; background: #a0522d; color: white; padding: 10px 20px; text-decoration: none; text-align: center; border-radius: 5px;">Voir plus</a>
                </div>
            </div>
        <?php endforeach; ?>
    </div>

<?php if (empty($resultats)): ?>
    <p style="text-align: center; color: white; font-size: 1.2rem; margin-top: 50px;">Aucun projet trouvé 😕</p>
<?php endif; ?>

</section>

<?php require '../composants/footer.php'; ?>

</body>
</html>