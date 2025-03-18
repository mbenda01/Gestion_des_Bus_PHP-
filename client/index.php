<?php
session_start();
require_once '../database.php';

$client_id = $_SESSION['client_id'];
$prenom = htmlspecialchars($_SESSION['prenom']);
?>

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Tableau de Bord - Client</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="assets/styles.css">
    <style>
        body {
            background-color: #f4f7fc;
            font-family: Arial, sans-serif;
            margin: 0;
        }
        /* Sidebar */
        .sidebar {
            background-color: #2c3e50;
            color: white;
            width: 250px;
            height: 100vh;
            position: fixed;
            top: 0;
            left: 0;
            padding-top: 20px;
            box-shadow: 2px 0 5px rgba(0, 0, 0, 0.2);
        }

        .sidebar a {
            text-decoration: none;
            color: white;
            display: block;
            padding: 15px;
            font-size: 18px;
            border-bottom: 1px solid #34495e;
            transition: background-color 0.3s ease;
        }

        .sidebar a:hover {
            background-color: #34495e;
        }

        .sidebar .profile-info {
            text-align: center;
            margin-bottom: 20px;
        }

        .sidebar .profile-img {
            width: 80px;
            height: 80px;
            border-radius: 50%;
            margin-bottom: 10px;
            border: 2px solid white;
        }

        .sidebar .logout {
            background-color: #e74c3c;
            color: white;
            padding: 10px;
            text-align: center;
            border-radius: 5px;
            margin-top: 20px;
            cursor: pointer;
        }

        /* Main content */
        .main-content {
            margin-left: 250px;
            padding: 30px;
            background-color: white;
            min-height: 100vh;
        }

        .logo {
            width: 200px;
            height: auto;
            margin-bottom: 30px;
        }

        .hero-section {
            text-align: center;
            margin-top: 50px;
        }

        .hero-section h1 {
            font-size: 3rem;
            color: #2980b9;
        }

        .hero-section .btn {
            background-color: #2980b9;
            color: white;
            padding: 15px 25px;
            font-size: 18px;
            margin-top: 20px;
            border-radius: 5px;
            transition: background-color 0.3s ease;
        }

        .hero-section .btn:hover {
            background-color: #1abc9c;
        }

        .help-section, .activities-section, .contact-section {
            margin-top: 40px;
        }

        .section-title {
            font-size: 24px;
            font-weight: bold;
            margin-bottom: 15px;
        }

        .help-section textarea {
            width: 100%;
            padding: 10px;
            margin-top: 10px;
            border-radius: 5px;
            border: 1px solid #ddd;
        }

        .help-section button {
            margin-top: 10px;
            background-color: #3498db;
            color: white;
            border: none;
            padding: 10px;
            border-radius: 5px;
            cursor: pointer;
        }

        .activity-card {
            background-color: #ecf0f1;
            padding: 15px;
            border-radius: 10px;
            margin-top: 15px;
            text-align: center;
        }

        .contact-section p {
            font-size: 18px;
            font-weight: bold;
            color: #2d3436;
        }
    </style>
</head>
<body>

    <!-- Sidebar -->
    <div class="sidebar">
        <div class="profile-info">
            <img src="assets/default.png" alt="Profile" class="profile-img">
            <p><?= $prenom ?></p>
        </div>
        <a href="index.php">Accueil</a>
        <a href="reservations/historique.php">Historique</a>
        <a href="reservations/reserve.php">Réserver un Ticket</a>
        <a href="#">Notifications</a>
        <a href="#">Panier</a>
        <a href="#">Nous Contacter</a>
        <a href="#">Mon Trajet</a>
        <div class="logout" onclick="window.location.href='auth/logout.php'">Déconnexion</div>
    </div>

    <!-- Main Content -->
    <div class="main-content">
        <!-- Hero Section -->
        <div class="hero-section">
            <img src="assets/transitFlow.png" alt="Logo TransitFlow" class="logo">
            <h1 class="fw-bold">Bienvenue sur TransitFlow!</h1>
            <p>Réservez facilement vos trajets en bus et accédez à vos informations personnelles.</p>
            <a href="reservations/reserve.php" class="btn">Réserver un Ticket 🎟️</a>
        </div>

        <!-- Formulaire d'Aide -->
        <section class="help-section">
            <h2 class="section-title">Formulaire d'Aide</h2>
            <form method="POST" action="help.php">
                <textarea name="message" placeholder="Comment pouvons-nous vous aider ?" rows="4"></textarea>
                <button type="submit">Envoyer</button>
            </form>
        </section>

        <!-- Activités -->
        <section class="activities-section">
            <h2 class="section-title">Activités Récentes</h2>
            <div class="activity-card">
                <img src="assets/activity_image.jpg" alt="Activité Image">
                <p>Description de l'activité en cours.</p>
            </div>
        </section>

        <!-- Numéro d'Assistance -->
        <section class="contact-section">
            <h2 class="section-title">Numéro d'Aide</h2>
            <p>Appelez-nous au <strong>123-456-789</strong> pour toute assistance.</p>
        </section>
    </div>

</body>
</html>
