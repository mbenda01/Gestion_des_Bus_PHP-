<?php
session_start();
require_once '../database.php';

if (!isset($_SESSION['client_id'])) {
    header("Location: auth/login.php");
    exit;
}

$client_id = $_SESSION['client_id'];
$prenom = htmlspecialchars($_SESSION['prenom']);

// Récupérer le nombre total de réservations du client
$sql = "SELECT COUNT(*) AS total_reservations FROM reservations WHERE client_id = ?";
$stmt = $connexion->prepare($sql);
$stmt->bind_param("i", $client_id);
$stmt->execute();
$result = $stmt->get_result();
$data = $result->fetch_assoc();
$total_reservations = $data['total_reservations'];

// Définition d'une image de profil par défaut
$profile_image = isset($_SESSION['profile_image']) ? $_SESSION['profile_image'] : "default-profile.png";
?>

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Tableau de Bord - Client</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <script src="https://kit.fontawesome.com/a076d05399.js" crossorigin="anonymous"></script> <!-- Icônes FontAwesome -->
    <style>
        body {
            background: linear-gradient(135deg, #007bff, #6610f2);
            color: white;
            min-height: 100vh;
        }
        .navbar {
            background-color: rgba(0, 0, 0, 0.7);
        }
        .card {
            border-radius: 15px;
            box-shadow: 0px 4px 10px rgba(0, 0, 0, 0.2);
            transition: transform 0.3s;
        }
        .card:hover {
            transform: translateY(-5px);
        }
        .profile-img {
            width: 60px;
            height: 60px;
            border-radius: 50%;
            border: 2px solid white;
        }
    </style>
</head>
<body>

    <!-- Barre de navigation -->
    <nav class="navbar navbar-expand-lg navbar-dark">
        <div class="container">
            <a class="navbar-brand" href="#"><i class="fas fa-bus"></i> Gestion Bus</a>
            <div class="d-flex align-items-center">
                <img src="../assets/<?= $profile_image ?>" class="profile-img me-2" alt="Photo de profil">
                <span class="fw-bold"><?= $prenom ?></span>
                <a href="auth/logout.php" class="btn btn-danger ms-3"><i class="fas fa-sign-out-alt"></i> Déconnexion</a>
            </div>
        </div>
    </nav>

    <!-- Contenu principal -->
    <div class="container mt-5">
        <h2 class="text-center fw-bold">🚍 Bienvenue, <?= $prenom ?> !</h2>
        <p class="text-center text-light">Votre tableau de bord client</p>

        <div class="row mt-4">
            <!-- Carte Tickets Réservés -->
            <div class="col-md-6">
                <div class="card text-center bg-white text-dark p-4">
                    <h4 class="text-primary"><i class="fas fa-ticket-alt"></i> Tickets Réservés</h4>
                    <h2 class="text-success fw-bold"><?= $total_reservations ?></h2>
                    <a href="reservations/historique.php" class="btn btn-outline-primary mt-2">📜 Voir l'historique</a>
                </div>
            </div>

            <!-- Carte Réserver un Nouveau Ticket -->
            <div class="col-md-6">
                <div class="card text-center bg-white text-dark p-4">
                    <h4 class="text-primary"><i class="fas fa-shopping-cart"></i> Nouvelle Réservation</h4>
                    <p>Réservez votre prochain trajet en quelques clics !</p>
                    <a href="reservations/reserve.php" class="btn btn-primary">🎟️ Réserver</a>
                </div>
            </div>
        </div>
    </div>

</body>
</html>
