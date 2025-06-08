<?php
session_start();
require_once '../config.php';  // adapte le chemin selon ton projet

// Vérifier que l'admin est connecté
if (!isset($_SESSION['admin_id'])) {
    header('Location: ../connexion.php');
    exit;
}

$erreur = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $nom = trim($_POST['nom'] ?? '');
    $description = trim($_POST['description'] ?? '');
    $prix = trim($_POST['prix'] ?? '');

    // Vérifie que tous les champs sont remplis
    if ($nom === '' || $description === '' || $prix === '') {
        $erreur = "Tous les champs sont obligatoires.";
    } elseif (!isset($_FILES['image']) || $_FILES['image']['error'] !== 0) {
        $erreur = "Veuillez sélectionner une image valide.";
    } else {
        // Dossier cible absolu (chemin réel sur le serveur)
        $targetDir = __DIR__ . '/../assets/image/';

        // Crée le dossier s'il n'existe pas
        if (!is_dir($targetDir)) {
            mkdir($targetDir, 0755, true);
        }

        // Nom de fichier unique
        $nomFichier = time() . '_' . basename($_FILES['image']['name']);
        $targetFile = $targetDir . $nomFichier;

        // Déplacer le fichier uploadé
        if (move_uploaded_file($_FILES['image']['tmp_name'], $targetFile)) {
            // Chemin relatif à enregistrer en BDD
            $cheminImageBDD = 'assets/image/' . $nomFichier;

            // Insertion en base
            $stmt = $pdo->prepare("INSERT INTO produits (nom, description, prix, image) VALUES (?, ?, ?, ?)");
            $stmt->execute([$nom, $description, $prix, $cheminImageBDD]);

            // Redirection vers la liste des produits
            header('Location: produits.php');
            exit;
        } else {
            $erreur = "Erreur lors du téléchargement de l'image.";
        }
    }
}
?>

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <title>Ajouter un Produit - Admin</title>
    <link rel="stylesheet" href="../style.css">
    <style>
        form {
            max-width: 500px;
            margin: 40px auto;
            background: #f9f9f9;
            padding: 20px;
            border-radius: 10px;
        }
        input, textarea {
            width: 100%;
            margin-bottom: 15px;
            padding: 10px;
            border-radius: 5px;
            border: 1px solid #ccc;
        }
        button {
            background-color: #2ecc71;
            color: white;
            border: none;
            padding: 10px 20px;
            border-radius: 5px;
            cursor: pointer;
        }
        h2 {
            text-align: center;
        }
        .error {
            color: red;
            text-align: center;
            margin-bottom: 15px;
        }
    </style>
</head>
<body>
<form method="POST" enctype="multipart/form-data">
    <h2>Ajouter un Produit</h2>
    <?php if ($erreur): ?>
        <div class="error"><?= htmlspecialchars($erreur) ?></div>
    <?php endif; ?>
    <input type="text" name="nom" placeholder="Nom du produit" required value="<?= htmlspecialchars($_POST['nom'] ?? '') ?>">
    <textarea name="description" placeholder="Description" required><?= htmlspecialchars($_POST['description'] ?? '') ?></textarea>
    <input type="number" step="0.01" name="prix" placeholder="Prix (€)" required value="<?= htmlspecialchars($_POST['prix'] ?? '') ?>">
    <input type="file" name="image" accept="image/*" required>
    <button type="submit">Ajouter</button>
</form>
</body>
</html>
