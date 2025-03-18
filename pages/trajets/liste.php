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
            background-color: #f1f3f5; /* Gris clair pour le fond de la page */
        }

        .container {
            margin-top: 20px;
            background: white; /* Fond blanc pour chaque section */
            padding: 20px;
            border-radius: 10px;
            box-shadow: 0px 0px 10px rgba(0, 0, 0, 0.1);
        }

        .table-container {
            background-color: white;
            padding: 20px;
            border-radius: 10px;
            box-shadow: 0px 0px 10px rgba(0, 0, 0, 0.1);
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

    <!-- Container pour le tableau -->
    <div class="table-container">
        <table class="table table-hover table-bordered">
            <thead class="table-dark">
                <tr>
                    <th>#</th>
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
                <?php 
                $index = 1;
                while ($trajet = $trajets->fetch_assoc()): 
                    // Gestion du badge couleur pour les tickets vendus
                    $badgeClass = "bg-danger"; // Par défaut, rouge (aucun ticket vendu)
                    if ($trajet['tickets_vendus'] > 0 && $trajet['tickets_vendus'] < $trajet['nbre_tickets']) {
                        $badgeClass = "bg-warning"; // Orange (vente en cours)
                    } elseif ($trajet['tickets_vendus'] == $trajet['nbre_tickets']) {
                        $badgeClass = "bg-success"; // Vert (complet)
                    }
                ?>
                    <tr>
                        <td><?= $index++ ?></td>
                        <td><?= htmlspecialchars($trajet['date']) ?></td>
                        <td><?= htmlspecialchars($trajet['type']) ?></td>
                        <td><?= htmlspecialchars($trajet['ligne_numero']) ?></td>
                        <td><?= htmlspecialchars($trajet['bus_immatriculation']) ?></td>
                        <td><?= htmlspecialchars($trajet['conducteur_nom']) ?></td>
                        <td><?= htmlspecialchars($trajet['nbre_tickets']) ?></td>
                        <td><span class="badge <?= $badgeClass ?>"><?= htmlspecialchars($trajet['tickets_vendus']) ?></span></td>
                        <td>
                            <a href="index.php?action=editTrajet&id=<?= $trajet['id'] ?>" class="btn btn-warning btn-sm">✏️ Modifier</a>
                            <a href="index.php?action=deleteTrajet&id=<?= $trajet['id'] ?>" class="btn btn-danger btn-sm" onclick="return confirm('Voulez-vous vraiment supprimer ce trajet ?')">🗑️ Supprimer</a>
                        </td>
                    </tr>
                <?php endwhile; ?>
            </tbody>
        </table>
    </div>

</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
