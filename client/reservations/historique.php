<?php
session_start();
require_once '../../database.php';

if (!isset($_SESSION['client_id'])) {
    header("Location: ../auth/login.php");
    exit;
}

$client_id = $_SESSION['client_id'];

$sql = "SELECT reservations.id, reservations.date, reservations.type, lignes.numero AS ligne_numero, lignes.tarif
        FROM reservations
        JOIN lignes ON reservations.ligne_id = lignes.id
        WHERE reservations.client_id = ?
        ORDER BY reservations.date DESC";
$stmt = $connexion->prepare($sql);
$stmt->bind_param("i", $client_id);
$stmt->execute();
$reservations = $stmt->get_result();
?>

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <title>Historique des Réservations</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body>
    <div class="container mt-5">
        <h2 class="text-center">📜 Historique des Réservations</h2>

        <?php if ($reservations->num_rows > 0): ?>
            <table class="table table-bordered table-hover">
                <thead class="table-dark">
                    <tr>
                        <th>ID</th>
                        <th>Date</th>
                        <th>Type</th>
                        <th>Ligne</th>
                        <th>Tarif</th>
                    </tr>
                </thead>
                <tbody>
                    <?php while ($reservation = $reservations->fetch_assoc()): ?>
                        <tr>
                            <td><?= $reservation['id'] ?></td>
                            <td><?= htmlspecialchars($reservation['date']) ?></td>
                            <td><?= htmlspecialchars($reservation['type']) ?></td>
                            <td>Ligne <?= htmlspecialchars($reservation['ligne_numero']) ?></td>
                            <td><?= htmlspecialchars($reservation['tarif']) ?> FCFA</td>
                        </tr>
                    <?php endwhile; ?>
                </tbody>
            </table>
        <?php else: ?>
            <p class="text-center text-muted">😕 Aucune réservation effectuée.</p>
        <?php endif; ?>

        <p class="mt-3 text-center">
            <a href="reserve.php" class="btn btn-primary">🎟️ Réserver un nouveau ticket</a>
        </p>

        <!-- 🔥 Ajout du bouton de retour vers `index.php` -->
        <p class="mt-3 text-center">
            <a href="../index.php" class="btn btn-success">🏠 Retour au Tableau de Bord</a>
        </p>
    </div>
</body>
</html>