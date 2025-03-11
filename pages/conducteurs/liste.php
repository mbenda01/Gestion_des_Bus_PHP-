<?php
require_once dirname(__DIR__, 2) . '/database.php';

// Récupérer le filtre d'état depuis l'URL
$etat = $_GET['etat'] ?? '';

// Construire la requête SQL en fonction du filtre
$sql = "SELECT * FROM bus";
if ($etat && in_array($etat, ['En circulation', 'Hors circulation', 'En panne'])) {
    $sql .= " WHERE etat = '$etat'";
}
$buses = $connexion->query($sql);
?>

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Liste des Bus</title>
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
    <h2 class="text-center text-primary">🚍 Liste des Bus</h2>

    <div class="d-flex justify-content-between mb-3">
        <form method="GET" class="d-flex">
            <label for="etat" class="me-2 align-self-center">Filtrer par état :</label>
            <select name="etat" class="form-select me-2" onchange="this.form.submit()">
                <option value="">Tous</option>
                <option value="En circulation" <?= $etat == 'En circulation' ? 'selected' : '' ?>>En circulation</option>
                <option value="Hors circulation" <?= $etat == 'Hors circulation' ? 'selected' : '' ?>>Hors circulation</option>
                <option value="En panne" <?= $etat == 'En panne' ? 'selected' : '' ?>>En panne</option>
            </select>
        </form>
        <a href="index.php?action=addBus" class="btn btn-success">+ Ajouter un Bus</a>
    </div>

    <table class="table table-hover table-bordered">
        <thead class="table-dark">
            <tr>
                <th>ID</th>
                <th>Immatriculation</th>
                <th>Type</th>
                <th>Kilométrage</th>
                <th>Places</th>
                <th>État</th>
                <th>Actions</th>
            </tr>
        </thead>
        <tbody>
            <?php while ($bus = $buses->fetch_assoc()): ?>
                <tr>
                    <td><?= $bus['id'] ?></td>
                    <td><?= htmlspecialchars($bus['immatriculation']) ?></td>
                    <td><?= htmlspecialchars($bus['type']) ?></td>
                    <td><?= htmlspecialchars($bus['kilometrage']) ?> km</td>
                    <td><?= htmlspecialchars($bus['nbre_place']) ?></td>
                    <td>
                        <span class="badge bg-<?= $bus['etat'] == 'En circulation' ? 'success' : ($bus['etat'] == 'Hors circulation' ? 'warning' : 'danger') ?>">
                            <?= $bus['etat'] ?>
                        </span>
                    </td>
                    <td>
                        <a href="index.php?action=editBus&id=<?= $bus['id'] ?>" class="btn btn-warning btn-sm">✏️ Modifier</a>
                        <a href="index.php?action=deleteBus&id=<?= $bus['id'] ?>" class="btn btn-danger btn-sm" onclick="return confirm('Voulez-vous vraiment supprimer ce bus ?')">🗑️ Supprimer</a>
                    </td>
                </tr>
            <?php endwhile; ?>
        </tbody>
    </table>

</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
