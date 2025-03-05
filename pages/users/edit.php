<?php
require_once dirname(__DIR__, 2) . '/database.php';

$id = $_GET['id'] ?? null;

if (!$id || !is_numeric($id)) {
    header("Location: /Gestion_des_bus_PHP/index.php?action=listeUsers");
    exit;
}

$result = $connexion->query("SELECT * FROM users WHERE id = $id");
$user = $result->fetch_assoc();


if (!$user) {
    header("Location: /Gestion_des_bus_PHP/index.php?action=listeUsers&error=notfound");
    exit;
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $prenom = trim($_POST['prenom']);
    $nom = trim($_POST['nom']);
    $email = trim($_POST['email']);
    $telephone = trim($_POST['telephone']);
    $role = $_POST['role'];

    $stmt = $connexion->prepare("UPDATE users SET prenom=?, nom=?, email=?, telephone=?, role=? WHERE id=?");
    $stmt->bind_param("sssssi", $prenom, $nom, $email, $telephone, $role, $id);
    $stmt->execute();

    header("Location: /Gestion_des_bus_PHP/index.php?action=listeUsers&success=edit");
    exit;
}
?>

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <title>Modifier un utilisateur</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body>
    <div class="container mt-5">
        <h2 class="text-center">Modifier un utilisateur</h2>
        <form action="#" method="POST" class="shadow p-4 rounded bg-light">
            <div class="mb-3">
                <label for="prenom" class="form-label">Prénom</label>
                <input type="text" class="form-control" id="prenom" name="prenom" value="<?= htmlspecialchars($user['prenom']) ?>" required>
            </div>
            <div class="mb-3">
                <label for="nom" class="form-label">Nom</label>
                <input type="text" class="form-control" id="nom" name="nom" value="<?= htmlspecialchars($user['nom']) ?>" required>
            </div>
            <div class="mb-3">
                <label for="email" class="form-label">Email</label>
                <input type="email" class="form-control" id="email" name="email" value="<?= htmlspecialchars($user['email']) ?>" required>
            </div>
            <div class="mb-3">
                <label for="telephone" class="form-label">Téléphone</label>
                <input type="text" class="form-control" id="telephone" name="telephone" value="<?= htmlspecialchars($user['telephone']) ?>" required>
            </div>
            <div class="mb-3">
                <label for="role" class="form-label">Rôle</label>
                <select class="form-control" id="role" name="role" required>
                    <option value="admin" <?= $user['role'] === 'admin' ? 'selected' : '' ?>>Admin</option>
                    <option value="Responsable de Parc" <?= $user['role'] === 'Responsable de Parc' ? 'selected' : '' ?>>Responsable de Parc</option>
                    <option value="Responsable de Trajet" <?= $user['role'] === 'Responsable de Trajet' ? 'selected' : '' ?>>Responsable de Trajet</option>
                </select>
            </div>
            <div class="d-flex justify-content-end">
                <button type="submit" class="btn btn-warning me-2">Modifier</button>
                <a href="/Gestion_des_bus_PHP/index.php?action=listeUsers" class="btn btn-secondary">Annuler</a>
            </div>
        </form>
    </div>
</body>
</html>
