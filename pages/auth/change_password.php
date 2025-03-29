<?php
session_start();
require_once '../../database.php';

// Vérifier si l'utilisateur est connecté (Admin ou Client)
if (!isset($_SESSION['user_id']) && !isset($_SESSION['client_id'])) {
    header("Location: login.php");
    exit;
}

// Vérifier si l'utilisateur doit changer son mot de passe
if (!isset($_SESSION['change_password']) || !$_SESSION['change_password']) {
    if (isset($_SESSION['user_id'])) {
        header("Location: ../../index.php");
    } else {
        header("Location: ../../client/index.php");
    }
    exit;
}

$error = "";

// Vérifier si le formulaire a été soumis
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $new_password = trim($_POST['new_password']);
    $confirm_password = trim($_POST['confirm_password']);

    if ($new_password !== $confirm_password) {
        $error = "❌ Les mots de passe ne correspondent pas.";
    } elseif (strlen($new_password) < 6) {
        $error = "❌ Le mot de passe doit contenir au moins 6 caractères.";
    } else {
        // Hachage du mot de passe
        $hashed_password = password_hash($new_password, PASSWORD_DEFAULT);

        if (isset($_SESSION['user_id'])) {
            // Mise à jour du mot de passe pour les ADMINs et RESPONSABLES
            $stmt = $connexion->prepare("UPDATE users SET password = ? WHERE id = ?");
            $stmt->bind_param("si", $hashed_password, $_SESSION['user_id']);
            $stmt->execute();
        } elseif (isset($_SESSION['client_id'])) {
            // Mise à jour du mot de passe pour les CLIENTS
            $stmt = $connexion->prepare("UPDATE clients SET password = ? WHERE id = ?");
            $stmt->bind_param("si", $hashed_password, $_SESSION['client_id']);
            $stmt->execute();
        }

        // Désactiver l'obligation de changement de mot de passe
        $_SESSION['change_password'] = false;

        // Rediriger selon le rôle de l'utilisateur
        if (isset($_SESSION['user_id'])) {
            header("Location: ../../index.php");
        } else {
            header("Location: ../../client/index.php");
        }
        exit;
    }
}
?>

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>🔒 Changer le mot de passe</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <script src="https://kit.fontawesome.com/a076d05399.js" crossorigin="anonymous"></script>
    
    <style>
        body {
            background-color: #f0f2f5;
            display: flex;
            justify-content: center;
            align-items: center;
            height: 100vh;
            margin: 0;
            font-family: 'Arial', sans-serif;
        }

        .change-password-container {
            background: white;
            padding: 40px;
            border-radius: 15px;
            box-shadow: 0px 8px 30px rgba(0, 0, 0, 0.2);
            max-width: 400px;
            width: 100%;
            text-align: center;
            transition: transform 0.3s ease, box-shadow 0.3s ease;
        }

        .change-password-container:hover {
            transform: translateY(-3px);
            box-shadow: 0px 12px 40px rgba(0, 0, 0, 0.25);
        }

        .password-container {
            position: relative;
            margin-bottom: 20px;
        }

        .password-container i {
            position: absolute;
            right: 15px;
            top: 50%;
            transform: translateY(-50%);
            cursor: pointer;
            color: #666;
            transition: color 0.3s ease;
        }

        .password-container i:hover {
            color: #333;
        }

        .btn-success {
            width: 100%;
            background-color: #007bff;
            border: none;
            font-size: 18px;
            font-weight: bold;
            padding: 12px;
            transition: background-color 0.3s ease;
            border-radius: 8px;
        }

        .btn-success:hover {
            background-color: #0056b3;
        }

        .alert {
            font-size: 15px;
            margin-top: 20px;
            display: flex;
            align-items: center;
            justify-content: center;
        }

        .form-label {
            font-weight: bold;
            color: #333;
        }

        .form-control {
            border-radius: 8px;
            border: 1px solid #ced4da;
            transition: border-color 0.3s ease, box-shadow 0.3s ease;
        }

        .form-control:focus {
            border-color: #007bff;
            box-shadow: 0 0 8px rgba(0, 123, 255, 0.25);
        }

        .text-primary {
            color: #007bff !important;
        }
    </style>
</head>
<body>

    <div class="change-password-container">
        <h2 class="text-primary"><i class="fas fa-lock"></i> Changer le mot de passe</h2>
        <p class="text-muted">Assurez-vous de choisir un mot de passe sécurisé.</p>

        <?php if (!empty($error)): ?>
            <div class="alert alert-danger">
                <i class="fas fa-exclamation-circle"></i> <?= $error ?>
            </div>
        <?php endif; ?>

        <form action="#" method="POST">
            <div class="password-container">
                <label for="new_password" class="form-label">Nouveau mot de passe</label>
                <input type="password" class="form-control" id="new_password" name="new_password" required>
                <i class="fas fa-eye" onclick="togglePassword('new_password', this)"></i>
            </div>
            <div class="password-container">
                <label for="confirm_password" class="form-label">Confirmer le mot de passe</label>
                <input type="password" class="form-control" id="confirm_password" name="confirm_password" required>
                <i class="fas fa-eye" onclick="togglePassword('confirm_password', this)"></i>
            </div>
            <button type="submit" class="btn btn-success"><i class="fas fa-save"></i> Enregistrer</button>
        </form>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    
    <script>
        function togglePassword(id, eyeIcon) {
            let input = document.getElementById(id);
            if (input.type === "password") {
                input.type = "text";
                eyeIcon.classList.remove("fa-eye");
                eyeIcon.classList.add("fa-eye-slash");
            } else {
                input.type = "password";
                eyeIcon.classList.remove("fa-eye-slash");
                eyeIcon.classList.add("fa-eye");
            }
        }
    </script>

</body>
</html>
