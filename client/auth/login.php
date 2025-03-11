<?php
// Vérification de l'état de la session avant de l'initialiser
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

// Inclure la connexion à la base de données
require_once '../../database.php';

// Initialisation des variables
$error = "";

// Vérification si le formulaire a été soumis
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['email'], $_POST['password'])) {
    $email = trim($_POST['email']);
    $password = trim($_POST['password']);

    if (empty($email) || empty($password)) {
        $error = "❌ Veuillez remplir tous les champs.";
    } else {
        // 🔹 Vérifier si l'utilisateur est un ADMIN/RESPONSABLE (table users)
        $stmt = $connexion->prepare("SELECT id, role, prenom, password FROM users WHERE email = ?");
        $stmt->bind_param("s", $email);
        $stmt->execute();
        $result = $stmt->get_result();
        $user = $result->fetch_assoc();
        $stmt->close();

        if ($user) {
            if ($user['password'] == 'default') {
                $_SESSION['user_id'] = $user['id'];
                $_SESSION['role'] = $user['role'];
                $_SESSION['prenom'] = htmlspecialchars($user['prenom']);
                $_SESSION['change_password'] = true;
                header("Location: change_password.php");
                exit;
            }

            if (password_verify($password, $user['password'])) {
                $_SESSION['user_id'] = $user['id'];
                $_SESSION['role'] = $user['role'];
                $_SESSION['prenom'] = htmlspecialchars($user['prenom']);
                header("Location: ../../client/index.php");
                exit;
            }
        }

        // 🔹 Vérifier si l'utilisateur est un CLIENT (table clients)
        $stmt = $connexion->prepare("SELECT id, prenom, password FROM clients WHERE email = ?");
        $stmt->bind_param("s", $email);
        $stmt->execute();
        $result = $stmt->get_result();
        $client = $result->fetch_assoc();
        $stmt->close();

        if ($client) {
            if ($client['password'] == 'default') {
                $_SESSION['client_id'] = $client['id'];
                $_SESSION['prenom'] = htmlspecialchars($client['prenom']);
                $_SESSION['change_password'] = true;
                header("Location: ../../pages/auth/change_password.php");
                exit;
            }

            if (password_verify($password, $client['password'])) {
                $_SESSION['client_id'] = $client['id'];
                $_SESSION['prenom'] = htmlspecialchars($client['prenom']);
                header("Location: ../../client/index.php");
                exit;
            }
        }

        // ❌ Si aucun des deux n'est trouvé
        $error = "❌ Identifiants incorrects.";
    }
}
?>

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Connexion - Gestion Bus</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body>
    <div class="container mt-5">
        <h2 class="text-center">🔑 Connexion</h2>

        <?php if (!empty($error)): ?>
            <div class="alert alert-danger"><?= htmlspecialchars($error) ?></div>
        <?php endif; ?>

        <form method="POST">
            <div class="mb-3">
                <label class="form-label">Email</label>
                <input type="email" class="form-control" name="email" required>
            </div>
            <div class="mb-3">
                <label class="form-label">Mot de passe</label>
                <input type="password" class="form-control" name="password" required>
            </div>
            <button type="submit" class="btn btn-primary w-100">Se connecter</button>
        </form>

        <p class="mt-3 text-center">Pas encore inscrit ? <a href="register.php">Créer un compte</a></p>
    </div>
</body>
</html>
