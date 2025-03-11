<?php
require_once dirname(__DIR__, 2) . '/database.php';

$error = "";
$success = "";
$id = $_GET['id'] ?? null;

// Vérifier si un ID valide est fourni
if (!$id || !is_numeric($id)) {
    header("Location: /Gestion_des_bus_PHP/index.php?action=listeRoles&error=invalidID");
    exit;
}

// Récupérer les informations du rôle
$stmt = $connexion->prepare("SELECT * FROM roles WHERE id = ?");
$stmt->bind_param("i", $id);
$stmt->execute();
$result = $stmt->get_result();
$role = $result->fetch_assoc();

if (!$role) {
    header("Location: /Gestion_des_bus_PHP/index.php?action=listeRoles&error=notfound");
    exit;
}

// Mise à jour du rôle si le formulaire est soumis
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $nom = trim($_POST['nom']);
    $description = trim($_POST['description']);

    // Validation des entrées
    if (empty($nom)) {
        $error = "Le nom du rôle est requis.";
    } elseif (!preg_match("/^[a-zA-ZÀ-ÿ\s]+$/", $nom)) {
        $error = "Le nom du rôle ne doit contenir que des lettres et des espaces.";
    }

    // Mise à jour si aucune erreur
    if (empty($error)) {
        $stmt = $connexion->prepare("UPDATE roles SET nom = ?, description = ? WHERE id = ?");
        $stmt->bind_param("ssi", $nom, $description, $id);
        
        if ($stmt->execute()) {
            $success = "✅ Rôle mis à jour avec succès !";
            // Recharger les données du rôle après modification
            $role = [
                'nom' => $nom,
                'description' => $description
            ];
        } else {
            $error = "❌ Erreur lors de la mise à jour du rôle.";
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
    <title>Modifier un Rôle</title>
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
        <h2 class="text-center text-primary">✏️ Modifier un Rôle</h2>

        <!-- Affichage des messages -->
        <?php if (!empty($error)): ?>
            <div class="alert alert-danger"><?= $error ?></div>
        <?php elseif (!empty($success)): ?>
            <div class="alert alert-success"><?= $success ?></div>
        <?php endif; ?>

        <form action="#" method="POST">
            <div class="mb-3">
                <label for="nom" class="form-label">Nom du Rôle</label>
                <input type="text" class="form-control" id="nom" name="nom" value="<?= htmlspecialchars($role['nom']) ?>" required>
            </div>
            <div class="mb-3">
                <label for="description" class="form-label">Description</label>
                <textarea class="form-control" id="description" name="description" rows="3"><?= htmlspecialchars($role['description']) ?></textarea>
            </div>
            <button type="submit" class="btn btn-warning w-100">Modifier</button>
        </form>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
