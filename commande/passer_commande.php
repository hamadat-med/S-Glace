<?php
session_start();
require '../config.php';

if (!isset($_SESSION['user_id'])) {
    header('Location: ../connexion.php');
    exit;
}

if (!isset($_SESSION['panier']) || empty($_SESSION['panier'])) {
    echo "Votre panier est vide.";
    exit;
}

// Récupération des données
$user_id = $_SESSION['user_id'];
$panier = $_SESSION['panier'];

// Calcul du total
$total = 0;
foreach ($panier as $id => $quantite) {
    $stmt = $pdo->prepare("SELECT prix FROM produits WHERE id = ?");
    $stmt->execute([$id]);
    $produit = $stmt->fetch();
    $total += $produit['prix'] * $quantite;
}

// Insertion dans la table commandes
$stmt = $pdo->prepare("INSERT INTO commandes (utilisateur_id, total) VALUES (?, ?)");
$stmt->execute([$user_id, $total]);
$commande_id = $pdo->lastInsertId();

// Insertion des produits commandés
foreach ($panier as $id => $quantite) {
    $stmt = $pdo->prepare("INSERT INTO commande_produits (commande_id, produit_id, quantite) VALUES (?, ?, ?)");
    $stmt->execute([$commande_id, $id, $quantite]);
}

// Vider le panier
unset($_SESSION['panier']);

echo "<h2>Commande passée avec succès !</h2>";
echo "<p>Merci pour votre achat. Un email de confirmation vous sera envoyé.</p>";
?>
