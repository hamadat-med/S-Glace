<?php
require_once '../config.php';

// Vérifie que l'ID de la commande est présent
if (!isset($_GET['id']) || empty($_GET['id'])) {
    header('Location: ../admin/admin_commande.php');
    exit();
}

$commande_id = intval($_GET['id']);

// Supprimer la commande (et éventuellement ses lignes associées si tu en as une table `commandes_details`)
try {
    // Si tu as une table liée aux produits de la commande, supprime-la d'abord :
    // $pdo->prepare("DELETE FROM commande_details WHERE commande_id = ?")->execute([$commande_id]);

    $stmt = $pdo->prepare("DELETE FROM commandes WHERE id = ?");
    $stmt->execute([$commande_id]);

    // Redirection avec succès
    header("Location: ../admin/admin_commande.php");
    exit();

} catch (PDOException $e) {
    echo "Erreur lors de la suppression : " . $e->getMessage();
}
