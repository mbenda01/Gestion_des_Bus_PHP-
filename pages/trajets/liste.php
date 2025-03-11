<?php
require_once dirname(__DIR__, 2) . '/database.php';

// Récupérer la liste des trajets avec les détails associés (ligne, bus, conducteur)
$sql = "SELECT trajets.id, trajets.date, trajets.type, trajets.nbre_tickets, trajets.tickets_vendus, 
               lignes.numero AS ligne_numero, bus.immatriculation AS bus_immatriculation, 
               CONCAT(conducteurs.nom, ' ', conducteurs.prenom) AS conducteur_nom
        FROM trajets
        JOIN lignes ON trajets.ligne_id = lignes.id
        JOIN bus ON trajets.bus_id = bus.id
        JOIN conducteurs ON trajets.conducteur_id = conducteurs.id
        ORDER BY trajets.date DESC, trajets.id DESC";
$trajets = $connexion->query($sql);
?>

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Liste des Trajets</title>
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
    <h2 class="text-center text-primary">🚍 Liste des Trajets</h2>

    <div class="d-flex justify-content-between mb-3">
        <h5>📋 Nombre total de trajets : <strong><?= $trajets->num_rows ?></strong></h5>
        <a href="index.php?action=addTrajet" class="btn btn-success">+ Ajouter un Trajet</a>
    </div>

    <table class="table table-hover table-bordered">
        <thead class="table-dark">
            <tr>
                <th>ID</th>
                <th>Date</th>
                <th>Type</th>
                <th>Ligne</th>
                <th>Bus</th>
                <th>Conducteur</th>
                <th>Tickets Disponibles</th>
                <th>Tickets Vendus</th>
                <th>Actions</th>
            </tr>
        </thead>
        <tbody>
            <?php while ($trajet = $trajets->fetch_assoc()): ?>
                <tr>
                    <td><?= $trajet['id'] ?></td>
                    <td><?= htmlspecialchars($trajet['date']) ?></td>
                    <td><?= htmlspecialchars($trajet['type']) ?></td>
                    <td><?= htmlspecialchars($trajet['ligne_numero']) ?></td>
                    <td><?= htmlspecialchars($trajet['bus_immatriculation']) ?></td>
                    <td><?= htmlspecialchars($trajet['conducteur_nom']) ?></td>
                    <td><?= htmlspecialchars($trajet['nbre_tickets']) ?></td>
                    <td><?= htmlspecialchars($trajet['tickets_vendus']) ?></td>
                    <td>
                        <a href="index.php?action=editTrajet&id=<?= $trajet['id'] ?>" class="btn btn-warning btn-sm">✏️ Modifier</a>
                        <a href="index.php?action=deleteTrajet&id=<?= $trajet['id'] ?>" class="btn btn-danger btn-sm" onclick="return confirm('Voulez-vous vraiment supprimer ce trajet ?')">🗑️ Supprimer</a>
                    </td>
                </tr>
            <?php endwhile; ?>
        </tbody>
    </table>

</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
