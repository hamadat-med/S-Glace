<?php
session_start();
require '../config.php';

// Traitement du formulaire
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $nom = trim($_POST['nom']);
    $message = trim($_POST['message']);
    $note = (int)$_POST['note'];

    if ($nom && $message && $note > 0) {
        $stmt = $pdo->prepare("INSERT INTO avis (nom, message, note) VALUES (?, ?, ?)");
        $stmt->execute([$nom, $message, $note]);
        header("Location: avis_form.php?success=1");
        exit;
    }
}
?>
<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <title>Laisser un avis</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
        body {
            background-color: #fff0f5;
            font-family: 'Segoe UI', sans-serif;
        }

        .avis-form {
            max-width: 600px;
            margin: 60px auto;
            background: white;
            padding: 40px;
            border-radius: 20px;
            box-shadow: 0 10px 25px rgba(255, 105, 180, 0.3);
            animation: fadeIn 1s ease-in-out;
        }

        h2 {
            text-align: center;
            color: hotpink;
            margin-bottom: 30px;
            font-weight: bold;
        }

        .form-control:focus, .form-select:focus {
            border-color: hotpink;
            box-shadow: 0 0 5px hotpink;
        }

        button {
            background-color: hotpink;
            border: none;
            padding: 12px 24px;
            color: white;
            font-weight: bold;
            border-radius: 10px;
            transition: 0.3s ease;
            width: 100%;
        }

        button:hover {
            background-color: deeppink;
            transform: scale(1.05);
        }

        .star {
            font-size: 1.6rem;
            color: #ff69b4;
            cursor: pointer;
            transition: transform 0.2s ease;
        }

        .star:hover {
            transform: scale(1.2);
        }

        @keyframes fadeIn {
            from { opacity: 0; transform: translateY(30px); }
            to { opacity: 1; transform: translateY(0); }
        }

        .note-container input[type="radio"] {
            display: none;
        }

        .note-container label .star {
            opacity: 0.3;
        }

        .note-container input[type="radio"]:checked ~ label .star {
            opacity: 1;
        }

        .note-container label:hover ~ label .star,
        .note-container label:hover .star {
            opacity: 1;
        }

        .success-message {
            background-color: #d1e7dd;
            color: #0f5132;
            padding: 15px;
            border-radius: 10px;
            text-align: center;
            margin-bottom: 20px;
        }

    </style>
</head>
<body>
<div class="avis-form">
    <h2>Laisser un avis</h2>

    <?php if (isset($_GET['success'])): ?>
        <div class="success-message">🎉 Merci pour votre avis !</div>
    <?php endif; ?>

    <form method="POST">
        <div class="mb-3">
            <input type="text" name="nom" class="form-control" placeholder="Votre nom" required>
        </div>
        <div class="mb-3">
            <textarea name="message" class="form-control" placeholder="Votre message" rows="4" required></textarea>
        </div>
        <div class="mb-3 note-container d-flex justify-content-center gap-2">
            <?php for ($i = 1; $i <= 5; $i++): ?>
                <input type="radio" id="note<?= $i ?>" name="note" value="<?= $i ?>" required>
                <label for="note<?= $i ?>"><span class="star">&#9733;</span></label>
            <?php endfor; ?>
        </div>
        <button type="submit">Envoyer</button>
    </form>
</div>
</body>
</html>
