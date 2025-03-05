<?php
require_once dirname(__DIR__, 2) . '/database.php';

// Récupérer tous les utilisateurs
$users = $connexion->query("SELECT * FROM users");
?>

<div class="container mt-4">
    <h2 class="text-center">Liste des utilisateurs</h2>
    <a href="index.php?action=addUser" class="btn btn-success mb-3">+ Ajouter un utilisateur</a>

    <table class="table table-hover table-bordered">
        <thead class="table-dark">
            <tr>
                <th>ID</th>
                <th>Prénom</th>
                <th>Nom</th>
                <th>Email</th>
                <th>Téléphone</th>
                <th>Rôle</th>
                <th>Actions</th>
            </tr>
        </thead>
        <tbody>
            <?php while ($user = $users->fetch_assoc()): ?>
                <tr>
                    <td><?= $user['id'] ?></td>
                    <td><?= $user['prenom'] ?></td>
                    <td><?= $user['nom'] ?></td>
                    <td><?= $user['email'] ?></td>
                    <td><?= $user['telephone'] ?></td>
                    <td><?= $user['role'] ?></td>
                    <td>
                        <!-- Bouton Modifier -->
                        <button type="button" class="btn btn-warning btn-sm edit-btn"
                                data-id="<?= $user['id'] ?>"
                                data-prenom="<?= htmlspecialchars($user['prenom']) ?>"
                                data-nom="<?= htmlspecialchars($user['nom']) ?>"
                                data-email="<?= htmlspecialchars($user['email']) ?>"
                                data-telephone="<?= htmlspecialchars($user['telephone']) ?>"
                                data-role="<?= $user['role'] ?>"
                                data-bs-toggle="modal"
                                data-bs-target="#editUserModal">
                            Modifier
                        </button>

                        <!-- Bouton Supprimer avec confirmation -->
                        <?php if ($user['role'] !== 'admin'): ?>
                            <a href="index.php?action=deleteUser&id=<?= $user['id'] ?>"
                               class="btn btn-danger btn-sm"
                               onclick="return confirm('Voulez-vous vraiment supprimer cet utilisateur ?')">
                               Supprimer
                            </a>
                        <?php else: ?>
                            <button class="btn btn-secondary btn-sm" disabled>Admin Protégé</button>
                        <?php endif; ?>
                    </td>
                </tr>
            <?php endwhile; ?>
        </tbody>
    </table>
</div>

<!-- Modale de modification d'un utilisateur -->
<div class="modal fade" id="editUserModal" tabindex="-1" aria-labelledby="editUserModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="editUserModalLabel">Modifier un utilisateur</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <form action="index.php?action=editUser" method="POST">
                <div class="modal-body">
                    <input type="hidden" name="id" id="editUserId">
                    <div class="mb-3">
                        <label for="editPrenom" class="form-label">Prénom</label>
                        <input type="text" class="form-control" id="editPrenom" name="prenom" required>
                    </div>
                    <div class="mb-3">
                        <label for="editNom" class="form-label">Nom</label>
                        <input type="text" class="form-control" id="editNom" name="nom" required>
                    </div>
                    <div class="mb-3">
                        <label for="editEmail" class="form-label">Email</label>
                        <input type="email" class="form-control" id="editEmail" name="email" required>
                    </div>
                    <div class="mb-3">
                        <label for="editTelephone" class="form-label">Téléphone</label>
                        <input type="text" class="form-control" id="editTelephone" name="telephone" required>
                    </div>
                    <div class="mb-3">
                        <label for="editRole" class="form-label">Rôle</label>
                        <select class="form-control" id="editRole" name="role" required>
                            <option value="admin">Admin</option>
                            <option value="Responsable de Parc">Responsable de Parc</option>
                            <option value="Responsable de Trajet">Responsable de Trajet</option>
                        </select>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="submit" class="btn btn-success">Enregistrer</button>
                </div>
            </form>
        </div>
    </div>
</div>

<!-- Script pour gérer la modification -->
<script>
document.addEventListener("DOMContentLoaded", function() {
    document.querySelectorAll('.edit-btn').forEach(button => {
        button.addEventListener('click', function() {
            document.getElementById('editUserId').value = this.dataset.id;
            document.getElementById('editPrenom').value = this.dataset.prenom;
            document.getElementById('editNom').value = this.dataset.nom;
            document.getElementById('editEmail').value = this.dataset.email;
            document.getElementById('editTelephone').value = this.dataset.telephone;
            document.getElementById('editRole').value = this.dataset.role;
        });
    });
});
</script>
