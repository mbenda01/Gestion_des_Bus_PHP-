<?php
require_once dirname(__DIR__, 2) . '/database.php';

$sql = "SELECT * FROM lignes ORDER BY numero ASC";
$lignes = $connexion->query($sql);
?>

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Liste des Lignes</title>
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
    <h2 class="text-center text-primary">🛣️ Liste des Lignes</h2>

    <div class="d-flex justify-content-between align-items-center mb-3">
        <h5>📋 Nombre total de lignes : <strong><?= $lignes->num_rows ?></strong></h5>
        <div>
            <a href="index.php?action=addLigne" class="btn btn-success me-2">+ Ajouter une Ligne</a>
            <button id="downloadPdfBtn" class="btn btn-danger">📄 Télécharger PDF</button>
        </div>
    </div>

    <div class="table-container">
        <table class="table table-hover table-bordered">
            <thead class="table-dark">
                <tr>
                    <th>ID</th>
                    <th>Numéro</th>
                    <th>Distance (km)</th>
                    <th>Actions</th>
                </tr>
            </thead>
            <tbody>
                <?php while ($ligne = $lignes->fetch_assoc()): ?>
                    <tr>
                        <td><?= $ligne['id'] ?></td>
                        <td><?= htmlspecialchars($ligne['numero']) ?></td>
                        <td><?= htmlspecialchars($ligne['nombre_kilometre']) ?> km</td>
                        <td>
                            <a href="index.php?action=editLigne&id=<?= $ligne['id'] ?>" class="btn btn-warning btn-sm">✏️ Modifier</a>
                            <a href="index.php?action=deleteLigne&id=<?= $ligne['id'] ?>" class="btn btn-danger btn-sm" onclick="return confirm('Voulez-vous vraiment supprimer cette ligne ?')">🗑️ Supprimer</a>
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
            alert("✅ Simulation : Le PDF des lignes a été généré avec succès !");
        }, 3000);
    });
</script>

</body>
</html>
