<?php
session_start();

// Protection d'accès
if (!isset($_SESSION['admin_id'])) {
    header("Location: ../connexion.php");
    exit;
}
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
            margin: 0;
            font-family: Arial, sans-serif;
            background: #f6cccc;
        }

        header {
            background-color: #fdbbc7;
            padding: 20px;
            color: #ffffff;
            text-align: center;
        }

        .dashboard-container {
            display: flex;
            justify-content: center;
            align-items: center;
            gap: 50px;
            height: 80vh;
        }

        .card {
            background: white;
            padding: 20px;
            border-radius: 10px;
            width: 250px;
            box-shadow: 0 4px 8px rgba(0,0,0,0.1);
            text-align: center;
            transition: transform 0.3s ease;
        }

        .card:hover {
            transform: scale(1.05);
        }

        .card img {
            width: 100%;
            height: 150px;
            object-fit: cover;
            border-radius: 8px;
        }

        .card h3 {
            margin: 15px 0;
            color: #333;
        }

        .card a {
            display: inline-block;
            margin-top: 10px;
            padding: 10px 20px;
            background-color: #d24ae1;
            color: white;
            border: none;
            border-radius: 5px;
            text-decoration: none;
            font-weight: bold;
        }

        .card a:hover {
            background-color: #e878cc;
        }

        footer {
            background: #333;
            color: white;
            text-align: center;
            padding: 15px;
        }
    </style>
</head>
<body>

<header>
    <h1>Bienvenue dans le Dashboard Admin</h1>
</header>

<div class="dashboard-container">
    <div class="card">
        <img src="../assets/image/produits.avif" alt="Produits">
        <h3>Gérer les produits</h3>
        <a href="../admin/produits.php">Accéder</a>
    </div>
    <div class="card">
        <img src="../assets/image/commandes.jpg" alt="Commandes">
        <h3>Gérer les commandes</h3>
        <a href="admin_commande.php">Accéder</a>
    </div>
    <div class="card">
        <img src="../assets/image/1749397597_avis.jpg" alt="Avis">
        <h3>Gérer les avis</h3>
        <a href="admin_avis.php">Accéder</a>
    </div>
    <div class="card">
        <img src="../assets/image/téléchargement.png" alt="Avis">
        <h3>les messages</h3>
        <a href="messages.php">Accéder</a>
    </div>
</div>

<a href="../deconnexion.php" style="background:red;color:white;padding:8px 12px;border-radius:5px;text-decoration:none;">Déconnexion</a>


<footer>
    &copy; <?= date('Y') ?> Studi Glace - Interface administrateur
</footer>

</body>
</html>
