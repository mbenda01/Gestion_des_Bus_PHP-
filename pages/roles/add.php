<?php
require_once dirname(__DIR__, 2) . '/database.php';

$error = "";
$success = "";

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $nom = trim($_POST['nom']);
    $description = trim($_POST['description']);

    // Validation des entrées
    if (empty($nom)) {
        $error = "Le nom du rôle est requis.";
    } elseif (!preg_match("/^[a-zA-ZÀ-ÿ\s]+$/", $nom)) {
        $error = "Le nom du rôle ne doit contenir que des lettres et des espaces.";
    }

    // Enregistrement si aucune erreur
    if (empty($error)) {
        $stmt = $connexion->prepare("INSERT INTO roles (nom, description) VALUES (?, ?)");
        $stmt->bind_param("ss", $nom, $description);

        if ($stmt->execute()) {
            $success = "✅ Rôle ajouté avec succès !";
        } else {
            $error = "❌ Erreur lors de l'ajout du rôle.";
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
    <title>Ajouter un Rôle</title>
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
        <h2 class="text-center text-primary">🔧 Ajouter un Rôle</h2>

        <?php if (!empty($error)): ?>
            <div class="alert alert-danger"><?= $error ?></div>
        <?php elseif (!empty($success)): ?>
            <div class="alert alert-success"><?= $success ?></div>
        <?php endif; ?>

        <form action="#" method="POST">
            <div class="mb-3">
                <label for="nom" class="form-label">Nom du Rôle</label>
                <input type="text" class="form-control" id="nom" name="nom" required>
            </div>
            <div class="mb-3">
                <label for="description" class="form-label">Description</label>
                <textarea class="form-control" id="description" name="description" rows="3"></textarea>
            </div>
            <button type="submit" class="btn btn-primary w-100">Ajouter</button>
        </form>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
