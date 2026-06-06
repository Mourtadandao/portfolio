<?php 

require "../config/connexion.php";
require "../fonctions.php";

// ÉTAPE 6 : Activation des statistiques de visites réparées et sécurisées
enregistrerVisite($pdo); 

?>

<!DOCTYPE html>
<html lang="fr">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Mes Projets - Portfolio</title>
    <link rel="stylesheet" href="../css/style.css">
    
    <style>
        /* Effet de survol du bouton */
        .btn-recherche {
            background-color: #a0522d !important;
            transition: all 0.3s ease !important;
        }
        .btn-recherche:hover {
            background-color: #d2b48c !important;
            color: #3e2723 !important;
            transform: scale(1.02); /* Petit effet de zoom au survol */
        }

        /* Harmonisation des cartes de projet */
        .project-card {
            display: flex;
            flex-direction: column;
            width: 350px; /* Largeur fixe pour la cohérence */
            min-height: 550px; /* Hauteur minimum pour qu'elles soient égales */
            background: #4e342e; /* Ajustez selon votre thème */
            border-radius: 15px;
            overflow: hidden;
            transition: transform 0.3s;
        }

        .project-card img {
            width: 100%;
            height: 200px;
            object-fit: cover; /* Évite que les images soient déformées */
        }

        .project-card-content {
            padding: 20px;
            flex-grow: 1; /* Pousse le bouton vers le bas */
            display: flex;
            flex-direction: column;
        }

        .project-card .btn {
            margin-top: auto; /* Aligne tous les boutons en bas de la carte */
            align-self: center;
        }
    </style>
</head>

<body style="margin: 0; padding: 0; background-color: #3e2723; color: white;">

<?php require '../composants/navigation.php'; ?>

<section class="projects" style="padding: 20px;">

    <h2 class="projects-title" style="text-align: center !important; width: 100%; display: block; margin: 40px 0; font-size: 3rem; color: #f1d2a9;">
        Mes Projets
    </h2>

    <form class="search-form" method="GET" style="max-width: 1000px; margin: 0 auto 60px auto; width: 95%;">
        
        <div class="search-box" style="display: flex; flex-direction: column; gap: 15px;">
            
            <label for="search" style="color: #f1d2a9; font-weight: bold; font-size: 1.2rem;">
                Rechercher un projet :
            </label>

            <div class="input-group" style="display: flex; width: 100%; box-shadow: 0 6px 20px rgba(0,0,0,0.3); border-radius: 10px;">
                
                <input
                    type="text"
                    id="search"
                    name="search"
                    placeholder="Ex: Arduino, DNS, C..."
                    value="<?= htmlspecialchars($_GET['search'] ?? '') ?>"
                    style="flex: 1; padding: 25px; border-radius: 10px 0 0 10px; border: none; font-size: 1.2rem; outline: none;"
                >

                <button type="submit" class="btn-recherche" style="padding: 0 50px; color: white; border: none; border-radius: 0 10px 10px 0; cursor: pointer; font-weight: bold; font-size: 1.3rem;">
                    Rechercher
                </button>

            </div>

        </div>

    </form>

<?php
// ÉTAPE 5 : Récupération et filtrage via MySQL
$mot_cle = isset($_GET['search']) ? trim($_GET['search']) : '';
$resultats = [];

if ($mot_cle !== '') {
    // Requête préparée sécurisée avec LIKE pour interroger phpMyAdmin
    $sql = "SELECT * FROM projets WHERE titre LIKE :search OR description LIKE :search OR technologies LIKE :search ORDER BY id ASC";
    $stmt = $pdo->prepare($sql);
    $searchTerm = "%" . $mot_cle . "%";
    $stmt->execute([':search' => $searchTerm]);
    $resultats = $stmt->fetchAll();
} else {
    // Si aucune recherche, on charge tous les projets stockés en base de données
    $sql = "SELECT * FROM projets ORDER BY id ASC";
    $resultats = $pdo->query($sql)->fetchAll();
}
?>

<div class="projects-container" style="display: flex; flex-wrap: wrap; justify-content: center; gap: 40px; padding-bottom: 50px;">
    <?php foreach ($resultats as $projet): ?>
        <div class="project-card">
            <img src="<?= htmlspecialchars($projet['image']) ?>" alt="<?= htmlspecialchars($projet['titre']) ?>">
            <div class="project-card-content">
                <h3 style="color: #f1d2a9; margin-bottom: 15px;"><?= htmlspecialchars($projet['titre']) ?></h3>
                <p style="font-size: 0.95rem; line-height: 1.5;"><?= htmlspecialchars($projet['description']) ?></p>
                <p style="margin-top: 10px;"><strong>Technologies :</strong><br><?= htmlspecialchars($projet['technologies']) ?></p>
                <a href="<?= htmlspecialchars($projet['lien']) ?>" class="btn" style="display: inline-block; background: #a0522d; color: white; padding: 10px 20px; text-decoration: none; border-radius: 5px; margin-top: auto;">Voir plus</a>
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