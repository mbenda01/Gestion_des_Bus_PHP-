<?php
require_once dirname(__DIR__, 2) . '/database.php';

// Récupérer la liste des stations avec les lignes associées
$sql = "SELECT stations.id, stations.numero, stations.nom, stations.adresse, lignes.numero AS ligne_numero 
        FROM stations 
        JOIN lignes ON stations.ligne_id = lignes.id 
        ORDER BY lignes.numero, stations.numero ASC";
$stations = $connexion->query($sql);
?>

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Liste des Stations</title>
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
    <h2 class="text-center text-primary">🏢 Liste des Stations</h2>

    <div class="d-flex justify-content-between mb-3">
        <h5>📋 Nombre total de stations : <strong><?= $stations->num_rows ?></strong></h5>
        <a href="index.php?action=addStation" class="btn btn-success">+ Ajouter une Station</a>
    </div>

    <table class="table table-hover table-bordered">
        <thead class="table-dark">
            <tr>
                <th>ID</th>
                <th>Numéro</th>
                <th>Nom</th>
                <th>Adresse</th>
                <th>Ligne Associée</th>
                <th>Actions</th>
            </tr>
        </thead>
        <tbody>
            <?php while ($station = $stations->fetch_assoc()): ?>
                <tr>
                    <td><?= $station['id'] ?></td>
                    <td><?= htmlspecialchars($station['numero']) ?></td>
                    <td><?= htmlspecialchars($station['nom']) ?></td>
                    <td><?= htmlspecialchars($station['adresse']) ?></td>
                    <td><?= htmlspecialchars($station['ligne_numero']) ?></td>
                    <td>
                        <a href="index.php?action=editStation&id=<?= $station['id'] ?>" class="btn btn-warning btn-sm">✏️ Modifier</a>
                        <a href="index.php?action=deleteStation&id=<?= $station['id'] ?>" class="btn btn-danger btn-sm" onclick="return confirm('Voulez-vous vraiment supprimer cette station ?')">🗑️ Supprimer</a>
                    </td>
                </tr>
            <?php endwhile; ?>
        </tbody>
    </table>

</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
