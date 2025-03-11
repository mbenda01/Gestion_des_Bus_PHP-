<?php
require_once dirname(__DIR__, 2) . '/database.php';

$error = "";
$success = "";

// Récupérer les lignes, bus et conducteurs disponibles
$lignes = $connexion->query("SELECT id, numero FROM lignes ORDER BY numero ASC");
$bus = $connexion->query("SELECT id, immatriculation FROM bus WHERE etat = 'En circulation' ORDER BY immatriculation ASC");
$conducteurs = $connexion->query("SELECT id, CONCAT(nom, ' ', prenom) AS nom_complet FROM conducteurs ORDER BY nom ASC");

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $date = $_POST['date'];
    $type = $_POST['type'];
    $nbre_tickets = intval($_POST['nbre_tickets']);
    $ligne_id = intval($_POST['ligne_id']);
    $bus_id = intval($_POST['bus_id']);
    $conducteur_id = intval($_POST['conducteur_id']);

    // Validation des entrées
    if (empty($date)) {
        $error = "Veuillez sélectionner une date.";
    } elseif (!in_array($type, ['Aller', 'Retour'])) {
        $error = "Type de trajet invalide.";
    } elseif ($nbre_tickets <= 0) {
        $error = "Le nombre de tickets doit être supérieur à 0.";
    } elseif ($ligne_id <= 0 || $bus_id <= 0 || $conducteur_id <= 0) {
        $error = "Veuillez sélectionner des valeurs valides pour la ligne, le bus et le conducteur.";
    }

    // Enregistrement si aucune erreur
    if (empty($error)) {
        $stmt = $connexion->prepare("INSERT INTO trajets (date, type, nbre_tickets, ligne_id, bus_id, conducteur_id) VALUES (?, ?, ?, ?, ?, ?)");
        $stmt->bind_param("ssiiii", $date, $type, $nbre_tickets, $ligne_id, $bus_id, $conducteur_id);
        
        if ($stmt->execute()) {
            $success = "✅ Trajet ajouté avec succès !";
        } else {
            $error = "❌ Erreur lors de l'ajout du trajet.";
        }
        $stmt->close();
    }
}
?>

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Ajouter un Trajet</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
        body {
            background-color: #f8f9fa;
        }
        .container {
            max-width: 500px;
            background: white;
            padding: 20px;
            border-radius: 10px;
            box-shadow: 0px 0px 10px rgba(0, 0, 0, 0.1);
            margin-top: 50px;
        }
    </style>
</head>
<body>
    <div class="container">
        <h2 class="text-center text-primary">🚍 Ajouter un Trajet</h2>

        <?php if (!empty($error)): ?>
            <div class="alert alert-danger"><?= $error ?></div>
        <?php elseif (!empty($success)): ?>
            <div class="alert alert-success"><?= $success ?></div>
        <?php endif; ?>

        <form action="#" method="POST">
            <div class="mb-3">
                <label for="date" class="form-label">Date</label>
                <input type="date" class="form-control" id="date" name="date" required>
            </div>
            <div class="mb-3">
                <label for="type" class="form-label">Type</label>
                <select class="form-control" id="type" name="type" required>
                    <option value="Aller">Aller</option>
                    <option value="Retour">Retour</option>
                </select>
            </div>
            <div class="mb-3">
                <label for="nbre_tickets" class="form-label">Nombre de Tickets</label>
                <input type="number" class="form-control" id="nbre_tickets" name="nbre_tickets" required>
            </div>
            <div class="mb-3">
                <label for="ligne_id" class="form-label">Ligne</label>
                <select class="form-control" id="ligne_id" name="ligne_id" required>
                    <option value="">Sélectionner une ligne</option>
                    <?php while ($ligne = $lignes->fetch_assoc()): ?>
                        <option value="<?= $ligne['id'] ?>"><?= htmlspecialchars($ligne['numero']) ?></option>
                    <?php endwhile; ?>
                </select>
            </div>
            <div class="mb-3">
                <label for="bus_id" class="form-label">Bus</label>
                <select class="form-control" id="bus_id" name="bus_id" required>
                    <?php while ($busItem = $bus->fetch_assoc()): ?>
                        <option value="<?= $busItem['id'] ?>"><?= htmlspecialchars($busItem['immatriculation']) ?></option>
                    <?php endwhile; ?>
                </select>
            </div>
            <div class="mb-3">
                <label for="conducteur_id" class="form-label">Conducteur</label>
                <select class="form-control" id="conducteur_id" name="conducteur_id" required>
                    <?php while ($conducteur = $conducteurs->fetch_assoc()): ?>
                        <option value="<?= $conducteur['id'] ?>"><?= htmlspecialchars($conducteur['nom_complet']) ?></option>
                    <?php endwhile; ?>
                </select>
            </div>
            <button type="submit" class="btn btn-primary w-100">Ajouter</button>
        </form>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
