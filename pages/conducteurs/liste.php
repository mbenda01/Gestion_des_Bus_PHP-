<?php
require_once dirname(__DIR__, 2) . '/database.php';

// Récupérer les conducteurs depuis la base
$sql = "SELECT * FROM conducteurs ORDER BY nom ASC";
$conducteurs = $connexion->query($sql);
?>

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Liste des Conducteurs</title>
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
    <h2 class="text-center text-primary">👨‍✈️ Liste des Conducteurs</h2>

    <!-- Boutons d'action alignés gauche/droite -->
    <div class="d-flex justify-content-between align-items-center mb-3">
        <a href="index.php?action=addConducteur" class="btn btn-success">+ Ajouter un Conducteur</a>
        <button id="downloadPdfBtn" class="btn btn-danger">📄 Télécharger PDF</button>
    </div>

    <div class="table-container">
        <table class="table table-hover table-bordered">
            <thead class="table-dark">
                <tr>
                    <th>ID</th>
                    <th>Matricule</th>
                    <th>Nom</th>
                    <th>Prénom</th>
                    <th>Téléphone</th>
                    <th>Type de permis</th>
                    <th>Actions</th>
                </tr>
            </thead>
            <tbody>
                <?php while ($conducteur = $conducteurs->fetch_assoc()): ?>
                    <tr>
                        <td><?= $conducteur['id'] ?></td>
                        <td><?= htmlspecialchars($conducteur['matricule']) ?></td>
                        <td><?= htmlspecialchars($conducteur['nom']) ?></td>
                        <td><?= htmlspecialchars($conducteur['prenom']) ?></td>
                        <td><?= htmlspecialchars($conducteur['telephone']) ?></td>
                        <td><?= htmlspecialchars($conducteur['type_permis']) ?></td>
                        <td>
                            <a href="index.php?action=editConducteur&id=<?= $conducteur['id'] ?>" class="btn btn-warning btn-sm">✏️ Modifier</a>
                            <a href="index.php?action=deleteConducteur&id=<?= $conducteur['id'] ?>" class="btn btn-danger btn-sm" onclick="return confirm('Voulez-vous vraiment supprimer ce conducteur ?')">🗑️ Supprimer</a>
                        </td>
                    </tr>
                <?php endwhile; ?>
            </tbody>
        </table>
    </div>
</div>

<div class="modal fade" id="loadingModal" tabindex="-1" aria-labelledby="loadingModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-body text-center">
                <div class="spinner-border text-primary" role="status">
                    <span class="visually-hidden">Chargement...</span>
                </div>
                <p class="mt-3">Génération du PDF en cours... Veuillez patienter.</p>
            </div>
        </div>
    </div>
</div>


<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
<script>
    document.getElementById('downloadPdfBtn').addEventListener('click', function () {
        const loadingModal = new bootstrap.Modal(document.getElementById('loadingModal'));
        loadingModal.show();

        setTimeout(() => {
            loadingModal.hide();
            alert("✅ Simulation : Le PDF des conducteurs a été généré avec succès !");
        }, 3000);
    });
</script>

</body>
</html>
