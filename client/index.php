<?php
session_start();
require_once '../database.php';

// 🔒 Vérification de la connexion
if (!isset($_SESSION['client_id'])) {
    // Redirige vers la page de connexion si l'utilisateur n'est pas connecté
    header("Location: auth/login.php");
    exit();
}

// ✅ Récupération des variables de session
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
            background-color: #f4f6f9;
            font-family: 'Arial', sans-serif;
        }
        .main-content {
            margin-left: 250px; /* Laisser de l'espace pour le sidebar */
            padding: 30px;
        }
        h1 {
            color: #007bff;
        }
        .help-section, .activities-section, .contact-section {
            background-color: white;
            padding: 20px;
            border-radius: 8px;
            box-shadow: 0px 4px 10px rgba(0, 0, 0, 0.1);
            margin-top: 30px;
        }
        .activity-card {
            display: flex;
            flex-direction: row;
            align-items: center;
            gap: 20px;
            margin-top: 15px;
        }
        .activity-card img {
            max-width: 150px;
            border-radius: 8px;
        }
        .btn-primary {
            background-color: #007bff;
            border-color: #007bff;
        }
        .btn-primary:hover {
            background-color: #0056b3;
            border-color: #0056b3;
        }
        .help-section textarea {
            width: 100%;
            border-radius: 8px;
            border: 1px solid #ddd;
            padding: 10px;
            font-size: 16px;
            margin-top: 10px;
        }
        .help-section button {
            margin-top: 15px;
        }
    </style>
</head>

<body>

    <?php include 'sidebar.php'; ?> <!-- Inclure le sidebar -->

    <div class="main-content">
        <div class="text-center mb-4">
            <h1 class="display-5 fw-bold">Bienvenue sur TransitFlow</h1>
        </div>

        <!-- Réserver un ticket (en haut de la page) -->
        <div class="text-center mb-5">
            <a href="reservations/reserve.php" class="btn btn-primary btn-lg">🎟️ Réserver un Ticket</a>
        </div>

        <!-- Formulaire d'aide -->
        <section class="help-section">
            <h2>Formulaire d'Aide</h2>
            <form method="POST" action="help.php">
                <textarea name="message" placeholder="Comment pouvons-nous vous aider ?" rows="4"></textarea>
                <button type="submit" class="btn btn-primary w-100">Envoyer</button>
            </form>
        </section>

        <!-- Activités Récentes -->
        <section class="activities-section">
            <h2>Activités Récentes</h2>
            <div class="activity-card">
                <img src="/Gestion_des_Bus_PHP/assets/activity1.png" alt="Activité 1">
                <div>
                    <h4>Activité 1</h4>
                    <p>Description de l'activité en cours. Restez à l'écoute pour plus de détails.</p>
                </div>
            </div>
            <div class="activity-card">
                <img src="/Gestion_des_Bus_PHP/assets/activity2.png" alt="Activité 2">
                <div>
                    <h4>Activité 2</h4>
                    <p>Ne manquez pas nos prochains événements. Plus d'informations à venir.</p>
                </div>
            </div>
        </section>

        <!-- Numéro d'assistance -->
        <section class="contact-section">
            <h2>Numéro d'Aide</h2>
            <p>Appelez-nous au <strong>123-456-789</strong> pour toute assistance.</p>
        </section>
    </div>

</body>
</html>
