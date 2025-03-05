<div class="d-flex">
    <!-- Sidebar -->
    <div class="bg-dark text-white vh-100 p-3 d-flex flex-column justify-content-between" style="width: 250px; position: fixed;">
        <div>
            <h4 class="text-center"> Gestion des Bus</h4>
            <ul class="nav flex-column">
                <li class="nav-item">
                    <a class="nav-link text-light" href="index.php?action=listeBus">🚌 Bus</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link text-light" href="index.php?action=listeConducteurs">👨‍✈️ Conducteurs</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link text-light" href="index.php?action=listeLignes">🛣️ Lignes</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link text-light" href="index.php?action=listeUsers">👥 Utilisateurs</a>
                </li>
            </ul>
        </div>

        <!-- Affichage de la photo de profil et prénom en bas -->
        <div class="text-center mt-3">
            <?php
            // Vérifier si une image de profil est enregistrée en session
            $profile_image = isset($_SESSION['profile_image']) && !empty($_SESSION['profile_image'])
                ? "/Gestion_des_Bus_PHP/assets/" . $_SESSION['profile_image']
                : "/Gestion_des_Bus_PHP/assets/default.png";
            ?>

            <img src="<?= $profile_image ?>" alt="Photo de profil"
                 class="rounded-circle border border-white" width="60" height="60">

            <p class="mt-2"><?= $_SESSION['prenom'] ?? 'Utilisateur' ?></p>
            <a class="nav-link text-danger fw-bold" href="index.php?action=logout"> Déconnexion</a>
        </div>
    </div>
</div>
