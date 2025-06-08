<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <title>Studi Glace - Accueil</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css">
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css">

    <style>
        body {
            background: #fffaf5;
            font-family: 'Segoe UI', sans-serif;
        }

        .about-section {
            padding: 60px 20px;
            text-align: center;
        }

        .about-section h2 {
            font-size: 3rem;
            margin-bottom: 20px;
            color: #ef6dc6;
        }

        .about-section p {
            font-size: 1.2rem;
            max-width: 900px;
            margin: auto;
            color: #555;
        }

        .team {
            display: flex;
            justify-content: center;
            flex-wrap: wrap;
            margin-top: 50px;
        }

        .team-member {
            margin: 20px;
            padding: 20px;
            background-color: #ec8fd4;
            border-radius: 15px;
            box-shadow: 0 2px 10px rgba(0,0,0,0.1);
            width: 280px;
            transition: transform 0.3s ease;
        }

        .team-member:hover {
            transform: scale(1.05);
        }

        .team-member img {
            width: 100px;
            height: 100px;
            border-radius: 50%;
            margin-bottom: 15px;
        }

        .team-member h4 {
            color: #4e123b;
            margin-bottom: 5px;
        }

        .team-member p {
            font-size: 0.95rem;
            color: #777;
        }

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
        <a class="navbar-brand d-flex align-items-center me-auto"  href="index.php">
            <img src="/images/creme-glacee.png" alt="Logo" width="40" height="40" class="me-2 logo-animate">
            <span class="fw-bold text-white">Studi Glace</span>
        </a>
        <button class="navbar-toggler bg-white" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav">
            <span class="navbar-toggler-icon"></span>
        </button>
        <div class="collapse navbar-collapse justify-content-end" id="navbarNav">
            <ul class="navbar-nav">
                <li class="nav-item"><a class="nav-link text-white" href="../index.php">Accueil</a></li>

                <li class="nav-item"><a class="nav-link text-white" href="../assets/contact.php">Contact</a></li>


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

</style>


<section class="about-section">
    <h2>À propos de Studi Glace</h2>
    <p >
        Studi Glace, produits artisanaux depuis 25 ans

        Depuis un quart de siècle, Studi Glace est synonyme de qualité et d’authenticité dans le domaine des glaces artisanales. Implantée au cœur de notre région, notre entreprise familiale perpétue la tradition en élaborant des recettes savoureuses avec des ingrédients soigneusement sélectionnés. Chaque parfum est le fruit d’un savoir-faire unique, mêlant passion, créativité et respect des matières premières. Chez Studi Glace, nous croyons que la gourmandise doit rimer avec naturalité, c’est pourquoi nos produits sont réalisés sans additifs artificiels ni conservateurs. Que vous soyez amateur de classiques intemporels ou en quête de nouvelles sensations, laissez-vous séduire par la fraîcheur et la richesse de nos créations, véritables instants de plaisir à partager.
    </p>

    <div class="team">
        <div class="team-member">
            <img src="https://randomuser.me/api/portraits/men/32.jpg" alt="Membre 1">
            <h4>Jean Dupont</h4>
            <p>Fondateur & Maître Glacier</p>
        </div>
        <div class="team-member">
            <img src="https://randomuser.me/api/portraits/women/45.jpg" alt="Membre 2">
            <h4>Claire Martin</h4>
            <p>Responsable Qualité</p>
        </div>
        <div class="team-member">
            <img src="/images/IMG-20240423-WA0018.jpg" alt="Membre 3">
            <h4>Mohamed Hamadat</h4>
            <p>Développeur Web</p>
        </div>
    </div>
</section>

<script>
    // Animation dynamique sur le texte d'intro
    const text = document.getElementById("description");
    const original = text.innerText;
    text.innerText = "";

    let i = 0;
    function typeWriter() {
        if (i < original.length) {
            text.innerText += original.charAt(i);
            i++;
            setTimeout(typeWriter, 25);
        }
    }
    window.onload = typeWriter;
</script>

<?php include '../assets/footer.php' ?>
</body>
</html>
