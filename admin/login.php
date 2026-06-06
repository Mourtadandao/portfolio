<?php
session_start();
require "../config/connexion.php";

$erreur = "";

if ($_SERVER["REQUEST_METHOD"] === "POST") {
    $identifiant = trim($_POST['identifiant']);
    $mot_de_passe = trim($_POST['mot_de_passe']);

    if (!empty($identifiant) && !empty($mot_de_passe)) {
        // Recherche de l'utilisateur
        $stmt = $pdo->prepare("SELECT * FROM utilisateurs WHERE identifiant = ?");
        $stmt->execute([$identifiant]);
        $user = $stmt->fetch();

        // Vérification du mot de passe haché
        if ($user && password_verify($mot_de_passe, $user['mot_de_passe'])) {
            $_SESSION['admin_connecte'] = true;
            $_SESSION['admin_id'] = $user['id'];
            $_SESSION['admin_nom'] = $user['identifiant'];
            
            // Redirection vers le tableau de bord
            header("Location: dashboard.php");
            exit;
        } else {
            $erreur = "Identifiant ou mot de passe incorrect.";
        }
    } else {
        $erreur = "Veuillez remplir tous les champs.";
    }
}
?>
<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Connexion Administration</title>
    <link rel="stylesheet" href="../css/style.css">
    <style>
        .login-body {
            display: flex;
            justify-content: center;
            align-items: center;
            min-height: 100vh;
            background-color: #35211e;
        }
        .login-card {
            background: #4e342e;
            padding: 40px;
            border-radius: 15px;
            box-shadow: 0 8px 25px rgba(0,0,0,0.4);
            width: 100%;
            max-width: 400px;
            text-align: center;
        }
        .login-card h2 {
            color: #f1d2a9;
            margin-bottom: 25px;
        }
        .form-group {
            margin-bottom: 20px;
            text-align: left;
        }
        .form-group label {
            display: block;
            color: #f1d2a9;
            margin-bottom: 8px;
            font-weight: bold;
        }
        .form-group input {
            width: 100%;
            padding: 12px;
            border: 1px solid #5d4037;
            border-radius: 8px;
            background: #35211e;
            color: #fff;
            box-sizing: border-box;
        }
        .btn-login {
            width: 100%;
            background: #a0522d;
            color: white;
            padding: 12px;
            border: none;
            border-radius: 8px;
            font-weight: bold;
            cursor: pointer;
            transition: background 0.3s;
            font-size: 1rem;
        }
        .btn-login:hover {
            background-color: #f1d2a9;
            color: #3e2723;
        }
        .alert-error {
            background-color: #d32f2f;
            color: white;
            padding: 10px;
            border-radius: 5px;
            margin-bottom: 20px;
            font-size: 0.9rem;
        }
        .back-home {
            display: inline-block;
            margin-top: 20px;
            color: #f1d2a9;
            text-decoration: none;
            font-size: 0.9rem;
        }
        .back-home:hover {
            text-decoration: underline;
        }
    </style>
</head>
<body class="login-body">

    <div class="login-card">
        <h2>Connexion Admin</h2>
        
        <?php if (!empty($erreur)): ?>
            <div class="alert-error"><?= htmlspecialchars($erreur) ?></div>
        <?php endif; ?>

        <form action="login.php" method="POST">
            <div class="form-group">
                <label for="identifiant">Identifiant</label>
                <input type="text" id="identifiant" name="identifiant" required autocomplete="username">
            </div>
            
            <div class="form-group">
                <label for="mot_de_passe">Mot de passe</label>
                <input type="password" id="mot_de_passe" name="mot_de_passe" required autocomplete="current-password">
            </div>
            
            <button type="submit" class="btn-login">Se connecter</button>
        </form>

        <a href="../index.php" class="back-home"><i class="fas fa-arrow-left"></i> Retour au site</a>
    </div>

</body>
</html>