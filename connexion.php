<?php
session_start();
require 'config.php';

$erreur = '';
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $email = trim($_POST['email'] ?? '');
    $mot_de_passe = $_POST['mot_de_passe'] ?? '';

    if (empty($email) || empty($mot_de_passe)) {
        $erreur = "Tous les champs sont obligatoires.";
    } else {
        $stmt = $pdo->prepare("SELECT * FROM utilisateurs WHERE email = ?");
        $stmt->execute([$email]);
        $user = $stmt->fetch();

        if ($user && password_verify($mot_de_passe, $user['mot_de_passe'])) {
            $_SESSION['utilisateur_id'] = $user['id'];
            $_SESSION['nom'] = $user['nom'];
            header("Location: user_dashboard.php");
            exit;
        } else {
            $erreur = "Email ou mot de passe incorrect.";
        }
    }
}
?>

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1" />
    <title>Connexion - Studi Glace</title>
    <link rel="stylesheet" href="style.css" />
    <style>
        body {
            font-family: Arial, sans-serif;
            background: #f9f9f9;
        }
        .connexion-container {
            max-width: 400px;
            margin: 80px auto;
            background: white;
            padding: 30px;
            box-shadow: 0 0 10px #ddd;
            border-radius: 8px;
        }
        h2 {
            text-align: center;
            margin-bottom: 25px;
            color: #333;
        }
        input[type="email"],
        input[type="password"] {
            width: 100%;
            padding: 12px 10px;
            margin-bottom: 15px;
            border: 1px solid #ccc;
            border-radius: 5px;
            font-size: 16px;
        }
        button {
            width: 100%;
            background-color: #3498db;
            color: white;
            border: none;
            padding: 12px;
            font-size: 18px;
            border-radius: 5px;
            cursor: pointer;
        }
        button:hover {
            background-color: #2980b9;
        }
        .erreur {
            color: #e74c3c;
            margin-bottom: 15px;
            text-align: center;
        }
        .lien-inscription {
            margin-top: 15px;
            text-align: center;
        }
        .lien-inscription a {
            color: #3498db;
            text-decoration: none;
        }
        .lien-inscription a:hover {
            text-decoration: underline;
        }
        .btn-admin {
            display: block;
            margin: 20px auto 0 auto; /* centre horizontalement avec margin top 20px */
            width: 200px;
            background-color: #d6336c; /* un joli rose foncé */
            color: white;
            font-weight: bold;
            font-size: 16px;
            border-radius: 30px;
            padding: 12px 0;
            text-align: center;
            text-decoration: none;
            transition: background-color 0.4s ease, transform 0.3s ease;
            box-shadow: 0 4px 8px rgba(214, 51, 108, 0.4);
        }

        .btn-admin i {
            margin-right: 8px;
            transition: transform 0.3s ease;
        }

        .btn-admin:hover {
            background-color: #b02a58;
            transform: scale(1.05);
            box-shadow: 0 6px 12px rgba(176, 42, 88, 0.6);
        }

        .btn-admin:hover i {
            transform: rotate(15deg);
        }

    </style>
</head>
<body>
<div class="connexion-container">
    <h2>Se connecter</h2>
    <?php if ($erreur): ?>
        <div class="erreur"><?= htmlspecialchars($erreur) ?></div>
    <?php endif; ?>
    <form method="POST" action="connexion.php">
        <input type="email" name="email" placeholder="Adresse email" required value="<?= htmlspecialchars($_POST['email'] ?? '') ?>" />
        <input type="password" name="mot_de_passe" placeholder="Mot de passe" required />
        <button type="submit">Connexion</button>
        <!-- Bouton Connexion Admin -->
        <a href="admin/admin_connexion.php" class="btn-admin">
            <i class="bi bi-shield-lock-fill"></i> Connexion Admin
        </a>
    </form>
    <div class="lien-inscription">
        <p>Pas encore inscrit ? <a href="inscription.php">Créer un compte</a></p>
    </div>
</div>
</body>
</html>
