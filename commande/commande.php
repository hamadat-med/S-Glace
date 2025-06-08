<?php
session_start();
require_once '../config.php'; // adapte le chemin si besoin

// Redirection si non connecté
if (!isset($_SESSION['utilisateur_id'])) {
    header('Location: connexion.php');
    exit();
}

$utilisateur_id = $_SESSION['utilisateur_id'];

// Récupérer les commandes de l'utilisateur
$stmt = $pdo->prepare("SELECT id, date_commande, statut, total FROM commandes WHERE utilisateur_id = ? ORDER BY date_commande DESC");
$stmt->execute([$utilisateur_id]);
$commandes = $stmt->fetchAll();
?>

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8" />
    <title>Mes commandes</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet" />
    <style>
        body {
            background: #fff0f6;
            color: #5a1e5d;
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            padding: 40px 20px;
            animation: fadeIn 1.5s ease forwards;
        }
        .container {
            max-width: 900px;
            background: #fff;
            padding: 40px 30px;
            border-radius: 18px;
            box-shadow: 0 12px 30px rgba(255, 105, 180, 0.25);
            margin: auto;
        }
        h2 {
            color: #d6336c;
            font-weight: 800;
            margin-bottom: 40px;
            text-align: center;
            text-transform: uppercase;
            letter-spacing: 3px;
            text-shadow: 1px 1px 4px #ffa3c0;
            animation: slideDown 1s ease forwards;
        }
        table {
            border-radius: 12px;
            overflow: hidden;
            box-shadow: 0 8px 20px rgba(214, 51, 108, 0.2);
            animation: pulse 3s infinite;
        }
        thead {
            background-color: #d6336c;
            color: white;
            font-weight: 700;
            font-size: 1.1rem;
        }
        tbody tr {
            transition: background-color 0.35s ease, transform 0.3s ease;
            cursor: default;
        }
        tbody tr:hover {
            background-color: #ffe6f0;
            transform: scale(1.02);
            box-shadow: 0 4px 12px rgba(255, 95, 162, 0.3);
        }
        td, th {
            padding: 16px 20px;
            text-align: center;
            vertical-align: middle;
            font-size: 1rem;
        }
        .btn-retour {
            display: block;
            width: 260px;
            margin: 30px auto 0;
            background: #ff99c8;
            color: #5a1e5d;
            font-weight: 700;
            padding: 14px 0;
            border-radius: 35px;
            text-align: center;
            text-decoration: none;
            box-shadow: 0 4px 10px rgba(255, 153, 200, 0.6);
            transition: background 0.3s ease, transform 0.3s ease;
            animation: pulse 2.5s infinite;
            user-select: none;
            font-size: 1.15rem;
        }
        .btn-retour:hover {
            background: #ff5fa2;
            color: white;
            transform: scale(1.08);
            box-shadow: 0 8px 20px rgba(255, 95, 162, 0.9);
        }

        /* Animations */
        @keyframes fadeIn {
            from {opacity: 0;}
            to {opacity: 1;}
        }
        @keyframes slideDown {
            from {opacity: 0; transform: translateY(-20px);}
            to {opacity: 1; transform: translateY(0);}
        }
        @keyframes pulse {
            0%, 100% {
                box-shadow: 0 4px 15px rgba(214, 51, 108, 0.6);
            }
            50% {
                box-shadow: 0 6px 30px rgba(255, 95, 162, 0.9);
            }
        }
    </style>
</head>
<body>
<div class="container">
    <h2>Mes commandes</h2>

    <?php if (empty($commandes)) : ?>
        <p class="text-center fs-4">Vous n'avez aucune commande pour le moment.</p>
    <?php else : ?>
        <table class="table table-bordered">
            <thead>
            <tr>
                <th>Numéro</th>
                <th>Date</th>
                <th>Statut</th>
                <th>Montant total</th>
            </tr>
            </thead>
            <tbody>
            <?php foreach ($commandes as $commande) : ?>
                <tr>
                    <td>#<?= htmlspecialchars($commande['id']) ?></td>
                    <td><?= date('d/m/Y H:i', strtotime($commande['date_commande'])) ?></td>
                    <td><?= htmlspecialchars(ucfirst($commande['statut'])) ?></td>
                    <td><?= number_format((float)($commande['total'] ?? 0), 2) ?> €</td>
                </tr>
            <?php endforeach; ?>
            </tbody>
        </table>
    <?php endif; ?>

    <a href="../user_dashboard.php" class="btn-retour">← Retour au tableau de bord</a>
</div>
</body>
</html>
