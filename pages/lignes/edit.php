<?php
require_once dirname(__DIR__, 2) . '/database.php';

$error = "";
$success = "";
$id = $_GET['id'] ?? null;

// Vérifier si un ID valide est fourni
if (!$id || !is_numeric($id)) {
    header("Location: /Gestion_des_bus_PHP/index.php?action=listeLignes&error=invalidID");
    exit;
}

// Récupérer les informations de la ligne
$stmt = $connexion->prepare("SELECT * FROM lignes WHERE id = ?");
$stmt->bind_param("i", $id);
$stmt->execute();
$result = $stmt->get_result();
$ligne = $result->fetch_assoc();

if (!$ligne) {
    header("Location: /Gestion_des_bus_PHP/index.php?action=listeLignes&error=notfound");
    exit;
}

// Mise à jour de la ligne si le formulaire est soumis
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $numero = strtoupper(trim($_POST['numero']));
    $nombre_kilometre = intval($_POST['nombre_kilometre']);

    // Validation des entrées
    if (!preg_match("/^[A-Z0-9-]+$/", $numero)) {
        $error = "Le numéro de ligne doit contenir uniquement des lettres, chiffres et tirets.";
    } elseif ($nombre_kilometre <= 0) {
        $error = "Le nombre de kilomètres doit être supérieur à 0.";
    }

    // Mise à jour si aucune erreur
    if (empty($error)) {
        $stmt = $connexion->prepare("UPDATE lignes SET numero = ?, nombre_kilometre = ? WHERE id = ?");
        $stmt->bind_param("sii", $numero, $nombre_kilometre, $id);
        
        if ($stmt->execute()) {
            $success = "✅ Ligne mise à jour avec succès !";
            // Recharger les données de la ligne après modification
            $ligne = [
                'numero' => $numero,
                'nombre_kilometre' => $nombre_kilometre
            ];
        } else {
            $error = "❌ Erreur lors de la mise à jour de la ligne.";
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
    <title>Modifier une Ligne</title>
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
        <h2 class="text-center text-primary">✏️ Modifier une Ligne</h2>

        <!-- Affichage des messages -->
        <?php if (!empty($error)): ?>
            <div class="alert alert-danger"><?= htmlspecialchars($error) ?></div>
        <?php elseif (!empty($success)): ?>
            <div class="alert alert-success"><?= htmlspecialchars($success) ?></div>
        <?php endif; ?>

        <form action="#" method="POST">
            <div class="mb-3">
                <label for="numero" class="form-label">Numéro de Ligne</label>
                <input type="text" class="form-control" id="numero" name="numero" value="<?= htmlspecialchars($ligne['numero']) ?>" required>
            </div>
            <div class="mb-3">
                <label for="nombre_kilometre" class="form-label">Nombre de Kilomètres</label>
                <input type="number" class="form-control" id="nombre_kilometre" name="nombre_kilometre" value="<?= htmlspecialchars($ligne['nombre_kilometre']) ?>" required>
            </div>
            <button type="submit" class="btn btn-warning w-100">Modifier</button>
        </form>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
