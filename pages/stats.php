<?php
require_once dirname(__DIR__, 1) . '/database.php';

// Récupération des statistiques globales
$stats = [
    "bus_en_circulation" => $connexion->query("SELECT COUNT(*) AS total FROM bus WHERE etat = 'En circulation'")->fetch_assoc()['total'],
    "bus_hors_circulation" => $connexion->query("SELECT COUNT(*) AS total FROM bus WHERE etat = 'Hors circulation'")->fetch_assoc()['total'],
    "bus_en_panne" => $connexion->query("SELECT COUNT(*) AS total FROM bus WHERE etat = 'En panne'")->fetch_assoc()['total'],
    "conducteurs" => $connexion->query("SELECT COUNT(*) AS total FROM conducteurs")->fetch_assoc()['total'],
    "lignes" => $connexion->query("SELECT COUNT(*) AS total FROM lignes")->fetch_assoc()['total'],
    "trajets" => $connexion->query("SELECT COUNT(*) AS total FROM trajets")->fetch_assoc()['total']
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
    <script src="https://kit.fontawesome.com/a076d05399.js" crossorigin="anonymous"></script>
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <style>
        body {
            background-color: #f8f9fa; /* Gris clair pour le fond de la page */
            font-family: Arial, sans-serif;
        }

        .container {
            background: white;
            padding: 20px;
            border-radius: 10px;
            box-shadow: 0px 0px 10px rgba(0, 0, 0, 0.1);
        }

        .stats-container {
            display: flex;
            justify-content: space-between;
            flex-wrap: wrap;
            gap: 20px;
            margin-top: 20px;
        }

        .stat-card {
            width: 23%;
            background-color: white;
            padding: 20px;
            border-radius: 10px;
            box-shadow: 0px 0px 10px rgba(0, 0, 0, 0.1);
            transition: transform 0.2s ease-in-out;
            text-align: center;
        }

        .stat-card:hover {
            transform: scale(1.05);
        }

        .stat-card h4 {
            font-size: 18px;
        }

        .stat-card h2 {
            font-size: 32px;
            margin: 10px 0;
        }

        .chart-container {
            display: flex;
            justify-content: space-between;
            margin-top: 30px;
        }

        canvas {
            max-width: 100% !important;
            height: 300px !important;
        }

        /* Responsive adjustments */
        @media (max-width: 768px) {
            .stat-card {
                width: 48%;
            }

            .chart-container {
                flex-direction: column;
                align-items: center;
            }

            canvas {
                height: 250px;
            }
        }
    </style>
</head>
<body>

    <?php require_once 'shared/sideBar.php'; ?>

    <div class="main-content">
        <div class="text-center mb-4">
            <h1 class="display-5 fw-bold text-primary">Statistiques - Gestion des Bus</h1>
            <p class="lead">Consultez toutes les statistiques liées à la gestion des bus.</p>
        </div>

        <!-- 📊 Statistiques Globales -->
        <div class="container">
            <div class="stats-container">
                <?php foreach ($stats as $key => $value): ?>
                    <div class="stat-card">
                        <h4 class="text-primary"><?= ucfirst(str_replace('_', ' ', $key)) ?></h4>
                        <h2 class="text-success fw-bold"><?= $value ?></h2>
                        <p>Nombre total de <?= str_replace('_', ' ', $key) ?>.</p>
                    </div>
                <?php endforeach; ?>
            </div>
        </div>

        <!-- 📈 Graphiques -->
        <div class="container mt-5">
            <div class="chart-container">
                <div class="col-md-6">
                    <h5 class="text-center">Nombre de Tickets Vendus par Mois</h5>
                    <canvas id="ticketsChart"></canvas>
                </div>
                <div class="col-md-6">
                    <h5 class="text-center">Top 5 Lignes les Plus Rentables</h5>
                    <canvas id="rentabiliteChart"></canvas>
                </div>
            </div>
        </div>
    </div>

    <!-- Bootstrap JS -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>

    <!-- Chart.js Script -->
    <script>
        // 📊 Graphique du nombre de tickets vendus par mois
        var ctx1 = document.getElementById('ticketsChart').getContext('2d');
        var ticketsChart = new Chart(ctx1, {
            type: 'bar', // Le type du graphique (ici, un graphique en barres)
            data: {
                labels: <?php echo json_encode($mois); ?>, // Mois (données PHP envoyées en JavaScript)
                datasets: [{
                    label: 'Tickets Vendus',
                    data: <?php echo json_encode($nbreTickets); ?>, // Tickets vendus par mois
                    backgroundColor: 'rgba(54, 162, 235, 0.6)', // Couleur des barres
                    borderColor: 'rgba(54, 162, 235, 1)', // Couleur des bordures des barres
                    borderWidth: 1
                }]
            },
            options: {
                scales: {
                    y: {
                        beginAtZero: true // L'axe Y commence à zéro
                    }
                }
            }
        });

        // 📊 Graphique des lignes les plus rentables
        var ctx2 = document.getElementById('rentabiliteChart').getContext('2d');
        var rentabiliteChart = new Chart(ctx2, {
            type: 'pie', // Le type du graphique (ici, un graphique en camembert)
            data: {
                labels: <?php echo json_encode($lignes); ?>, // Lignes (données PHP envoyées en JavaScript)
                datasets: [{
                    label: 'Revenus par Ligne',
                    data: <?php echo json_encode($revenus); ?>, // Revenus par ligne
                    backgroundColor: ['#FF6384', '#36A2EB', '#FFCE56', '#FF9F40', '#4BC0C0'], // Couleurs des secteurs
                    borderWidth: 1
                }]
            }
        });
    </script>

</body>
</html>
