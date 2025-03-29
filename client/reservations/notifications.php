<?php
session_start();

if (!isset($_SESSION['client_id'])) {
    header("Location: ../auth/login.php");
    exit;
}

$prenom = $_SESSION['prenom'] ?? 'Client';

// 🔔 Notifications simulées (normalement viendraient d'une base de données)
$notifications = [
    ['id' => 1, 'message' => "🚍 Vous êtes abonné(e) à la ligne 7 : Dakar - Thiès.", 'date' => '2025-03-28', 'read' => false],
    ['id' => 2, 'message' => "📅 Votre trajet du 27/03/2025 à 08:00 vers Kaolack est confirmé.", 'date' => '2025-03-26', 'read' => true],
    ['id' => 3, 'message' => "🎫 Nouveau ticket réservé avec succès pour le trajet vers Rufisque.", 'date' => '2025-03-25', 'read' => false],
    ['id' => 4, 'message' => "❗ Le bus de la ligne 3 est temporairement hors service.", 'date' => '2025-03-24', 'read' => true],
    ['id' => 5, 'message' => "🔔 Votre abonnement mensuel expire le 31/03/2025. Renouvelez-le à temps !", 'date' => '2025-03-23', 'read' => false],
];
?>

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <title>Mes Notifications</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css">

    <style>
        body {
            background: #CCCDC6;
            color: #fff;
            font-family: 'Arial', sans-serif;
        }

        .main-content {
            flex-grow: 1;
            padding: 2rem;
            background: #fff;
            color: #000;
            border-radius: 10px;
            margin: 1rem;
            width: 100%;
            margin-left: 260px;
        }

        .notification-item {
            display: flex;
            justify-content: space-between;
            padding: 10px;
            margin-bottom: 5px;
            background-color: #f0f0f0;
            border-radius: 8px;
            color: #000;
        }

        .notification-item button {
            background-color: #d9534f;
            border: none;
            padding: 5px 10px;
            border-radius: 5px;
            color: white;
            margin-left: 5px;
        }

        .notification-item .mark-read {
            background-color: green;
        }

        .back-btn {
            background-color: #007bff;
            color: white;
            padding: 10px 20px;
            border-radius: 8px;
            text-decoration: none;
            font-weight: bold;
            margin-bottom: 20px;
            display: inline-block;
        }

        .back-btn:hover {
            background-color: #0056b3;
        }
    </style>
</head>
<body>
    <div class="d-flex">
        <!-- Inclure le sidebar existant -->
        <?php include '../sidebar.php'; ?>

        <!-- Main Content -->
        <div class="main-content">
            <!-- Bouton Retour -->
            <a href="../index.php" class="back-btn">⬅️ Retour au Tableau de Bord</a>

            <div class="d-flex justify-content-between align-items-center mb-4">
                <h3>Bonjour <?= htmlspecialchars($prenom) ?> 👋</h3>
                <span><?= date('d/m/Y') ?></span>
            </div>

            <h4>🔵 Notifications Non Lues</h4>
            <?php
            $unreadFound = false;
            foreach ($notifications as $notif) {
                if (!$notif['read']) {
                    $unreadFound = true;
                    echo '<div class="notification-item">';
                    echo "<div><strong>{$notif['message']}</strong><br><small>{$notif['date']}</small></div>";
                    echo '<div>
                            <button disabled>Supprimer</button>
                            <button class="mark-read" disabled>Marquer comme lue</button>
                          </div>';
                    echo '</div>';
                }
            }
            if (!$unreadFound) {
                echo '<p class="text-muted">Aucune notification non lue.</p>';
            }
            ?>

            <h4 class="mt-4">✅ Notifications Lues</h4>
            <?php
            $readFound = false;
            foreach ($notifications as $notif) {
                if ($notif['read']) {
                    $readFound = true;
                    echo '<div class="notification-item">';
                    echo "<div><strong>{$notif['message']}</strong><br><small>{$notif['date']}</small></div>";
                    echo '<div>
                            <button disabled>Supprimer</button>
                          </div>';
                    echo '</div>';
                }
            }
            if (!$readFound) {
                echo '<p class="text-muted">Aucune notification lue.</p>';
            }
            ?>
        </div>
    </div>
</body>
</html>
