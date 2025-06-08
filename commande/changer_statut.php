<?php
require_once '../config.php';

// Vérifie que l'ID est passé en GET
if (!isset($_GET['id'])) {
    header('Location: ../admin/admin_commande.php');
    exit();
}

$commande_id = $_GET['id'];

// Récupère la commande depuis la base de données
$stmt = $pdo->prepare("SELECT * FROM commandes WHERE id = ?");
$stmt->execute([$commande_id]);
$commande = $stmt->fetch();

if (!$commande) {
    echo "Commande introuvable.";
    exit();
}

// Si le formulaire est soumis
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $nouveau_statut = $_POST['statut'];

    // Mise à jour du statut
    $update = $pdo->prepare("UPDATE commandes SET statut = ? WHERE id = ?");
    $update->execute([$nouveau_statut, $commande_id]);

    // Redirection
    header('Location: ../admin/admin_commande.php');
    exit();
}
?>

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <title>Changer le statut</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
        body {
            background-color: #fff0f6;
            font-family: 'Segoe UI', sans-serif;
            display: flex;
            justify-content: center;
            align-items: center;
            height: 100vh;
        }
        .box {
            background: white;
            padding: 30px;
            border-radius: 15px;
            box-shadow: 0 10px 25px rgba(255, 105, 180, 0.2);
            width: 400px;
            animation: fadeIn 0.6s ease-in-out;
        }
        h3 {
            color: #d6336c;
            margin-bottom: 20px;
            text-align: center;
        }
        label {
            font-weight: bold;
        }
        select, button {
            margin-top: 10px;
        }
        .btn-pink {
            background-color: #ff66a5;
            border: none;
            color: white;
            padding: 10px 20px;
            border-radius: 20px;
            transition: 0.3s;
            display: block;
            width: 100%;
            margin-top: 15px;
        }
        .btn-pink:hover {
            background-color: #e60073;
            transform: scale(1.05);
        }
        @keyframes fadeIn {
            from {opacity: 0; transform: translateY(20px);}
            to {opacity: 1; transform: translateY(0);}
        }
    </style>
</head>
<body>
<div class="box">
    <h3>Modifier le statut de la commande #<?= htmlspecialchars($commande['id']) ?></h3>
    <form method="POST">
        <label for="statut">Nouveau statut :</label>
        <select name="statut" id="statut" class="form-select">
            <option value="en attente" <?= $commande['statut'] === 'en attente' ? 'selected' : '' ?>>En attente</option>
            <option value="validée" <?= $commande['statut'] === 'validée' ? 'selected' : '' ?>>Validée</option>
            <option value="expédiée" <?= $commande['statut'] === 'expédiée' ? 'selected' : '' ?>>Expédiée</option>
            <option value="livrée" <?= $commande['statut'] === 'livrée' ? 'selected' : '' ?>>Livrée</option>
            <option value="annulée" <?= $commande['statut'] === 'annulée' ? 'selected' : '' ?>>Annulée</option>
        </select>
        <button type="submit" class="btn-pink">Enregistrer</button>
    </form>
</div>
</body>
</html>
