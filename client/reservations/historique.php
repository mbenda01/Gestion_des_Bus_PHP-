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
    <link rel="stylesheet" href="../../assets/styles.css">
    <style>
        body {
            background-color: #f1f3f5;
        }
        /* Structure principale avec flexbox */
        .wrapper {
            display: flex;
            min-height: 100vh;
        }
        .sidebar {
            width: 250px; /* Ajuster selon la largeur du sidebar */
            flex-shrink: 0;
        }
        .main-content {
            flex-grow: 1;
            padding: 20px;
            margin-left: 260px; /* Marge pour ne pas chevaucher le sidebar */
        }
        .container {
            background-color: white;
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
        .btn-custom {
            margin-top: 20px;
            font-weight: bold;
        }
    </style>
</head>
<body>

    <div class="wrapper">
        <!-- Sidebar -->
        <div class="sidebar">
            <?php include '../sidebar.php'; ?> 
        </div>

        <!-- Contenu principal -->
        <div class="main-content">
            <div class="container">
                <h2 class="text-center text-primary">📜 Historique des Réservations</h2>

                <?php if ($reservations->num_rows > 0): ?>
                    <div class="table-container">
                        <table class="table table-bordered table-striped">
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
                    </div>
                <?php else: ?>
                    <div class="alert alert-warning text-center">😕 Aucune réservation effectuée.</div>
                <?php endif; ?>

                <p class="text-center">
                    <a href="reserve.php" class="btn btn-primary btn-custom">🎟️ Réserver un nouveau ticket</a>
                </p>

                <!-- 🔥 Ajout du bouton de retour vers `index.php` -->
                <p class="text-center">
                    <a href="../index.php" class="btn btn-success">🏠 Retour au Tableau de Bord</a>
                </p>
            </div>
        </div>
    </div>

</body>
</html>
