<?php
require_once dirname(__DIR__, 2) . '/database.php';

$error = "";
$success = "";

// Récupérer les lignes existantes
$lignes = $connexion->query("SELECT id, numero FROM lignes ORDER BY numero ASC");

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $numero = strtoupper(trim($_POST['numero']));
    $nom = trim($_POST['nom']);
    $ligne_id = intval($_POST['ligne_id']);

    // Validation des entrées
    if (!preg_match("/^[A-Z0-9-]+$/", $numero)) {
        $error = "Le numéro de l'arrêt doit contenir uniquement des lettres, chiffres et tirets.";
    } elseif (empty($nom)) {
        $error = "Le nom de l'arrêt est requis.";
    } elseif ($ligne_id <= 0) {
        $error = "Veuillez sélectionner une ligne valide.";
    }

    // Enregistrement si aucune erreur
    if (empty($error)) {
        $stmt = $connexion->prepare("INSERT INTO arrets (numero, nom, ligne_id) VALUES (?, ?, ?)");
        $stmt->bind_param("ssi", $numero, $nom, $ligne_id);
        
        if ($stmt->execute()) {
            $success = "✅ Arrêt ajouté avec succès !";
        } else {
            $error = "❌ Erreur lors de l'ajout de l'arrêt.";
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
    <title>Ajouter un Arrêt</title>
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
        <h2 class="text-center text-primary">🚏 Ajouter un Arrêt</h2>

        <?php if (!empty($error)): ?>
            <div class="alert alert-danger"><?= $error ?></div>
        <?php elseif (!empty($success)): ?>
            <div class="alert alert-success"><?= $success ?></div>
        <?php endif; ?>

        <form action="#" method="POST">
            <div class="mb-3">
                <label for="numero" class="form-label">Numéro de l'Arrêt</label>
                <input type="text" class="form-control" id="numero" name="numero" required>
            </div>
            <div class="mb-3">
                <label for="nom" class="form-label">Nom de l'Arrêt</label>
                <input type="text" class="form-control" id="nom" name="nom" required>
            </div>
            <div class="mb-3">
                <label for="ligne_id" class="form-label">Ligne Associée</label>
                <select class="form-control" id="ligne_id" name="ligne_id" required>
                    <option value="">Sélectionner une ligne</option>
                    <?php while ($ligne = $lignes->fetch_assoc()): ?>
                        <option value="<?= $ligne['id'] ?>"><?= htmlspecialchars($ligne['numero']) ?></option>
                    <?php endwhile; ?>
                </select>
            </div>
            <button type="submit" class="btn btn-primary w-100">Ajouter</button>
        </form>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
