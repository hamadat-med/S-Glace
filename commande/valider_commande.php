<?php
session_start();
require_once '../config.php';

if (!isset($_SESSION['utilisateur_id'])) {
    header('Location: connexion.php');
    exit();
}

$utilisateur_id = $_SESSION['utilisateur_id'];

// Récupérer les produits du panier
$stmt = $pdo->prepare("SELECT p.id, p.nom, p.prix, p.image, pa.quantite
                       FROM panier pa
                       JOIN produits p ON pa.produit_id = p.id
                       WHERE pa.utilisateur_id = ?");
$stmt->execute([$utilisateur_id]);
$produits_panier = $stmt->fetchAll();

if (empty($produits_panier)) {
    echo '
    <style>
        .empty-panier {
            margin: 100px auto;
            max-width: 500px;
            padding: 40px;
            background: #ffe6f0;
            border: 2px dashed #ff66a5;
            color: #d6336c;
            font-size: 1.3rem;
            font-weight: bold;
            text-align: center;
            border-radius: 20px;
            box-shadow: 0 10px 30px rgba(255, 102, 165, 0.3);
            animation: fadeIn 1.2s ease-in-out;
        }

        @keyframes fadeIn {
            from { opacity: 0; transform: translateY(30px); }
            to { opacity: 1; transform: translateY(0); }
        }

        .btn-retour {
            display: inline-block;
            margin-top: 20px;
            padding: 12px 25px;
            background-color: #ff66a5;
            color: #fff;
            text-decoration: none;
            border-radius: 30px;
            transition: background 0.3s, transform 0.2s;
            font-weight: 500;
        }

        .btn-retour:hover {
            background-color: #ff3d7a;
            transform: scale(1.05);
        }
    </style>

    <div class="empty-panier">
        Votre panier est vide 😢
        <br>
        <a href="../user_dashboard.php" class="btn-retour">Retour à l\'accueil</a>
    </div>';
    exit();
}


if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['confirmer'])) {
    try {
        $pdo->beginTransaction();

        // Calculer total général
        $total_general = 0;
        foreach ($produits_panier as $produit) {
            $total_general += $produit['prix'] * $produit['quantite'];
        }

        // Insérer la commande avec total
        $stmt = $pdo->prepare("INSERT INTO commandes (utilisateur_id, date_commande, statut, total) VALUES (?, NOW(), 'En attente', ?)");
        $stmt->execute([$utilisateur_id, $total_general]);
        $commande_id = $pdo->lastInsertId();

        // Insérer les détails de la commande
        $stmt_detail = $pdo->prepare("INSERT INTO commandes_details (commande_id, produit_id, quantite, prix_unitaire) VALUES (?, ?, ?, ?)");

        foreach ($produits_panier as $produit) {
            $stmt_detail->execute([
                $commande_id,
                $produit['id'],
                $produit['quantite'],
                $produit['prix']
            ]);
        }

        // Vider le panier
        $stmt = $pdo->prepare("DELETE FROM panier WHERE utilisateur_id = ?");
        $stmt->execute([$utilisateur_id]);

        $pdo->commit();

        echo '
<div class="confirmation-box text-center">
    <h3 class="message">🎉 Commande validée avec succès !</h3>
    <p class="merci">Merci pour votre achat 💖</p>
    <a href="../user_dashboard.php" class="btn-accueil">← Retour à l\'accueil</a>
</div>';

        exit();

    } catch (Exception $e) {
        $pdo->rollBack();
        echo "<p class='alert alert-danger mt-4'>Erreur lors de la validation de la commande : " . $e->getMessage() . "</p>";
    }
}
?>

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <title>Valider la commande</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
        body {
            background: #fff0f6;
            color: #5a1e5d;
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
        }
        h2 {
            color: #d6336c;
            font-weight: 700;
            margin-bottom: 30px;
            text-align: center;
            text-transform: uppercase;
            letter-spacing: 2px;
            text-shadow: 1px 1px 2px #ffa3c0;
        }
        table {
            background: #fff;
            border-radius: 15px;
            box-shadow: 0 10px 25px rgba(255, 105, 180, 0.25);
            transition: box-shadow 0.3s ease;
        }
        table:hover {
            box-shadow: 0 14px 40px rgba(255, 105, 180, 0.5);
        }
        thead {
            background-color: #d6336c;
            color: white;
            border-radius: 15px;
        }
        tbody tr {
            transition: background-color 0.3s ease, transform 0.2s ease;
        }
        tbody tr:hover {
            background-color: #ffe6f0;
            transform: scale(1.02);
        }
        .btn-pink {
            background: #ff66a5;
            color: white;
            font-weight: 600;
            padding: 12px 30px;
            border-radius: 30px;
            box-shadow: 0 6px 15px rgba(255, 102, 165, 0.6);
            border: none;
            transition: background 0.3s ease, transform 0.2s ease;
            user-select: none;
            display: inline-block;
        }
        .btn-pink:hover, .btn-pink:focus {
            background: #ff3d7a;
            color: white;
            transform: scale(1.1);
            box-shadow: 0 10px 30px rgba(255, 61, 122, 0.8);
            outline: none;
        }
        .animate-pulse {
            animation: pulse 2.5s infinite;
        }
        @keyframes pulse {
            0%, 100% {
                box-shadow: 0 6px 15px rgba(255, 102, 165, 0.6);
            }
            50% {
                box-shadow: 0 10px 30px rgba(255, 61, 122, 0.9);
            }
        }
        .container {
            max-width: 900px;
            margin-top: 50px;
        }


        .btn-pink {
            background: #ff66a5;
            color: white;
            padding: 12px 30px;
            font-size: 1.1rem;
            border: none;
            border-radius: 30px;
            cursor: pointer;
            transition: background 0.3s ease, transform 0.2s ease;
            box-shadow: 0 4px 12px rgba(255, 102, 165, 0.4);
            animation: pulseBtn 2.5s infinite;
        }

        .btn-pink:hover {
            background: #ff3d7a;
            transform: scale(1.05);
            box-shadow: 0 6px 18px rgba(255, 61, 122, 0.5);
        }

        @keyframes pulseBtn {
            0%, 100% {
                box-shadow: 0 4px 12px rgba(255, 102, 165, 0.4);
            }
            50% {
                box-shadow: 0 6px 24px rgba(255, 61, 122, 0.6);
            }
        }

        .btn-grey {
            background: #ddd;
            color: #333;
            padding: 12px 25px;
            font-size: 1.1rem;
            border-radius: 30px;
            transition: background 0.3s ease;
            margin-left: 15px;
            text-decoration: none;
        }

        .btn-grey:hover {
            background: #bbb;
            color: white;
        }




    </style>
</head>
<body>
<div class="container">
    <h2>Validation de la commande</h2>
    <table class="table table-bordered text-center align-middle">
        <thead class="table-dark">
        <tr>
            <th>Produit</th>
            <th>Quantité</th>
            <th>Prix unitaire</th>
            <th>Sous-total</th>
        </tr>
        </thead>
        <tbody>
        <?php
        $total_general = 0;
        foreach ($produits_panier as $produit):
            $sous_total = $produit['prix'] * $produit['quantite'];
            $total_general += $sous_total;
            ?>
            <tr>
                <td><?= htmlspecialchars($produit['nom']) ?></td>
                <td><?= $produit['quantite'] ?></td>
                <td><?= number_format($produit['prix'], 2, ',', ' ') ?> €</td>
                <td><?= number_format($sous_total, 2, ',', ' ') ?> €</td>
            </tr>
        <?php endforeach; ?>
        <tr style="font-weight: 700; font-size: 1.2rem; background: #ffcde1;">
            <td colspan="3" class="text-end">Total</td>
            <td><?= number_format($total_general, 2, ',', ' ') ?> €</td>
        </tr>
        </tbody>
    </table>


    <form method="POST" class="text-center mt-4">
        <button type="submit" name="confirmer" class="btn-pink">✅ Confirmer la commande</button>
        <a href="../panier/panier.php" class="btn-grey">← Retour au panier</a>
    </form>

</div>
</body>
</html>
