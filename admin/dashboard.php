<?php
session_start();

// Protection de la page : si l'admin n'est pas connecté, on le renvoie au login
if (!isset($_SESSION['admin_connecte']) || $_SESSION['admin_connecte'] !== true) {
    header("Location: login.php");
    exit;
}

require "../config/connexion.php";

// 1. Récupération des statistiques des visites
$total_visites = $pdo->query("SELECT COUNT(*) FROM visites")->fetchColumn();
$visites_par_page = $pdo->query("SELECT page, COUNT(*) as total FROM visites GROUP BY page ORDER BY total DESC")->fetchAll();

// 2. Récupération des messages de contact (Table `contacts`)
$messages_contact = $pdo->query("SELECT * FROM contacts ORDER BY date_envoi DESC")->fetchAll();

// 3. Récupération des demandes de projets (Table `demandes_projets`)
$demandes_projets = $pdo->query("SELECT * FROM demandes_projets ORDER BY date_demande DESC")->fetchAll();

// 4. Récupération de la liste des projets pour la suppression
$liste_projets_admin = $pdo->query("SELECT id, titre, technologies FROM projets ORDER BY id DESC")->fetchAll();
?>
<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Tableau de Bord - Administration</title>
    <link rel="stylesheet" href="../css/style.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <style>
        body {
            background-color: #35211e;
            color: #fff;
            padding: 20px;
        }
        .admin-header {
            display: flex;
            justify-content: space-between;
            align-items: center;
            border-bottom: 2px solid #5d4037;
            padding-bottom: 15px;
            margin-bottom: 30px;
            flex-wrap: wrap;
            gap: 15px;
        }
        .admin-header h1 { color: #f1d2a9; margin: 0; }
        .btn-logout {
            background: #d32f2f;
            color: white;
            padding: 10px 15px;
            text-decoration: none;
            border-radius: 5px;
            font-weight: bold;
            display: inline-flex;
            align-items: center;
            gap: 8px;
        }
        .btn-logout:hover { background: #b71c1c; }
        
        /* Grille des statistiques */
        .stats-grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(250px, 1fr));
            gap: 20px;
            margin-bottom: 40px;
        }
        .stat-card {
            background: #4e342e;
            padding: 20px;
            border-radius: 10px;
            box-shadow: 0 4px 15px rgba(0,0,0,0.2);
        }
        .stat-card h3 { color: #f1d2a9; margin-bottom: 10px; }
        .stat-number { font-size: 2rem; font-weight: bold; color: #fff; }

        /* Tables de données */
        .data-section {
            background: #4e342e;
            padding: 25px;
            border-radius: 10px;
            margin-bottom: 40px;
            box-shadow: 0 4px 15px rgba(0,0,0,0.2);
            overflow-x: auto;
        }
        .data-section h2 {
            color: #f1d2a9;
            margin-bottom: 20px;
            border-bottom: 1px solid #5d4037;
            padding-bottom: 10px;
        }
        table {
            width: 100%;
            border-collapse: collapse;
            text-align: left;
        }
        th, td {
            padding: 12px 15px;
            border-bottom: 1px solid #5d4037;
        }
        th { background-color: #35211e; color: #f1d2a9; }
        tr:hover { background-color: #5d4037; }
        .date-col { font-size: 0.85rem; color: #f1d2a9; }
    </style>
</head>
<body>

    <header class="admin-header">
        <h1><i class="fas fa-tachometer-alt"></i> Panel Administration</h1>
        <div style="display: flex; align-items: center; gap: 15px; flex-wrap: wrap;">
            <span>Bienvenue, <strong><?= htmlspecialchars($_SESSION['admin_nom']) ?></strong></span>
            <a href="../index.php" class="btn" style="background: #a0522d; padding: 10px 15px; text-decoration: none; border-radius: 5px; font-weight: bold; color: white;">Voir le site</a>
            <a href="logout.php" class="btn-logout"><i class="fas fa-sign-out-alt"></i> Déconnexion</a>
        </div>
    </header>

    <div style="margin-bottom: 25px;">
        <a href="ajouter-projet.php" class="btn" style="background: #a0522d; padding: 12px 20px; text-decoration: none; border-radius: 8px; font-weight: bold; color: white; display: inline-flex; align-items: center; gap: 8px;">
            <i class="fas fa-plus"></i> Ajouter un nouveau projet en ligne
        </a>
    </div>

    <div class="stats-grid">
        <div class="stat-card">
            <h3>Total des Visites uniques</h3>
            <div class="stat-number"><?= $total_visites ?></div>
        </div>
        
        <div class="stat-card">
            <h3>Visites par Page</h3>
            <ul style="list-style: none; padding: 0; margin-top: 10px;">
                <?php foreach ($visites_par_page as $vp): ?>
                    <li style="display: flex; justify-content: space-between; padding: 5px 0; border-bottom: 1px dashed #5d4037;">
                        <span><?= htmlspecialchars($vp['page']) ?></span>
                        <strong style="color: #f1d2a9;"><?= $vp['total'] ?></strong>
                    </li>
                <?php endforeach; ?>
            </ul>
        </div>
    </div>

    <section class="data-section">
        <h2><i class="fas fa-tasks"></i> Liste des Projets en Ligne (Suppression)</h2>
        <?php if (empty($liste_projets_admin)): ?>
            <p>Aucun projet publié pour le moment.</p>
        <?php else: ?>
            <table>
                <thead>
                    <tr>
                        <th>Titre du Projet</th>
                        <th>Technologies</th>
                        <th style="text-align: center;">Action</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($liste_projets_admin as $p_admin): ?>
                        <tr>
                            <td><strong><?= htmlspecialchars($p_admin['titre']) ?></strong></td>
                            <td><span style="background: #a0522d; padding: 3px 8px; border-radius: 4px; font-size: 0.85rem; color: white;"><?= htmlspecialchars($p_admin['technologies'] ?? 'Non spécifié') ?></span></td>
                            <td style="text-align: center;">
                                <a href="supprimer-projet.php?id=<?= $p_admin['id'] ?>" 
                                   onclick="return confirm('Êtes-vous sûr de vouloir supprimer ce projet ?');" 
                                   style="background: #d32f2f; color: white; padding: 6px 12px; text-decoration: none; border-radius: 5px; font-weight: bold; font-size: 0.9rem; display: inline-flex; align-items: center; gap: 5px;">
                                    <i class="fas fa-trash-alt"></i> Supprimer
                                </a>
                            </td>
                        </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        <?php endif; ?>
    </section>

    <section class="data-section">
        <h2><i class="fas fa-envelope"></i> Messages reçus (Contactez-moi)</h2>
        <?php if (empty($messages_contact)): ?>
            <p>Aucun message reçu pour le moment.</p>
        <?php else: ?>
            <table>
                <thead>
                    <tr>
                        <th>Nom</th>
                        <th>Email</th>
                        <th>Message</th>
                        <th>Date</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($messages_contact as $msg): ?>
                        <tr>
                            <td><strong><?= htmlspecialchars($msg['nom']) ?></strong></td>
                            <td><?= htmlspecialchars($msg['email']) ?></td>
                            <td><?= nl2br(htmlspecialchars($msg['message'])) ?></td>
                            <td class="date-col"><?= $msg['date_envoi'] ?></td>
                        </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        <?php endif; ?>
    </section>

    <section class="data-section">
        <h2><i class="fas fa-folder-open"></i> Demandes de Projets Clients</h2>
        <?php if (empty($demandes_projets)): ?>
            <p>Aucune demande de projet soumise.</p>
        <?php else: ?>
            <table>
                <thead>
                    <tr>
                        <th>Client</th>
                        <th>Email</th>
                        <th>Type de projet</th>
                        <th>Budget estimé</th>
                        <th>Détails / Cahier des charges</th>
                        <th>Date</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($demandes_projets as $demande): ?>
                        <tr>
                            <td><strong><?= htmlspecialchars($demande['client_nom']) ?></strong></td>
                            <td><?= htmlspecialchars($demande['client_email']) ?></td>
                            <td><span style="background: #a0522d; padding: 3px 8px; border-radius: 4px; font-size: 0.85rem;"><?= htmlspecialchars($demande['type_projet']) ?></span></td>
                            <td style="color: #f1d2a9; font-weight: bold;"><?= htmlspecialchars($demande['budget'] ?? 'Non spécifié') ?></td>
                            <td><?= nl2br(htmlspecialchars($demande['details'])) ?></td>
                            <td class="date-col"><?= $demande['date_demande'] ?></td>
                        </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        <?php endif; ?>
    </section>

</body>
</html>