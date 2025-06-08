<?php
session_start();
require '../config.php';

if (!isset($_SESSION['admin_id'])) {
    header("Location: admin_connexion.php");
    exit;
}


// Suppression du produit
if (isset($_GET['supprimer_id'])) {
    $id = intval($_GET['supprimer_id']);

    // Supprimer l'image si elle existe (optionnel mais recommandé)
    $stmtImage = $pdo->prepare("SELECT image FROM produits WHERE id = ?");
    $stmtImage->execute([$id]);
    $image = $stmtImage->fetchColumn();
    if ($image && file_exists($image)) {
        unlink($image); // Supprime l'image du dossier si elle existe
    }

    // Supprimer le produit de la base de données
    $stmt = $pdo->prepare("DELETE FROM produits WHERE id = ?");
    $stmt->execute([$id]);

    // Redirection pour éviter la suppression multiple en refresh
    header("Location: produits.php");
    exit;
}


// Récupération des produits
$stmt = $pdo->query("SELECT * FROM produits ORDER BY id DESC");
$produits = $stmt->fetchAll();
?>

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <title>Studi Glace - Accueil</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css">
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
    <style>
        body {
            font-family: Arial, sans-serif;
            background: #f4f4f4;
            margin: 0;
            padding: 0;
        }

        header {
            background-color: #e878cc;
            color: white;
            padding: 20px;
            text-align: center;
        }

        .container {
            max-width: 1000px;
            margin: 30px auto;
            background: white;
            padding: 20px;
            border-radius: 10px;
            box-shadow: 0 2px 8px rgba(0,0,0,0.1);
        }

        h2 {
            text-align: center;
            margin-bottom: 30px;
        }

        .ajouter {
            display: block;
            margin-bottom: 20px;
            text-align: right;
        }

        .ajouter a {
            background-color: #28a745;
            color: white;
            padding: 10px 15px;
            text-decoration: none;
            border-radius: 5px;
        }

        table {
            width: 100%;
            border-collapse: collapse;
        }

        table th, table td {
            padding: 12px;
            border: 1px solid #ddd;
            text-align: center;
        }

        table th {
            background-color: #ed43cb;
            color: white;
        }

        .action a {
            margin: 0 5px;
            text-decoration: none;
            padding: 5px 10px;
            border-radius: 5px;
            color: white;
        }

        .modifier {
            background-color: #007bff;
        }

        .supprimer {
            background-color: #dc3545;
        }

        img {
            width: 80px;
            height: 60px;
            object-fit: cover;
            border-radius: 5px;
        }

        footer {
            margin-top: 40px;
            background: #333;
            color: white;
            text-align: center;
            padding: 15px;
        }


        .btn-retour-achat {
            background-color: #ff69b4; /* rose vif */
            border: none;
            color: white;
            font-weight: bold;
            padding: 14px 28px;
            border-radius: 30px;
            font-size: 1.1rem;
            box-shadow: 0 4px 10px rgba(255, 105, 180, 0.4);
            transition: background-color 0.3s ease, transform 0.2s ease, box-shadow 0.3s ease;
            text-decoration: none;
            display: inline-block;
            animation: pulseRetour 2.5s infinite;
        }

        .btn-retour-achat:hover {
            background-color: #ff1493;
            transform: scale(1.05);
            box-shadow: 0 6px 14px rgba(255, 20, 147, 0.6);
        }

        @keyframes pulseRetour {
            0%, 100% {
                box-shadow: 0 4px 10px rgba(255, 105, 180, 0.4);
            }
            50% {
                box-shadow: 0 6px 20px rgba(255, 105, 180, 0.6);
            }
        }

    </style>
</head>
<body>

<header>
    <h1>Gestion des produits - Studi Glace</h1>
</header>

<div class="container">
    <div class="ajouter">
        <a href="ajouter_produit.php">+ Ajouter un produit</a>
    </div>

    <table>
        <thead>
        <tr>
            <th>ID</th>
            <th>Image</th>
            <th>Nom</th>
            <th>Prix (€)</th>
            <th>Description</th>
            <th>Actions</th>
        </tr>
        </thead>
        <tbody>
        <?php foreach ($produits as $produit): ?>
            <tr>
                <td><?= $produit['id'] ?></td>
                <td><img src="<?= htmlspecialchars($produit['image']) ?>" alt="<?= $produit['nom'] ?>"></td>
                <td><?= htmlspecialchars($produit['nom']) ?></td>
                <td><?= number_format($produit['prix'], 2, ',', ' ') ?></td>
                <td><?= htmlspecialchars($produit['description']) ?></td>
                <td class="action">
                    <a class="modifier" href="../admin/produit_modifier.php?id=<?= $produit['id'] ?>">Modifier</a>
                    <a href="?supprimer_id=<?= $produit['id'] ?>" class="supprimer" onclick="return confirm('Supprimer ce produit ?')">Supprimer</a>

                </td>
            </tr>
        <?php endforeach; ?>
        </tbody>
    </table>
</div>

<div class="text-center mt-5">
    <a href="dashboard.php" class="btn-retour-achat">← Retour au tableau de bord</a>
</div>




</body>
</html>

