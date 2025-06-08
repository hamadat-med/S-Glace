<?php
require '../config.php';
$message_envoye = false;

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $nom = htmlspecialchars($_POST['nom']);
    $email = htmlspecialchars($_POST['email']);
    $sujet = htmlspecialchars($_POST['sujet']);
    $message = htmlspecialchars($_POST['message']);

    $stmt = $pdo->prepare("INSERT INTO messages_contact (nom, email, sujet, message, date_envoi) VALUES (?, ?, ?, ?, NOW())");
    $stmt->execute([$nom, $email, $sujet, $message]);

    $message_envoye = true;
}
?>

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <title>Studi Glace - Contact</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css">
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css">
<style>
    .icon-hover {
        transition: transform 0.3s ease;
    }
    .icon-hover:hover {
        transform: scale(1.2);
        color: #fff;
    }

</style>
</head>
<body>

<nav class="navbar navbar-expand-lg" style="background-color: #ff8e9d;">
    <div class="container">
        <a class="navbar-brand d-flex align-items-center me-auto"  href="../index.php">
            <img src="/images/creme-glacee.png" alt="Logo" width="40" height="40" class="me-2 logo-animate">
            <span class="fw-bold text-white">Studi Glace</span>
        </a>
        <button class="navbar-toggler bg-white" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav">
            <span class="navbar-toggler-icon"></span>
        </button>
        <div class="collapse navbar-collapse justify-content-end" id="navbarNav">
            <ul class="navbar-nav">
                <li class="nav-item"><a class="nav-link text-white" href="../index.php">Accueil</a></li>
                <li class="nav-item"><a class="nav-link text-white" href="../assets/a_propos.php">À propos</a></li>
                <li class="nav-item"><a class="nav-link text-white" href="../connexion.php">Connexion</a></li>
            </ul>
        </div>
    </div>
</nav>

<style>
    /* Animation logo */
    .logo-animate {
        transition: transform 0.3s ease;
    }
    .logo-animate:hover {
        transform: rotate(10deg) scale(1.1);
    }

    /* Responsive padding */
    .navbar-nav .nav-link {
        padding: 0.5rem 1rem;
        transition: background 0.3s;
    }
    .navbar-nav .nav-link:hover {
        background-color: rgba(255, 255, 255, 0.2);
        border-radius: 5px;
    }


    .btn-retour-achat {
        background-color: #ff69b4; /* rose vif */
        border: none;
        color: white;
        font-weight: bold;
        padding: 14px 28px;
        border-radius: 30px;
        font-size: 1.1rem;
        box-shadow: 0 4px 10px rgba(255, 105, 180, 0.4);
        transition: background-color 0.3s ease, transform 0.2s ease, box-shadow 0.3s ease;
        text-decoration: none;
        display: inline-block;
        animation: pulseRetour 2.5s infinite;
    }

    .btn-retour-achat:hover {
        background-color: #ff1493;
        transform: scale(1.05);
        box-shadow: 0 6px 14px rgba(255, 20, 147, 0.6);
    }

    @keyframes pulseRetour {
        0%, 100% {
            box-shadow: 0 4px 10px rgba(255, 105, 180, 0.4);
        }
        50% {
            box-shadow: 0 6px 20px rgba(255, 105, 180, 0.6);
        }
    }


</style>


<div class="container mt-5">
    <h2 class="mb-4">📬 Contactez-nous</h2>

    <?php if ($message_envoye): ?>
        <div class="alert alert-success">Merci, votre message a été envoyé avec succès !</div>
    <?php endif; ?>

    <form method="POST" class="row g-3">
        <div class="col-md-6">
            <label for="nom" class="form-label">Nom</label>
            <input type="text" class="form-control" id="nom" name="nom" required>
        </div>
        <div class="col-md-6">
            <label for="email" class="form-label">Adresse email</label>
            <input type="email" class="form-control" id="email" name="email" required>
        </div>
        <div class="col-12">
            <label for="sujet" class="form-label">Sujet</label>
            <input type="text" class="form-control" id="sujet" name="sujet" required>
        </div>
        <div class="col-12">
            <label for="message" class="form-label">Message</label>
            <textarea class="form-control" id="message" name="message" rows="5" required></textarea>
        </div>
        <div class="col-12">
            <button type="submit" class="btn btn-primary">Envoyer</button>
        </div>
    </form>
</div>
<div class="text-center mt-5">
    <a href="../user_dashboard.php" class="btn-retour-achat">← Continuer vos achats</a>
</div>


<?php include '../assets/footer.php'; ?>
</body>
</html>
