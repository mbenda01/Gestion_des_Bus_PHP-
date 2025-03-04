<?php
require_once 'shared/navBar.php';
require_once 'database.php';

$action = $_GET['action'] ?? 'home';
?>

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Gestion des Bus</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
        body {
            background-color: #f8f9fa;
        }
        .container {
            margin-top: 50px;
        }
        .card {
            transition: transform 0.3s;
        }
        .card:hover {
            transform: scale(1.05);
        }
    </style>
</head>

<body>

    <div class="container">
        <div class="text-center mb-4">
            <h1 class="display-4 text-primary">🚍 Gestion des Bus</h1>
            <p class="lead">Gérez facilement les bus, conducteurs et trajets en un clic.</p>
        </div>

        <div class="row">
            <div class="col-md-4">
                <div class="card shadow-sm">
                    <div class="card-body text-center">
                        <h5 class="card-title">🚌 Bus</h5>
                        <p class="card-text">Gérez la liste des bus, ajoutez ou modifiez des bus.</p>
                        <a href="index.php?action=listeBus" class="btn btn-primary">Voir les bus</a>
                    </div>
                </div>
            </div>
            
            <div class="col-md-4">
                <div class="card shadow-sm">
                    <div class="card-body text-center">
                        <h5 class="card-title">👨‍✈️ Conducteurs</h5>
                        <p class="card-text">Ajoutez, modifiez ou gérez les conducteurs.</p>
                        <a href="index.php?action=listeConducteurs" class="btn btn-primary">Voir les conducteurs</a>
                    </div>
                </div>
            </div>

            <div class="col-md-4">
                <div class="card shadow-sm">
                    <div class="card-body text-center">
                        <h5 class="card-title">🛣️ Lignes</h5>
                        <p class="card-text">Gérez les lignes de bus et stations associées.</p>
                        <a href="index.php?action=listeLignes" class="btn btn-primary">Voir les lignes</a>
                    </div>
                </div>
            </div>
        </div>

        <div class="text-center mt-4">
            <a href="index.php?action=logout" class="btn btn-danger btn-lg">🚪 Déconnexion</a>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
