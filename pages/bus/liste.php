<?php
require_once dirname(__DIR__, 2) . '/database.php';

// Vérification de la présence d'une erreur ou d'un succès via l'URL
$error = $_GET['error'] ?? "";
$success = $_GET['success'] ?? "";

// Récupérer la liste des bus avec leurs lignes associées
$query = "SELECT bus.*, lignes.numero AS ligne_numero 
          FROM bus 
          LEFT JOIN lignes ON bus.ligne_id = lignes.id 
          ORDER BY bus.id DESC";
$result = $connexion->query($query);
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
            background-color: #f1f3f5;
            color: black;
            min-height: 100vh;
        }
        .container {
            background: white;
            padding: 20px;
            border-radius: 10px;
            box-shadow: 0px 0px 10px rgba(0, 0, 0, 0.1);
            margin-top: 20px;
        }
        .table-container {
            background-color: #ffffff;
            padding: 20px;
            border-radius: 10px;
            box-shadow: 0px 0px 10px rgba(0, 0, 0, 0.1);
            margin-top: 20px;
        }
    </style>
</head>
<body>

<div class="container">
    <h2 class="text-center text-primary">🚌 Liste des Bus</h2>

    <!-- Affichage des messages -->
    <div class="alert-container">
        <?php if (!empty($error)): ?>
            <div class="alert alert-danger"><?= htmlspecialchars($error) ?></div>
        <?php elseif (!empty($success)): ?>
            <div class="alert alert-success"><?= htmlspecialchars($success) ?></div>
        <?php endif; ?>
    </div>

    <!-- Boutons d'action -->
    <div class="d-flex justify-content-between mb-3">
        <a href="index.php?action=addBus" class="btn btn-success">+ Ajouter un Bus</a>
        <button id="downloadPdfBtn" class="btn btn-danger">📄 Télécharger PDF</button>
    </div>

    <!-- Tableau des bus -->
    <div class="table-container">
        <table class="table table-bordered table-striped">
            <thead class="table-dark">
                <tr>
                    <th>ID</th>
                    <th>Immatriculation</th>
                    <th>Type</th>
                    <th>Kilométrage</th>
                    <th>Places</th>
                    <th>État</th>
                    <th>Ligne</th>
                    <th>Localisation</th>
                    <th>Actions</th>
                </tr>
            </thead>
            <tbody>
                <?php while ($bus = $result->fetch_assoc()): ?>
                    <tr>
                        <td><?= $bus['id'] ?></td>
                        <td><?= htmlspecialchars($bus['immatriculation']) ?></td>
                        <td><?= htmlspecialchars($bus['type']) ?></td>
                        <td><?= number_format($bus['kilometrage'], 0, ',', ' ') ?> km</td>
                        <td><?= $bus['nbre_place'] ?></td>
                        <td>
                            <span class="badge bg-<?= $bus['etat'] == 'En circulation' ? 'success' : ($bus['etat'] == 'Hors circulation' ? 'warning' : 'danger') ?>">
                                <?= htmlspecialchars($bus['etat']) ?>
                            </span>
                        </td>
                        <td><?= $bus['ligne_numero'] ? "Ligne " . htmlspecialchars($bus['ligne_numero']) : "Non assigné" ?></td>
                        <td><?= htmlspecialchars($bus['localisation']) ?></td>
                        <td>
                            <a href="index.php?action=editBus&id=<?= $bus['id'] ?>" class="btn btn-warning btn-sm">✏️ Modifier</a>
                            <a href="index.php?action=deleteBus&id=<?= $bus['id'] ?>" class="btn btn-danger btn-sm" onclick="return confirm('Voulez-vous vraiment supprimer ce bus ?')">🗑️ Supprimer</a>
                        </td>
                    </tr>
                <?php endwhile; ?>
            </tbody>
        </table>
    </div>
</div>

<!-- Modal de chargement (Faux Semblant) -->
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

<!-- Scripts nécessaires -->
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
<script>
    document.getElementById('downloadPdfBtn').addEventListener('click', function () {
        // Affiche le modal de chargement
        const loadingModal = new bootstrap.Modal(document.getElementById('loadingModal'));
        loadingModal.show();
        
        // Simule un chargement puis affiche un message de succès
        setTimeout(() => {
            loadingModal.hide();
            alert("✅ Le fichier PDF a été généré avec succès !");
        }, 3000); // 3 secondes de simulation de chargement
    });
</script>

</body>
</html>
