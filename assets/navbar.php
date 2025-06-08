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
                <li class="nav-item"><a class="nav-link text-white" href="index.php">Accueil</a></li>
                <li class="nav-item"><a class="nav-link text-white" href="assets/a_propos.php">À propos</a></li>
                <li class="nav-item"><a class="nav-link text-white" href="assets/contact.php">Contact</a></li>
                <li class="nav-item"><a class="nav-link text-white" href="connexion.php">Connexion</a></li>
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
