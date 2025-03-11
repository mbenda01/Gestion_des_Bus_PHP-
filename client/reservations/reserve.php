<?php
session_start();
require_once '../../database.php';

if (!isset($_SESSION['client_id'])) {
    header("Location: ../auth/login.php");
    exit;
}

$error = "";
$success = "";

// Récupérer les lignes disponibles
$lignes = $connexion->query("SELECT id, numero, tarif FROM lignes ORDER BY numero ASC");

// Réservation du ticket
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $client_id = $_SESSION['client_id'];
    $ligne_id = $_POST['ligne_id'];
    $trajet_type = $_POST['trajet_type'];
    $date = $_POST['date'];

    // Vérifier si la ligne existe
    $ligne_check = $connexion->prepare("SELECT id FROM lignes WHERE id = ?");
    $ligne_check->bind_param("i", $ligne_id);
    $ligne_check->execute();
    if ($ligne_check->get_result()->num_rows == 0) {
        $error = "❌ Ligne invalide.";
    } else {
        // Insérer la réservation
        $stmt = $connexion->prepare("INSERT INTO reservations (client_id, ligne_id, type, date, created_at) VALUES (?, ?, ?, ?, NOW())");
        $stmt->bind_param("iiss", $client_id, $ligne_id, $trajet_type, $date);
        if ($stmt->execute()) {
            $success = "✅ Réservation effectuée avec succès !";
        } else {
            $error = "❌ Erreur lors de la réservation.";
        }
    }
}
?>

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <title>Réserver un Ticket</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body>
    <div class="container mt-5">
        <h2 class="text-center">🎟️ Réserver un Ticket</h2>

        <?php if (!empty($error)): ?>
            <div class="alert alert-danger"><?= $error ?></div>
        <?php elseif (!empty($success)): ?>
            <div class="alert alert-success"><?= $success ?></div>
        <?php endif; ?>

        <form method="POST">
            <div class="mb-3">
                <label class="form-label">Ligne</label>
                <select class="form-control" name="ligne_id" required>
                    <option value="">-- Sélectionnez une ligne --</option>
                    <?php while ($ligne = $lignes->fetch_assoc()): ?>
                        <option value="<?= $ligne['id'] ?>">Ligne <?= htmlspecialchars($ligne['numero']) ?> - Tarif: <?= htmlspecialchars($ligne['tarif']) ?> FCFA</option>
                    <?php endwhile; ?>
                </select>
            </div>
            <div class="mb-3">
                <label class="form-label">Type de Trajet</label>
                <select class="form-control" name="trajet_type" required>
                    <option value="Aller">Aller</option>
                    <option value="Retour">Retour</option>
                </select>
            </div>
            <div class="mb-3">
                <label class="form-label">Date</label>
                <input type="date" class="form-control" name="date" required>
            </div>
            <button type="submit" class="btn btn-primary w-100">Réserver</button>
        </form>

        <p class="mt-3 text-center"><a href="historique.php">📜 Voir mes réservations</a></p>
    </div>
</body>
</html>
