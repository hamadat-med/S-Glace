<?php
session_start();
require 'config.php';

$erreur = '';
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $nom = trim($_POST['nom'] ?? '');
    $email = trim($_POST['email'] ?? '');
    $mot_de_passe = $_POST['mot_de_passe'] ?? '';
    $mot_de_passe_confirm = $_POST['mot_de_passe_confirm'] ?? '';

    // Vérifications simples
    if (empty($nom) || empty($email) || empty($mot_de_passe) || empty($mot_de_passe_confirm)) {
        $erreur = "Tous les champs sont obligatoires.";
    } elseif (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        $erreur = "L'adresse email n'est pas valide.";
    } elseif ($mot_de_passe !== $mot_de_passe_confirm) {
        $erreur = "Les mots de passe ne correspondent pas.";
    } else {
        // Vérifier si email existe déjà
        $stmt = $pdo->prepare("SELECT id FROM utilisateurs WHERE email = ?");
        $stmt->execute([$email]);
        if ($stmt->fetch()) {
            $erreur = "Cet email est déjà utilisé.";
        } else {
            // Insérer utilisateur
            $hash = password_hash($mot_de_passe, PASSWORD_DEFAULT);
            $stmt = $pdo->prepare("INSERT INTO utilisateurs (nom, email, mot_de_passe) VALUES (?, ?, ?)");
            $stmt->execute([$nom, $email, $hash]);

            $_SESSION['user_id'] = $pdo->lastInsertId();
            $_SESSION['nom'] = $nom;
            header('Location: index.php');
            exit;
        }
    }
}
?>

<!DOCTYPE html>
<html lang="fr">
<head>
    <title>Studi Glace - Accueil</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css">
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
    <style>
        body {
            font-family: Arial, sans-serif;
            background: #f9f9f9;
        }
        .inscription-container {
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
        input[type="text"],
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
        .lien-connexion {
            margin-top: 15px;
            text-align: center;
        }
        .lien-connexion a {
            color: #3498db;
            text-decoration: none;
        }
        .lien-connexion a:hover {
            text-decoration: underline;
        }
    </style>
</head>
<body>

<?php include 'assets/navbar.php'; ?>

<div class="inscription-container">
    <h2>Créer un compte</h2>
    <?php if ($erreur): ?>
        <div class="erreur"><?= htmlspecialchars($erreur) ?></div>
    <?php endif; ?>
    <form method="POST" action="inscription.php">
        <input type="text" name="nom" placeholder="Nom complet" required value="<?= htmlspecialchars($_POST['nom'] ?? '') ?>" />
        <input type="email" name="email" placeholder="Adresse email" required value="<?= htmlspecialchars($_POST['email'] ?? '') ?>" />
        <input type="password" name="mot_de_passe" placeholder="Mot de passe" required />
        <input type="password" name="mot_de_passe_confirm" placeholder="Confirmez le mot de passe" required />
        <button type="submit">S'inscrire</button>
    </form>
    <div class="lien-connexion">
        <p>Déjà inscrit ? <a href="connexion.php">Se connecter</a></p>
    </div>
</div>

<?php include 'assets/footer.php'; ?>
</body>
</html>
