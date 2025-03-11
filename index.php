<?php
session_start();
ob_start(); // Démarrer la capture de sortie pour éviter les erreurs d'envoi de headers
require_once 'database.php';

// Vérifier si l'utilisateur souhaite se déconnecter
if (isset($_GET['action']) && $_GET['action'] === 'logout') {
    session_destroy();
    header("Location: pages/auth/login.php");
    exit;
}

// Vérifier l'authentification avant toute sortie
if (!isset($_SESSION['user_id']) || empty($_SESSION['user_id'])) {
    header("Location: pages/auth/login.php");
    exit;
}

if (isset($_SESSION['change_password']) && $_SESSION['change_password'] === true) {
    header("Location: pages/auth/change_password.php");
    exit;
}

// Récupération des statistiques globales
$stats = [
    "bus" => $connexion->query("SELECT COUNT(*) AS total FROM bus")->fetch_assoc()['total'],
    "conducteurs" => $connexion->query("SELECT COUNT(*) AS total FROM conducteurs")->fetch_assoc()['total'],
    "lignes" => $connexion->query("SELECT COUNT(*) AS total FROM lignes")->fetch_assoc()['total'],
    "stations" => $connexion->query("SELECT COUNT(*) AS total FROM stations")->fetch_assoc()['total'],
    "trajets" => $connexion->query("SELECT COUNT(*) AS total FROM trajets")->fetch_assoc()['total']
];

?>

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Gestion des Bus</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <script src="https://kit.fontawesome.com/a076d05399.js" crossorigin="anonymous"></script>
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
    </style>
</head>

<body>

    <?php require_once 'shared/sideBar.php'; ?>

    <div class="main-content">
        <div class="text-center mb-4">
            <h1 class="display-5 fw-bold text-primary">Gestion des Bus</h1>
            <p class="lead">Gérez efficacement les bus, conducteurs, trajets et utilisateurs.</p>
        </div>

        <!-- Statistiques Globales -->
        <div class="container">
            <div class="stats-container">
                <div class="card stat-card bg-white text-dark">
                    <h4 class="text-primary"><i class="fas fa-bus"></i> Bus</h4>
                    <h2 class="text-success fw-bold"><?= $stats["bus"] ?></h2>
                </div>
                <div class="card stat-card bg-white text-dark">
                    <h4 class="text-primary"><i class="fas fa-user-tie"></i> Conducteurs</h4>
                    <h2 class="text-success fw-bold"><?= $stats["conducteurs"] ?></h2>
                </div>
                <div class="card stat-card bg-white text-dark">
                    <h4 class="text-primary"><i class="fas fa-route"></i> Trajets</h4>
                    <h2 class="text-success fw-bold"><?= $stats["trajets"] ?></h2>
                </div>
                <div class="card stat-card bg-white text-dark">
                    <h4 class="text-primary"><i class="fas fa-map-marker-alt"></i> Stations</h4>
                    <h2 class="text-success fw-bold"><?= $stats["stations"] ?></h2>
                </div>
                <div class="card stat-card bg-white text-dark">
                    <h4 class="text-primary"><i class="fas fa-road"></i> Lignes</h4>
                    <h2 class="text-success fw-bold"><?= $stats["lignes"] ?></h2>
                </div>
            </div>
        </div>

        <div class="container-fluid mt-4">
            <div class="row">
                <div class="col-12">
                    <?php
                    $action = $_GET['action'] ?? 'home';

                    switch ($action) {
                        case 'listeBus':
                            require_once 'pages/bus/liste.php';
                            break;
                        case 'addBus':
                            require_once 'pages/bus/add.php';
                            break;
                        case 'editBus':
                            require_once 'pages/bus/edit.php';
                            break;
                        case 'listeConducteurs':
                            require_once 'pages/conducteurs/liste.php';
                            break;
                        case 'addConducteur':
                            require_once 'pages/conducteurs/add.php';
                            break;
                        case 'editConducteur':
                            require_once 'pages/conducteurs/edit.php';
                            break;
                        case 'listeLignes':
                            require_once 'pages/lignes/liste.php';
                            break;
                        case 'addLigne':
                            require_once 'pages/lignes/add.php';
                            break;
                        case 'editLigne':
                            require_once 'pages/lignes/edit.php';
                            break;
                        case 'listeArrets':
                            require_once 'pages/arrets/liste.php';
                            break;
                        case 'addArret':
                            require_once 'pages/arrets/add.php';
                            break;
                        case 'editArret':
                            require_once 'pages/arrets/edit.php';
                            break;
                        case 'listeStations':
                            require_once 'pages/stations/liste.php';
                            break;
                        case 'addStation':
                            require_once 'pages/stations/add.php';
                            break;
                        case 'editStation':
                            require_once 'pages/stations/edit.php';
                            break;
                        case 'listeTrajets':
                            require_once 'pages/trajets/liste.php';
                            break;
                        case 'addTrajet':
                            require_once 'pages/trajets/add.php';
                            break;
                        case 'editTrajet':
                            require_once 'pages/trajets/edit.php';
                            break;
                        case 'listeUsers':
                            require_once 'pages/users/liste.php';
                            break;
                        case 'addUser':
                            require_once 'pages/users/add.php';
                            break;
                        case 'editUser':
                            require_once 'pages/users/edit.php';
                            break;
                        default:
                            echo '<h2 class="text-center">Bienvenue sur la plateforme de gestion des bus !</h2>';
                    }
                    ?>
                </div>
            </div>
        </div>
    </div>

    <!-- Bootstrap JS -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
