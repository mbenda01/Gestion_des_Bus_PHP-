<!-- reserve.php -->
<?php
session_start();
require_once '../../database.php';

if (!isset($_SESSION['client_id'])) {
    header("Location: ../auth/login.php");
    exit;
}

// Récupérer les lignes disponibles
$lignes = $connexion->query("SELECT id, numero, tarif FROM lignes ORDER BY numero ASC");

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $client_id = $_SESSION['client_id'];
    $ligne_id = $_POST['ligne_id'];
    $trajet_type = $_POST['trajet_type'];
    $date = $_POST['date'];

    // Validation et insertion
    $stmt = $connexion->prepare("INSERT INTO reservations (client_id, ligne_id, type, date) VALUES (?, ?, ?, ?)");
    $stmt->bind_param("iiss", $client_id, $ligne_id, $trajet_type, $date);
    if ($stmt->execute()) {
        header("Location: payment.php");
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
        <h2 class="text-center">Réservez votre Ticket</h2>

        <form method="POST">
            <div class="mb-3">
                <label class="form-label">Sélectionnez une ligne</label>
                <select class="form-select" name="ligne_id" required>
                    <option value="">Choisissez une ligne</option>
                    <?php while ($ligne = $lignes->fetch_assoc()): ?>
                        <option value="<?= $ligne['id'] ?>">Ligne <?= htmlspecialchars($ligne['numero']) ?> - Tarif: <?= htmlspecialchars($ligne['tarif']) ?> FCFA</option>
                    <?php endwhile; ?>
                </select>
            </div>

            <div class="mb-3">
                <label class="form-label">Type de Trajet</label>
                <select class="form-select" name="trajet_type" required>
                    <option value="Aller">Aller</option>
                    <option value="Retour">Retour</option>
                </select>
            </div>

            <div class="mb-3">
                <label class="form-label">Date</label>
                <input type="date" class="form-control" name="date" required>
            </div>

            <button type="submit" class="btn btn-primary">Réserver</button>
        </form>
    </div>
</body>
</html>
