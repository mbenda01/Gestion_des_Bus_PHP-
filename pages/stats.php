<?php
require_once dirname(__DIR__, 1) . '/database.php';

// Récupération des statistiques globales
$stats = [
    "Bus en circulation" => $connexion->query("SELECT COUNT(*) AS total FROM bus WHERE etat = 'En circulation'")->fetch_assoc()['total'],
    "Bus hors circulation" => $connexion->query("SELECT COUNT(*) AS total FROM bus WHERE etat = 'Hors circulation'")->fetch_assoc()['total'],
    "Bus en panne" => $connexion->query("SELECT COUNT(*) AS total FROM bus WHERE etat = 'En panne'")->fetch_assoc()['total'],
    "Conducteurs" => $connexion->query("SELECT COUNT(*) AS total FROM conducteurs")->fetch_assoc()['total'],
    "Lignes" => $connexion->query("SELECT COUNT(*) AS total FROM lignes")->fetch_assoc()['total'],
    "Trajets" => $connexion->query("SELECT COUNT(*) AS total FROM trajets")->fetch_assoc()['total']
];

// 📊 Tickets vendus par mois (pour Chart.js)
$queryTickets = "SELECT DATE_FORMAT(date, '%Y-%m') AS mois, SUM(tickets_vendus) AS total FROM trajets GROUP BY mois ORDER BY mois";
$resultTickets = $connexion->query($queryTickets);
$mois = [];
$nbreTickets = [];
while ($row = $resultTickets->fetch_assoc()) {
    $mois[] = $row['mois'];
    $nbreTickets[] = $row['total'];
}

// 🏆 Lignes les plus rentables (pour Chart.js)
$queryRentable = "SELECT l.numero, b.type, SUM(t.tickets_vendus) AS total 
                  FROM trajets t 
                  JOIN lignes l ON t.ligne_id = l.id 
                  JOIN bus b ON t.bus_id = b.id
                  GROUP BY l.numero, b.type 
                  ORDER BY total DESC LIMIT 5";
$resultRentable = $connexion->query($queryRentable);
$lignes = [];
$revenus = [];
while ($row = $resultRentable->fetch_assoc()) {
    $lignes[] = "Ligne " . $row['numero'] . " (" . $row['type'] . ")";
    $revenus[] = $row['total'];
}
?>

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Statistiques - Gestion des Bus</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <style>
        body {
            background-color: #f8f9fa;
            font-family: Arial, sans-serif;
        }

        /* Ajout d'une marge pour éviter le chevauchement avec la sidebar */
        .main-content {
            margin-left: 220px;
            padding: 20px;
        }

        .container-box {
            background: white;
            padding: 20px;
            border-radius: 10px;
            box-shadow: 0px 0px 10px rgba(0, 0, 0, 0.1);
        }

        .stats-container {
            display: flex;
            flex-wrap: wrap;
            justify-content: space-between;
            gap: 15px;
            margin-top: 20px;
        }

        .stat-card {
            flex: 1 1 calc(33% - 20px);
            background: white;
            padding: 20px;
            border-radius: 10px;
            box-shadow: 0px 0px 10px rgba(0, 0, 0, 0.1);
            text-align: center;
            min-width: 200px;
        }

        .stat-card h4 {
            font-size: 16px;
            color: #007bff;
        }

        .stat-card h2 {
            font-size: 28px;
            color: #28a745;
            font-weight: bold;
        }

        .chart-container {
            display: flex;
            flex-wrap: wrap;
            justify-content: space-between;
            gap: 20px;
            margin-top: 30px;
        }

        .chart-box {
            flex: 1;
            min-width: 300px;
            text-align: center;
        }

        canvas {
            max-width: 100% !important;
            height: 280px !important;
        }

        @media (max-width: 768px) {
            .main-content {
                margin-left: 0;
            }

            .stats-container {
                flex-direction: column;
                align-items: center;
            }

            .stat-card {
                width: 90%;
            }

            .chart-container {
                flex-direction: column;
                align-items: center;
            }
        }
    </style>
</head>
<body>

    <!-- Sidebar déjà existante -->
    <?php require_once 'shared/sideBar.php'; ?>

    <div class="main-content">
        <div class="text-center mb-4">
            <h1 class="display-5 fw-bold text-primary">📊 Statistiques - Gestion des Bus</h1>
            <p class="lead">Analysez les performances des bus et des lignes.</p>
        </div>

        <!-- 📊 Statistiques Globales -->
        <div class="container-box">
            <div class="stats-container">
                <?php foreach ($stats as $titre => $valeur): ?>
                    <div class="stat-card">
                        <h4><?= $titre ?></h4>
                        <h2><?= $valeur ?></h2>
                        <p>Total <?= strtolower($titre) ?></p>
                    </div>
                <?php endforeach; ?>
            </div>
        </div>


    <script>
        var ctx1 = document.getElementById('ticketsChart').getContext('2d');
        var ticketsChart = new Chart(ctx1, {
            type: 'bar',
            data: {
                labels: <?php echo json_encode($mois); ?>,
                datasets: [{
                    label: 'Tickets Vendus',
                    data: <?php echo json_encode($nbreTickets); ?>,
                    backgroundColor: 'rgba(54, 162, 235, 0.6)',
                    borderColor: 'rgba(54, 162, 235, 1)',
                    borderWidth: 1
                }]
            }
        });

        var ctx2 = document.getElementById('rentabiliteChart').getContext('2d');
        var rentabiliteChart = new Chart(ctx2, {
            type: 'pie',
            data: {
                labels: <?php echo json_encode($lignes); ?>,
                datasets: [{
                    data: <?php echo json_encode($revenus); ?>,
                    backgroundColor: ['#FF6384', '#36A2EB', '#FFCE56', '#FF9F40', '#4BC0C0']
                }]
            }
        });
    </script>

</body>
</html>
