<?php
session_start();
require_once '../config.php';

// Vérifier si connecté
if (!isset($_SESSION['utilisateur_id'])) {
    header('Location: connexion.php');
    exit();
}

$utilisateur_id = $_SESSION['utilisateur_id'];

// Récupérer infos utilisateur
$stmt = $pdo->prepare("SELECT * FROM utilisateurs WHERE id = ?");
$stmt->execute([$utilisateur_id]);
$user = $stmt->fetch();

// Récupérer le panier
$stmt = $pdo->prepare("SELECT p.nom, p.prix, panier.quantite 
                       FROM panier 
                       JOIN produits p ON panier.produit_id = p.id 
                       WHERE panier.utilisateur_id = ?");
$stmt->execute([$utilisateur_id]);
$panier = $stmt->fetchAll();

// Récupérer tous les produits
$stmt = $pdo->query("SELECT * FROM produits ORDER BY nom ASC");
$produits = $stmt->fetchAll();
?>

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <title>Mon compte - Studi Glace</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css">
</head>
<body>

<?php include '../assets/navbar.php'; ?>

<div class="container-fluid mt-4">
    <div class="row">
        <!-- Menu à gauche -->
        <div class="col-md-3">
            <div class="list-group">
                <a href="#" class="list-group-item active">Tableau de bord</a>
                <a href="#infos" class="list-group-item">Mes informations</a>
                <a href="#panier" class="list-group-item">Mon panier</a>
                <a href="#produits" class="list-group-item">Produits disponibles</a>
                <a href="logout.php" class="list-group-item text-danger">Déconnexion</a>
            </div>
        </div>

        <!-- Contenu principal -->
        <div class="col-md-9">
            <h2 class="mb-4">Bienvenue, <?= htmlspecialchars($user['nom']) ?> !</h2>

            <!-- Infos utilisateur -->
            <section id="infos">
                <h4>Mes informations</h4>
                <p><strong>Nom :</strong> <?= htmlspecialchars($user['nom']) ?></p>
                <p><strong>Email :</strong> <?= htmlspecialchars($user['email']) ?></p>
                <a href="modifier_profil.php" class="btn btn-primary btn-sm">Modifier mon profil</a>
                <hr>
            </section>

            <!-- Panier -->
            <section id="panier">
                <h4>Mon panier</h4>
                <?php if (empty($panier)): ?>
                    <p>Votre panier est vide.</p>
                <?php else: ?>
                    <ul class="list-group mb-3">
                        <?php foreach ($panier as $item): ?>
                            <li class="list-group-item d-flex justify-content-between">
                                <?= htmlspecialchars($item['nom']) ?> × <?= $item['quantite'] ?>
                                <span><?= number_format($item['prix'] * $item['quantite'], 2, ',', ' ') ?> €</span>
                            </li>
                        <?php endforeach; ?>
                    </ul>
                    <a href="../admin/panier.php" class="btn btn-success btn-sm">Voir mon panier</a>
                <?php endif; ?>
                <hr>
            </section>

            <!-- Produits disponibles -->
            <section id="produits">
                <h4>Produits disponibles</h4>
                <div class="row">
                    <?php foreach ($produits as $p): ?>
                        <div class="col-md-4 mb-4">
                            <div class="card h-100">
                                <img src="<?= htmlspecialchars($p['image']) ?>" class="card-img-top" alt="<?= htmlspecialchars($p['nom']) ?>">
                                <div class="card-body">
                                    <h5 class="card-title"><?= htmlspecialchars($p['nom']) ?></h5>
                                    <p class="card-text"><?= number_format($p['prix'], 2, ',', ' ') ?> €</p>
                                    <form action="ajouter_panier.php" method="post">
                                        <input type="hidden" name="produit_id" value="<?= $p['id'] ?>">
                                        <input type="number" name="quantite" value="1" min="1" class="form-control mb-2" required>
                                        <button type="submit" class="btn btn-outline-primary btn-sm w-100">Ajouter au panier</button>
                                    </form>
                                </div>
                            </div>
                        </div>
                    <?php endforeach; ?>
                </div>
            </section>
        </div>
    </div>
</div>

<?php include '../assets/footer.php'; ?>
</body>
</html>
