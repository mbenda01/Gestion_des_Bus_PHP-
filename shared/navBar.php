<nav class="navbar navbar-expand-lg navbar-dark bg-dark shadow">
    <div class="container">
        <a class="navbar-brand fw-bold text-warning" href="index.php">🚍 Gestion des Bus</a>
        <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav" aria-controls="navbarNav" aria-expanded="false" aria-label="Toggle navigation">
            <span class="navbar-toggler-icon"></span>
        </button>
        <div class="collapse navbar-collapse" id="navbarNav">
            <ul class="navbar-nav ms-auto">
                <li class="nav-item">
                    <a class="nav-link text-light" href="index.php?action=listeBus">Bus</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link text-light" href="index.php?action=listeConducteurs">Conducteurs</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link text-light" href="index.php?action=listeLignes">Lignes</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link text-danger fw-bold" href="index.php?action=logout">Déconnexion</a>
                </li>
            </ul>
        </div>
    </div>
</nav>

<style>
    .navbar-nav .nav-link:hover {
        background-color: rgba(255, 255, 255, 0.2);
        border-radius: 5px;
    }
</style>
