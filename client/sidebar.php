<div class="d-flex">
    <div class="bg-dark text-white vh-100 p-3 d-flex flex-column justify-content-between" style="width: 250px; position: fixed;">
        <div>
            <h4 class="text-center">TransitFlow</h4>
            <ul class="nav flex-column">
                <li class="nav-item">
                    <a class="nav-link text-light fw-bold" href="index.php">🏠 Accueil</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link text-light fw-bold" href="reservations/reserve.php">🎟️ Réserver un Ticket</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link text-light fw-bold" href="reservations/historique.php">📜 Mon historique</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link text-light fw-bold" href="#">🔔 Notifications</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link text-light fw-bold" href="#">🛒 Panier</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link text-light fw-bold" href="#">📞 Nous Contacter</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link text-light fw-bold" href="#">🚶 Mon Trajet</a>
                </li>
            </ul>
        </div>

        <div class="text-center mt-3">
            <?php
            $profile_image = !empty($_SESSION['profile_image'])
                ? "/Gestion_des_Bus_PHP/assets/" . $_SESSION['profile_image']
                : "/Gestion_des_Bus_PHP/assets/default.png";
            ?>
            <img src="<?= $profile_image ?>" alt="Photo de profil" class="rounded-circle border border-white" width="60" height="60">
            <p class="mt-2"><?= $_SESSION['prenom'] ?? 'Utilisateur' ?></p>
            <a class="nav-link text-danger fw-bold" href="auth/logout.php">Déconnexion</a>
        </div>
    </div>
</div>
