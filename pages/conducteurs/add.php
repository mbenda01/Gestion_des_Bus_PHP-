<?php
require_once dirname(__DIR__, 2) . '/database.php';

$error = "";
$success = "";

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $matricule = strtoupper(trim($_POST['matricule']));
    $prenom = ucfirst(trim($_POST['prenom']));
    $nom = strtoupper(trim($_POST['nom']));
    $telephone = trim($_POST['telephone']);
    $type_permis = $_POST['type_permis'];

    // Validation des données
    if (!preg_match("/^[A-Z0-9-]+$/", $matricule)) {
        $error = "Le matricule doit contenir uniquement des lettres, chiffres et tirets.";
    } elseif (!preg_match("/^[a-zA-ZÀ-ÿ\s]+$/", $prenom)) {
        $error = "Le prénom ne doit contenir que des lettres.";
    } elseif (!preg_match("/^[a-zA-ZÀ-ÿ\s]+$/", $nom)) {
        $error = "Le nom ne doit contenir que des lettres.";
    } elseif (!preg_match("/^\d{9}$/", $telephone)) {
        $error = "Le numéro de téléphone doit contenir exactement 9 chiffres.";
    } elseif (!in_array($type_permis, ['Lourd', 'Léger'])) {
        $error = "Type de permis invalide.";
    }

    // Enregistrement dans la base de données si aucune erreur
    if (empty($error)) {
        $stmt = $connexion->prepare("INSERT INTO conducteurs (matricule, prenom, nom, telephone, type_permis) VALUES (?, ?, ?, ?, ?)");
        $stmt->bind_param("sssss", $matricule, $prenom, $nom, $telephone, $type_permis);
        
        if ($stmt->execute()) {
            $success = "✅ Conducteur ajouté avec succès !";
        } else {
            $error = "❌ Erreur lors de l'ajout du conducteur.";
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
    <title>Ajouter un Conducteur</title>
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
        <h2 class="text-center text-primary">👨‍✈️ Ajouter un Conducteur</h2>

        <?php if (!empty($error)): ?>
            <div class="alert alert-danger"><?= $error ?></div>
        <?php elseif (!empty($success)): ?>
            <div class="alert alert-success"><?= $success ?></div>
        <?php endif; ?>

        <form action="#" method="POST">
            <div class="mb-3">
                <label for="matricule" class="form-label">Matricule</label>
                <input type="text" class="form-control" id="matricule" name="matricule" required>
            </div>
            <div class="mb-3">
                <label for="prenom" class="form-label">Prénom</label>
                <input type="text" class="form-control" id="prenom" name="prenom" required>
            </div>
            <div class="mb-3">
                <label for="nom" class="form-label">Nom</label>
                <input type="text" class="form-control" id="nom" name="nom" required>
            </div>
            <div class="mb-3">
                <label for="telephone" class="form-label">Téléphone</label>
                <input type="text" class="form-control" id="telephone" name="telephone" required>
            </div>
            <div class="mb-3">
                <label for="type_permis" class="form-label">Type de Permis</label>
                <select class="form-control" id="type_permis" name="type_permis" required>
                    <option value="Lourd">Lourd</option>
                    <option value="Léger">Léger</option>
                </select>
            </div>
            <button type="submit" class="btn btn-primary w-100">Ajouter</button>
        </form>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
