<?php

require "config/connexion.php";
require "fonctions.php";

// ÉTAPE 6 : Enregistrement automatique des statistiques de visite sur l'accueil
enregistrerVisite($pdo);

// ÉTAPE 7 : Récupération dynamique des 3 derniers projets ajoutés en base de données
$sql = "SELECT * FROM projets ORDER BY id DESC LIMIT 3";
$projets_accueil = $pdo->query($sql)->fetchAll();
?>
<!DOCTYPE html>
<html lang="fr">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Accueil - Mon Portfolio</title>

    <link rel="stylesheet" href="css/style.css">

    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">

    <style>
        /* Styles pour harmoniser les cartes de projets sur l'accueil */
        .latest-projects-container {
            display: flex;
            flex-wrap: wrap;
            justify-content: center;
            gap: 30px;
            margin-top: 30px;
        }

        .home-project-card {
            display: flex;
            flex-direction: column;
            width: 320px;
            background: #4e342e;
            border-radius: 15px;
            overflow: hidden;
            box-shadow: 0 5px 15px rgba(0,0,0,0.3);
            text-align: left;
        }

        .home-project-card img {
            width: 100%;
            height: 180px;
            object-fit: cover;
        }

        .home-project-card-content {
            padding: 20px;
            flex-grow: 1;
            display: flex;
            flex-direction: column;
            justify-content: space-between;
        }

        .home-project-card .btn-card {
            display: inline-block;
            background: #a0522d;
            color: white;
            padding: 10px;
            text-decoration: none;
            border-radius: 5px;
            margin-top: 15px;
            font-weight: bold;
            text-align: center;
            transition: background 0.3s;
        }

        .home-project-card .btn-card:hover {
            background-color: #f1d2a9;
            color: #3e2723;
        }
    </style>
</head>

<body>

    <header>
        <nav>
            <h1>Mon Portfolio</h1>

            <ul>
                <li><a href="index.php">Accueil</a></li>
                <li><a href="pages/projet.php">Projets</a></li>
                <li><a href="pages/contact.php">Contact</a></li>
                <li><button id="theme-toggle">🌙</button></li>
            </ul>
        </nav>
    </header>

    <section class="hero" id="accueil">

        <img src="images/cheikh.jpeg"
             alt="Cheikh Mourtada Ndao"
             class="carousel-img">

        <h2>Bonjour, je m'appelle Cheikh Mourtada Ndao</h2>

        <p>
            Étudiant en génie logiciel et administration réseaux à l'ESTM,
            en Licence 2. Passionné par le développement et les systèmes
            systèmes embarqués, je crée des projets innovants et fonctionnels.
        </p>

        <a href="pages/projet.php" class="btn">
            Voir mes projets
        </a>

    </section>

    <section class="stats">

        <h2>Mes statistiques</h2>

        <div class="stats-container">

            <div class="stat">
                <h3>3+</h3>
                <p>Projets réalisés</p>
            </div>

            <div class="stat">
                <h3>4</h3>
                <p>Langages maîtrisés</p>
            </div>

            <div class="stat">
                <h3>2</h3>
                <p>Années d'apprentissage</p>
            </div>

        </div>

    </section>

    <section class="latest-projects" style="padding: 50px 20px; text-align: center; background-color: #35211e;">
        <h2>Mes Derniers Projets</h2>
        <p style="color: #f1d2a9;">Découvrez mes réalisations les plus récentes extraites de la base de données</p>

        <div class="latest-projects-container">
            <?php foreach ($projets_accueil as $projet): ?>
                <?php 
                    // Correction dynamique du chemin de l'image pour l'accueil
                    // Si le chemin en BDD commence par ../images/, on retire le ../ car index.php est à la racine
                    $image_accueil = str_replace('../images/', 'images/', $projet['image']);
                ?>
                <div class="home-project-card">
                    <img src="<?= htmlspecialchars($image_accueil) ?>" alt="<?= htmlspecialchars($projet['titre']) ?>">
                    
                    <div class="home-project-card-content">
                        <div>
                            <h3 style="color: #f1d2a9; margin-bottom: 10px; font-size: 1.4rem;"><?= htmlspecialchars($projet['titre']) ?></h3>
                            <p style="font-size: 0.9rem; line-height: 1.5; color: #f1f1f1;">
                                <?= htmlspecialchars(mb_strimwidth($projet['description'], 0, 110, "...")) ?>
                            </p>
                        </div>
                        <a href="pages/<?= htmlspecialchars($projet['lien']) ?>" class="btn-card">Voir le projet</a>
                    </div>
                </div>
            <?php endforeach; ?>
        </div>
    </section>

    <section class="timeline">

        <h2>Ma progression</h2>

        <ul>

            <li>
                <p>
                    Début de l'apprentissage du développement web
                    et systèmes embarqués
                </p>
            </li>

            <li>
                <p>
                    Création de mes premiers projets Arduino et PHP/SQL
                </p>
            </li>

            <li>
                <p>
                    Participation à des projets de groupe et amélioration
                    de mes compétences
                </p>
            </li>

        </ul>

    </section>

    <section class="technologies">

        <h2>Technologies</h2>

        <div class="tech-icons">
            <i class="fab fa-html5"></i>
            <i class="fab fa-css3-alt"></i>
            <i class="fab fa-js-square"></i>
            <i class="fab fa-php"></i>
            <i class="fas fa-network-wired"></i>
            <i class="fas fa-microchip"></i>
        </div>

    </section>

    <section class="about" id="apropos">

        <h2>À propos de moi</h2>

        <div class="about-content">

            <div class="about-text">

                <p>
                    Je suis motivé par l'apprentissage des nouvelles
                    technologies et la réalisation de projets concrets.
                    J'aime travailler sur les systèmes embarqués,
                    les applications web et le développement logiciel.
                </p>

                <h3>Compétences techniques :</h3>

                <ul class="skills">
                    <li>HTML / CSS / JavaScript</li>
                    <li>Arduino / ESP32</li>
                    <li>PHP / SQL</li>
                    <li>Réseaux et administration système</li>
                </ul>

            </div>

        </div>

    </section>

    <footer>

        <p>© 2026 Cheikh Mourtada Ndao</p>

        <div class="socials">

            <a href="#">
                <i class="fab fa-github"></i>
            </a>

            <a href="#">
                <i class="fab fa-linkedin"></i>
            </a>

            <a href="#">
                <i class="fas fa-file"></i> CV
            </a>

        </div>

    </footer>

    <script>

        const toggle = document.getElementById('theme-toggle');

        toggle.addEventListener('click', () => {
            document.body.classList.toggle('dark');
        });

    </script>

</body>

</html>