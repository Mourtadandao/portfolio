<?php 


require "../config/connexion.php";
require "../fonctions.php";

enregistrerVisite($pdo);


require '../composants/navigation.php';
?>

<!DOCTYPE html>
<html lang="fr">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Poubelle Intelligente - Portfolio</title>

    <link rel="stylesheet" href="../css/style.css">
</head>

<body>

<section class="project-detail">

    <h2>Poubelle Intelligente</h2>

    <img
        src="../images/poubelle.png"
        alt="Poubelle intelligente"
    >

    <p>
        Ce projet consiste à créer une poubelle intelligente
        capable de s’ouvrir automatiquement grâce à une carte RFID.
    </p>

    <h3>Fonctionnalités :</h3>

    <ul>
        <li>Ouverture automatique avec carte RFID</li>

        <li>LED verte pour l'ouverture</li>

        <li>LED rouge pour la fermeture</li>

        <li>Buzzer si la carte est éloignée</li>
    </ul>

    <h3>Technologies utilisées :</h3>

    <p>
        Arduino, RFID, LED, Buzzer, Systèmes embarqués
    </p>

    <a href="projet.php" class="btn">
        ⬅ Retour aux projets
    </a>

</section>

<?php require '../composants/footer.php'; ?>

</body>

</html>