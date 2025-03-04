<?php
session_start();
require_once dirname(__DIR__, 2) . '/database.php';

$error = "";

if (isset($_SESSION['user_id'])) {
    header("Location: /Gestion_des_bus_PHP/index.php");
    exit;
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $login = trim($_POST['login']);
    $password = trim($_POST['password']);

    if (empty($login) || empty($password)) {
        $error = "Veuillez remplir tous les champs.";
    } else {
        $stmt = $connexion->prepare("SELECT * FROM users WHERE login = ?");
        $stmt->bind_param("s", $login);
        $stmt->execute();
        $result = $stmt->get_result();
        $user = $result->fetch_assoc();

        if (!$user) {
            $error = "Utilisateur introuvable.";
        } else {
            if ($user['password'] == 'default') {
                $_SESSION['user_id'] = $user['id'];
                $_SESSION['change_password'] = true;
                header("Location: /Gestion_des_bus_PHP/pages/auth/change_password.php");
                exit;
            }

            if (password_verify($password, $user['password'])) {
                $_SESSION['user_id'] = $user['id'];
                $_SESSION['role'] = $user['role'];
                $_SESSION['change_password'] = false;
                header("Location: /Gestion_des_bus_PHP/index.php");
                exit;
            } else {
                $error = "Mot de passe incorrect.";
            }
        }
    }
}
?>

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <title>Connexion</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body>
    <div class="container mt-5">
        <h2 class="text-center">Connexion</h2>
        <?php if (!empty($error)): ?>
            <div class="alert alert-danger"><?= $error ?></div>
        <?php endif; ?>
        <form action="#" method="POST" class="shadow p-4 rounded bg-light">
            <div class="mb-3">
                <label for="login" class="form-label">Identifiant</label>
                <input type="text" class="form-control" id="login" name="login" required>
            </div>
            <div class="mb-3">
                <label for="password" class="form-label">Mot de passe</label>
                <input type="password" class="form-control" id="password" name="password" required>
            </div>
            <button type="submit" class="btn btn-success w-100">Se connecter</button>
        </form>
    </div>
</body>
</html>
