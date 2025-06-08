<?php

require_once 'config.php';

if (!isset($_SESSION['utilisateur_id'])) {
    echo "<p>Veuillez vous connecter pour voir votre panier.</p>";
    exit();
}

$utilisateur_id = $_SESSION['utilisateur_id'];

$stmt = $pdo->prepare("SELECT p.nom, p.prix, p.image, pa.quantite
                       FROM panier pa
                       JOIN produits p ON pa.produit_id = p.id
                       WHERE pa.utilisateur_id = ?");
$stmt->execute([$utilisateur_id]);
$produits = $stmt->fetchAll();

if (empty($produits)) {
    echo '<p>Votre panier est vide.</p>';
    return;
}

$html = '<ul class="list-group">';
$total = 0;

foreach ($produits as $produit) {
    $nom = htmlspecialchars($produit['nom']);
    $image = htmlspecialchars($produit['image']);
    $prix = (float)$produit['prix'];
    $quantite = (int)$produit['quantite'];
    $sousTotal = $quantite * $prix;
    $total += $sousTotal;

    $html .= '<li class="list-group-item d-flex align-items-center">';
    $html .= '<img src="admin/uploads/' . $image . '" alt="' . $nom . '" style="width:50px; height:50px; object-fit:cover; border-radius:5px; margin-right:10px;">';
    $html .= '<div style="flex-grow:1;">' . $nom . '</div>';
    $html .= '<span class="badge bg-primary rounded-pill">' . $quantite . '</span>';
    $html .= '<span style="margin-left:10px;">' . number_format($sousTotal, 2) . ' €</span>';
    $html .= '</li>';
}

$html .= '</ul><hr>';
$html .= '<p><strong>Total : ' . number_format($total, 2) . ' €</strong></p>';

echo $html;
