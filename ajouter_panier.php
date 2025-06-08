<?php
session_start();
require_once 'config.php';

if (!isset($_SESSION['utilisateur_id'])) {
    echo "Vous devez être connecté pour ajouter au panier.";
    exit();
}

$utilisateur_id = $_SESSION['utilisateur_id'];
$produit_id = isset($_POST['produit_id']) ? intval($_POST['produit_id']) : 0;

if ($produit_id > 0) {
    // Vérifie si le produit est déjà dans le panier
    $check = $pdo->prepare("SELECT quantite FROM panier WHERE utilisateur_id = ? AND produit_id = ?");
    $check->execute([$utilisateur_id, $produit_id]);
    $row = $check->fetch();

    if ($row) {
        // Mise à jour de la quantité
        $newQuantite = $row['quantite'] + 1;
        $update = $pdo->prepare("UPDATE panier SET quantite = ? WHERE utilisateur_id = ? AND produit_id = ?");
        $update->execute([$newQuantite, $utilisateur_id, $produit_id]);
    } else {
        // Insertion nouvelle ligne
        $insert = $pdo->prepare("INSERT INTO panier (utilisateur_id, produit_id, quantite) VALUES (?, ?, ?)");
        $insert->execute([$utilisateur_id, $produit_id, 1]);
    }
}

// Retourner le HTML mis à jour depuis panier_bloc.php
require 'panier_bloc.php';
