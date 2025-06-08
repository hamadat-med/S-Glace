<?php
session_start();
header('Content-Type: application/json');

require_once 'config.php';

if (!isset($_SESSION['panier'])) {
    $_SESSION['panier'] = [];
}

$action = $_GET['action'] ?? '';

if (!$action) {
    echo json_encode(['success' => false, 'message' => 'Aucune action spécifiée']);
    exit;
}

switch ($action) {
    case 'ajouter':
        $produit_id = $_POST['produit_id'] ?? null;
        if (!$produit_id) {
            echo json_encode(['success' => false, 'message' => 'Produit manquant']);
            exit;
        }

        if (isset($_SESSION['panier'][$produit_id])) {
            $_SESSION['panier'][$produit_id]++;
        } else {
            $_SESSION['panier'][$produit_id] = 1;
        }

        // Renvoie panier à jour
        $ids = array_keys($_SESSION['panier']);
        if (count($ids) === 0) {
            $panier_html = "<p>Panier vide.</p>";
        } else {
            $placeholders = implode(',', array_fill(0, count($ids), '?'));
            $stmt = $pdo->prepare("SELECT id, nom, prix FROM produits WHERE id IN ($placeholders)");
            $stmt->execute($ids);
            $produits = $stmt->fetchAll(PDO::FETCH_ASSOC);

            $panier_html = "<ul class='list-group'>";
            foreach ($produits as $p) {
                $qty = $_SESSION['panier'][$p['id']];
                $total = number_format($p['prix'] * $qty, 2);
                $panier_html .= "<li class='list-group-item d-flex justify-content-between align-items-center'>";
                $panier_html .= htmlspecialchars($p['nom']) . " (x$qty)";
                $panier_html .= "<span>$total €</span></li>";
            }
            $panier_html .= "</ul>";
        }

        echo json_encode(['success' => true, 'panier_html' => $panier_html]);
        break;

    case 'afficher':
        $ids = array_keys($_SESSION['panier']);
        if (count($ids) === 0) {
            $panier_html = "<p>Panier vide.</p>";
        } else {
            $placeholders = implode(',', array_fill(0, count($ids), '?'));
            $stmt = $pdo->prepare("SELECT id, nom, prix FROM produits WHERE id IN ($placeholders)");
            $stmt->execute($ids);
            $produits = $stmt->fetchAll(PDO::FETCH_ASSOC);

            $panier_html = "<ul class='list-group'>";
            foreach ($produits as $p) {
                $qty = $_SESSION['panier'][$p['id']];
                $total = number_format($p['prix'] * $qty, 2);
                $panier_html .= "<li class='list-group-item d-flex justify-content-between align-items-center'>";
                $panier_html .= htmlspecialchars($p['nom']) . " (x$qty)";
                $panier_html .= "<span>$total €</span></li>";
            }
            $panier_html .= "</ul>";
        }
        echo json_encode(['success' => true, 'panier_html' => $panier_html]);
        break;

    default:
        echo json_encode(['success' => false, 'message' => 'Action invalide']);
        break;
}
