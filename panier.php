<?php
session_start();
require_once 'config.php';

if (!isset($_SESSION['utilisateur_id'])) {
    header('Location: connexion.php');
    exit();
}

$utilisateur_id = $_SESSION['utilisateur_id'];

// Mise à jour des quantités
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (isset($_POST['update'])) {
        foreach ($_POST['quantite'] as $produit_id => $quantite) {
            $quantite = max(1, intval($quantite));
            $stmt = $pdo->prepare("UPDATE panier SET quantite = ? WHERE utilisateur_id = ? AND produit_id = ?");
            $stmt->execute([$quantite, $utilisateur_id, $produit_id]);
        }
    }

    if (isset($_POST['delete'])) {
        $produit_id = intval($_POST['delete']);
        $stmt = $pdo->prepare("DELETE FROM panier WHERE utilisateur_id = ? AND produit_id = ?");
        $stmt->execute([$utilisateur_id, $produit_id]);
    }

    header("Location: panier.php");
    exit();
}

// Récupération des produits dans le panier
$stmt = $pdo->prepare("SELECT p.id, p.nom, p.prix, p.image, pa.quantite
                       FROM panier pa
                       JOIN produits p ON pa.produit_id = p.id
                       WHERE pa.utilisateur_id = ?");
$stmt->execute([$utilisateur_id]);
$produits_panier = $stmt->fetchAll();
?>

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <title>Mon Panier</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
        .produit-img {
            height: 80px;
            object-fit: cover;
        }
        .total {
            font-size: 1.5em;
            font-weight: bold;
        }





        .btn-valider {
            background-color: #ff4d6d;
            border: none;
            color: white;
            font-weight: 600;
            padding: 12px 30px;
            border-radius: 30px;
            cursor: pointer;
            box-shadow: 0 4px 10px rgba(255, 77, 109, 0.4);
            transition: background-color 0.3s ease, transform 0.2s ease, box-shadow 0.3s ease;
            user-select: none;
            display: block;
            margin: 0 auto;
            text-decoration: none;
            text-align: center;
            font-size: 1.1rem;
            animation: pulse 2.5s infinite;
        }

        .btn-valider:hover {
            background-color: #ff1a40;
            transform: scale(1.08);
            box-shadow: 0 6px 14px rgba(255, 26, 64, 0.7);
        }

        @keyframes pulse {
            0%, 100% {
                box-shadow: 0 4px 10px rgba(255, 77, 109, 0.4);
            }
            50% {
                box-shadow: 0 6px 20px rgba(255, 77, 109, 0.7);
            }
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
<body class="bg-light">

<div class="container mt-5">
    <h2 class="mb-4">🛒 Mon Panier</h2>

    <form method="post">
        <table class="table table-bordered bg-white">
            <thead class="table-dark">
            <tr>
                <th>Image</th>
                <th>Produit</th>
                <th>Prix</th>
                <th>Quantité</th>
                <th>Total</th>
                <th>Supprimer</th>
            </tr>
            </thead>
            <tbody>
            <?php if (empty($produits_panier)) : ?>
                <tr>
                    <td colspan="6" class="text-center">Votre panier est vide.</td>
                </tr>
            <?php else :
                $total_general = 0;
                foreach ($produits_panier as $produit) :
                    $total = $produit['prix'] * $produit['quantite'];
                    $total_general += $total;
                    ?>
                    <tr>
                        <td><img src="admin/uploads/<?= htmlspecialchars($produit['image']) ?>" class="produit-img"></td>
                        <td><?= htmlspecialchars($produit['nom']) ?></td>
                        <td><?= number_format($produit['prix'], 2) ?> €</td>
                        <td style="width: 120px;">
                            <input type="number" name="quantite[<?= $produit['id'] ?>]" value="<?= $produit['quantite'] ?>" class="form-control" min="1">
                        </td>
                        <td><?= number_format($total, 2) ?> €</td>
                        <td>
                            <button type="submit" name="delete" value="<?= $produit['id'] ?>" class="btn btn-danger btn-sm">Supprimer</button>
                        </td>
                    </tr>
                <?php endforeach; ?>
                <tr>
                    <td colspan="4" class="text-end total">Total général :</td>
                    <td colspan="2" class="total"><?= number_format($total_general, 2) ?> €</td>
                </tr>
            <?php endif; ?>
            </tbody>
        </table>

        <?php if (!empty($produits_panier)) : ?>
            <div class="d-flex justify-content-between">
                <div class="text-center mt-5">
                    <a href="user_dashboard.php" class="btn-retour-achat">← Continuer vos achats</a>
                </div>

                <button type="submit" name="update" class="btn btn-success">💾 Mettre à jour</button>
            </div>
        <?php endif; ?>
    </form>
</div>


<form method="POST" action="commande/valider_commande.php">
    <button type="submit" class="btn-valider">Valider la commande</button>
</form>


</body>
</html>

