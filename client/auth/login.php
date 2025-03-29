<?php
// Initialisation de la session
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}
require_once '../../database.php';

$error = "";

// Vérification du formulaire
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $email = trim($_POST['email']);
    $password = trim($_POST['password']);

    if (empty($email) || empty($password)) {
        $error = "❌ Veuillez remplir tous les champs.";
    } else {
        // Vérifier si c’est un client
        $stmt = $connexion->prepare("SELECT id, prenom, password, profile_image FROM clients WHERE email = ?");
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
                header("Location: change_password.php");
                exit;
            }

            if (password_verify($password, $client['password'])) {
                $_SESSION['client_id'] = $client['id'];
                $_SESSION['prenom'] = htmlspecialchars($client['prenom']);
                $_SESSION['profile_image'] = $client['profile_image'] ?? 'default.png';

                // Gestion upload image
                if (!empty($_FILES['profile_image']['name'])) {
                    $target_dir = "../../assets/";
                    $file_name = basename($_FILES['profile_image']['name']);
                    $target_file = $target_dir . $file_name;
                    $imageFileType = strtolower(pathinfo($target_file, PATHINFO_EXTENSION));
                    $extensions_autorisees = ['jpg', 'jpeg', 'png', 'gif'];

                    if (in_array($imageFileType, $extensions_autorisees)) {
                        move_uploaded_file($_FILES['profile_image']['tmp_name'], $target_file);
                        $_SESSION['profile_image'] = $file_name;

                        $stmt = $connexion->prepare("UPDATE clients SET profile_image = ? WHERE id = ?");
                        $stmt->bind_param("si", $file_name, $client['id']);
                        $stmt->execute();
                    }
                }

                header("Location: ../../client/index.php");
                exit;
            } else {
                $error = "❌ Identifiants incorrects.";
            }
        } else {
            $error = "❌ Utilisateur non trouvé.";
        }
    }
}
?>

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Connexion Client | TransitFlow</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
        body {
            background: linear-gradient(to right, #007bff, #6c63ff);
            height: 100vh;
            display: flex;
            align-items: center;
            justify-content: center;
        }
        .login-box {
            background: white;
            padding: 30px;
            border-radius: 15px;
            box-shadow: 0 8px 20px rgba(0,0,0,0.2);
            width: 100%;
            max-width: 450px;
        }
        .form-control:focus {
            box-shadow: none;
            border-color: #6c63ff;
        }
        .btn-primary {
            background-color: #6c63ff;
            border-color: #6c63ff;
        }
        .btn-primary:hover {
            background-color: #5848d6;
            border-color: #5848d6;
        }
    </style>
</head>
<body>

<div class="login-box">
    <h3 class="text-center text-primary mb-4">🚍TransitFlow</h3>

    <?php if (!empty($error)): ?>
        <div class="alert alert-danger"><?= htmlspecialchars($error) ?></div>
    <?php endif; ?>

    <form method="POST" enctype="multipart/form-data">
        <div class="mb-3">
            <label for="email" class="form-label">Adresse Email</label>
            <input type="email" class="form-control" name="email" id="email" required>
        </div>

        <div class="mb-3">
            <label for="password" class="form-label">Mot de passe</label>
            <input type="password" class="form-control" name="password" id="password" required>
        </div>

        <div class="mb-3">
            <label for="profile_image" class="form-label">Photo de profil (facultatif)</label>
            <input type="file" class="form-control" name="profile_image" id="profile_image" accept="image/*">
        </div>

        <button type="submit" class="btn btn-primary w-100">Se connecter</button>

        <p class="mt-3 text-center">Pas encore de compte ? <a href="register.php">Créer un compte</a></p>
    </form>
</div>

</body>
</html>
