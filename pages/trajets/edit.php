<?php
require_once dirname(__DIR__, 2) . '/database.php';

$error = "";
$success = "";
$id = $_GET['id'] ?? null;

// Vérifier si un ID valide est fourni
if (!$id || !is_numeric($id)) {
    header("Location: /Gestion_des_bus_PHP/index.php?action=listeTrajets&error=invalidID");
    exit;
}

// Récupérer les informations du trajet
$stmt = $connexion->prepare("SELECT * FROM trajets WHERE id = ?");
$stmt->bind_param("i", $id);
$stmt->execute();
$result = $stmt->get_result();
$trajet = $result->fetch_assoc();

if (!$trajet) {
    header("Location: /Gestion_des_bus_PHP/index.php?action=listeTrajets&error=notfound");
    exit;
}

// Récupérer les lignes, bus et conducteurs existants
$lignes = $connexion->query("SELECT id, numero FROM lignes ORDER BY numero ASC");
$bus = $connexion->query("SELECT id, immatriculation FROM bus WHERE etat = 'En circulation' ORDER BY immatriculation ASC");
$conducteurs = $connexion->query("SELECT id, CONCAT(nom, ' ', prenom) AS nom_complet FROM conducteurs ORDER BY nom ASC");

// Mise à jour du trajet si le formulaire est soumis
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $date = $_POST['date'];
    $type = $_POST['type'];
    $nbre_tickets = intval($_POST['nbre_tickets']);
    $tickets_vendus = intval($_POST['tickets_vendus']);
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
    } elseif ($tickets_vendus < 0 || $tickets_vendus > $nbre_tickets) {
        $error = "Le nombre de tickets vendus doit être compris entre 0 et le nombre de tickets disponibles.";
    } elseif ($ligne_id <= 0 || $bus_id <= 0 || $conducteur_id <= 0) {
        $error = "Veuillez sélectionner des valeurs valides pour la ligne, le bus et le conducteur.";
    }

    // Mise à jour si aucune erreur
    if (empty($error)) {
        $stmt = $connexion->prepare("UPDATE trajets SET date = ?, type = ?, nbre_tickets = ?, tickets_vendus = ?, ligne_id = ?, bus_id = ?, conducteur_id = ? WHERE id = ?");
        $stmt->bind_param("ssiiiiii", $date, $type, $nbre_tickets, $tickets_vendus, $ligne_id, $bus_id, $conducteur_id, $id);
        
        if ($stmt->execute()) {
            $success = "✅ Trajet mis à jour avec succès !";
            // Recharger les données du trajet après modification
            $trajet = [
                'date' => $date,
                'type' => $type,
                'nbre_tickets' => $nbre_tickets,
                'tickets_vendus' => $tickets_vendus,
                'ligne_id' => $ligne_id,
                'bus_id' => $bus_id,
                'conducteur_id' => $conducteur_id
            ];
        } else {
            $error = "❌ Erreur lors de la mise à jour du trajet.";
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
    <title>Modifier un Trajet</title>
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
        <h2 class="text-center text-primary">✏️ Modifier un Trajet</h2>

        <!-- Affichage des messages -->
        <?php if (!empty($error)): ?>
            <div class="alert alert-danger"><?= $error ?></div>
        <?php elseif (!empty($success)): ?>
            <div class="alert alert-success"><?= $success ?></div>
        <?php endif; ?>

        <form action="#" method="POST">
            <div class="mb-3">
                <label for="date" class="form-label">Date</label>
                <input type="date" class="form-control" id="date" name="date" value="<?= htmlspecialchars($trajet['date']) ?>" required>
            </div>
            <div class="mb-3">
                <label for="type" class="form-label">Type</label>
                <select class="form-control" id="type" name="type" required>
                    <option value="Aller" <?= $trajet['type'] === 'Aller' ? 'selected' : '' ?>>Aller</option>
                    <option value="Retour" <?= $trajet['type'] === 'Retour' ? 'selected' : '' ?>>Retour</option>
                </select>
            </div>
            <div class="mb-3">
                <label for="nbre_tickets" class="form-label">Nombre de Tickets</label>
                <input type="number" class="form-control" id="nbre_tickets" name="nbre_tickets" value="<?= htmlspecialchars($trajet['nbre_tickets']) ?>" required>
            </div>
            <div class="mb-3">
                <label for="tickets_vendus" class="form-label">Tickets Vendus</label>
                <input type="number" class="form-control" id="tickets_vendus" name="tickets_vendus" value="<?= htmlspecialchars($trajet['tickets_vendus']) ?>" required>
            </div>
            <div class="mb-3">
                <label for="ligne_id" class="form-label">Ligne</label>
                <select class="form-control" id="ligne_id" name="ligne_id" required>
                    <?php while ($ligne = $lignes->fetch_assoc()): ?>
                        <option value="<?= $ligne['id'] ?>" <?= $trajet['ligne_id'] == $ligne['id'] ? 'selected' : '' ?>>
                            <?= htmlspecialchars($ligne['numero']) ?>
                        </option>
                    <?php endwhile; ?>
                </select>
            </div>
            <div class="mb-3">
                <label for="bus_id" class="form-label">Bus</label>
                <select class="form-control" id="bus_id" name="bus_id" required>
                    <?php while ($busItem = $bus->fetch_assoc()): ?>
                        <option value="<?= $busItem['id'] ?>" <?= $trajet['bus_id'] == $busItem['id'] ? 'selected' : '' ?>>
                            <?= htmlspecialchars($busItem['immatriculation']) ?>
                        </option>
                    <?php endwhile; ?>
                </select>
            </div>
            <div class="mb-3">
                <label for="conducteur_id" class="form-label">Conducteur</label>
                <select class="form-control" id="conducteur_id" name="conducteur_id" required>
                    <?php while ($conducteur = $conducteurs->fetch_assoc()): ?>
                        <option value="<?= $conducteur['id'] ?>" <?= $trajet['conducteur_id'] == $conducteur['id'] ? 'selected' : '' ?>>
                            <?= htmlspecialchars($conducteur['nom_complet']) ?>
                        </option>
                    <?php endwhile; ?>
                </select>
            </div>
            <button type="submit" class="btn btn-warning w-100">Modifier</button>
        </form>
    </div>
</body>
</html>
