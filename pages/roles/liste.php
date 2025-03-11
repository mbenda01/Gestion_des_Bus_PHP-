<?php
require_once dirname(__DIR__, 2) . '/database.php';

// Récupérer la liste des rôles
$sql = "SELECT id, nom, description FROM roles ORDER BY id ASC";
$roles = $connexion->query($sql);
?>

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Liste des Rôles</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
        body {
            background-color: #f8f9fa;
        }
        .container {
            margin-top: 20px;
        }
    </style>
</head>
<body>

<div class="container">
    <h2 class="text-center text-primary">🔧 Liste des Rôles</h2>

    <div class="d-flex justify-content-between mb-3">
        <h5>📋 Nombre total de rôles : <strong><?= $roles->num_rows ?></strong></h5>
        <a href="index.php?action=addRole" class="btn btn-success">+ Ajouter un Rôle</a>
    </div>

    <table class="table table-hover table-bordered">
        <thead class="table-dark">
            <tr>
                <th>ID</th>
                <th>Nom</th>
                <th>Description</th>
                <th>Actions</th>
            </tr>
        </thead>
        <tbody>
            <?php while ($role = $roles->fetch_assoc()): ?>
                <tr>
                    <td><?= $role['id'] ?></td>
                    <td><?= htmlspecialchars($role['nom']) ?></td>
                    <td><?= htmlspecialchars($role['description']) ?></td>
                    <td>
                        <a href="index.php?action=editRole&id=<?= $role['id'] ?>" class="btn btn-warning btn-sm">✏️ Modifier</a>
                        <a href="index.php?action=deleteRole&id=<?= $role['id'] ?>" class="btn btn-danger btn-sm" onclick="return confirm('Voulez-vous vraiment supprimer ce rôle ?')">🗑️ Supprimer</a>
                    </td>
                </tr>
            <?php endwhile; ?>
        </tbody>
    </table>

</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
