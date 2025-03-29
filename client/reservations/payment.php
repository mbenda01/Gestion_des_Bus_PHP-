<?php
session_start();
require_once '../../database.php';

if (!isset($_SESSION['client_id'])) {
    header("Location: ../auth/login.php");
    exit;
}

$prix = 150; // Prix constant
$paiement_effectue = isset($_GET['success']) && $_GET['success'] == 'true';
$prenom = $_SESSION['prenom'] ?? "Client";
?>

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Paiement du Ticket</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
        body {
            background: linear-gradient(135deg, #87CEFA, #00BFFF);
            color: #fff;
            display: flex;
            justify-content: center;
            align-items: center;
            min-height: 100vh;
            font-family: 'Arial', sans-serif;
        }
        .container {
            background: #fff;
            color: #333;
            max-width: 600px;
            border-radius: 15px;
            box-shadow: 0 4px 15px rgba(0, 0, 0, 0.2);
            padding: 30px;
            text-align: center;
        }
        .price {
            font-size: 1.5rem;
            font-weight: bold;
            color: #007bff;
        }
        .payment-method {
            display: flex;
            justify-content: space-between;
            margin-top: 20px;
        }
        .payment-method button {
            border: none;
            background: none;
            padding: 10px;
            cursor: pointer;
            transition: transform 0.2s;
        }
        .payment-method button:hover {
            transform: scale(1.1);
        }
        .success-message {
            background: #d4edda;
            color: #155724;
            padding: 15px;
            border-radius: 10px;
            margin-top: 20px;
            font-weight: bold;
        }
        .download-btn {
            background: #28a745;
            color: white;
            padding: 10px;
            border-radius: 8px;
            text-decoration: none;
            display: inline-block;
            margin-top: 15px;
            font-weight: bold;
        }
        .download-btn:hover {
            background: #218838;
        }
        .back-btn {
            margin-top: 20px;
            background: #f44336;
            color: white;
            border-radius: 8px;
            padding: 10px;
            text-decoration: none;
            display: inline-block;
            width: 100%;
        }
        .back-btn:hover {
            background: #c62828;
        }
        .ticket {
            margin-top: 20px;
            padding: 20px;
            background: #f8f9fa;
            border-radius: 10px;
            text-align: center;
            border: 2px dashed #007bff;
        }
        .ticket img {
            max-width: 150px;
            margin-bottom: 10px;
        }
    </style>
</head>
<body>

    <div class="container">
        <h2 class="mb-4">💳 Paiement du Ticket</h2>

        <p class="price">Montant à payer : <strong><?= $prix ?> FCFA</strong></p>

        <?php if (!$paiement_effectue): ?>
            <div class="payment-method">
                <form method="GET">
                    <button type="submit" name="success" value="true">
                        <img src="../../assets/wave.png" alt="Payer avec Wave" style="width: 120px;">
                    </button>
                </form>
                <form method="GET">
                    <button type="submit" name="success" value="true">
                        <img src="../../assets/OM.png" alt="Payer avec Orange Money" style="width: 120px;">
                    </button>
                </form>
            </div>
        <?php else: ?>
            <div class="success-message">
                ✅ Paiement effectué avec succès ! Vous pouvez télécharger votre ticket ci-dessous.
            </div>

            <!-- Ticket simulé -->
            <div class="ticket">
                <h4>🎟️ Ticket de Transport</h4>
                <p><strong>Nom : </strong> <?= $prenom ?></p>
                <p><strong>Date : </strong> <?= date('d-m-Y H:i:s') ?></p>
                <p><strong>Prix : </strong> <?= $prix ?> FCFA</p>
                <img src="../../assets/fake_qr.jpg" alt="QR Code Ticket">
            </div>

            <!-- Bouton de téléchargement -->
            <a href="download_ticket.php" class="download-btn">📥 Télécharger le ticket</a>
        <?php endif; ?>

        <a href="../index.php" class="back-btn">🏠 Retour à l'accueil</a>
    </div>

</body>
</html>
