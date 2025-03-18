<?php
session_start();
ob_start();
require_once 'database.php';

// 🔒 Vérification de l'authentification
if (!isset($_SESSION['user_id']) || empty($_SESSION['user_id'])) {
    header("Location: pages/auth/login.php");
    exit;
}

// 🔄 Gestion de la déconnexion
if (isset($_GET['action']) && $_GET['action'] === 'logout') {
    session_destroy();
    header("Location: pages/auth/login.php");
    exit;
}

// 🔄 Redirection si changement de mot de passe requis
if (isset($_SESSION['change_password']) && $_SESSION['change_password'] === true) {
    header("Location: pages/auth/change_password.php");
    exit;
}

// 📊 Récupération des statistiques globales
$stats = [
    "bus" => $connexion->query("SELECT COUNT(*) AS total FROM bus")->fetch_assoc()['total'],
    "conducteurs" => $connexion->query("SELECT COUNT(*) AS total FROM conducteurs")->fetch_assoc()['total'],
    "lignes" => $connexion->query("SELECT COUNT(*) AS total FROM lignes")->fetch_assoc()['total'],
    "trajets" => $connexion->query("SELECT COUNT(*) AS total FROM trajets")->fetch_assoc()['total']
];

// 📊 Tickets vendus par mois (pour Chart.js) - Données fictives pour le graphique
$mois = [
    '2024-10', '2024-11', '2024-12', '2025-01', '2025-02', '2025-03'
];
$nbreTickets = [
    300, 420, 350, 480, 550, 600  // Tickets vendus, avec des variations réalistes
];

// 🏆 Lignes les plus rentables (pour Chart.js) - Limité aux 5 premières lignes les plus rentables
$lignes = ["Ligne 1", "Ligne 2", "Ligne 3", "Ligne 4", "Ligne 5"];
$revenus = [500, 420, 350, 300, 250]; // Revenus par ligne (fictifs)
?>

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Gestion des Bus</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <script src="https://kit.fontawesome.com/a076d05399.js" crossorigin="anonymous"></script>
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script> <!-- Intégration Chart.js -->
    <style>
        body {
            background-color: #ffffff;
            color: black;
            min-height: 100vh;
        }
        .main-content {
            margin-left: 270px;
            padding: 20px;
        }
        .stats-container {
            display: flex;
            justify-content: center;
            flex-wrap: wrap;
            gap: 20px;
            margin-top: 20px;
        }
        .stat-card {
            width: 200px;
            height: 120px;
            border-radius: 12px;
            box-shadow: 0px 2px 8px rgba(0, 0, 0, 0.1);
            transition: transform 0.2s ease-in-out;
            padding: 15px;
            text-align: center;
            font-size: 16px;
        }
        .stat-card:hover {
            transform: scale(1.05);
        }
        .stat-card h2 {
            font-size: 24px;
        }
        canvas {
            max-width: 100% !important;
            height: 300px !important; /* Assure que tous les graphiques ont la même hauteur */
        }
    </style>
</head>

<body>

    <?php require_once 'shared/sideBar.php'; ?>

    <div class="main-content">
        <div class="text-center mb-4">
            <h1 class="display-5 fw-bold text-primary">Gestion des Bus</h1>
            <p class="lead">Gérez efficacement les bus, conducteurs, trajets et utilisateurs.</p>
        </div>

        <!-- Affichage du message de bienvenue uniquement si aucune action n'est définie -->
        <?php if (!isset($_GET['action'])): ?>
            <h2 class="text-center">Bienvenue sur la plateforme de gestion des bus !</h2>
        <?php endif; ?>

        <!-- 📊 Statistiques Globales -->
        <div class="container">
            <div class="stats-container">
                <?php foreach ($stats as $key => $value): ?>
                    <div class="card stat-card bg-white text-dark">
                        <h4 class="text-primary">
                            <i class="fas fa-<?= $key === 'bus' ? 'bus' : ($key === 'conducteurs' ? 'user-tie' : ($key === 'trajets' ? 'route' : 'road')) ?>"></i>
                            <?= ucfirst($key) ?>
                        </h4>
                        <h2 class="text-success fw-bold"><?= $value ?></h2>
                    </div>
                <?php endforeach; ?>
            </div>
        </div>

        <!-- 📈 Graphiques -->
        <div class="container mt-5">
            <div class="row">
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

        <!-- 🔀 Gestion des pages dynamiques -->
        <div class="container-fluid mt-4">
            <div class="row">
                <div class="col-12">
                    <?php
                    $action = $_GET['action'] ?? null;
                    if ($action) {
                        // Si une action est spécifiée, afficher la page correspondante
                        $pages = [
                            'listeBus' => 'pages/bus/liste.php',
                            'addBus' => 'pages/bus/add.php',
                            'editBus' => 'pages/bus/edit.php',
                            'listeConducteurs' => 'pages/conducteurs/liste.php',
                            'addConducteur' => 'pages/conducteurs/add.php',
                            'editConducteur' => 'pages/conducteurs/edit.php',
                            'listeLignes' => 'pages/lignes/liste.php',
                            'addLigne' => 'pages/lignes/add.php',
                            'editLigne' => 'pages/lignes/edit.php',
                            'listeArrets' => 'pages/arrets/liste.php',
                            'addArret' => 'pages/arrets/add.php',
                            'editArret' => 'pages/arrets/edit.php',
                            'listeStations' => 'pages/stations/liste.php',
                            'addStation' => 'pages/stations/add.php',
                            'editStation' => 'pages/stations/edit.php',
                            'listeTrajets' => 'pages/trajets/liste.php',
                            'addTrajet' => 'pages/trajets/add.php',
                            'editTrajet' => 'pages/trajets/edit.php',
                            'listeUsers' => 'pages/users/liste.php',
                            'addUser' => 'pages/users/add.php',
                            'editUser' => 'pages/users/edit.php',
                            'statistiques' => 'pages/stats.php'
                        ];
                        require_once $pages[$action] ?? '<h2 class="text-center">Page non trouvée.</h2>';
                    }
                    ?>
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
