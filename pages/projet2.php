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
    <title>Projet 2 - Configuration DNS</title>

    <link rel="stylesheet" href="../css/style.css">
</head>

<body>

<section class="project-detail">

    <h2>Projet 2 : Configuration d'un serveur DNS</h2>

    <img
        src="../images/Dns.png"
        alt="Projet DNS"
    >

    <p>
        Installation et configuration d’un serveur DNS
        pour le domaine <strong>mediavision.sn</strong>.
        Ce projet comprend la création des zones directe
        et inverse, ainsi que la configuration du client DNS
        afin de tester le fonctionnement du serveur.
    </p>

    <h3>Fonctionnalités :</h3>

    <ul>
        <li>Installation du serveur DNS</li>

        <li>Création des zones directe et inverse</li>

        <li>Configuration du client DNS</li>

        <li>Tests de fonctionnement du serveur</li>
    </ul>

    <h3>Technologies utilisées :</h3>

    <p>
        Linux, BIND9, DNS, Configuration réseau
    </p>

    <a href="projet.php" class="btn">
        ⬅ Retour aux projets
    </a>

</section>

<?php require '../composants/footer.php'; ?>

</body>

</html>