<?php
session_start();
require_once dirname(__DIR__, 2) . '/database.php';

if (!isset($_SESSION['user_id']) || !$_SESSION['change_password']) {
    header("Location: /Gestion_des_bus_PHP/index.php");
    exit;
}

$error = "";

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $new_password = $_POST['new_password'];
    $confirm_password = $_POST['confirm_password'];

    if ($new_password !== $confirm_password) {
        $error = "❌ Les mots de passe ne correspondent pas.";
    } else {
        $hashed_password = password_hash($new_password, PASSWORD_DEFAULT);

        $stmt = $connexion->prepare("UPDATE users SET password = ? WHERE id = ?");
        $stmt->bind_param("si", $hashed_password, $_SESSION['user_id']);
        $stmt->execute();

        $_SESSION['change_password'] = false;
        header("Location: /Gestion_des_bus_PHP/index.php");
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
    <script src="https://kit.fontawesome.com/a076d05399.js" crossorigin="anonymous"></script> <!-- Icônes FontAwesome -->
    
    <style>
        body {
            background-color: #ffffff; /* Fond blanc épuré */
            display: flex;
            justify-content: center;
            align-items: center;
            height: 100vh;
        }

        .change-password-container {
            background: white;
            padding: 30px;
            border-radius: 12px;
            box-shadow: 0px 4px 15px rgba(0, 0, 0, 0.1);
            max-width: 450px;
            width: 100%;
            text-align: center;
        }

        .password-container {
            position: relative;
        }

        .password-container i {
            position: absolute;
            right: 15px;
            top: 50%;
            transform: translateY(-50%);
            cursor: pointer;
            color: #888;
        }

        .btn-success {
            width: 100%;
            background-color: #28a745;
            border: none;
            font-size: 16px;
            font-weight: bold;
            padding: 12px;
            transition: 0.3s;
            border-radius: 8px;
        }

        .btn-success:hover {
            background-color: #218838;
        }

        .alert {
            font-size: 14px;
        }

        .form-label {
            font-weight: bold;
            color: #333;
        }
    </style>
</head>
<body>

    <div class="change-password-container">
        <h2 class="text-primary"><i class="fas fa-lock"></i> Changer le mot de passe</h2>
        <p class="text-muted">Assurez-vous de choisir un mot de passe sécurisé.</p>

        <!-- Message d'erreur -->
        <?php if (!empty($error)): ?>
            <div class="alert alert-danger"><?= $error ?></div>
        <?php endif; ?>

        <form action="#" method="POST">
            <div class="mb-3 password-container">
                <label for="new_password" class="form-label">Nouveau mot de passe</label>
                <input type="password" class="form-control" id="new_password" name="new_password" required>
                <i class="fas fa-eye" onclick="togglePassword('new_password', this)"></i>
            </div>
            <div class="mb-3 password-container">
                <label for="confirm_password" class="form-label">Confirmer le mot de passe</label>
                <input type="password" class="form-control" id="confirm_password" name="confirm_password" required>
                <i class="fas fa-eye" onclick="togglePassword('confirm_password', this)"></i>
            </div>
            <button type="submit" class="btn btn-success"><i class="fas fa-save"></i> Enregistrer</button>
        </form>
    </div>

    <!-- Bootstrap JS -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    
    <!-- Script pour voir/masquer les mots de passe -->
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
