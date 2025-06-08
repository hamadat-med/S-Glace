<?php
session_start();
require '../config.php';

if (!isset($_SESSION['admin_id'])) {
    header('Location: ../connexion.php');
    exit;
}

if (!isset($_GET['id'])) {
    header('Location: produits.php');
    exit;
}

$produit_id = (int) $_GET['id'];

// Vérifier si le produit est utilisé dans commandes_details
$stmt = $pdo->prepare("SELECT COUNT(*) FROM commandes_details WHERE produit_id = ?");
$stmt->execute([$produit_id]);
$count = $stmt->fetchColumn();

if ($count > 0) {
    // Produit utilisé => impossible de supprimer
    $_SESSION['error'] = "Impossible de supprimer ce produit, il est utilisé dans des commandes.";
    header('Location: produits.php');
    exit;
} else {
    // Supprimer le produit (tu peux aussi supprimer l’image ici si besoin)
    $stmt = $pdo->prepare("DELETE FROM produits WHERE id = ?");
    $stmt->execute([$produit_id]);

    $_SESSION['success'] = "Produit supprimé avec succès.";
    header('Location: produits.php');
    exit;
}
