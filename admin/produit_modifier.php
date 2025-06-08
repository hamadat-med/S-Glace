<?php
session_start();
require '../config.php';

if (!isset($_SESSION['admin_id'])) {
    header("Location: admin_connexion.php");
    exit;
}

$id = intval($_GET['id'] ?? 0);
if ($id <= 0) {
    header("Location: produits.php");
    exit;
}

$stmt = $pdo->prepare("SELECT * FROM produits WHERE id = ?");
$stmt->execute([$id]);
$produit = $stmt->fetch();
if (!$produit) {
    header("Location: produits.php");
    exit;
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $nom = $_POST['nom'] ?? '';
    $description = $_POST['description'] ?? '';
    $prix = floatval($_POST['prix'] ?? 0);
    $image = $_FILES['image'] ?? null;

    if ($nom && $description && $prix > 0) {
        $filename = $produit['image'];

        if ($image && $image['error'] === 0) {
            $ext = pathinfo($image['name'], PATHINFO_EXTENSION);
            $allowed = ['jpg', 'jpeg', 'png', 'webp'];
            if (in_array(strtolower($ext), $allowed)) {
                $filename = 'uploads/' . uniqid() . '.' . $ext;
                move_uploaded_file($image['tmp_name'], '../' . $filename);
                if (file_exists('../' . $produit['image'])) {
                    unlink('../' . $produit['image']);
                }
            } else {
                $erreur = "Format d'image non autorisé. Formats acceptés : JPG, JPEG, PNG, WEBP.";
            }
        }

        if (!isset($erreur)) {
            $stmt = $pdo->prepare("UPDATE produits SET nom = ?, description = ?, prix = ?, image = ? WHERE id = ?");
            $stmt->execute([$nom, $description, $prix, $filename, $id]);
            header("Location: produits.php");
            exit;
        }
    } else {
        $erreur = "Veuillez remplir tous les champs correctement.";
    }
}
?>

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <title>Modifier Produit - Admin Studi Glace</title>
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons/font/bootstrap-icons.css">
</head>
<body class="bg-light">
<div class="container mt-5 p-4 bg-white shadow rounded">
    <h2 class="text-primary mb-4"><i class="bi bi-pencil-square"></i> Modifier le produit</h2>
    <a href="produits.php" class="btn btn-secondary mb-3"><i class="bi bi-arrow-left"></i> Retour à la liste</a>

    <?php if (isset($erreur)): ?>
        <div class="alert alert-danger"><?= htmlspecialchars($erreur) ?></div>
    <?php endif; ?>

    <form method="POST" enctype="multipart/form-data">
        <div class="mb-3">
            <label class="form-label">Nom du produit :</label>
            <input type="text" name="nom" class="form-control" value="<?= htmlspecialchars($produit['nom']) ?>" required>
        </div>

        <div class="mb-3">
            <label class="form-label">Description :</label>
            <textarea name="description" class="form-control" rows="4" required><?= htmlspecialchars($produit['description']) ?></textarea>
        </div>

        <div class="mb-3">
            <label class="form-label">Prix (€) :</label>
            <input type="number" name="prix" step="0.01" class="form-control" value="<?= $produit['prix'] ?>" required>
        </div>

        <div class="mb-3">
            <label class="form-label">Image actuelle :</label><br>
            <img src="../<?= htmlspecialchars($produit['image']) ?>" alt="Image produit" style="max-height:150px;" class="img-thumbnail">
        </div>

        <div class="mb-3">
            <label class="form-label">Changer l'image :</label>
            <input type="file" name="image" class="form-control" accept=".jpg,.jpeg,.png,.webp">
        </div>

        <button type="submit" class="btn btn-primary"><i class="bi bi-check-circle"></i> Enregistrer les modifications</button>
    </form>
</div>
</body>
</html>
