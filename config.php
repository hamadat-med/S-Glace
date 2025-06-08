<?php
$host = 'localhost:3307';
$db   = 'studi_glace';  // Nom de ta base de données
$user = 'root';         // Utilisateur MySQL (par défaut sous XAMPP)
$pass = '';             // Mot de passe MySQL (souvent vide sous XAMPP)
$charset = 'utf8mb4';

$dsn = "mysql:host=$host;dbname=$db;charset=$charset";

$options = [
    PDO::ATTR_ERRMODE            => PDO::ERRMODE_EXCEPTION, // Afficher les erreurs
    PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,       // Mode de récupération par défaut
    PDO::ATTR_EMULATE_PREPARES   => false,                  // Pour sécurité
];

try {
    $pdo = new PDO($dsn, $user, $pass, $options);
} catch (\PDOException $e) {
    exit('Erreur de connexion à la base de données : ' . $e->getMessage());
}
?>
