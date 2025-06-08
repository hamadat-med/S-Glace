<?php
session_start();
require_once 'config.php';

if (!isset($_SESSION['utilisateur_id'])) {
    header('Location: connexion.php');
    exit();
}

$utilisateur_id = $_SESSION['utilisateur_id'];
$stmt = $pdo->prepare("SELECT * FROM utilisateurs WHERE id = ?");
$stmt->execute([$utilisateur_id]);
$user = $stmt->fetch();
?>

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <title>Tableau de bord utilisateur</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.5/font/bootstrap-icons.css" rel="stylesheet">
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
    <style>
        body {
            background: #f4f6f9;
        }
        .sidebar {
            background: #343a40;
            color: white;
            height: 100vh;
            padding: 20px;
        }
        .sidebar h4 {
            color: #f8f9fa;
        }
        .product-card {
            box-shadow: 0 2px 10px rgba(0,0,0,0.1);
            border-radius: 10px;
            padding: 15px;
            margin-bottom: 20px;
            transition: transform 0.3s;
            background: #fff;
        }
        .product-card:hover {
            transform: scale(1.02);
        }
        .product-img {
            max-height: 150px;
            width: 100%;
            object-fit: cover;
            border-radius: 10px;
        }
        h2.text-center {
            color: pink;
            font-style: italic;
            font-family: 'Times New Roman', Times, serif;
            /* margin-bottom déjà en place */
        }

        /* Centrer les produits dans la rangée */
        .row {
            justify-content: center; /* Centre horizontalement les colonnes */
        }

        /* Optionnel: Forcer chaque carte produit à avoir une largeur fixe pour un bel alignement */
        .product-card {
            max-width: 300px;
            margin: 0 auto; /* centre la carte dans sa colonne */
        }

    </style>
</head>
<body>

<!-- Navbar -->
<nav class="navbar navbar-expand-lg navbar-dark bg-dark">
    <div class="container-fluid">
        <a class="navbar-brand" href="index.php"><i class="bi bi-cone-striped"></i> Studi Glace</a>
        <div class="collapse navbar-collapse">
            <ul class="navbar-nav ms-auto">
                <li class="nav-item"><a class="nav-link" href="index.php"><i class="bi bi-house-door-fill"></i> Accueil</a></li>

                <li class="nav-item"><a class="nav-link" href="deconnexion.php"><i class="bi bi-box-arrow-right"></i> Déconnexion</a></li>
            </ul>
        </div>
    </div>
</nav>

<div class="container-fluid">
    <div class="row">

        <!-- Sidebar -->
        <div class="col-md-2 sidebar">
            <h4>Bienvenue</h4>
            <p><?= htmlspecialchars($user['nom']) ?><br><?= htmlspecialchars($user['email']) ?></p>
            <hr>
            <ul class="nav flex-column">
                <li class="nav-item"><a class="nav-link text-light" href="#"><i class="bi bi-person-circle"></i> Mes Infos</a></li>
                <li class="nav-item"><a class="nav-link text-light" href="panier.php"><i class="bi bi-cart"></i> Mon Panier</a></li>
                <li class="nav-item"><a class="nav-link text-light" href="commande/valider_commande.php"><i class="bi bi-clipboard-check"></i> Commandes</a></li>
                <li class="nav-item"><a class="nav-link text-light" href="admin/avis_form.php"><i class="bi bi-chat-left-text"></i> Avis</a></li>
                <li class="nav-item"><a class="nav-link text-light" href="assets/contact.php"><i class="bi bi-envelope-fill"></i> Messages</a></li>
            </ul>
        </div>

        <!-- Contenu principal -->
        <div class="col-md-7">
            <!-- Slider -->
            <div id="carouselExample" class="carousel slide mt-3 mb-4" data-bs-ride="carousel">
                <div class="carousel-inner rounded">
                    <div class="carousel-item active">
                        <img src="images/glace1.jpg" class="d-block w-100" alt="glace1">
                    </div>
                    <div class="carousel-item">
                        <img src="images/glace2.jpg" class="d-block w-100" alt="glace2">
                    </div>
                </div>
            </div>

            <!-- Produits -->
            <h2 class="text-center mb-4">Nos Délicieuses Glaces 🍦</h2>
            <div class="row">
                <?php
                $stmt = $pdo->query("SELECT * FROM produits");
                while ($produit = $stmt->fetch(PDO::FETCH_ASSOC)) {
                    ?>
                    <div class="col-md-4">
                        <div class="product-card text-center">
                            <img src="/<?= $produit['image'] ?>" class="product-img mb-3" alt="<?= htmlspecialchars($produit['nom']) ?>">
                            <h5><?= htmlspecialchars($produit['nom']) ?></h5>
                            <p><?= htmlspecialchars($produit['description']) ?></p>
                            <p><strong><?= number_format($produit['prix'], 2) ?> €</strong></p>
                            <button class="btn btn-primary ajouter-panier" data-id="<?= $produit['id'] ?>">
                                <i class="bi bi-cart-plus"></i> Ajouter au panier
                            </button>
                        </div>
                    </div>
                    <?php
                }
                ?>
            </div>
        </div>

        <!-- Panier -->
        <div class="col-md-3 bg-light p-3">
            <h5><i class="bi bi-cart4"></i> Mon panier</h5>
            <div id="panier-contenu">
                <?php include 'panier_bloc.php'; ?>
            </div>
        </div>

    </div>
</div>

<!-- Script AJAX -->
<script>
    document.querySelectorAll('.ajouter-panier').forEach(button => {
        button.addEventListener('click', function () {
            const produitId = this.getAttribute('data-id');

            fetch('ajouter_panier.php', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/x-www-form-urlencoded'
                },
                body: 'produit_id=' + produitId
            })
                .then(response => response.text())
                .then(html => {
                    document.getElementById('panier-contenu').innerHTML = html;
                });
        });
    });
</script>

</body>
</html>
