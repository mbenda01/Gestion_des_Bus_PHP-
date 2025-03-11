<?php
require_once '../../database.php';
$error = "";

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $prenom = trim($_POST['prenom']);
    $nom = trim($_POST['nom']);
    $email = trim($_POST['email']);
    $telephone = trim($_POST['telephone']);
    $password = password_hash($_POST['password'], PASSWORD_DEFAULT);

    if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        $error = "❌ Email invalide.";
    } else {
        $stmt = $connexion->prepare("INSERT INTO clients (prenom, nom, email, telephone, password) VALUES (?, ?, ?, ?, ?)");
        $stmt->bind_param("sssss", $prenom, $nom, $email, $telephone, $password);
        if ($stmt->execute()) {
            header("Location: login.php?success=registered");
            exit;
        } else {
            $error = "❌ Email déjà utilisé.";
        }
    }
}
?>

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <title>Inscription Client</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body>
    <div class="container mt-5">
        <h2 class="text-center">🛒 Inscription Client</h2>
        
        <?php if (!empty($error)): ?>
            <div class="alert alert-danger"><?= $error ?></div>
        <?php endif; ?>

        <form method="POST">
            <div class="mb-3">
                <label class="form-label">Prénom</label>
                <input type="text" class="form-control" name="prenom" required>
            </div>
            <div class="mb-3">
                <label class="form-label">Nom</label>
                <input type="text" class="form-control" name="nom" required>
            </div>
            <div class="mb-3">
                <label class="form-label">Email</label>
                <input type="email" class="form-control" name="email" required>
            </div>
            <div class="mb-3">
                <label class="form-label">Téléphone</label>
                <input type="text" class="form-control" name="telephone" required>
            </div>
            <div class="mb-3">
                <label class="form-label">Mot de passe</label>
                <input type="password" class="form-control" name="password" required>
            </div>
            <button type="submit" class="btn btn-primary w-100">S'inscrire</button>
        </form>

        <p class="mt-3 text-center">Déjà un compte ? <a href="login.php">Se connecter</a></p>
    </div>
</body>
</html>
