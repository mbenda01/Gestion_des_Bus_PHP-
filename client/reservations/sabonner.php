<?php
session_start();
require_once '../../database.php';

if (!isset($_SESSION['client_id'])) {
    header("Location: ../auth/login.php");
    exit;
}

$prenom = $_SESSION['prenom'] ?? 'Client';
$client_id = $_SESSION['client_id'];

// Récupérer l'abonnement sélectionné
if (!isset($_GET['type'])) {
    header("Location: abonnements.php"); // Redirige vers la page des abonnements si aucun abonnement sélectionné
    exit;
}

$type = htmlspecialchars($_GET['type']); // Sécuriser la donnée
$date_abonnement = date('Y-m-d'); // Date actuelle

// Prix de l'abonnement en fonction du type choisi
$prix_abonnements = [
    "Zone 1 - Zone 2" => 5000,
    "Zone 1 - Zone 3" => 7000,
    "Zone 1 - Zone 4" => 10000,
    "Zone 1 - Zone 1" => 3000,
    "Zone 2 - Zone 2" => 3000,
    "Zone 2 - Zone 3" => 5000,
    "Zone 2 - Zone 4" => 7000,
    "Zone 3 - Zone 3" => 3000,
    "Zone 3 - Zone 4" => 5000,
    "Zone 4 - Zone 4" => 3000,
    "Etudiant" => 2000,
    "Premium" => 15000,
];

// Vérifier si le type d'abonnement existe
if (!array_key_exists($type, $prix_abonnements)) {
    header("Location: abonnements.php"); // Redirige si l'abonnement est invalide
    exit;
}

$prix = $prix_abonnements[$type];

// Enregistrement de l'abonnement en base de données
$stmt = $connexion->prepare("INSERT INTO abonnements (client_id, type, prix, date_abonnement) VALUES (?, ?, ?, ?)");
$stmt->bind_param("isis", $client_id, $type, $prix, $date_abonnement);

if ($stmt->execute()) {
    $success_message = "✅ Abonnement au forfait <strong>$type</strong> enregistré avec succès !";
} else {
    $error_message = "❌ Une erreur est survenue lors de l'enregistrement de l'abonnement.";
}
$stmt->close();

?>

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <title>S'abonner - TransitFlow</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css">
    <style>
        body {
            background: #E6F7FF;
            color: #333;
            font-family: Arial, sans-serif;
            display: flex;
            justify-content: center;
            align-items: center;
            height: 100vh;
        }

        .card {
            padding: 20px;
            border-radius: 12px;
            box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
            width: 450px;
            background-color: white;
            text-align: center;
        }

        .btn-primary {
            background-color: #007bff;
            border: none;
            border-radius: 20px;
            padding: 10px 20px;
            margin-top: 20px;
        }

        .btn-primary:hover {
            background-color: #007bff;
        }

        .back-btn {
            margin-top: 20px;
            text-decoration: none;
            color: #00BFFF;
            font-weight: bold;
        }

        .back-btn:hover {
            text-decoration: underline;
        }
    </style>
</head>
<body>
    <div class="card">
        <h2>📜 Abonnement</h2>

        <?php if (isset($success_message)): ?>
            <div class="alert alert-success"><?= $success_message ?></div>
        <?php elseif (isset($error_message)): ?>
            <div class="alert alert-danger"><?= $error_message ?></div>
        <?php endif; ?>

        <div class="mt-4">
            <p>Type d'abonnement : <strong><?= $type ?></strong></p>
            <p>Prix : <strong><?= number_format($prix, 0, '', ' ') ?> FCFA</strong></p>
            <p>Date d'abonnement : <strong><?= $date_abonnement ?></strong></p>
        </div>

        <a href="abonnements.php" class="btn btn-primary">Revenir aux Abonnements</a>
        <a href="../index.php" class="back-btn">🏠 Retour au Tableau de Bord</a>
    </div>
</body>
</html>
