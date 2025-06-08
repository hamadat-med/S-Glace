<?php
session_start();
require_once 'config.php';
?>

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <title>Studi Glace - Accueil</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css">
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.5/font/bootstrap-icons.css">

    <!-- Font Awesome CDN pour les icônes -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css" integrity="sha512-..." crossorigin="anonymous" referrerpolicy="no-referrer" />

    <style>
        .product-card {
            border: 1px solid #ddd;
            border-radius: 10px;
            padding: 15px;
            margin-bottom: 30px;
            transition: transform 0.3s ease;
            background-color: #fff;
            box-shadow: 0 4px 8px rgba(0,0,0,0.1);
        }
        .product-card:hover {
            transform: scale(1.02);
        }
        .product-img {
            height: 200px;
            object-fit: cover;
            border-radius: 10px;
            width: 100%;
        }
        h2{
            color: #f8088a;

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







        .bulle-avis {
            background-color: pink;
            border-radius: 50%;
            width: 250px;
            height: 250px;
            padding: 20px;
            color: white;
            position: relative;
            animation: float 3s ease-in-out infinite;
            box-shadow: 0 8px 20px rgba(255, 105, 180, 0.3);
            display: flex;
            flex-direction: column;
            justify-content: center;
        }

        .bulle-avis h5 {
            font-weight: bold;
            margin-bottom: 5px;
        }

        .bulle-avis .etoiles {
            margin: 10px 0;
        }

        .bulle-avis i.active {
            color: white;
            animation: pulse 0.5s ease;
        }

        @keyframes float {
            0%, 100% { transform: translateY(0); }
            50% { transform: translateY(-10px); }
        }

        @keyframes pulse {
            0% { transform: scale(1); }
            50% { transform: scale(1.2); }
            100% { transform: scale(1); }
        }



        .icon-hover {
            transition: transform 0.3s ease;
        }
        .icon-hover:hover {
            transform: scale(1.2);
            color: #fff;
        }


    </style>
</head>
<body>

<?php include 'assets/navbar.php'; ?>

<!-- Slider Header -->
<div id="carouselExampleIndicators" class="carousel slide mb-5" data-bs-ride="carousel">
    <div class="carousel-inner">
        <div class="carousel-item active">
            <img src="images/glace1.jpg" class="d-block w-100" alt="Glace 1" height="400">
        </div>
        <div class="carousel-item">
            <img src="images/glace2.jpg" class="d-block w-100" alt="Glace 2" height="400">
        </div>
        <div class="carousel-item">
            <img src="images/glace3.webp" class="d-block w-100" alt="Glace 3" height="400">
        </div>
    </div>
    <button class="carousel-control-prev" type="button" data-bs-target="#carouselExampleIndicators" data-bs-slide="prev">
        <span class="carousel-control-prev-icon"></span>
    </button>
    <button class="carousel-control-next" type="button" data-bs-target="#carouselExampleIndicators" data-bs-slide="next">
        <span class="carousel-control-next-icon"></span>
    </button>
</div>

<!-- Produits -->
<div class="container">
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
                    <form method="POST" action="connexion.php">
                        <input type="hidden" name="produit_id" value="<?= $produit['id'] ?>">
                        <button type="submit" class="btn btn-primary">Ajouter au panier</button>
                    </form>
                </div>
            </div>
            <?php
        }
        ?>
    </div>
</div>



<!-- Avis 5 étoiles -->
<h2 class="text-center mt-5 mb-4">⭐  Avis</h2>
<div class="container mb-5">
    <div class="row justify-content-center">
        <?php
        $stmt = $pdo->query("SELECT * FROM avis WHERE note = 5 ORDER BY date_avis DESC LIMIT 3");
        while ($avis = $stmt->fetch(PDO::FETCH_ASSOC)) {
            ?>
            <div class="col-md-4 d-flex justify-content-center mb-4">
                <div class="bulle-avis text-center">
                    <h5><?= htmlspecialchars($avis['nom']) ?></h5>
                    <p><?= nl2br(htmlspecialchars($avis['message'])) ?></p>
                    <div class="etoiles">
                        <?php for ($i = 1; $i <= 5; $i++): ?>
                            <i class="bi bi-star-fill active"></i>
                        <?php endfor; ?>
                    </div>
                    <small><?= date('d/m/Y', strtotime($avis['date_avis'])) ?></small>
                </div>
            </div>
        <?php } ?>
    </div>
</div>



<?php include 'assets/footer.php'; ?>

</body>
</html>
