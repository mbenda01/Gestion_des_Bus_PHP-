<?php
require_once dirname(__DIR__, 2) . '/database.php';

$error = "";
$success = "";

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $numero = strtoupper(trim($_POST['numero']));
    $nombre_kilometre = intval($_POST['nombre_kilometre']);
    $tarif = floatval($_POST['tarif']);

    // Validation des entrées
    if (!preg_match("/^[A-Z0-9-]+$/", $numero)) {
        $error = "Le numéro de ligne ne doit contenir que des lettres, chiffres et tirets.";
    } elseif ($nombre_kilometre <= 0) {
        $error = "Le nombre de kilomètres doit être supérieur à 0.";
    } elseif ($tarif < 0) {
        $error = "Le tarif doit être positif.";
    }

    // Enregistrement si aucune erreur
    if (empty($error)) {
        $stmt = $connexion->prepare("INSERT INTO lignes (numero, nombre_kilometre, tarif) VALUES (?, ?, ?)");
        $stmt->bind_param("sid", $numero, $nombre_kilometre, $tarif);
        
        if ($stmt->execute()) {
            $success = "✅ Ligne ajoutée avec succès !";
        } else {
            $error = "❌ Erreur lors de l'ajout de la ligne.";
        }
        $stmt->close();
    }
}
?>

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Ajouter une Ligne</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
        body {
            background-color: #f8f9fa;
        }
        .container {
            max-width: 500px;
            background: white;
            padding: 20px;
            border-radius: 10px;
            box-shadow: 0px 0px 10px rgba(0, 0, 0, 0.1);
            margin-top: 50px;
        }
    </style>
</head>
<body>
    <div class="container">
        <h2 class="text-center text-primary">🛣️ Ajouter une Ligne</h2>

        <?php if (!empty($error)): ?>
            <div class="alert alert-danger"><?= $error ?></div>
        <?php elseif (!empty($success)): ?>
            <div class="alert alert-success"><?= $success ?></div>
        <?php endif; ?>

        <form action="#" method="POST">
            <div class="mb-3">
                <label for="numero" class="form-label">Numéro de Ligne</label>
                <input type="text" class="form-control" id="numero" name="numero" required>
            </div>
            <div class="mb-3">
                <label for="nombre_kilometre" class="form-label">Nombre de Kilomètres</label>
                <input type="number" class="form-control" id="nombre_kilometre" name="nombre_kilometre" required>
            </div>
            <div class="mb-3">
                <label for="tarif" class="form-label">Tarif</label>
                <input type="number" step="0.01" class="form-control" id="tarif" name="tarif" required>
            </div>
            <button type="submit" class="btn btn-primary w-100">Ajouter</button>
        </form>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
