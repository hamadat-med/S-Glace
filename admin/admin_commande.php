<?php
session_start();
require_once '../config.php';



// Récupérer toutes les commandes
$stmt = $pdo->query("SELECT c.id, c.date_commande, c.statut, c.total, u.nom, u.email 
                     FROM commandes c 
                     JOIN utilisateurs u ON c.utilisateur_id = u.id 
                     ORDER BY c.date_commande DESC");
$commandes = $stmt->fetchAll();
?>

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <title>Gestion des commandes</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
        body {
            background-color: #fff0f6;
            font-family: 'Segoe UI', sans-serif;
            padding: 30px;
        }
        .container {
            background: white;
            padding: 30px;
            border-radius: 15px;
            box-shadow: 0 10px 25px rgba(255, 105, 180, 0.2);
            animation: fadeIn 1s ease-in-out;
        }
        h2 {
            text-align: center;
            color: #d6336c;
            margin-bottom: 30px;
            text-shadow: 1px 1px 2px #ffb6d9;
        }
        table thead {
            background-color: #d6336c;
            color: white;
        }
        table tbody tr:hover {
            background-color: #ffe6f0;
            transition: background 0.3s;
        }
        .btn-pink {
            background: #ff66a5;
            color: white;
            padding: 6px 14px;
            border-radius: 20px;
            text-decoration: none;
            transition: 0.3s ease;
            font-size: 0.9rem;
        }
        .btn-pink:hover {
            background: #e60073;
            transform: scale(1.05);
        }
        @keyframes fadeIn {
            from { opacity: 0; transform: translateY(20px); }
            to { opacity: 1; transform: translateY(0); }
        }
    </style>
</head>
<body>
<div class="container">
    <h2>📦 Gestion des commandes</h2>

    <?php if (empty($commandes)) : ?>
        <p class="text-center fs-4">Aucune commande enregistrée.</p>
    <?php else : ?>
        <div class="table-responsive">
            <table class="table table-bordered align-middle text-center">
                <thead>
                <tr>
                    <th>ID</th>
                    <th>Date</th>
                    <th>Utilisateur</th>
                    <th>Email</th>
                    <th>Statut</th>
                    <th>Total</th>
                    <th>Actions</th>
                </tr>
                </thead>
                <tbody>
                <?php foreach ($commandes as $commande): ?>
                    <tr>
                        <td>#<?= $commande['id'] ?></td>
                        <td><?= date('d/m/Y H:i', strtotime($commande['date_commande'])) ?></td>
                        <td><?= htmlspecialchars($commande['nom']) ?></td>
                        <td><?= htmlspecialchars($commande['email']) ?></td>
                        <td><?= htmlspecialchars(ucfirst($commande['statut'])) ?></td>
                        <td><?= number_format($commande['total'] ?? 0, 2) ?> €</td>
                        <td>
                            <a href="../commande/changer_statut.php?id=<?= $commande['id'] ?>" class="btn-pink">Modifier</a>
                            <a href="../commande/supprimer_commande.php?id=<?= $commande['id'] ?>" class="btn btn-danger btn-sm ms-2" onclick="return confirm('Supprimer cette commande ?')">🗑️</a>
                        </td>
                    </tr>
                <?php endforeach; ?>
                </tbody>
            </table>
        </div>
    <?php endif; ?>
</div>

<div class="text-center mt-5">
    <a href="dashboard.php" class="btn-retour-achat">← Retour au tableau de bord</a>
</div>


</body>
</html>
