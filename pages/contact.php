<?php 

require "../config/connexion.php";
require "../fonctions.php";

enregistrerVisite($pdo);

$erreurs = [];

/* =========================
    LOGIQUE PHP
========================= */
$succes = false;
$nom = ''; $email = ''; $sujet = ''; $message = '';

$succes_projet = false;
$client_nom = ''; $client_email = ''; $type_projet = ''; $budget = ''; $details = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    
    // Traitement du Formulaire de Contact Classique
    if (isset($_POST['envoyer_contact'])) {
        $nom = nettoyer($_POST['name'] ?? '');
        $email = nettoyer($_POST['email'] ?? '');
        $sujet = nettoyer($_POST['subject'] ?? '');
        $message = nettoyer($_POST['message'] ?? '');
        
        if (!champ_requis($nom)) { $erreurs['nom'] = "Nom obligatoire"; }
        if (!filter_var($email, FILTER_VALIDATE_EMAIL)) { $erreurs['email'] = "Email invalide"; }
        if (!champ_requis($message)) { $erreurs['message'] = "Message obligatoire"; }
        
        if (empty($erreurs)) {
            try {
                // Insertion sécurisée dans la table contacts
                $sql = "INSERT INTO contacts (nom, email, sujet, message) VALUES (:nom, :email, :sujet, :message)";
                $stmt = $pdo->prepare($sql);
                $stmt->execute([
                    ':nom'     => $nom,
                    ':email'   => $email,
                    ':sujet'   => $sujet,
                    ':message' => $message
                ]);
                
                $succes = true;
                // On réinitialise les champs après succès
                $nom = ''; $email = ''; $sujet = ''; $message = '';
            } catch (PDOException $e) {
                $erreurs['global'] = "Erreur lors de l'enregistrement : " . $e->getMessage();
            }
        }
    }
    
    // Traitement du Formulaire de Demande de Projet
    if (isset($_POST['envoyer_projet'])) {
        $client_nom = nettoyer($_POST['client-name'] ?? '');
        $client_email = nettoyer($_POST['client-email'] ?? '');
        $type_projet = nettoyer($_POST['project-type'] ?? '');
        $budget = nettoyer($_POST['budget'] ?? '');
        $details = nettoyer($_POST['details'] ?? '');
        
        if (!champ_requis($client_nom)) { $erreurs['client-name'] = "Nom obligatoire"; }
        if (!filter_var($client_email, FILTER_VALIDATE_EMAIL)) { $erreurs['client-email'] = "Email invalide"; }
        if (!champ_requis($type_projet)) { $erreurs['project-type'] = "Choisissez un type de projet"; }
        if (!champ_requis($details)) { $erreurs['details'] = "Description obligatoire"; }
        
        if (empty($erreurs)) {
            try {
                // Insertion sécurisée dans la table demandes_projets
                $sql = "INSERT INTO demandes_projets (client_nom, client_email, type_projet, budget, details) 
                        VALUES (:client_nom, :client_email, :type_projet, :budget, :details)";
                $stmt = $pdo->prepare($sql);
                $stmt->execute([
                    ':client_nom'   => $client_nom,
                    ':client_email' => $client_email,
                    ':type_projet'  => $type_projet,
                    ':budget'       => $budget,
                    ':details'      => $details
                ]);
                
                $succes_projet = true;
                // On réinitialise les champs après succès
                $client_nom = ''; $client_email = ''; $type_projet = ''; $budget = ''; $details = '';
            } catch (PDOException $e) {
                $erreurs['global_projet'] = "Erreur lors de l'enregistrement : " . $e->getMessage();
            }
        }
    }
}
?>

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Contact - Mon Portfolio</title>
    <link rel="stylesheet" href="../css/style.css">
    
    <style>
        body {
            background-color: #3e2723 !important;
            color: #f1f1f1 !important;
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            margin: 0;
            padding: 0;
        }

        /* HEADER HARMONISÉ */
        header {
            background-color: #a0522d; 
            padding: 10px 0;
        }

        nav {
            display: flex;
            justify-content: space-between;
            align-items: center;
            max-width: 1200px;
            margin: 0 auto;
            padding: 0 20px;
        }

        nav h1 {
            color: #f1d2a9;
            margin: 0;
            font-size: 1.8rem;
        }

        nav ul {
            list-style: none;
            display: flex;
            gap: 30px;
            margin: 0;
            padding: 0;
        }

        nav ul li a {
            color: #f1d2a9;
            text-decoration: none;
            font-weight: bold;
            font-size: 1.1rem;
            transition: 0.3s;
        }

        nav ul li a:hover, nav ul li a.active {
            color: white;
            border-bottom: 2px solid white;
        }

        /* FORMULAIRES */
        h1, h2, h3 { color: #f1d2a9 !important; }

        .contact form, .project-form {
            background: #4e342e !important;
            padding: 35px;
            border-radius: 15px;
            max-width: 700px;
            margin: 0 auto 40px auto;
            border: 1px solid #5d4037;
            box-shadow: 0 10px 25px rgba(0,0,0,0.4);
        }

        label {
            display: block;
            text-align: left;
            margin-top: 20px;
            color: #f1d2a9 !important;
            font-weight: bold;
        }

        input, textarea, select {
            width: 100%;
            padding: 14px;
            margin-top: 8px;
            border-radius: 20px; 
            border: 1px solid #f1d2a9;
            background: #2b1b17 !important;
            color: #ffffff !important;
            font-size: 1rem;
            box-sizing: border-box;
        }

        button {
            background-color: #a0522d !important;
            color: #ffffff !important;
            padding: 16px;
            border: none;
            border-radius: 25px;
            cursor: pointer;
            font-weight: bold;
            width: 100%;
            margin-top: 25px;
            transition: 0.3s;
        }

        button:hover {
            background-color: #f1d2a9 !important;
            color: #3e2723 !important;
        }

        .success-message {
            background-color: rgba(139, 195, 74, 0.15) !important;
            border: 2px solid #8bc34a !important;
            border-radius: 10px;
            padding: 20px;
            margin: 20px auto;
            max-width: 700px;
            color: #ffffff !important;
        }

        .error-message { color: #ffab91 !important; font-size: 0.9rem; display: block; margin-top: 5px; text-align: left; }
    </style>
</head>

<body>

    <header>
        <nav>
            <h1>Mon Portfolio</h1>
            <ul>
                <li><a href="../index.php">Accueil</a></li>
                <li><a href="projet.php">Projets</a></li>
                <li><a href="contact.php" class="active">Contact</a></li>
            </ul>
        </nav>
    </header>

    <section class="contact" style="padding: 50px 20px; text-align: center;">

        <h2 style="font-size: 2.5rem; margin-bottom: 10px;">Contactez-moi</h2>
        
        <?php if (isset($erreurs['global'])): ?>
            <p style="color: #ffab91; font-weight: bold;"><?= $erreurs['global'] ?></p>
        <?php endif; ?>

        <?php if ($succes): ?>
            <div class="success-message">
                <h3 style="color: #8bc34a !important; margin-top:0;">Message envoyé avec succès ✅</h3>
                <p>Merci, votre message a bien été enregistré dans notre base de données.</p>
            </div>
        <?php endif; ?>

        <form method="POST" action="contact.php">
            <label>Nom :</label>
            <input type="text" name="name" placeholder="Votre nom" value="<?= htmlspecialchars($nom) ?>">
            <?php if (isset($erreurs['nom'])): ?> <span class="error-message">⚠️ <?= $erreurs['nom'] ?></span> <?php endif; ?>

            <label>Email :</label>
            <input type="email" name="email" placeholder="Votre email" value="<?= htmlspecialchars($email) ?>">
            <?php if (isset($erreurs['email'])): ?> <span class="error-message">⚠️ <?= $erreurs['email'] ?></span> <?php endif; ?>

            <label>Sujet :</label>
            <input type="text" name="subject" placeholder="Sujet" value="<?= htmlspecialchars($sujet) ?>">

            <label>Message :</label>
            <textarea name="message" rows="5" placeholder="Votre message..." style="border-radius: 15px;"><?= htmlspecialchars($message) ?></textarea>
            <?php if (isset($erreurs['message'])): ?> <span class="error-message">⚠️ <?= $erreurs['message'] ?></span> <?php endif; ?>

            <button type="submit" name="envoyer_contact">Envoyer</button>
        </form>

        <div style="margin: 60px 0;">
            <hr style="border: 0; height: 1px; background: #5d4037; width: 60%; margin: 0 auto;">
        </div>

        <h2>Demande de projet</h2>

        <?php if (isset($erreurs['global_projet'])): ?>
            <p style="color: #ffab91; font-weight: bold;"><?= $erreurs['global_projet'] ?></p>
        <?php endif; ?>

        <?php if ($succes_projet): ?>
            <div class="success-message">
                <h3 style="color: #8bc34a !important; margin-top:0;">Demande projet reçue ✅</h3>
                <p>Merci, votre demande de projet a bien été ajoutée à notre base de données !</p>
            </div>
        <?php endif; ?>

        <form class="project-form" method="POST" action="contact.php">
            <label>Nom / Entreprise :</label>
            <input type="text" name="client-name" placeholder="Nom complet" value="<?= htmlspecialchars($client_nom) ?>">
            <?php if (isset($erreurs['client-name'])): ?> <span class="error-message">⚠️ <?= $erreurs['client-name'] ?></span> <?php endif; ?>

            <label>Email professionnel :</label>
            <input type="email" name="client-email" placeholder="email@exemple.com" value="<?= htmlspecialchars($client_email) ?>">
            <?php if (isset($erreurs['client-email'])): ?> <span class="error-message">⚠️ <?= $erreurs['client-email'] ?></span> <?php endif; ?>

            <label>Type de projet :</label>
            <select name="project-type" style="border-radius: 20px;">
                <option value="">-- Sélectionner --</option>
                <option value="Site web" <?= $type_projet == "Site web" ? "selected" : "" ?>>Site web</option>
                <option value="Application" <?= $type_projet == "Application" ? "selected" : "" ?>>Application Mobile / Web</option>
                <option value="Design" <?= $type_projet == "Design" ? "selected" : "" ?>>UI/UX Design</option>
            </select>
            <?php if (isset($erreurs['project-type'])): ?> <span class="error-message">⚠️ <?= $erreurs['project-type'] ?></span> <?php endif; ?>

            <label>Budget estimé :</label>
            <input type="text" name="budget" placeholder="Ex: 500 000 FCFA" value="<?= htmlspecialchars($budget) ?>">

            <label>Description détaillée :</label>
            <textarea name="details" rows="5" placeholder="Décrivez votre projet..." style="border-radius: 15px;"><?= htmlspecialchars($details) ?></textarea>
            <?php if (isset($erreurs['details'])): ?> <span class="error-message">⚠️ <?= $erreurs['details'] ?></span> <?php endif; ?>

            <button type="submit" name="envoyer_projet">Envoyer la demande</button>
        </form>

    </section>

    <footer style="text-align: center; padding: 40px; background: #2b1b17; border-top: 1px solid #4e342e;">
        <p>© 2026 Cheikh Mourtada Ndao - Tous droits réservés</p>
    </footer>

</body>
</html>