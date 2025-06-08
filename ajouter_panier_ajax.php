<?php
session_start();
require_once 'config.php'; // ta connexion PDO

if (!isset($_SESSION['panier'])) {
    $_SESSION['panier'] = [];
}

$produit_id = $_POST['produit_id'] ?? null;
if (!$produit_id) {
    echo '<p>Produit manquant</p>';
    exit;
}

// Ajouter ou incrémenter la quantité du produit dans le panier
if (isset($_SESSION['panier'][$produit_id])) {
    $_SESSION['panier'][$produit_id]++;
} else {
    $_SESSION['panier'][$produit_id] = 1;
}

// Fonction pour générer le HTML du panier
function genererHtmlPanier($pdo) {
    if (empty($_SESSION['panier'])) {
        return "<p>Panier vide.</p>";
    }

    $ids = array_keys($_SESSION['panier']);
    $placeholders = implode(',', array_fill(0, count($ids), '?'));
    $stmt = $pdo->prepare("SELECT * FROM produits WHERE id IN ($placeholders)");
    $stmt->execute($ids);
    $produits = $stmt->fetchAll(PDO::FETCH_ASSOC);

    $html = '<ul class="list-group">';
    $totalGlobal = 0;
    foreach ($produits as $p) {
        $id = $p['id'];
        $qty = $_SESSION['panier'][$id];
        $total = $p['prix'] * $qty;
        $totalGlobal += $total;

        $html .= '<li class="list-group-item d-flex justify-content-between align-items-center">';
        $html .= htmlspecialchars($p['nom']) . ' x ' . $qty;
        $html .= '<span>' . number_format($total, 2) . ' €</span>';
        // Boutons de modifier / supprimer (à compléter ensuite en AJAX)
        $html .= '<div>';
        $html .= '<button class="btn btn-sm btn-secondary me-1 modifier-qty" data-id="'.$id.'" data-action="moins">-</button>';
        $html .= '<button class="btn btn-sm btn-secondary me-1 modifier-qty" data-id="'.$id.'" data-action="plus">+</button>';
        $html .= '<button class="btn btn-sm btn-danger supprimer-produit" data-id="'.$id.'">x</button>';
        $html .= '</div>';
        $html .= '</li>';
    }
    $html .= '</ul>';
    $html .= '<hr>';
    $html .= '<strong>Total: ' . number_format($totalGlobal, 2) . ' €</strong>';

    return $html;
}

echo genererHtmlPanier($pdo);
