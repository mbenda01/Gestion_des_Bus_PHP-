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
            background-color: #f1f3f5; /* Gris clair pour le fond de la page */
            color: black;
            min-height: 100vh;
        }

        .container {
            background: white; /* Sections avec fond blanc */
            padding: 20px;
            border-radius: 10px;
            box-shadow: 0px 0px 10px rgba(0, 0, 0, 0.1);
        }

        /* Section des alertes (success/error) */
        .alert-container {
            margin-bottom: 20px;
        }

        /* Conteneur pour le tableau des bus */
        .table-container {
            background-color: #ffffff; /* Fond blanc pour la section contenant le tableau */
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

        <!-- Filtre pour l'état des bus -->
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
                                <a href="index.php?action=deleteBus&id=<?= $bus['id'] ?>" class="btn btn-danger btn-sm" onclick="return confirm('Êtes-vous sûr de vouloir supprimer ce bus ?')">🗑️ Supprimer</a>
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
