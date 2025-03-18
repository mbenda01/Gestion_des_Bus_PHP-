<?php
require_once dirname(__DIR__, 2) . '/database.php';

// Récupérer la liste des arrêts avec les lignes et les zones associées
$sql = "SELECT arrets.id, arrets.numero, arrets.nom, arrets.zone, lignes.numero AS ligne_numero 
        FROM arrets 
        JOIN lignes ON arrets.ligne_id = lignes.id 
        ORDER BY lignes.numero, arrets.numero ASC";
$arrets = $connexion->query($sql);
?>

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Liste des Arrêts</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
        body {
            background-color: #f8f9fa;
        }
        .container {
            margin-top: 20px;
            background: white;
            padding: 20px;
            border-radius: 10px;
            box-shadow: 0px 0px 10px rgba(0, 0, 0, 0.1);
        }
    </style>
</head>
<body>

<div class="container">
    <h2 class="text-center text-primary">🚏 Liste des Arrêts</h2>

    <div class="d-flex justify-content-between mb-3">
        <h5>📋 Nombre total d'arrêts : <strong><?= $arrets->num_rows ?></strong></h5>
        <a href="index.php?action=addArret" class="btn btn-success">➕ Ajouter un Arrêt</a>
    </div>

    <?php if ($arrets->num_rows > 0): ?>
        <table class="table table-hover table-bordered">
            <thead class="table-dark">
                <tr>
                    <th>ID</th>
                    <th>Numéro de l'Arrêt</th>
                    <th>Nom</th>
                    <th>Ligne Associée</th>
                    <th>Zone</th>
                    <th>Actions</th>
                </tr>
            </thead>
            <tbody>
                <?php while ($arret = $arrets->fetch_assoc()): ?>
                    <tr>
                        <td><?= $arret['id'] ?></td>
                        <td><?= htmlspecialchars($arret['numero']) ?></td>
                        <td><?= htmlspecialchars($arret['nom']) ?></td>
                        <td>🚏 Ligne <?= htmlspecialchars($arret['ligne_numero']) ?></td>
                        <td>
                            <span class="badge bg-info"><?= htmlspecialchars($arret['zone']) ?></span>
                        </td>
                        <td>
                            <a href="index.php?action=editArret&id=<?= $arret['id'] ?>" class="btn btn-warning btn-sm"> Modifier</a>
                            <a href="index.php?action=deleteArret&id=<?= $arret['id'] ?>" class="btn btn-danger btn-sm" onclick="return confirm('Voulez-vous vraiment supprimer cet arrêt ?')">Supprimer</a>
                        </td>
                    </tr>
                <?php endwhile; ?>
            </tbody>
        </table>
    <?php else: ?>
        <div class="alert alert-warning text-center">Aucun arrêt enregistré pour le moment.</div>
    <?php endif; ?>
</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
