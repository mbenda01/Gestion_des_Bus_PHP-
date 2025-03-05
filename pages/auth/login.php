<?php
session_start();
require_once dirname(__DIR__, 2) . '/database.php';

$error = "";

// Vérifier si l'utilisateur est déjà connecté
if (isset($_SESSION['user_id'])) {
    header("Location: /Gestion_des_bus_PHP/index.php");
    exit;
}

// Vérifier si le formulaire a été soumis
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $login = trim($_POST['login']);
    $password = trim($_POST['password']);

    if (empty($login) || empty($password)) {
        $error = "Veuillez remplir tous les champs.";
    } else {
        // Vérifier si l'utilisateur existe
        $stmt = $connexion->prepare("SELECT * FROM users WHERE login = ?");
        $stmt->bind_param("s", $login);
        $stmt->execute();
        $result = $stmt->get_result();
        $user = $result->fetch_assoc();

        if (!$user) {
            $error = "Identifiant ou mot de passe incorrect.";
        } else {
            // Vérifier si le mot de passe est "default"
            if ($user['password'] == 'default') {
                $_SESSION['user_id'] = $user['id'];
                $_SESSION['role'] = $user['role'];
                $_SESSION['prenom'] = $user['prenom'];
                $_SESSION['profile_image'] = $user['profile_image'] ?? 'default.png';
                $_SESSION['change_password'] = true;
                header("Location: /Gestion_des_bus_PHP/pages/auth/change_password.php");
                exit;
            }

            // Vérifier le mot de passe avec password_verify
            if (password_verify($password, $user['password'])) {
                $_SESSION['user_id'] = $user['id'];
                $_SESSION['role'] = $user['role'];
                $_SESSION['prenom'] = $user['prenom'];
                $_SESSION['profile_image'] = $user['profile_image'] ?? 'default.png';
                $_SESSION['change_password'] = false;

                // Vérifier si une image de profil est envoyée
                if (!empty($_FILES['profile_image']['name'])) {
                    $target_dir = dirname(__DIR__, 2) . "/assets/";
                    $file_name = basename($_FILES['profile_image']['name']);
                    $target_file = $target_dir . $file_name;

                    // Vérifier si c'est une image valide
                    $imageFileType = strtolower(pathinfo($target_file, PATHINFO_EXTENSION));
                    $extensions_autorisees = ['jpg', 'jpeg', 'png', 'gif'];

                    if (in_array($imageFileType, $extensions_autorisees)) {
                        move_uploaded_file($_FILES['profile_image']['tmp_name'], $target_file);
                        $_SESSION['profile_image'] = $file_name;

                        // Mettre à jour l'image de profil en base de données
                        $stmt = $connexion->prepare("UPDATE users SET profile_image = ? WHERE id = ?");
                        $stmt->bind_param("si", $file_name, $user['id']);
                        $stmt->execute();
                    }
                }

                header("Location: /Gestion_des_bus_PHP/index.php");
                exit;
            } else {
                $error = "Identifiant ou mot de passe incorrect.";
            }
        }
    }
}
?>

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Connexion - Gestion des Bus</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
        body {
            background-color: #f8f9fa;
            display: flex;
            align-items: center;
            justify-content: center;
            height: 100vh;
        }
        .login-container {
            max-width: 400px;
            padding: 20px;
            background: white;
            border-radius: 10px;
            box-shadow: 0px 0px 10px rgba(0, 0, 0, 0.1);
        }
        .btn-primary {
            width: 100%;
        }
    </style>
</head>
<body>

<div class="login-container">
    <h2 class="text-center text-primary">🚍 Connexion</h2>

    <!-- Affichage des erreurs -->
    <?php if (!empty($error)): ?>
        <div class="alert alert-danger"><?= $error ?></div>
    <?php endif; ?>

    <form action="#" method="POST" enctype="multipart/form-data">
        <div class="mb-3">
            <label for="login" class="form-label">Identifiant</label>
            <input type="text" class="form-control" id="login" name="login" required>
        </div>
        <div class="mb-3">
            <label for="password" class="form-label">Mot de passe</label>
            <input type="password" class="form-control" id="password" name="password" required>
        </div>
        <div class="mb-3">
            <label for="profile_image" class="form-label">Photo de profil</label>
            <input type="file" class="form-control" id="profile_image" name="profile_image" accept="image/*">
        </div>
        <button type="submit" class="btn btn-primary">Se connecter</button>
    </form>
</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>

</body>
</html>
