<?php
session_start();
require '../config.php';

// Vérifier que l'admin est connecté
if (!isset($_SESSION['admin_id'])) {
    header("Location: ../admin/admin_produits.php");
    exit;
}

// Supprimer un produit (si demandé)
if (isset($_GET['supprimer_id'])) {
    $supprimer_id = (int)$_GET['supprimer_id'];
    $stmt = $pdo->prepare("DELETE FROM produits WHERE id = ?");
    $stmt->execute([$supprimer_id]);
    header("Location: admin_produits.php");
    exit;
}

// Récupérer tous les produits
$produits = $pdo->query("SELECT * FROM produits ORDER BY id DESC")->fetchAll();
?>

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8" />
    <title>Gestion Produits - Admin Studi Glace</title>
    <meta name="viewport" content="width=device-width, initial-scale=1" />
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons/font/bootstrap-icons.css">

    <style>
        body {
            background-color: #f8f9fa;
        }
        h1 {
            margin-top: 20px;
        }
        .container {
            background: white;
            padding: 30px;
            margin-top: 30px;
            border-radius: 10px;
            box-shadow: 0px 4px 10px rgba(0,0,0,0.05);
        }
        .table img {
            height: 60px;
            width: auto;
            border-radius: 5px;
        }
        .btn-supprimer {
            background-color: #dc3545;
            color: white;
        }
        .btn-supprimer:hover {
            background-color: #c82333;
        }
    </style>
</head>
<body>

<div class="container">
    <h1 class="text-center text-primary">Gestion des Produits</h1>
    <div class="text-end mb-3">
        <a href="dashboard.php" class="btn btn-secondary"><i class="bi bi-arrow-left"></i> Retour au tableau de bord</a>
        <a href="ajouter_produit.php" class="btn btn-success"><i class="bi bi-plus-circle"></i> Ajouter un produit</a>
    </div>

    <h2 class="text-center mb-4">Liste des produits</h2>

    <div class="table-responsive">
        <table class="table table-striped align-middle text-center">
            <thead class="table-primary">
            <tr>
                <th>ID</th>
                <th>Nom</th>
                <th>Description</th>
                <th>Prix (€)</th>
                <th>Image</th>
                <th>Action</th>
            </tr>
            </thead>
            <tbody>
            <?php foreach ($produits as $p): ?>
                <tr>
                    <td><?= $p['id'] ?></td>
                    <td><?= htmlspecialchars($p['nom']) ?></td>
                    <td><?= nl2br(htmlspecialchars($p['description'])) ?></td>
                    <td><?= number_format($p['prix'], 2) ?></td>
                    <td>
                        <img src="../<?= htmlspecialchars($p['image']) ?>" alt="<?= htmlspecialchars($p['nom']) ?>">
                    </td>
                    <td>
                        <a href="?supprimer_id=<?= $p['id'] ?>" class="btn btn-supprimer btn-sm"
                           onclick="return confirm('Supprimer ce produit ?')">
                            <i class="bi bi-trash3-fill"></i> Supprimer
                        </a>
                    </td>
                </tr>
            <?php endforeach; ?>
            </tbody>
        </table>
    </div>
</div>

</body>
</html>
