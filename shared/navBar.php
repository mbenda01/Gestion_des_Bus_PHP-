// navBar.php
<?php
require_once 'authMiddleware.php';
?>

<nav class="navbar navbar-expand-lg navbar-dark bg-dark">
    <div class="container-fluid">
        <a class="navbar-brand" href="/index.php">TransitFlow</a>
        <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav" aria-controls="navbarNav" aria-expanded="false" aria-label="Toggle navigation">
            <span class="navbar-toggler-icon"></span>
        </button>
        <div class="collapse navbar-collapse" id="navbarNav">
            <ul class="navbar-nav">
                <li class="nav-item">
                    <a class="nav-link" href="/index.php">Accueil</a>
                </li>
                <?php if (\$_SESSION['role'] == 'admin'): ?>
                    <li class="nav-item">
                        <a class="nav-link" href="/pages/bus/liste.php">Bus</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="/pages/conducteurs/liste.php">Conducteurs</a>
                    </li>
                <?php endif; ?>
                <li class="nav-item">
                    <a class="nav-link" href="/pages/stats.php">Statistiques</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link text-danger" href="/pages/auth/logout.php">Déconnexion</a>
                </li>
            </ul>
        </div>
    </div>
</nav>
