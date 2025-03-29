<div class="d-flex">
    <!-- Sidebar -->
    <div class="bg-dark text-white vh-100 p-3 d-flex flex-column justify-content-between" style="width: 250px; position: fixed;">
        <div>
            <h4 class="text-center">Gestion des Bus</h4>
            <ul class="nav flex-column">
                <?php if ($_SESSION['role'] === 'Admin' || $_SESSION['role'] === 'Responsable de Trajet'): ?>
                    <li class="nav-item">
                        <a class="nav-link text-light fw-bold" href="index.php?action=listeBus">🚌 Bus</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link text-light fw-bold" href="index.php?action=listeConducteurs">👨‍✈️ Conducteurs</a>
                    </li>
                <?php endif; ?>

                <?php if ($_SESSION['role'] === 'Admin' || $_SESSION['role'] === 'Responsable de Parc'): ?>
                    <li class="nav-item">
                        <a class="nav-link text-light fw-bold" href="index.php?action=listeLignes">🛣️ Lignes</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link text-light fw-bold" href="index.php?action=listeArrets">🚏 Arrêts</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link text-light fw-bold" href="index.php?action=listeTrajets">🗺️ Trajets</a>
                    </li>
                <?php endif; ?>

                <?php if ($_SESSION['role'] === 'Admin'): ?>
                    <li class="nav-item">
                        <a class="nav-link text-light fw-bold" href="index.php?action=listeUsers">👥 Utilisateurs</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link text-light fw-bold" href="index.php?action=statistiques">📊 Statistiques</a>
                    </li>
                <?php endif; ?>
            </ul>
        </div>

        <div class="text-center mt-3">
            <?php
            $profile_image = !empty($_SESSION['profile_image'])
                ? "/Gestion_des_Bus_PHP/assets/" . $_SESSION['profile_image']
                : "/Gestion_des_Bus_PHP/assets/default.png";
            ?>
            <img src="<?= $profile_image ?>" id="userIcon" alt="Photo de profil"
                 class="rounded-circle border border-white" width="60" height="60" style="cursor: pointer;">

            <p class="mt-2"><?= $_SESSION['prenom'] ?? 'Utilisateur' ?></p>
            <a class="nav-link text-danger fw-bold" href="index.php?action=logout">Déconnexion</a>
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
                <img id="profileImage" src="<?= $profile_image ?>" alt="Photo de Profil" class="modal-profile-picture rounded-circle mb-3" width="100" height="100">
                
                <div class="mt-3 mb-4">
                    <label for="profileImageInput" class="form-label text-dark">Changer l'image de profil :</label>
                    <input type="file" id="profileImageInput" accept="image/*" class="form-control">
                </div>
                
                <div class="user-info text-start text-dark">
                    <p><strong>Nom :</strong> <?= $_SESSION['nom'] ?? 'Fall' ?></p>
                    <p><strong>Prénom :</strong> <?= $_SESSION['prenom'] ?? 'Bachir' ?></p>
                    <p><strong>Téléphone :</strong> <?= $_SESSION['telephone'] ?? '77 686 67 77' ?></p>
                    <p><strong>Email :</strong> <?= $_SESSION['email'] ?? 'chef@gmail.com' ?></p>
                    <p><strong>Adresse :</strong> <?= $_SESSION['adresse'] ?? 'Yoff' ?></p>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Fermer</button>
            </div>
        </div>
    </div>
</div>

<!-- Scripts nécessaires -->
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>

<script>
    // Afficher le modal lorsqu'on clique sur la photo de profil
    document.getElementById('userIcon').addEventListener('click', () => {
        const userProfileModal = new bootstrap.Modal(document.getElementById('userProfileModal'));
        userProfileModal.show();
    });

    // Prévisualiser l'image de profil avant téléchargement
    document.getElementById('profileImageInput').addEventListener('change', (event) => {
        const file = event.target.files[0];
        if (file) {
            const reader = new FileReader();
            reader.onload = (e) => {
                document.getElementById('profileImage').src = e.target.result;
            };
            reader.readAsDataURL(file);
        }
    });
</script>
