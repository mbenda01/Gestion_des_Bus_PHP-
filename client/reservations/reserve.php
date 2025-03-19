<?php
session_start();
require_once '../../database.php';

if (!isset($_SESSION['client_id'])) {
    header("Location: ../auth/login.php");
    exit;
}

// Récupérer les lignes disponibles
$lignes = $connexion->query("SELECT id, numero FROM lignes ORDER BY numero ASC");

// Récupérer les arrêts et zones
$arrets = $connexion->query("SELECT id, nom, zone FROM arrets ORDER BY nom ASC");

$prix = 150; // Prix constant
$busArriveDans = "Le bus arrive dans 15 minutes."; // Message constant

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $client_id = $_SESSION['client_id'];
    $ligne_id = $_POST['ligne_id'];
    $trajet_type = $_POST['trajet_type'];
    $date = $_POST['date'];
    $depart_id = $_POST['depart_id'];
    $arrivee_id = $_POST['arrivee_id'];

    // Validation et insertion
    $stmt = $connexion->prepare("INSERT INTO reservations (client_id, ligne_id, type, date, depart_id, arrivee_id, tarif) VALUES (?, ?, ?, ?, ?, ?, ?)");
    $stmt->bind_param("iissiii", $client_id, $ligne_id, $trajet_type, $date, $depart_id, $arrivee_id, $prix);

    if ($stmt->execute()) {
        header("Location: payment.php");
        exit;
    }
}
?>

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Réserver un Ticket</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="assets/styles.css">
    <style>
        body {
            background: linear-gradient(135deg,rgb(4, 143, 189),rgb(4, 143, 189)); /* Dégradé bleu ciel */
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
            max-width: 800px;
            border-radius: 15px;
            box-shadow: 0 4px 15px rgba(0, 0, 0, 0.2);
            padding: 30px;
            text-align: center;
        }
        .form-label {
            font-weight: bold;
        }
        .form-select, .form-control {
            border-radius: 8px;
        }
        .btn-custom {
            background:rgb(4, 143, 189);
            color: #fff;
            border-radius: 8px;
            padding: 10px;
            font-weight: bold;
            width: 100%;
        }
        .btn-custom:hover {
            background: #008CBA;
        }
        #busMessage {
            font-weight: bold;
            color: #008CBA;
            display: none;
            font-size: 1.1em;
            margin-top: 10px;
        }
        .back-btn {
            margin-top: 20px;
            background: #f44336;
            color: white;
            font-weight: bold;
            border-radius: 8px;
            padding: 10px;
            text-decoration: none;
            display: inline-block;
            width: 100%;
        }
        .back-btn:hover {
            background: #c62828;
        }
    </style>
</head>
<body>
    <div class="container">
        <h2 class="mb-4">🚍 Réservez votre Ticket</h2>

        <!-- Formulaire de réservation -->
        <form method="POST">
            <!-- Sélectionner la ligne -->
            <div class="mb-3">
                <label class="form-label">Sélectionnez une ligne</label>
                <select class="form-select" name="ligne_id" id="ligneSelect" required>
                    <option value="">Choisissez une ligne</option>
                    <?php while ($ligne = $lignes->fetch_assoc()): ?>
                        <option value="<?= $ligne['id'] ?>">Ligne <?= htmlspecialchars($ligne['numero']) ?></option>
                    <?php endwhile; ?>
                </select>
            </div>

            <!-- Message "Le bus arrive dans 15 minutes" -->
            <p id="busMessage">🚏 Le bus arrive dans 15 minutes.</p>

            <!-- Sélectionner un arrêt de départ -->
            <div class="mb-3">
                <label class="form-label">Sélectionnez un arrêt de départ</label>
                <select class="form-select" name="depart_id" required>
                    <option value="">Choisissez un arrêt de départ</option>
                    <?php while ($arret = $arrets->fetch_assoc()): ?>
                        <option value="<?= $arret['id'] ?>"><?= htmlspecialchars($arret['nom']) ?></option>
                    <?php endwhile; ?>
                </select>
            </div>

            <!-- Sélectionner un arrêt d'arrivée -->
            <div class="mb-3">
                <label class="form-label">Sélectionnez un arrêt d'arrivée</label>
                <select class="form-select" name="arrivee_id" required>
                    <option value="">Choisissez un arrêt d'arrivée</option>
                    <?php
                    $arrets->data_seek(0);
                    while ($arret = $arrets->fetch_assoc()):
                    ?>
                        <option value="<?= $arret['id'] ?>"><?= htmlspecialchars($arret['nom']) ?></option>
                    <?php endwhile; ?>
                </select>
            </div>

            <!-- Type de trajet -->
            <div class="mb-3">
                <label class="form-label">Type de Trajet</label>
                <select class="form-select" name="trajet_type" required>
                    <option value="Aller">Aller</option>
                    <option value="Retour">Retour</option>
                </select>
            </div>

            <!-- Date -->
            <div class="mb-3">
                <label class="form-label">Date</label>
                <input type="date" class="form-control" name="date" required>
            </div>

            <!-- Prix du trajet -->
            <div class="mb-3">
                <label class="form-label">Prix du trajet</label>
                <input type="text" class="form-control" value="150 FCFA" disabled>
            </div>

            <!-- Bouton de soumission -->
            <button type="submit" class="btn btn-custom">✅ Réserver</button>
        </form>

        <!-- Bouton Retour -->
        <a href="../index.php" class="back-btn">🏠 Retour à l'accueil</a>
    </div>

    <script>
        document.getElementById('ligneSelect').addEventListener('change', function() {
            let busMessage = document.getElementById('busMessage');
            if (this.value !== "") {
                busMessage.style.display = "block"; // Affiche le message si une ligne est sélectionnée
            } else {
                busMessage.style.display = "none";
            }
        });
    </script>
</body>
</html>
