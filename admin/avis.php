<?php
session_start();
require '../config.php';

// Ajouter un avis
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $nom = trim($_POST['nom']);
    $message = trim($_POST['message']);
    $note = (int)$_POST['note'];

    if ($nom && $message && $note > 0) {
        $stmt = $pdo->prepare("INSERT INTO avis (nom, message, note) VALUES (?, ?, ?)");
        $stmt->execute([$nom, $message, $note]);
    }
}

// Supprimer un avis (admin uniquement)
if (isset($_GET['supprimer']) && isset($_SESSION['admin']) && $_SESSION['admin'] === true) {
    $stmt = $pdo->prepare("DELETE FROM avis WHERE id = ?");
    $stmt->execute([$_GET['supprimer']]);
}

// Récupérer les avis
$avis = $pdo->query("SELECT * FROM avis ORDER BY date_avis DESC")->fetchAll(PDO::FETCH_ASSOC);
?>

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <title>Avis des Clients</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
        body {
            background-color: #ffe6f0;
            font-family: 'Arial', sans-serif;
        }
        .container {
            max-width: 800px;
            margin: auto;
            margin-top: 40px;
            background: #fff0f5;
            padding: 30px;
            border-radius: 15px;
            box-shadow: 0 0 20px rgba(255, 105, 180, 0.2);
        }
        .star {
            color: #ff69b4;
            font-size: 1.3rem;
            transition: transform 0.3s ease-in-out;
        }
        .star:hover {
            transform: scale(1.3);
            cursor: pointer;
        }
        .avis-item {
            background: white;
            padding: 15px;
            border-radius: 12px;
            margin-bottom: 15px;
            border-left: 5px solid #ff69b4;
            transition: transform 0.3s;
        }
        .avis-item:hover {
            transform: scale(1.02);
        }
        .form-control, .btn {
            border-radius: 10px;
        }
        h2 {
            text-align: center;
            color: #d63384;
        }
    </style>
</head>
<body>
<div class="container">
    <h2>🌟 Avis des Clients</h2>

    <form method="POST" class="mb-4">
        <div class="mb-3">
            <input type="text" name="nom" class="form-control" placeholder="Votre nom" required>
        </div>
        <div class="mb-3">
            <textarea name="message" class="form-control" placeholder="Votre avis..." required></textarea>
        </div>
        <div class="mb-3">
            <label for="note">Note :</label>
            <div>
                <?php for ($i = 1; $i <= 5; $i++): ?>
                    <label>
                        <input type="radio" name="note" value="<?= $i ?>" required hidden>
                        <span class="star">&#9733;</span>
                    </label>
                <?php endfor; ?>
            </div>
        </div>
        <button class="btn btn-pink text-white bg-pink px-4 py-2">Envoyer l'avis</button>
    </form>

    <?php foreach ($avis as $a): ?>
        <div class="avis-item">
            <strong><?= htmlspecialchars($a['nom']) ?></strong>
            <p><?= nl2br(htmlspecialchars($a['message'])) ?></p>
            <p>
                <?php for ($i = 1; $i <= 5; $i++): ?>
                    <span class="star" style="opacity: <?= $i <= $a['note'] ? '1' : '0.2' ?>">&#9733;</span>
                <?php endfor; ?>
            </p>
            <small class="text-muted"><?= date('d/m/Y H:i', strtotime($a['date_avis'])) ?></small>
            <?php if (isset($_SESSION['admin']) && $_SESSION['admin'] === true): ?>
                <a href="?supprimer=<?= $a['id'] ?>" onclick="return confirm('Supprimer cet avis ?')" class="btn btn-sm btn-danger float-end">Supprimer</a>
            <?php endif; ?>
        </div>
    <?php endforeach; ?>
</div>

<script>
    const stars = document.querySelectorAll('input[name="note"] + .star');
    stars.forEach((star, index) => {
        star.addEventListener('click', () => {
            stars.forEach((s, i) => {
                s.style.opacity = i <= index ? "1" : "0.2";
            });
        });
    });
</script>
</body>
</html>
