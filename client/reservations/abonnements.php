<?php
session_start();

if (!isset($_SESSION['client_id'])) {
    header("Location: ../auth/login.php");
    exit;
}

$prenom = $_SESSION['prenom'] ?? 'Client';
?>

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <title>Abonnements</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css">

    <style>
        body {
            background: #f5f5f5;
            font-family: 'Arial', sans-serif;
            color: #fff;
        }

        .main-content {
            margin-left: 260px;
            padding: 30px;
            background-color: #f5f5f5;
            border-radius: 10px;
            min-height: 100vh;
        }

        .subscription-card {
            background-color: white;
            border-radius: 10px;
            padding: 15px;
            box-shadow: 0 4px 10px rgba(0,0,0,0.05);
            margin-bottom: 20px;
            height: 100%;
        }

        .subscription-title {
            color: #007bff; /* bleu ciel */
            font-weight: bold;
            font-size: 1.1rem;
            margin-bottom: 5px;
        }

        .price {
            color: #28a745;
            font-weight: bold;
            font-size: 0.9rem;
            margin-bottom: 5px;
        }

        .promo-banner {
            background-color: orange;
            color: white;
            padding: 10px;
            border-radius: 8px;
            font-weight: bold;
            margin-bottom: 20px;
            text-align: center;
            font-size: 0.9rem;
        }

        .btn-primary {
            background-color: #007bff; /* bleu ciel */
            border: none;
            border-radius: 20px;
            padding: 8px 16px;
            font-size: 0.9rem;
        }

        .btn-primary:hover {
            background-color: #007bff; /* un peu plus foncé */
        }
    </style>
</head>
<body>
    <div class="d-flex">
        <?php include '../sidebar.php'; ?>

        <div class="main-content">
            <h1 class="text-center text-primary mb-4">📜 Abonnements Mensuels</h1>

            <!-- Bande Promo -->
            <div class="promo-banner">
                🎉 Activez votre localisation et indiquez votre destination du mois ! La zonation est calculée automatiquement.
            </div>

            <!-- Abonnements Zones -->
            <div class="row row-cols-1 row-cols-md-2 g-3">

                <?php
                $abonnements = [
                    ["Zone 1 - Zone 2", 5000],
                    ["Zone 1 - Zone 3", 7000],
                    ["Zone 1 - Zone 4", 10000],
                    ["Zone 1 - Zone 1", 3000],
                    ["Zone 2 - Zone 2", 3000],
                    ["Zone 2 - Zone 3", 5000],
                    ["Zone 2 - Zone 4", 7000],
                    ["Zone 3 - Zone 3", 3000],
                    ["Zone 3 - Zone 4", 5000],
                    ["Zone 4 - Zone 4", 3000]
                ];

                foreach ($abonnements as $ab) {
                    echo '<div class="col">';
                    echo '  <div class="subscription-card">';
                    echo "    <div class='subscription-title'>{$ab[0]}</div>";
                    echo "    <div class='price'>" . number_format($ab[1], 0, '', ' ') . " FCFA - Aller/Retour</div>";
                    echo "    <a href='sabonner.php?type=" . urlencode($ab[0]) . "' class='btn btn-primary w-100'>S'abonner</a>";
                    echo '  </div>';
                    echo '</div>';
                }
                ?>

                <!-- Spéciaux -->
                <div class="col">
                    <div class="subscription-card">
                        <div class="subscription-title">Abonnement Étudiant 🎓</div>
                        <div class="price">2 000 FCFA</div>
                        <a href="sabonner.php?type=Etudiant" class="btn btn-primary w-100">S'abonner</a>
                    </div>
                </div>

                <div class="col">
                    <div class="subscription-card">
                        <div class="subscription-title">Abonnement Premium 🌟</div>
                        <div class="price">15 000 FCFA - Quand tu veux, où tu veux !</div>
                        <a href="sabonner.php?type=Premium" class="btn btn-primary w-100">S'abonner</a>
                    </div>
                </div>

            </div>
        </div>
    </div>
</body>
</html>
