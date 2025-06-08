<?php
session_start();
require '../config.php';

$nom = "Admin Studi";
$email = "studi@gmail.com";
$mot_de_passe = password_hash("12345", PASSWORD_DEFAULT);

// Vérifie si l'admin existe déjà
$stmt = $pdo->prepare("SELECT * FROM admin WHERE email = ?");
$stmt->execute([$email]);
$existe = $stmt->fetch();

if ($existe) {
    echo "Cet administrateur existe déjà.";
} else {
    try {
        $stmt = $pdo->prepare("INSERT INTO admin (nom, email, mot_de_passe) VALUES (?, ?, ?)");
        $stmt->execute([$nom, $email, $mot_de_passe]);

        $_SESSION['admin_id'] = $pdo->lastInsertId();
        $_SESSION['admin_nom'] = $nom;

        header("Location: dashboard.php");
        exit;
    } catch (PDOException $e) {
        echo "Erreur lors de la création de l'admin : " . $e->getMessage();
    }
}
?>
