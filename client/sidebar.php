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
                    <a class="nav-link text-light fw-bold" href="reservations/notifications.php">🔔 Notifications</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link text-light fw-bold" href="reservations/abonnements.php">🛒 Abonnements</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link text-light fw-bold" href="reservations/contacts.php">📞 Nous Contacter</a>
                </li>
            </ul>
        </div>

        <div class="text-center mt-3">
            <?php
            $profile_image = !empty($_SESSION['profile_image'])
                ? "/Gestion_des_Bus_PHP/assets/" . $_SESSION['profile_image']
                : "/Gestion_des_Bus_PHP/assets/default.png";
            ?>
            <!-- Image de profil cliquable -->
            <img src="<?= $profile_image ?>" alt="Photo de profil" class="rounded-circle border border-white" width="60" height="60" id="profileImageClickable" style="cursor: pointer;">
            <p class="mt-2"><?= $_SESSION['prenom'] ?? 'Utilisateur' ?></p>
            <a class="nav-link text-danger fw-bold" href="auth/logout.php">Déconnexion</a>
        </div>
    </div>
</div>

<!-- Modal Profil Utilisateur -->
<div class="modal fade" id="userProfileModal" tabindex="-1" aria-labelledby="userProfileModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title text-dark" id="userProfileModalLabel">Profil Utilisateur</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body text-center">
                <img id="profileImage" src="<?= $profile_image ?>" alt="Photo de Profil" class="rounded-circle mb-3" width="100" height="100">
                <div class="mt-3 mb-4">
                    <label for="profileImageInput" class="form-label text-dark">Changer l'image de profil :</label>
                    <input type="file" id="profileImageInput" accept="image/*" class="form-control">
                </div>
                <div class="user-info text-start text-dark">
                    <p><strong>Nom :</strong> Fall</p>
                    <p><strong>Prénom :</strong> <?= $_SESSION['prenom'] ?? 'Utilisateur' ?></p>
                    <p><strong>Téléphone :</strong> 77 686 67 77</p>
                    <p><strong>Email :</strong> fallmoussa@gmail.com</p>
                    <p><strong>Adresse :</strong> Yoff</p>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Fermer</button>
            </div>
        </div>
    </div>
</div>

<!-- Bootstrap JavaScript -->
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
<script>
    document.getElementById('profileImageClickable').addEventListener('click', () => {
        const userProfileModal = new bootstrap.Modal(document.getElementById('userProfileModal'));
        userProfileModal.show();
    });
</script>
